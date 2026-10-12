<?php

require_once __DIR__ . "/../../core/core.php";

require_login();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Account | Shoppn</title>

    <link
        rel="stylesheet"
        href="<?php echo base_url(); ?>/css/style.css"
    >

</head>

<body>

<?php require_once __DIR__ . "/../layout/header.php"; ?>


<main class="container">

    <h1>My Account</h1>


    <p>
        Welcome,
        <strong>
            <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
        </strong>!
    </p>


    <?php if (is_admin()): ?>

        <!-- ADMIN ACCOUNT -->

        <p>
            You are logged in as an <strong>Administrator</strong>.
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
        </p>

        <p>
            You have access to the Shoppn administration features.
        </p>


    <?php else: ?>

        <!-- CUSTOMER ACCOUNT -->

        <p>
            You are logged in as a <strong>Customer</strong>.
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
        </p>

        <p>
            Welcome to your Shoppn customer account.
        </p>

    <?php endif; ?>


    <p>

        <a href="<?php echo base_url(); ?>/index.php">
            Back to Home
        </a>

    </p>

</main>


</body>

</html>