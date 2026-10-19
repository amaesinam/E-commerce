
<?php
require_once __DIR__ . "/../core/core.php";

$cart_url = base_url() . "/views/cart.php";
$home_url = base_url() . "/index.php";

require_once __DIR__ . "/layout/header.php";
?>

<main class="container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
    <h1>Checkout</h1>

    <p>
        Welcome to checkout! The checkout process is not available yet.
        Please return to your cart while we prepare this feature.
    </p>

    <a href="<?php echo htmlspecialchars($cart_url); ?>"
       class="btn btn-success">
        Return to Cart
    </a>

    <a href="<?php echo htmlspecialchars($home_url); ?>"
       class="btn btn-secondary">
        Continue Shopping
    </a>
</main>

<?php require_once __DIR__ . "/layout/footer.php"; ?>
