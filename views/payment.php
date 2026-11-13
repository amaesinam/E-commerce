
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

// Only logged-in customers can make payments.
if (!is_logged_in()) {
    $_SESSION['error'] = "Please log in before making a payment.";
    header("Location: " . base_url() . "/views/login.php");
    exit;
}

$customerId = (int) $_SESSION['customer_id'];
$customerEmail = $_SESSION['customer_email'] ?? '';

if (
    $customerId < 1 ||
    !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)
) {
    $_SESSION['error'] = "Valid customer information is required.";
    header("Location: " . base_url() . "/views/checkout.php");
    exit;
}

$ip = get_ip();

$cartController = new CartController();

// Retrieve only this customer's cart.
$cartItems = $cartController->getCartItems($ip, $customerId);
$total = $cartController->getCartTotal($ip, $customerId);

if (empty($cartItems) || $total <= 0) {
    $_SESSION['error'] = "Your cart is empty. Add products before making payment.";
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

// Paystack expects the amount in pesewas.
$amountInPesewas = (int) round($total * 100);

$cartUrl = base_url() . "/views/cart.php";
$paymentActionUrl = base_url() . "/actions/process_payment_action.php";

require_once __DIR__ . "/layout/header.php";

?>

<main class="container" style="max-width: 900px; margin: 40px auto; padding: 20px;">

    <h1>Payment</h1>

    <p>Review your order before proceeding to Paystack.</p>

    <div class="mb-3">
        <strong>Customer:</strong>
        <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?>
    </div>

    <div class="mb-4">
        <strong>Email:</strong>
        <?php echo htmlspecialchars($customerEmail); ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <?php
                        $quantity = (int) $item['qty'];
                        $price = (float) $item['product_price'];
                        $subtotal = $quantity * $price;
                    ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($item['product_title']); ?>
                        </td>

                        <td><?php echo $quantity; ?></td>

                        <td>GH₵ <?php echo number_format($price, 2); ?></td>

                        <td>GH₵ <?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3 class="text-end mb-4">
        Total: GH₵ <?php echo number_format($total, 2); ?>
    </h3>

    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo htmlspecialchars($cartUrl); ?>"
           class="btn btn-secondary">
            Return to Cart
        </a>

        <button type="button"
                class="btn btn-success"
                onclick="payWithPaystack()">
            Pay with Paystack
        </button>
    </div>

</main>

<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
function payWithPaystack() {

    var handler = PaystackPop.setup({
        key: 'pk_test_f763b781cb91cd778a3067ab10e0205810116203',

        email: <?php echo json_encode($customerEmail); ?>,

        amount: <?php echo $amountInPesewas; ?>,

        currency: 'GHS',

        onSuccess: function(response) {
            window.location.href =
                <?php echo json_encode($paymentActionUrl); ?> +
                '?ref=' + encodeURIComponent(response.reference);
        },

        onCancel: function() {
            alert('Payment was cancelled.');
        }
    });

    handler.openIframe();
}
</script>

<?php require_once __DIR__ . "/layout/footer.php"; ?>
