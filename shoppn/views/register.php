<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Customer Registration</title>

</head>


<body>

    <h1>Create an Account</h1>

    <p>Register as a new customer.</p>


    <!-- Message from JavaScript -->
    <div id="register-message"></div>


    <form
        id="register-form"
        method="POST"
        enctype="multipart/form-data"
    >

        <!-- Full Name -->
        <div>

            <label for="customer_name">
                Full Name
            </label>

            <input
                type="text"
                id="customer_name"
                name="customer_name"
                placeholder="Enter your full name"
                required
            >

        </div>


        <br>


        <!-- Email -->
        <div>

            <label for="customer_email">
                Email
            </label>

            <input
                type="email"
                id="customer_email"
                name="customer_email"
                placeholder="Enter your email"
                required
            >

        </div>


        <br>


        <!-- Password -->
        <div>

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


        <br>


        <!-- Country -->
        <div>

            <label for="customer_country">
                Country
            </label>

            <input
                type="text"
                id="customer_country"
                name="customer_country"
                placeholder="Enter your country"
                required
            >

        </div>


        <br>


        <!-- City -->
        <div>

            <label for="customer_city">
                City
            </label>

            <input
                type="text"
                id="customer_city"
                name="customer_city"
                placeholder="Enter your city"
                required
            >

        </div>


        <br>


        <!-- Contact -->
        <div>

            <label for="customer_contact">
                Contact Number
            </label>

            <input
                type="text"
                id="customer_contact"
                name="customer_contact"
                placeholder="Enter your contact number"
                required
            >

        </div>


        <br>


        <!-- Optional image -->
        <div>

            <label for="customer_image">
                Profile Image (Optional)
            </label>

            <input
                type="file"
                id="customer_image"
                name="customer_image"
                accept="image/*"
            >

        </div>


        <br>


        <button type="submit">
            Register
        </button>

    </form>


    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>


    <script src="../js/customer.js"></script>

</body>

</html>