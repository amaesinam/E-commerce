<?php

session_start();

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/admin/brand.php");
    exit;
}

$id = $_POST['brand_id'] ?? '';
$name = trim($_POST['brand_name'] ?? '');

if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {

    $_SESSION['error'] = "Invalid brand ID.";

    header("Location: ../views/admin/brand.php");
    exit;
}

if ($name === '') {

    $_SESSION['error'] = "Brand name is required.";

    header("Location: ../views/admin/brand.php?edit_id=" . $id);
    exit;
}

$name = strip_tags($name);

$controller = new ProductController();

if ($controller->updateBrand($id, $name)) {

    $_SESSION['success'] = "Brand updated successfully.";

} else {

    $_SESSION['error'] = "Unable to update brand.";
}

header("Location: ../views/admin/brand.php");
exit;

?>