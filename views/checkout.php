
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

// Payment requires a logged-in customer.
if (!is_logged_in()) {
    $_SESSION['error'] = "Please log in before proceeding to checkout.";
    header("Location: " . base_url() . "/views/login.php");
    exit;
}

$customerId = (int) $_SESSION['customer_id'];
$ip = get_ip();

$cartController = new CartController();

// Retrieve only this logged-in customer's cart.
$cartItems = $cartController->getCartItems($ip, $customerId);
$total = $cartController->getCartTotal($ip, $customerId);

$cart_url = base_url() . "/views/cart.php";
$payment_url = base_url() . "/views/payment.php";

require_once __DIR__ . "/layout/header.php";
?>

<main class="container" style="max-width: 900px; margin: 40px auto; padding: 20px;">

    <h1>Checkout</h1>

    <p>
        Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?>.
        Please review your order before proceeding to payment.
    </p>

    <?php if (empty($cartItems)): ?>

        <div class="alert alert-warning">
            Your shopping cart is empty.
        </div>

        <a href="<?php echo htmlspecialchars(base_url() . '/index.php'); ?>"
           class="btn btn-success">
            Continue Shopping
        </a>

    <?php else: ?>

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
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                            $quantity = (int) $item['qty'];
                            $price = (float) $item['product_price'];
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

        <h4 class="text-end">
            Total: GH₵ <?php echo number_format($total, 2); ?>
        </h4>

        <div class="mt-4">
            <a href="<?php echo htmlspecialchars($cart_url); ?>"
               class="btn btn-secondary">
                Return to Cart
            </a>

            <a href="<?php echo htmlspecialchars($payment_url); ?>"
               class="btn btn-success">
                Proceed to Payment
            </a>
        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . "/layout/footer.php"; ?>
