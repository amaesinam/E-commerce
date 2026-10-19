
<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';

$productController = new ProductController();

// Task 10 requires the pro_id parameter.
$product_id = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT);

if (!$product_id || $product_id < 1) {
    header("Location: " . $base_url . "/index.php");
    exit;
}

$product = $productController->getProductById($product_id);

if (!$product) {
    header("Location: " . $base_url . "/index.php");
    exit;
}

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

$categoryName = $product['cat_name']
    ?? $product['category_name']
    ?? 'Not specified';

$brandName = $product['brand_name'] ?? 'Not specified';

$keywords = $product['product_keywords'] ?? 'Not specified';

$cartUrl = $base_url . '/index.php?add_cart=' . (int) $product['product_id'];
?>

<?php include __DIR__ . "/layout/header.php"; ?>

<main>
    <section class="product-details">

        <div class="product-details-image">
            <?php if ($imageUrl !== ''): ?>
                <img
                    src="<?php echo htmlspecialchars($imageUrl); ?>"
                    alt="<?php echo htmlspecialchars($product['product_title'] ?? 'Product'); ?>"
                >
            <?php else: ?>
                <p>Image unavailable.</p>
            <?php endif; ?>
        </div>

        <div class="product-details-info">

            <h1>
                <?php echo htmlspecialchars($product['product_title'] ?? 'Product'); ?>
            </h1>

            <p class="product-price">
                GH₵ <?php echo number_format((float) ($product['product_price'] ?? 0), 2); ?>
            </p>

            <p>
                <strong>Category:</strong>
                <?php echo htmlspecialchars($categoryName); ?>
            </p>

            <p>
                <strong>Brand:</strong>
                <?php echo htmlspecialchars($brandName); ?>
            </p>

            <h3>Product Description</h3>

            <p>
                <?php
                echo nl2br(
                    htmlspecialchars(
                        $product['product_desc'] ?? 'No description available.'
                    )
                );
                ?>
            </p>

            <p>
                <strong>Keywords:</strong>
                <?php echo htmlspecialchars($keywords); ?>
            </p>

            <div class="product-actions">
                <a class="btn" href="<?php echo htmlspecialchars($cartUrl); ?>">
                    Add to Cart
                </a>

                <a class="btn" href="<?php echo htmlspecialchars($base_url . '/index.php'); ?>">
                    Back to Shop
                </a>
            </div>

        </div>
    </section>
</main>

<?php include __DIR__ . "/layout/footer.php"; ?>
