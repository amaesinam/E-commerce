
<?php

require_once __DIR__ . "/../core/core.php";

// Get the payment error message.
$errorMessage = $_SESSION['payment_error'] ?? 
    "Your payment could not be completed. Please try again.";

// Clear the message after retrieving it.
unset($_SESSION['payment_error']);

$paymentUrl = base_url() . "/views/payment.php";
$cartUrl = base_url() . "/views/cart.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Shoppn</title>

    <style>
        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background: #f8f5ef;
            color: #333;
        }

        .failure-container {
            max-width: 550px;
            margin: 70px auto;
            padding: 35px;
            background: #fff;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .failure-icon {
            font-size: 55px;
            color: #c0392b;
            margin-bottom: 15px;
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
        }

        .message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            margin: 6px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .payment-btn {
            background: #477a50;
            color: white;
        }

        .cart-btn {
            background: #eee7dc;
            color: #333;
        }
    </style>
</head>

<body>

<div class="failure-container">

    <div class="failure-icon">&#10006;</div>

    <h1>Payment Failed</h1>

    <p class="message">
        <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <a href="<?php echo htmlspecialchars($paymentUrl, ENT_QUOTES, 'UTF-8'); ?>"
       class="btn payment-btn">
        Try Again
    </a>

    <a href="<?php echo htmlspecialchars($cartUrl, ENT_QUOTES, 'UTF-8'); ?>"
       class="btn cart-btn">
        Return to Cart
    </a>

</div>

</body>
</html>
