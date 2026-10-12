
<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';

$productController = new ProductController();

$user_query = trim($_GET['user_query'] ?? '');

$products = [];

if ($user_query !== '') {
    $products = $productController->searchProducts($user_query);
}

if (!is_array($products)) {
    $products = [];
}
?>

<?php include __DIR__ . "/layout/header.php"; ?>

<main class="shop-layout">

    <?php include __DIR__ . "/layout/sidebar.php"; ?>

    <section class="shop-content">

        <h1>Search Results</h1>

        <?php if ($user_query === ''): ?>

            <p>Please enter a search term to find products.</p>

        <?php else: ?>

            <p>
                Results for:
                <strong><?php echo htmlspecialchars($user_query); ?></strong>
            </p>

            <?php if (!empty($products)): ?>

                <div class="product-grid">

                    <?php foreach ($products as $product): ?>

                        <?php
                        $productId = (int) ($product['product_id'] ?? 0);
                        $imageFile = trim($product['product_image'] ?? '');

                        if (
                            $imageFile !== '' &&
                            strpos($imageFile, 'images/products/') === 0
                        ) {
                            $imageUrl = $base_url . '/' . $imageFile;
                        } elseif ($imageFile !== '') {
                            $imageUrl = $base_url . '/images/products/' . $imageFile;
                        } else {
                            $imageUrl = '';
                        }

                        $detailsUrl = $base_url
                            . '/views/single_product.php?pro_id=' . $productId;

                        $cartUrl = $base_url
                            . '/index.php?add_cart=' . $productId;
                        ?>

                        <article class="product-card">

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

                                <h3>
                                    <?php echo htmlspecialchars($product['product_title'] ?? 'Unnamed Product'); ?>
                                </h3>

                                <p class="product-price">
                                    GH₵
                                    <?php echo number_format((float) ($product['product_price'] ?? 0), 2); ?>
                                </p>

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

        <?php endif; ?>

    </section>

</main>

<?php include __DIR__ . "/layout/footer.php"; ?>
