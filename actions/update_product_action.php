
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

// Only administrators can update products.
require_admin();

// Accept POST requests only.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/admin/product.php");
    exit;
}

// Get the product ID.
$id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: ../views/admin/product.php?error=invalid");
    exit;
}

$controller = new ProductController();

// Confirm the product exists.
$existingProduct = $controller->getProductById($id);

if (!$existingProduct) {
    header("Location: ../views/admin/product.php?error=notfound");
    exit;
}

// Get submitted product details.
$cat = filter_input(INPUT_POST, "product_cat", FILTER_VALIDATE_INT);
$brand = filter_input(INPUT_POST, "product_brand", FILTER_VALIDATE_INT);
$title = trim($_POST["product_title"] ?? "");
$price = $_POST["product_price"] ?? "";
$desc = trim($_POST["product_desc"] ?? "");
$keywords = trim($_POST["product_keywords"] ?? "");

// Validate required fields.
if (
    !$cat ||
    !$brand ||
    $title === "" ||
    $price === "" ||
    !is_numeric($price) ||
    (float) $price < 0 ||
    $desc === "" ||
    $keywords === ""
) {
    header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=invalid");
    exit;
}

// Keep the current image unless a replacement is uploaded.
$imageFilename = $existingProduct["product_image"];
$newImagePath = null;

if (
    isset($_FILES["product_image"]) &&
    $_FILES["product_image"]["error"] !== UPLOAD_ERR_NO_FILE
) {
    $image = $_FILES["product_image"];

    // Stop if the upload reported an error.
    if ($image["error"] !== UPLOAD_ERR_OK) {
        header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=image");
        exit;
    }

    // Limit image size to 5 MB.
    $maxSize = 5 * 1024 * 1024;

    if ($image["size"] > $maxSize) {
        header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=size");
        exit;
    }

    // Check actual MIME type, not just the filename extension.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($image["tmp_name"]);

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/gif"  => "gif",
        "image/webp" => "webp"
    ];

    if (
        !isset($allowedTypes[$mimeType]) ||
        getimagesize($image["tmp_name"]) === false
    ) {
        header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=type");
        exit;
    }

    // Ensure the destination folder exists.
    $imageDirectory = __DIR__ . "/../images/products/";

    if (
        !is_dir($imageDirectory) &&
        !mkdir($imageDirectory, 0755, true) &&
        !is_dir($imageDirectory)
    ) {
        header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=upload");
        exit;
    }

    // Generate a unique filename for the replacement image.
    $imageFilename = uniqid("product_", true)
        . "." . $allowedTypes[$mimeType];

    $newImagePath = $imageDirectory . $imageFilename;

    if (!move_uploaded_file($image["tmp_name"], $newImagePath)) {
        header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=upload");
        exit;
    }
}

// Update product details in the database.
$success = $controller->updateProduct(
    $id,
    $cat,
    $brand,
    $title,
    (float) $price,
    $desc,
    $imageFilename,
    $keywords
);

if ($success) {
    // Remove the old image only after the database update succeeds.
    if (
        $newImagePath !== null &&
        !empty($existingProduct["product_image"])
    ) {
        $oldImagePath = __DIR__ . "/../images/products/"
            . basename($existingProduct["product_image"]);

        if (
            is_file($oldImagePath) &&
            realpath($oldImagePath) !== realpath($newImagePath)
        ) {
            unlink($oldImagePath);
        }
    }

    header("Location: ../views/admin/product.php?success=updated");
    exit;
}

// If the database update failed, remove the newly uploaded image.
if ($newImagePath !== null && is_file($newImagePath)) {
    unlink($newImagePath);
}

header("Location: ../views/admin/product.php?edit_id=" . $id . "&error=database");
exit;

?>
