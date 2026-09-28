<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . "/../controllers/CustomerController.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../views/login.php");
    exit;
}


$email = trim($_POST['customer_email'] ?? '');

$password = $_POST['customer_pass'] ?? '';


if ($email === '' || $password === '') {

    $_SESSION['error'] = "Email and password are required.";

    header("Location: ../views/login.php");
    exit;
}


$email = strip_tags($email);


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] = "Please enter a valid email address.";

    header("Location: ../views/login.php");
    exit;
}


$controller = new CustomerController();

$customer = $controller->login($email, $password);


if ($customer !== false) {

    $_SESSION['customer_id'] = $customer['customer_id'];

    $_SESSION['customer_name'] = $customer['customer_name'];

    $_SESSION['customer_email'] = $customer['customer_email'];

    $_SESSION['user_role'] = $customer['user_role'];


    header("Location: ../index.php");

    exit;
}


$_SESSION['error'] = "Invalid email or password.";

header("Location: ../views/login.php");

exit;

?>