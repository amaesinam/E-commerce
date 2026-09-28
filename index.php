<?php
require_once __DIR__ . "/core/core.php";
?>

<?php include __DIR__ . "/views/layout/header.php"; ?>

<h2>Welcome to Shoppn</h2>

<?php if (is_logged_in()): ?>

    <h3>
        Welcome,
        <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!
    </h3>

    <p>You are successfully logged in.</p>

    <p>
        Email:
        <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
    </p>

    <p>
        User Role:
        <?php echo htmlspecialchars($_SESSION['user_role']); ?>
    </p>

<?php else: ?>

    <p>You are not logged in.</p>

    <p>
        <a href="views/login.php">Login</a>
    </p>

    <p>
        <a href="views/register.php">Register</a>
    </p>

<?php endif; ?>

<?php include __DIR__ . "/views/layout/footer.php"; ?>