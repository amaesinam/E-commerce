
<?php

require_once __DIR__ . "/../core/db_class.php";

class CartClass extends Database
{
    // Check whether a product is already in this user's cart.
    public function isInCart($product_id, $ip)
    {
        $sql = "SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("is", $product_id, $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;

        $stmt->close();

        return $exists;
    }

    // Add a product to the cart.
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        if (
            filter_var($product_id, FILTER_VALIDATE_INT) === false ||
            $product_id < 1 ||
            filter_var($qty, FILTER_VALIDATE_INT) === false ||
            $qty < 1
        ) {
            return false;
        }

        if ($customer_id === null || (int) $customer_id <= 0) {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, NULL, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param("isi", $product_id, $ip, $qty);
        } else {
            $customer_id = (int) $customer_id;

            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "isii",
                $product_id,
                $ip,
                $customer_id,
                $qty
            );
        }

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Retrieve cart items belonging to a specific customer or guest.
    public function getCartItems($ip, $customer_id = null)
    {
        if ($customer_id !== null && (int) $customer_id > 0) {
            $sql = "SELECT cart.*, products.product_title,
                           products.product_price, products.product_image
                    FROM cart
                    JOIN products ON cart.p_id = products.product_id
                    WHERE cart.ip_add = ? AND cart.c_id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [];
            }

            $customer_id = (int) $customer_id;
            $stmt->bind_param("si", $ip, $customer_id);
        } else {
            $sql = "SELECT cart.*, products.product_title,
                           products.product_price, products.product_image
                    FROM cart
                    JOIN products ON cart.p_id = products.product_id
                    WHERE cart.ip_add = ? AND cart.c_id IS NULL";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                return [];
            }

            $stmt->bind_param("s", $ip);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $items;
    }

    // Count cart rows.
    public function getCartCount($ip)
    {
        $sql = "SELECT COUNT(*) AS total FROM cart WHERE ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }

    // Calculate the cart total.
    public function getCartTotal($ip, $customer_id = null)
    {
        $items = $this->getCartItems($ip, $customer_id);
        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['qty'] *
                      (float) $item['product_price'];
        }

        return $total;
    }

    // Remove a product belonging to this customer.
    public function removeFromCart($product_id, $ip, $customer_id)
    {
        if (
            filter_var($product_id, FILTER_VALIDATE_INT) === false ||
            $product_id < 1 ||
            filter_var($customer_id, FILTER_VALIDATE_INT) === false ||
            (int) $customer_id < 1
        ) {
            return false;
        }

        $customer_id = (int) $customer_id;

        $sql = "DELETE FROM cart
                WHERE p_id = ? AND ip_add = ? AND c_id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("isi", $product_id, $ip, $customer_id);
        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Update a product quantity belonging to this customer.
    public function updateQty($product_id, $new_qty, $ip, $customer_id)
    {
        if (
            filter_var($product_id, FILTER_VALIDATE_INT) === false ||
            $product_id < 1 ||
            filter_var($new_qty, FILTER_VALIDATE_INT) === false ||
            $new_qty < 1 ||
            filter_var($customer_id, FILTER_VALIDATE_INT) === false ||
            (int) $customer_id < 1
        ) {
            return false;
        }

        $customer_id = (int) $customer_id;

        $sql = "UPDATE cart
                SET qty = ?
                WHERE p_id = ? AND ip_add = ? AND c_id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iisi",
            $new_qty,
            $product_id,
            $ip,
            $customer_id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Create an order only after Paystack payment is verified.
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        if (
            filter_var($customer_id, FILTER_VALIDATE_INT) === false ||
            (int) $customer_id < 1 ||
            empty($ip) ||
            empty($reference) ||
            strlen($reference) > 100 ||
            !is_numeric($amount) ||
            (float) $amount <= 0
        ) {
            return false;
        }

        $customer_id = (int) $customer_id;
        $reference = trim($reference);
        $order_status = "paid";

        try {
            $this->conn->begin_transaction();

            // Check whether this payment reference was already used.
            $sql = "SELECT order_id, customer_id
                    FROM orders
                    WHERE payment_reference = ?
                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Could not check payment reference.");
            }

            $stmt->bind_param("s", $reference);
            $stmt->execute();

            $existing = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($existing) {
                $this->conn->rollback();

                if ((int) $existing['customer_id'] === $customer_id) {
                    return (int) $existing['order_id'];
                }

                return false;
            }

            // Lock and check this customer's cart.
            $sql = "SELECT p_id, qty
                    FROM cart
                    WHERE ip_add = ? AND c_id = ?
                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Could not retrieve customer cart.");
            }

            $stmt->bind_param("si", $ip, $customer_id);
            $stmt->execute();

            $cartResult = $stmt->get_result();
            $cartCount = $cartResult->num_rows;

            $stmt->close();

            if ($cartCount < 1) {
                throw new Exception("Customer cart is empty.");
            }

            // Generate and insert an invoice number.
            // Retry if the unique invoice number already exists.
            $order_id = false;

            for ($attempt = 1; $attempt <= 10; $attempt++) {

                $invoice_no = random_int(100000, 999999);

                $sql = "INSERT INTO orders
                        (customer_id, invoice_no, order_date,
                         order_status, payment_reference)
                        VALUES (?, ?, CURDATE(), ?, ?)";

                $stmt = $this->conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Could not prepare order insertion.");
                }

                $stmt->bind_param(
                    "iiss",
                    $customer_id,
                    $invoice_no,
                    $order_status,
                    $reference
                );

                try {
                    $inserted = $stmt->execute();

                } catch (mysqli_sql_exception $e) {
                    $errorCode = (int) $e->getCode();
                    $stmt->close();

                    // MySQL error 1062 means a duplicate unique value.
                    if ($errorCode === 1062) {
                        continue;
                    }

                    throw $e;
                }

                if ($inserted) {
                    $order_id = $this->conn->insert_id;
                    $stmt->close();
                    break;
                }

                $errorCode = (int) $stmt->errno;
                $stmt->close();

                if ($errorCode === 1062) {
                    continue;
                }

                throw new Exception("Could not create order.");
            }

            if (!$order_id) {
                throw new Exception(
                    "Could not generate a unique invoice number after several attempts."
                );
            }

            // Copy only this customer's cart items into orderdetails.
            $sql = "INSERT INTO orderdetails
                    (order_id, product_id, qty)
                    SELECT ?, p_id, qty
                    FROM cart
                    WHERE ip_add = ? AND c_id = ?";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Could not prepare order details.");
            }

            $stmt->bind_param("isi", $order_id, $ip, $customer_id);

            if (!$stmt->execute() || $stmt->affected_rows < 1) {
                throw new Exception("No cart items were added to the order.");
            }

            $stmt->close();

            // Delete only this customer's cart items.
            $sql = "DELETE FROM cart
                    WHERE ip_add = ? AND c_id = ?";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Could not prepare cart deletion.");
            }

            $stmt->bind_param("si", $ip, $customer_id);

            if (!$stmt->execute()) {
                throw new Exception("Could not clear the customer cart.");
            }

            $stmt->close();

            $this->conn->commit();

            return (int) $order_id;

        } catch (Throwable $e) {
            $this->conn->rollback();

            error_log("Payment processing error: " . $e->getMessage());

            // Handle a duplicate reference created by a concurrent request.
            $sql = "SELECT order_id, customer_id
                    FROM orders
                    WHERE payment_reference = ?";

            $stmt = $this->conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $reference);
                $stmt->execute();

                $existing = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (
                    $existing &&
                    (int) $existing['customer_id'] === $customer_id
                ) {
                    return (int) $existing['order_id'];
                }
            }

            return false;
        }
    }

    // Retrieve an order only if it belongs to the logged-in customer.
    public function getOrderById($order_id, $customer_id)
    {
        if (
            filter_var($order_id, FILTER_VALIDATE_INT) === false ||
            (int) $order_id < 1 ||
            filter_var($customer_id, FILTER_VALIDATE_INT) === false ||
            (int) $customer_id < 1
        ) {
            return false;
        }

        $order_id = (int) $order_id;
        $customer_id = (int) $customer_id;

        // Confirm ownership of the order.
        $sql = "SELECT *
                FROM orders
                WHERE order_id = ? AND customer_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $order_id, $customer_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        $stmt->close();

        if (!$order) {
            return false;
        }

        // Retrieve products belonging to this order.
        $sql = "SELECT orderdetails.order_id,
                       orderdetails.product_id,
                       orderdetails.qty,
                       products.product_title,
                       products.product_price
                FROM orderdetails
                JOIN products
                    ON orderdetails.product_id = products.product_id
                WHERE orderdetails.order_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return [
            'order' => $order,
            'items' => $items
        ];
    }
}
