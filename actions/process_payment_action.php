
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";
require_once __DIR__ . "/../core/paystack_config.php";

// Redirect to the payment failure page.
function paymentFailed($message)
{
    $_SESSION['payment_error'] = $message;

    header("Location: " . base_url() . "/views/payment_failed.php");
    exit;
}

// Require a logged-in customer.
if (!is_logged_in()) {
    paymentFailed("Please log in before completing payment.");
}

$customerId = (int) $_SESSION['customer_id'];
$customerEmail = $_SESSION['customer_email'] ?? '';
$reference = trim($_GET['ref'] ?? '');

if (
    $customerId < 1 ||
    !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)
) {
    paymentFailed("Invalid customer information. Please log in again.");
}

// Validate the Paystack reference.
if (
    $reference === '' ||
    strlen($reference) > 100 ||
    !preg_match('/^[A-Za-z0-9\-_.=]+$/', $reference)
) {
    paymentFailed("Invalid payment reference.");
}

// Check that the secret key is configured.
if (
    !defined('PAYSTACK_SECRET_KEY') ||
    strpos(PAYSTACK_SECRET_KEY, 'sk_test_') !== 0
) {
    error_log("Paystack test secret key is not configured.");
    paymentFailed("Payment service is not configured yet.");
}

// Check whether this reference was already processed in this session.
if (isset($_SESSION['completed_payments'][$reference])) {
    $existingOrderId = (int) $_SESSION['completed_payments'][$reference];

    header(
        "Location: " . base_url() .
        "/views/payment_success.php?order_id=" . $existingOrderId
    );
    exit;
}

$ip = get_ip();

$cartController = new CartController();

// Retrieve only this customer's cart.
$cartItems = $cartController->getCartItems($ip, $customerId);
$cartTotal = $cartController->getCartTotal($ip, $customerId);

if (empty($cartItems) || $cartTotal <= 0) {
    paymentFailed("Your cart is empty or the total is invalid.");
}

// Convert expected amount to pesewas.
$expectedAmount = (int) round($cartTotal * 100);

// Verify the transaction directly with Paystack.
$url = "https://api.paystack.co/transaction/verify/" .
       rawurlencode($reference);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
        "Cache-Control: no-cache"
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 10
]);

$apiResponse = curl_exec($curl);
$curlError = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($apiResponse === false || $curlError !== '') {
    error_log("Paystack connection error: " . $curlError);
    paymentFailed("Unable to contact the payment service. Please try again.");
}

$verification = json_decode($apiResponse, true);

if (
    $httpCode !== 200 ||
    !is_array($verification) ||
    ($verification['status'] ?? false) !== true ||
    !isset($verification['data'])
) {
    error_log("Paystack verification failed. HTTP: " . $httpCode);
    paymentFailed("We could not verify your payment.");
}

$transaction = $verification['data'];

// Extract verified transaction details.
$verifiedStatus = $transaction['status'] ?? '';
$verifiedReference = $transaction['reference'] ?? '';
$verifiedAmount = (int) ($transaction['amount'] ?? 0);
$verifiedCurrency = $transaction['currency'] ?? '';
$verifiedEmail = $transaction['customer']['email'] ?? '';

// Confirm that Paystack reports a successful payment.
if ($verifiedStatus !== 'success') {
    paymentFailed("Your payment was not successful.");
}

// Confirm the transaction reference.
if ($verifiedReference !== $reference) {
    paymentFailed("The payment reference does not match.");
}

// Confirm the amount.
if ($verifiedAmount !== $expectedAmount) {
    paymentFailed("The payment amount does not match your cart total.");
}

// Confirm the currency.
if ($verifiedCurrency !== 'GHS') {
    paymentFailed("The payment currency is incorrect.");
}

// Confirm the customer's email.
if (strcasecmp($verifiedEmail, $customerEmail) !== 0) {
    paymentFailed("The payment email does not match your account.");
}

// Create the order only after successful verification.
$orderId = $cartController->processPayment(
    $customerId,
    $ip,
    $reference,
    $verifiedAmount / 100
);

if (!$orderId) {
    error_log("Verified payment could not be converted into an order.");

    paymentFailed(
        "Your payment was verified, but your order could not be created. Please contact support."
    );
}

// Store the completed reference in the session.
$_SESSION['completed_payments'][$reference] = (int) $orderId;

// Redirect to the invoice.
header(
    "Location: " . base_url() .
    "/views/payment_success.php?order_id=" . (int) $orderId
);

exit;

?>
