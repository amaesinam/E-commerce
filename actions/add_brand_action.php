<?php

session_start();

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/admin/brand.php");
    exit;
}

$brand_name = trim($_POST['brand_name'] ?? '');

if ($brand_name === '') {
    $_SESSION['error'] = "Brand name is required.";
    header("Location: ../views/admin/brand.php");
    exit;
}

$brand_name = strip_tags($brand_name);

$controller = new ProductController();

if ($controller->addBrand($brand_name)) {

    $_SESSION['success'] = "Brand added successfully.";

} else {

    $_SESSION['error'] = "Unable to add brand.";
}

header("Location: ../views/admin/brand.php");
exit;

?>