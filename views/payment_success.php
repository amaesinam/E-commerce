
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

require_login();

$customerId = (int) $_SESSION['customer_id'];
$base_url = base_url();

$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

if (!$order_id || $order_id < 1 || $customerId < 1) {
    header("Location: " . $base_url . "/views/cart.php");
    exit;
}

$cartController = new CartController();

// Retrieve the order only if it belongs to this customer.
$orderData = $cartController->getOrderById($order_id, $customerId);

if (
    !$orderData ||
    empty($orderData['order']) ||
    empty($orderData['items'])
) {
    http_response_code(404);
    echo "Order not found or you are not authorized to view this invoice.";
    exit;
}

$order = $orderData['order'];
$items = $orderData['items'];

$total = 0;

foreach ($items as $item) {
    $total += (float) $item['product_price'] * (int) $item['qty'];
}

require_once __DIR__ . "/layout/header.php";

?>

<main class="container" style="max-width:850px; margin:50px auto; padding:30px;">

    <div style="text-align:center; margin-bottom:30px;">
        <h1 style="color:green;">Payment Successful!</h1>
        <p>Thank you for shopping with Shoppn.</p>
        <p>Your order has been confirmed.</p>
    </div>

    <hr>

    <h2>Order Invoice</h2>

    <p>
        <strong>Order Number:</strong>
        <?php echo htmlspecialchars((string)$order['order_id']); ?>
    </p>

    <p>
        <strong>Invoice Number:</strong>
        <?php echo htmlspecialchars((string)$order['invoice_no']); ?>
    </p>

    <p>
        <strong>Order Date:</strong>
        <?php echo htmlspecialchars((string)$order['order_date']); ?>
    </p>

    <p>
        <strong>Payment Status:</strong>
        <?php echo htmlspecialchars((string)$order['order_status']); ?>
    </p>

    <h3>Purchased Products</h3>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php
                        $quantity = (int)$item['qty'];
                        $price = (float)$item['product_price'];
                        $subtotal = $quantity * $price;
                    ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($item['product_title']); ?>
                        </td>

                        <td><?php echo $quantity; ?></td>

                        <td>
                            GH₵ <?php echo number_format($price, 2); ?>
                        </td>

                        <td>
                            GH₵ <?php echo number_format($subtotal, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3 style="text-align:right;">
        Total: GH₵ <?php echo number_format($total, 2); ?>
    </h3>

    <div style="text-align:center; margin-top:30px;">
        <a href="<?php echo $base_url; ?>/index.php"
           class="btn btn-success">
            Continue Shopping
        </a>
    </div>

</main>

<?php require_once __DIR__ . "/layout/footer.php"; ?>
