
<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';

$productController = new ProductController();

// Get the selected category or brand
$category_id = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
$brand_id = filter_input(INPUT_GET, 'brand', FILTER_VALIDATE_INT);

if (isset($_GET['cat'])) {
    $products = (
        $category_id !== false &&
        $category_id !== null &&
        $category_id > 0
    )
        ? $productController->getProductsByCategory($category_id)
        : [];

    $pageTitle = 'Products by Category';

} elseif (isset($_GET['brand'])) {
    $products = (
        $brand_id !== false &&
        $brand_id !== null &&
        $brand_id > 0
    )
        ? $productController->getProductsByBrand($brand_id)
        : [];

    $pageTitle = 'Products by Brand';

} else {
    $products = $productController->getFeaturedProducts(8);
    $pageTitle = 'Featured Products';
}

if (!is_array($products)) {
    $products = [];
}
?>

<?php include __DIR__ . "/layout/header.php"; ?>

<main>

    <!-- Add to Cart success or error message -->
    <?php if (!empty($_SESSION['cart_message'])): ?>

        <div
            class="cart-message <?php
                echo (
                    ($_SESSION['cart_message']['type'] ?? '') === 'success'
                ) ? 'success' : 'error';
            ?>"
            role="status"
            style="
                max-width: 1200px;
                margin: 20px auto;
                padding: 15px 20px;
                border-radius: 8px;
                background-color: <?php
                    echo (
                        ($_SESSION['cart_message']['type'] ?? '') === 'success'
                    ) ? '#e6f4ea' : '#fde8e7';
                ?>;
                color: <?php
                    echo (
                        ($_SESSION['cart_message']['type'] ?? '') === 'success'
                    ) ? '#216e39' : '#a12622';
                ?>;
                border: 1px solid <?php
                    echo (
                        ($_SESSION['cart_message']['type'] ?? '') === 'success'
                    ) ? '#b7dfc1' : '#f5c2c0';
                ?>;
            "
        >
            <?php
            echo htmlspecialchars(
                $_SESSION['cart_message']['text'] ?? 'Cart update completed.'
            );

            // Display the message only once
            unset($_SESSION['cart_message']);
            ?>
        </div>

    <?php endif; ?>

    <!-- Hero section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Shoppn</h1>
            <p>Discover products you'll love, all in one place.</p>
            <a class="btn" href="#featured-products">Shop Now</a>
        </div>
    </section>

    <!-- Logged-in customer information -->
    <?php if (is_logged_in()): ?>

        <section class="account-container">
            <h2>
                Welcome,
                <?php
                echo htmlspecialchars(
                    $_SESSION['customer_name'] ?? 'Customer'
                );
                ?>!
            </h2>

            <p>You are successfully logged in.</p>

            <p>
                Email:
                <?php
                echo htmlspecialchars(
                    $_SESSION['customer_email'] ?? ''
                );
                ?>
            </p>
        </section>

    <?php endif; ?>

    <!-- Shop layout -->
    <div class="shop-layout">

        <?php include __DIR__ . "/layout/sidebar.php"; ?>

        <section class="shop-content" id="featured-products">

            <h2><?php echo htmlspecialchars($pageTitle); ?></h2>

            <?php if (!empty($products)): ?>

                <div class="product-grid">

                    <?php foreach ($products as $product): ?>

                        <?php
                        $productId = (int) ($product['product_id'] ?? 0);
                        $imageFile = trim(
                            $product['product_image'] ?? ''
                        );

                        if (strpos($imageFile, 'images/products/') === 0) {
                            $imageUrl = $base_url . '/' . $imageFile;
                        } elseif ($imageFile !== '') {
                            $imageUrl = $base_url
                                . '/images/products/'
                                . $imageFile;
                        } else {
                            $imageUrl = '';
                        }

                        $detailsUrl = $base_url
                            . '/views/single_product.php?pro_id='
                            . $productId;

                        $cartUrl = $base_url
                            . '/index.php?add_cart='
                            . $productId;
                        ?>

                        <article class="product-card">

                            <!-- Product image -->
                            <a href="<?php echo htmlspecialchars($detailsUrl); ?>">

                                <?php if ($imageUrl !== ''): ?>

                                    <img
                                        src="<?php echo htmlspecialchars($imageUrl); ?>"
                                        alt="<?php echo htmlspecialchars($product['product_title'] ?? 'Product'); ?>"
                                        loading="lazy"
                                    >

                                <?php else: ?>

                                    <div class="product-image-placeholder">
                                        Image unavailable
                                    </div>

                                <?php endif; ?>

                            </a>

                            <div class="product-info">

                                <!-- Product name -->
                                <h3>
                                    <?php
                                    echo htmlspecialchars(
                                        $product['product_title']
                                            ?? 'Unnamed Product'
                                    );
                                    ?>
                                </h3>

                                <!-- Product price -->
                                <p class="product-price">
                                    GH₵
                                    <?php
                                    echo number_format(
                                        (float) (
                                            $product['product_price'] ?? 0
                                        ),
                                        2
                                    );
                                    ?>
                                </p>

                                <!-- Product description -->
                                <?php if (!empty($product['product_desc'])): ?>

                                    <p class="product-description">
                                        <?php
                                        $description = $product['product_desc'];

                                        echo htmlspecialchars(
                                            strlen($description) > 90
                                                ? substr($description, 0, 90) . '...'
                                                : $description
                                        );
                                        ?>
                                    </p>

                                <?php endif; ?>

                                <!-- Product actions -->
                                <div class="product-actions">

                                    <a
                                        class="btn"
                                        href="<?php echo htmlspecialchars($detailsUrl); ?>"
                                    >
                                        Details
                                    </a>

                                    <a
                                        class="btn"
                                        href="<?php echo htmlspecialchars($cartUrl); ?>"
                                    >
                                        Add to Cart
                                    </a>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p>No products found.</p>

            <?php endif; ?>

        </section>

    </div>

</main>

<?php include __DIR__ . "/layout/footer.php"; ?>
