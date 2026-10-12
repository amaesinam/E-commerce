
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | Shoppn</title>

    <style>
        /* General page styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f3eb;
            color: #25352b;
            min-height: 100vh;
            padding: 40px 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Registration card */
        .register-container {
            width: 100%;
            max-width: 620px;
            background: #ffffff;
            padding: 38px;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(35, 55, 40, 0.09);
            border: 1px solid #eee8dd;
        }

        /* Heading */
        .brand {
            text-align: center;
            font-size: 30px;
            font-weight: 800;
            color: #286344;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .brand span {
            color: #d69b69;
        }

        h1 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 10px;
            color: #25352b;
        }

        .intro {
            text-align: center;
            color: #777e76;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        /* Form fields */
        #register-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            color: #35463a;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #dddcd3;
            border-radius: 9px;
            background: #fdfcf9;
            color: #25352b;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input::placeholder {
            color: #a3a59e;
        }

        input:focus {
            border-color: #367953;
            box-shadow: 0 0 0 3px rgba(54, 121, 83, 0.12);
            background: #ffffff;
        }

        input[type="file"] {
            padding: 10px;
            background: #f8f7f2;
            font-size: 12px;
        }

        /* Register button */
        button {
            grid-column: 1 / -1;
            width: 100%;
            padding: 15px;
            margin-top: 4px;
            border: none;
            border-radius: 9px;
            background: #286344;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        button:hover {
            background: #1e4d34;
            transform: translateY(-1px);
        }

        button:focus-visible {
            outline: 3px solid #d69b69;
            outline-offset: 3px;
        }

        /* JavaScript response messages */
        #register-message {
            display: none;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        #register-message:not(:empty) {
            display: block;
        }

        /* Login link */
        .login-text {
            margin-top: 24px;
            text-align: center;
            color: #777e76;
            font-size: 14px;
            line-height: 1.8;
        }

        .login-text a {
            color: #286344;
            font-weight: 700;
            text-decoration: none;
        }

        .login-text a:hover {
            color: #1e4d34;
            text-decoration: underline;
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
            color: #a09e94;
            font-size: 11px;
        }

        /* Mobile layout */
        @media (max-width: 520px) {
            body {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .register-container {
                padding: 26px 20px;
                border-radius: 14px;
            }

            .brand {
                font-size: 27px;
            }

            h1 {
                font-size: 23px;
            }

            #register-form {
                grid-template-columns: 1fr;
                gap: 17px;
            }

            .form-group.full-width,
            button {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

    <main class="register-container">

        <div class="brand">vicky<span>shoppn</span></div>

        <h1>Create an Account</h1>

        <p class="intro">
            Join us today and discover products you'll love.
            Fill in your details below to get started.
        </p>

        <!-- Message from JavaScript -->
        <div id="register-message" role="status" aria-live="polite"></div>

        <form
            id="register-form"
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- Full Name -->
            <div class="form-group full-width">
                <label for="customer_name">Full Name</label>
                <input
                    type="text"
                    id="customer_name"
                    name="customer_name"
                    placeholder="Enter your full name"
                    autocomplete="name"
                    required
                >
            </div>

            <!-- Email -->
            <div class="form-group full-width">
                <label for="customer_email">Email Address</label>
                <input
                    type="email"
                    id="customer_email"
                    name="customer_email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    required
                >
            </div>

            <!-- Password -->
            <div class="form-group full-width">
                <label for="customer_pass">Password</label>
                <input
                    type="password"
                    id="customer_pass"
                    name="customer_pass"
                    placeholder="Create a password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <!-- Country -->
            <div class="form-group">
                <label for="customer_country">Country</label>
                <input
                    type="text"
                    id="customer_country"
                    name="customer_country"
                    placeholder="e.g. Ghana"
                    autocomplete="country-name"
                    required
                >
            </div>

            <!-- City -->
            <div class="form-group">
                <label for="customer_city">City</label>
                <input
                    type="text"
                    id="customer_city"
                    name="customer_city"
                    placeholder="Enter your city"
                    autocomplete="address-level2"
                    required
                >
            </div>

            <!-- Contact -->
            <div class="form-group full-width">
                <label for="customer_contact">Contact Number</label>
                <input
                    type="tel"
                    id="customer_contact"
                    name="customer_contact"
                    placeholder="Enter your contact number"
                    autocomplete="tel"
                    required
                >
            </div>

            <!-- Optional profile image -->
            <div class="form-group full-width">
                <label for="customer_image">Profile Image (Optional)</label>
                <input
                    type="file"
                    id="customer_image"
                    name="customer_image"
                    accept="image/*"
                >
            </div>

            <button type="submit">Create My Account</button>

        </form>

        <p class="login-text">
            Already have an account?
            <a href="login.php">Log in here</a>
        </p>

        <p class="footer-note">
            &copy; 2026 Shoppn. All rights reserved.
        </p>

    </main>

    <script src="../js/customer.js"></script>

</body>
</html>
