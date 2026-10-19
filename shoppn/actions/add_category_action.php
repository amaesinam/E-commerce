<?php

session_start();

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/admin/category.php");
    exit;
}

$category_name = trim($_POST['cat_name'] ?? '');

if ($category_name === '') {

    $_SESSION['error'] = "Category name is required.";

    header("Location: ../views/admin/category.php");
    exit;
}

$category_name = strip_tags($category_name);

$controller = new ProductController();

if ($controller->addCategory($category_name)) {

    $_SESSION['success'] = "Category added successfully.";

} else {

    $_SESSION['error'] = "Unable to add category.";
}

header("Location: ../views/admin/category.php");
exit;

?>