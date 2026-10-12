
<?php
session_start();

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

// Allow only administrators to add products.
require_admin();

// Only process submitted forms.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/admin/product.php");
    exit;
}

// Get product details.
$title = trim($_POST["product_title"] ?? "");
$price = filter_var(
    $_POST["product_price"] ?? "",
    FILTER_VALIDATE_FLOAT
);
$category = filter_var(
    $_POST["product_cat"] ?? "",
    FILTER_VALIDATE_INT
);
$brand = filter_var(
    $_POST["product_brand"] ?? "",
    FILTER_VALIDATE_INT
);
$description = trim($_POST["product_desc"] ?? "");
$keywords = trim($_POST["product_keywords"] ?? "");

// Validate required details.
if (
    $title === "" ||
    $price === false ||
    $price < 0 ||
    !$category ||
    !$brand ||
    $description === ""
) {
    exit("Please enter valid details for all required fields.");
}

// Check the uploaded image.
if (
    !isset($_FILES["product_image"]) ||
    $_FILES["product_image"]["error"] !== UPLOAD_ERR_OK
) {
    exit("Please select a product image and try again.");
}

$image = $_FILES["product_image"];

// Allow JPG, PNG, and WEBP images only.
$allowedTypes = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp"
];

$imageInfo = getimagesize($image["tmp_name"]);

if (
    $imageInfo === false ||
    $image["size"] > 5 * 1024 * 1024
) {
    exit("The image is invalid or exceeds the 5 MB limit.");
}

$mimeType = $imageInfo["mime"];

if (!isset($allowedTypes[$mimeType])) {
    exit("Please upload a JPG, PNG, or WEBP image.");
}

// Generate a unique image filename.
$extension = $allowedTypes[$mimeType];
$imageFilename = bin2hex(random_bytes(16)) . "." . $extension;

// Set the image upload directory.
$imageDirectory = realpath(__DIR__ . "/../images/products");

if (
    $imageDirectory === false ||
    !is_dir($imageDirectory) ||
    !is_writable($imageDirectory)
) {
    exit("The images folder is missing or not writable.");
}

// Build the destination path.
$imagePath = $imageDirectory . DIRECTORY_SEPARATOR . $imageFilename;

// Upload the image.
if (!move_uploaded_file($image["tmp_name"], $imagePath)) {
    exit("The product image could not be uploaded.");
}

// Save the product details in the database.
$productController = new ProductController();

$success = $productController->addProduct(
    $category,
    $brand,
    $title,
    $price,
    $description,
    $imageFilename,
    $keywords
);

if ($success) {
    header("Location: ../views/admin/product.php?success=added");
    exit;
}

// Delete the uploaded image if saving fails.
if (file_exists($imagePath)) {
    unlink($imagePath);
}

exit(
    "The product could not be saved. " .
    "Please check the database and product table."
);
?>
