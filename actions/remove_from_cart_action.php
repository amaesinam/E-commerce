
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

// Only logged-in customers can remove cart items.
if (!is_logged_in()) {
    $_SESSION['error'] = "Please log in before modifying your cart.";
    header("Location: " . base_url() . "/views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

$customerId = (int) $_SESSION['customer_id'];
$selected = $_POST['remove'] ?? [];

if (!is_array($selected)) {
    $_SESSION['error'] = "Invalid product selection.";
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

if (empty($selected)) {
    $_SESSION['error'] = "Please select an item to remove.";
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

$controller = new CartController();
$ip = get_ip();
$removed = 0;

foreach ($selected as $product_id) {
    $valid_id = filter_var($product_id, FILTER_VALIDATE_INT);

    if ($valid_id === false || $valid_id < 1) {
        continue;
    }

    if ($controller->removeFromCart($valid_id, $ip, $customerId)) {
        $removed++;
    }
}

if ($removed > 0) {
    $_SESSION['success'] = "Selected item(s) removed from your cart.";
} else {
    $_SESSION['error'] = "No items were removed.";
}

header("Location: " . base_url() . "/views/cart.php");
exit;
