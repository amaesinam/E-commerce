
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

$quantities = $_POST['qty'] ?? [];
$product_ids = $_POST['update'] ?? [];

if (
    !is_array($quantities) ||
    !is_array($product_ids) ||
    count($quantities) !== count($product_ids) ||
    empty($product_ids)
) {
    $_SESSION['error'] = "Invalid quantity update request.";
    header("Location: " . base_url() . "/views/cart.php");
    exit;
}

$controller = new CartController();
$ip = get_ip();
$updated = 0;
$invalid = false;

foreach ($product_ids as $index => $product_id) {
    $valid_id = filter_var($product_id, FILTER_VALIDATE_INT);
    $qty = $quantities[$index] ?? null;
    $valid_qty = filter_var($qty, FILTER_VALIDATE_INT);

    if (
        $valid_id === false ||
        $valid_id < 1 ||
        $valid_qty === false ||
        $valid_qty < 1
    ) {
        $invalid = true;
        continue;
    }

    if ($controller->updateQty($valid_id, $valid_qty, $ip)) {
        $updated++;
    }
}

if ($invalid) {
    $_SESSION['error'] = "Some quantities were invalid. Enter whole numbers of at least 1.";
} elseif ($updated > 0) {
    $_SESSION['success'] = "Cart quantities updated successfully.";
} else {
    $_SESSION['error'] = "No cart quantities were updated.";
}

header("Location: " . base_url() . "/views/cart.php");
exit;
