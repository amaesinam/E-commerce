<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | vickyshoppn</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f5ef;
            color: #26352b;
        }

        /* =========================
           NAVIGATION
        ========================= */

        .navbar {
            height: 75px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 7%;
            border-bottom: 1px solid #e8e3d8;
        }

        .logo {
            font-size: 26px;
            font-weight: 700;
            color: #315c43;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #344238;
            font-size: 15px;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: #315c43;
        }

        .cart {
            position: relative;
            font-size: 20px;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #d98245;
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-icon {
            font-size: 21px;
        }

        /* =========================
           BANNER
        ========================= */

        .banner {
            background: #eee2cf;
            padding: 35px 20px;
            text-align: center;
        }

        .banner h1 {
            font-size: 30px;
            color: #315c43;
            font-weight: 600;
        }

        /* =========================
           LOGIN SECTION
        ========================= */

        .login-section {
            min-height: 650px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .login-card {
            width: 900px;
            max-width: 100%;
            min-height: 500px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(49, 92, 67, 0.10);
            display: grid;
            grid-template-columns: 45% 55%;
            overflow: hidden;
        }

        /* =========================
           LEFT VISUAL
        ========================= */

        .login-visual {
            background: #e8dfce;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .visual-circle {
            width: 290px;
            height: 290px;
            border-radius: 50%;
            background: #f5eee2;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 35px;
        }

        .shopping-icon {
            font-size: 100px;
        }

        .visual-text {
            position: absolute;
            bottom: 35px;
            left: 0;
            right: 0;
            text-align: center;
            color: #315c43;
            font-size: 14px;
            font-weight: 600;
        }

        /* =========================
           FORM
        ========================= */

        .login-form {
            padding: 60px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            color: #315c43;
            font-size: 30px;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .login-form .subtitle {
            color: #7a827c;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .error-message {
            background: #fbe8df;
            color: #a94e2e;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #344238;
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            height: 50px;
            border: 1px solid #d8ddd8;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 14px;
            outline: none;
            background: #fafbf9;
        }

        .form-group input:focus {
            border-color: #315c43;
            box-shadow: 0 0 0 3px rgba(49, 92, 67, 0.08);
        }

        /* =========================
           REMEMBER / FORGOT
        ========================= */

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0 25px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #59635c;
        }

        .remember input {
            accent-color: #315c43;
        }

        .forgot {
            color: #315c43;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        /* =========================
           BUTTONS
        ========================= */

        .login-button {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: #315c43;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .login-button:hover {
            background: #264a35;
        }

        .register-button {
            display: block;
            width: 100%;
            height: 50px;
            border: 1px solid #315c43;
            border-radius: 10px;
            background: white;
            color: #315c43;
            text-decoration: none;
            text-align: center;
            padding-top: 14px;
            margin-top: 12px;
            font-size: 15px;
            font-weight: 600;
        }

        .register-button:hover {
            background: #f3f7f3;
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #315c43;
            color: #e8eee9;
            text-align: center;
            padding: 25px;
            font-size: 13px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 750px) {

            .navbar {
                height: auto;
                padding: 20px;
                flex-direction: column;
                gap: 18px;
            }

            .nav-links {
                gap: 18px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .login-card {
                grid-template-columns: 1fr;
            }

            .login-visual {
                min-height: 250px;
            }

            .visual-circle {
                width: 190px;
                height: 190px;
            }

            .shopping-icon {
                font-size: 65px;
            }

            .visual-text {
                display: none;
            }

            .login-form {
                padding: 40px 25px;
            }

            .login-form h2 {
                font-size: 25px;
            }
        }

    </style>

</head>

<body>

<!-- =========================
     NAVIGATION
========================= -->

<nav class="navbar">

    <a href="../index.php" class="logo">
        vickyshoppn
    </a>

    <div class="nav-links">

        <a href="../index.php">Home</a>

        <a href="#">Shop</a>

        <a href="#">About</a>

        <a href="#" class="cart">
            🛒
            <span class="cart-badge">0</span>
        </a>

        <a href="login.php" class="user-icon">
            ♙
        </a>

    </div>

</nav>


<!-- =========================
     PAGE BANNER
========================= -->

<section class="banner">

    <h1>Welcome to vickyshoppn</h1>

</section>


<!-- =========================
     LOGIN
========================= -->

<section class="login-section">

    <div class="login-card">

        <!-- LEFT SIDE -->

        <div class="login-visual">

            <div class="visual-circle">

                <div class="shopping-icon">
                    🛍️
                </div>

            </div>

            <div class="visual-text">
                Shop smarter. Shop happier.
            </div>

        </div>


        <!-- RIGHT SIDE -->

        <div class="login-form">

            <h2>
                Welcome Back.<br>
                Please Log In To Your Account.
            </h2>

            <p class="subtitle">
                Enter your details to continue shopping with us.
            </p>


            <?php

            if (isset($_SESSION['error'])) {

                echo '<div class="error-message">'
                    . htmlspecialchars($_SESSION['error'])
                    . '</div>';

                unset($_SESSION['error']);
            }

            ?>


            <form action="../actions/login_action.php" method="POST">

                <div class="form-group">

                    <label for="customer_email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="customer_email"
                        name="customer_email"
                        placeholder="abcemail@gmail.com"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="customer_pass">
                        Password
                    </label>

                    <input
                        type="password"
                        id="customer_pass"
                        name="customer_pass"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        Remember Password

                    </label>


                    <a href="#" class="forgot">
                        Forget Password?
                    </a>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Login
                </button>


                <a
                    href="register.php"
                    class="register-button"
                >
                    Create Account
                </a>

            </form>

        </div>

    </div>

</section>


<!-- =========================
     FOOTER
========================= -->

<footer>

    © 2026 vickyshoppn. All rights reserved.

</footer>

</body>

</html>