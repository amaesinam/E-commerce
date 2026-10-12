
<?php
require_once __DIR__ . "/../../core/core.php";

$base_url = ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/shoppn'
    : '/~victoria.nyonator/ecommerce/shoppn';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shoppn</title>

    <!-- Load the latest stylesheet -->
    <link rel="stylesheet"
          href="<?php echo htmlspecialchars($base_url); ?>/css/style.css?v=4">
</head>

<body>

<header>
    <div class="logo">
        <a href="<?php echo htmlspecialchars($base_url); ?>/index.php">
            Shoppn
        </a>
    </div>

    <nav>
        <a href="<?php echo htmlspecialchars($base_url); ?>/index.php">Home</a>

        <?php if (is_logged_in()): ?>
            <a href="<?php echo htmlspecialchars($base_url); ?>/views/account/index.php">
                My Account
            </a>

            <?php if (is_admin()): ?>
                <a href="<?php echo htmlspecialchars($base_url); ?>/views/admin/brand.php">
                    Brands
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>/views/admin/category.php">
                    Categories
                </a>
                <a href="<?php echo htmlspecialchars($base_url); ?>/views/admin/product.php">
                    Products
                </a>
            <?php endif; ?>

            <a href="<?php echo htmlspecialchars($base_url); ?>/actions/logout_action.php">
                Logout
            </a>
        <?php else: ?>
            <a href="<?php echo htmlspecialchars($base_url); ?>/views/login.php">
                Login
            </a>
            <a href="<?php echo htmlspecialchars($base_url); ?>/views/register.php">
                Register
            </a>
        <?php endif; ?>
    </nav>

    <form
        class="search-form"
        action="<?php echo htmlspecialchars($base_url . '/views/search_results.php'); ?>"
        method="GET"
    >
        <input
            type="text"
            name="user_query"
            placeholder="Search products..."
            aria-label="Search products"
            required
        >
        <button type="submit">Search</button>
    </form>
</header>
