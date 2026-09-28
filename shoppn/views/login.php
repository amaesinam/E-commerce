<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Login</title>
</head>

<body>

    <h1>Customer Login</h1>

    <?php

    if (isset($_SESSION['error'])) {

        echo "<p>" . htmlspecialchars($_SESSION['error']) . "</p>";

        unset($_SESSION['error']);
    }

    ?>

    <form action="../actions/login_action.php" method="POST">

        <div>
            <label for="customer_email">Email</label>

            <input
                type="email"
                id="customer_email"
                name="customer_email"
                placeholder="Enter your email"
                required
            >
        </div>

        <br>

        <div>
            <label for="customer_pass">Password</label>

            <input
                type="password"
                id="customer_pass"
                name="customer_pass"
                placeholder="Enter your password"
                required
            >
        </div>

        <br>

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</body>

</html>