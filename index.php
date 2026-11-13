
<?php

require_once __DIR__ . "/core/core.php";
require_once __DIR__ . "/controllers/ProductController.php";

/* =========================
   ADD TO CART
========================= */

if (isset($_GET['add_cart'])) {

    $productId = filter_input(
        INPUT_GET,
        'add_cart',
        FILTER_VALIDATE_INT
    );

    if ($productId === false || $productId === null || $productId < 1) {
        $_SESSION['cart_message'] = [
            'type' => 'error',
            'text' => 'Invalid product selected.'
        ];

        header("Location: " . base_url() . "/index.php");
        exit;
    }

    $productController = new ProductController();

    // Preserve NULL for guests.
    $customerId = isset($_SESSION['customer_id'])
        ? (int) $_SESSION['customer_id']
        : null;

    if ($customerId !== null && $customerId <= 0) {
        $customerId = null;
    }

    $ipAddress = get_ip();

    try {

        $added = $productController->addToCart(
            $productId,
            $ipAddress,
            $customerId
        );

        $_SESSION['cart_message'] = [
            'type' => $added ? 'success' : 'error',
            'text' => $added
                ? 'Product added to cart successfully!'
                : 'Unable to add product to cart.'
        ];

    } catch (Throwable $e) {

        error_log(
            "Add to Cart Error: " . $e->getMessage()
        );

        $_SESSION['cart_message'] = [
            'type' => 'error',
            'text' => 'Something went wrong while adding the product. Please try again.'
        ];
    }

    header("Location: " . base_url() . "/index.php");
    exit;
}

/* =========================
   HOMEPAGE
========================= */

require_once __DIR__ . "/views/home.php";

?>
