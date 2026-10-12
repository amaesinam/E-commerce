
<?php
require_once __DIR__ . "/core/core.php";
require_once __DIR__ . "/controllers/ProductController.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';

$productController = new ProductController();

// Handle Add to Cart
if (isset($_GET['add_cart'])) {
    $productId = filter_input(
        INPUT_GET,
        'add_cart',
        FILTER_VALIDATE_INT
    );

    if ($productId !== false && $productId !== null && $productId > 0) {
        $customerId = (int) ($_SESSION['customer_id'] ?? 0);
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $added = $productController->addToCart(
            $productId,
            $ipAddress,
            $customerId
        );

        if ($added) {
            $_SESSION['cart_message'] = [
                'type' => 'success',
                'text' => 'Product added successfully!'
            ];
        } else {
            $_SESSION['cart_message'] = [
                'type' => 'error',
                'text' => 'Unable to add this product to your cart. Please try again.'
            ];
        }
    } else {
        $_SESSION['cart_message'] = [
            'type' => 'error',
            'text' => 'Invalid product selected.'
        ];
    }

    header("Location: " . $base_url . "/index.php");
    exit;
}

// Display the home page
require_once __DIR__ . "/views/home.php";
?>
