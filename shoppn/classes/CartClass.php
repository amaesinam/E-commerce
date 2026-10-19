
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

        $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("isii", $product_id, $ip, $customer_id, $qty);
        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Retrieve cart items with product details.
    public function getCartItems($ip)
    {
        $sql = "SELECT cart.*, products.product_title,
                       products.product_price, products.product_image
                FROM cart
                JOIN products ON cart.p_id = products.product_id
                WHERE cart.ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $items;
    }

    // Count the number of cart rows.
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

    // Calculate the total price of the cart.
    public function getCartTotal($ip)
    {
        $items = $this->getCartItems($ip);
        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['qty'] * (float) $item['product_price'];
        }

        return $total;
    }

    // Remove a product belonging to this IP address.
    public function removeFromCart($product_id, $ip)
    {
        if (
            filter_var($product_id, FILTER_VALIDATE_INT) === false ||
            $product_id < 1
        ) {
            return false;
        }

        $sql = "DELETE FROM cart WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("is", $product_id, $ip);
        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Update a product quantity belonging to this IP address.
    public function updateQty($product_id, $new_qty, $ip)
    {
        if (
            filter_var($product_id, FILTER_VALIDATE_INT) === false ||
            $product_id < 1 ||
            filter_var($new_qty, FILTER_VALIDATE_INT) === false ||
            $new_qty < 1
        ) {
            return false;
        }

        $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iis", $new_qty, $product_id, $ip);
        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
}
