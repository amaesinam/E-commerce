<?php

require_once __DIR__ . "/../../core/core.php";

require_login();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
</head>
<body>

    <h1>My Account</h1>

    <p>
        Welcome,
        <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!
    </p>

    <p>This page is only available to logged-in customers.</p>

</body>
</html>