<?php

require_once __DIR__ . "/../controllers/CustomerController.php";

header("Content-Type: application/json");


// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


// Get form data
$name = trim($_POST['customer_name'] ?? '');

$email = trim($_POST['customer_email'] ?? '');

$pass = $_POST['customer_pass'] ?? '';

$country = trim($_POST['customer_country'] ?? '');

$city = trim($_POST['customer_city'] ?? '');

$contact = trim($_POST['customer_contact'] ?? '');


// Image is optional during registration
$image = null;


// New registrations are normal customers
$role = 2;


// Check required fields
if (
    $name === '' ||
    $email === '' ||
    $pass === '' ||
    $country === '' ||
    $city === '' ||
    $contact === ''
) {

    echo json_encode([
        "success" => false,
        "message" => "All required fields must be filled."
    ]);

    exit;
}


// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);

    exit;
}


// Validate password length
if (strlen($pass) < 8) {

    echo json_encode([
        "success" => false,
        "message" => "Password must be at least 8 characters."
    ]);

    exit;
}


// Create controller
$controller = new CustomerController();


// Check whether email already exists
if ($controller->emailExists($email)) {

    echo json_encode([
        "success" => false,
        "message" => "Email already registered."
    ]);

    exit;
}


// Insert customer
// The password is hashed inside CustomerClass.php.
$result = $controller->insert(
    $name,
    $email,
    $pass,
    $country,
    $city,
    $contact,
    $image,
    $role
);


// Send response
if ($result) {

    echo json_encode([
        "success" => true,
        "message" => "Registration successful."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
}

?>