
<?php
require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/ProductController.php";

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';

$productController = new ProductController();

$categories = $productController->getAllCategories();
$brands = $productController->getAllBrands();
?>

<aside class="sidebar">

    <div class="sidebar-section">
        <h3>Categories</h3>

        <?php if (!empty($categories)): ?>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="<?php
                            echo htmlspecialchars(
                                $base_url . '/index.php?cat='
                                . (int) $category['cat_id']
                            );
                        ?>">
                            <?php echo htmlspecialchars($category['cat_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No categories available.</p>
        <?php endif; ?>
    </div>

    <div class="sidebar-section">
        <h3>Brands</h3>

        <?php if (!empty($brands)): ?>
            <ul>
                <?php foreach ($brands as $brand): ?>
                    <li>
                        <a href="<?php
                            echo htmlspecialchars(
                                $base_url . '/index.php?brand='
                                . (int) $brand['brand_id']
                            );
                        ?>">
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No brands available.</p>
        <?php endif; ?>
    </div>

    <div class="sidebar-section">
        <a href="<?php echo htmlspecialchars($base_url . '/index.php'); ?>">
            View All Products
        </a>
    </div>

</aside>
