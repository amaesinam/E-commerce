
<?php

require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/ProductController.php";

// Only administrators can access this page.
require_admin();

$controller = new ProductController();

// Helper function to safely display values.
function productEscape($value)
{
    return htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, "UTF-8");
}

// Check whether we are adding or editing a product.
$editId = filter_input(INPUT_GET, "edit_id", FILTER_VALIDATE_INT);
$isEditing = $editId && $editId > 0;

$product = null;

if ($isEditing) {
    $product = $controller->getProductById($editId);

    if (!$product) {
        header("Location: product.php?error=notfound");
        exit;
    }
}

// Get categories and brands for the dropdown menus.
$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();

$categories = is_array($categories) ? $categories : [];
$brands = is_array($brands) ? $brands : [];

// Display feedback messages.
$successMessage = "";
$errorMessage = "";

if (isset($_GET["success"])) {
    if ($_GET["success"] === "added") {
        $successMessage = "Product added successfully!";
    } elseif ($_GET["success"] === "updated") {
        $successMessage = "Product updated successfully!";
    }
}

if (isset($_GET["error"])) {
    $errors = [
        "invalid"  => "Please complete all fields correctly.",
        "image"    => "Please select an image and try again.",
        "size"     => "The image must not exceed 5 MB.",
        "type"     => "Only JPEG, PNG, GIF, and WEBP images are allowed.",
        "upload"   => "The image could not be uploaded. Please try again.",
        "database" => "The product could not be saved. Please try again.",
        "notfound" => "The requested product could not be found."
    ];

    $errorMessage = $errors[$_GET["error"]] ?? "Something went wrong.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $isEditing ? "Edit Product" : "Add Product" ?> | Shoppn Admin
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f5ef;
            color: #26352b;
        }

        .topbar {
            background: #244d38;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .topbar a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            font-size: 14px;
        }

        .topbar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 850px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-heading {
            margin-bottom: 22px;
        }

        .page-heading h2 {
            margin-bottom: 8px;
            font-size: 28px;
        }

        .page-heading p {
            color: #69736b;
            line-height: 1.6;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d8ded8;
            border-radius: 7px;
            font-size: 15px;
            font-family: inherit;
            background: white;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: 2px solid #b6d3be;
            border-color: #386b4b;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6c756e;
            font-size: 12px;
        }

        .current-image {
            margin-top: 12px;
        }

        .current-image img {
            display: block;
            width: 150px;
            height: 150px;
            object-fit: contain;
            border: 1px solid #e1e5df;
            border-radius: 8px;
            padding: 5px;
            background: #fff;
        }

        .message {
            padding: 13px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .success {
            background: #e5f4e9;
            color: #205b34;
        }

        .error {
            background: #fde8e6;
            color: #922f28;
        }

        .button-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .button {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .primary-button {
            background: #244d38;
            color: white;
        }

        .primary-button:hover {
            background: #193b29;
        }

        .secondary-button {
            background: #e9ece7;
            color: #26352b;
        }

        .secondary-button:hover {
            background: #dce2da;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #737b74;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .topbar {
                padding: 18px 5%;
            }

            .topbar a {
                margin-left: 0;
                margin-right: 12px;
            }

            .form-card {
                padding: 20px;
            }

            .page-heading h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <h1>Shoppn Admin</h1>

    <nav>
        <a href="../../index.php">View Store</a>
        <a href="brand.php">Brands</a>
        <a href="category.php">Categories</a>
    </nav>
</header>

<main class="container">

    <div class="page-heading">
        <h2>
            <?= $isEditing ? "Edit Product" : "Add New Product" ?>
        </h2>

        <p>
            <?= $isEditing
                ? "Update the product information below."
                : "Enter the details of the product you want to add to your store." ?>
        </p>
    </div>

    <?php if ($successMessage !== ""): ?>
        <div class="message success">
            <?= productEscape($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage !== ""): ?>
        <div class="message error">
            <?= productEscape($errorMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($categories) || empty($brands)): ?>

        <div class="message error">
            Before adding a product, make sure you have at least one category
            and one brand.

            <?php if (empty($categories)): ?>
                <p>
                    <a href="category.php">Add a category</a>
                </p>
            <?php endif; ?>

            <?php if (empty($brands)): ?>
                <p>
                    <a href="brand.php">Add a brand</a>
                </p>
            <?php endif; ?>
        </div>

    <?php else: ?>

        <section class="form-card">

            <form
                action="<?= $isEditing
                    ? "../../actions/update_product_action.php"
                    : "../../actions/add_product_action.php" ?>"
                method="POST"
                enctype="multipart/form-data"
            >

                <?php if ($isEditing): ?>
                    <input
                        type="hidden"
                        name="product_id"
                        value="<?= (int) $product["product_id"] ?>"
                    >
                <?php endif; ?>

                <div class="form-group">
                    <label for="product_title">Product Title</label>

                    <input
                        type="text"
                        id="product_title"
                        name="product_title"
                        maxlength="255"
                        required
                        value="<?= productEscape(
                            $product["product_title"] ?? ""
                        ) ?>"
                        placeholder="e.g. Classic T-Shirt"
                    >
                </div>

                <div class="form-group">
                    <label for="product_price">Price</label>

                    <input
                        type="number"
                        id="product_price"
                        name="product_price"
                        min="0"
                        step="0.01"
                        required
                        value="<?= productEscape(
                            $product["product_price"] ?? ""
                        ) ?>"
                        placeholder="e.g. 120.00"
                    >
                </div>

                <div class="form-group">
                    <label for="product_cat">Category</label>

                    <select
                        id="product_cat"
                        name="product_cat"
                        required
                    >
                        <option value="">Select a category</option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= (int) $category["cat_id"] ?>"
                                <?= (
                                    (string) ($product["product_cat"] ?? "")
                                    === (string) $category["cat_id"]
                                ) ? "selected" : "" ?>
                            >
                                <?= productEscape($category["cat_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product_brand">Brand</label>

                    <select
                        id="product_brand"
                        name="product_brand"
                        required
                    >
                        <option value="">Select a brand</option>

                        <?php foreach ($brands as $brand): ?>
                            <option
                                value="<?= (int) $brand["brand_id"] ?>"
                                <?= (
                                    (string) ($product["product_brand"] ?? "")
                                    === (string) $brand["brand_id"]
                                ) ? "selected" : "" ?>
                            >
                                <?= productEscape($brand["brand_name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product_desc">Description</label>

                    <textarea
                        id="product_desc"
                        name="product_desc"
                        required
                        placeholder="Describe the product..."
                    ><?= productEscape(
                        $product["product_desc"] ?? ""
                    ) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="product_keywords">Keywords</label>

                    <input
                        type="text"
                        id="product_keywords"
                        name="product_keywords"
                        required
                        value="<?= productEscape(
                            $product["product_keywords"] ?? ""
                        ) ?>"
                        placeholder="e.g. shirt, clothing, fashion"
                    >

                    <small class="help-text">
                        Separate keywords with commas.
                    </small>
                </div>

                <div class="form-group">
                    <label for="product_image">
                        Product Image
                        <?= $isEditing ? "(Optional)" : "" ?>
                    </label>

                    <input
                        type="file"
                        id="product_image"
                        name="product_image"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        <?= $isEditing ? "" : "required" ?>
                    >

                    <small class="help-text">
                        JPEG, PNG, GIF, or WEBP. Maximum size: 5 MB.
                    </small>

                    <?php if (
                        $isEditing &&
                        !empty($product["product_image"])
                    ): ?>
                        <div class="current-image">
                            <p><strong>Current image:</strong></p>

                            <img
                                src="../../images/<?= rawurlencode(
                                    basename($product["product_image"])
                                ) ?>"
                                alt="Current product image"
                            >
                        </div>
                    <?php endif; ?>

                    <div
                        class="current-image"
                        id="image-preview"
                        style="display: none;"
                    >
                        <p><strong>Selected image preview:</strong></p>
                        <img id="preview-image" src="" alt="Selected image preview">
                    </div>
                </div>

                <div class="button-row">
                    <button
                        type="submit"
                        class="button primary-button"
                    >
                        <?= $isEditing ? "Update Product" : "Add Product" ?>
                    </button>

                    <a
                        href="product.php"
                        class="button secondary-button"
                    >
                        <?= $isEditing ? "Cancel Editing" : "Clear Form" ?>
                    </a>
                </div>

            </form>

        </section>

    <?php endif; ?>

</main>

<footer class="footer">
    &copy; <?= date("Y") ?> Shoppn. All rights reserved.
</footer>

<script>
    const imageInput = document.getElementById("product_image");
    const previewContainer = document.getElementById("image-preview");
    const previewImage = document.getElementById("preview-image");

    if (imageInput) {
        imageInput.addEventListener("change", function () {
            const file = this.files[0];

            if (!file) {
                previewContainer.style.display = "none";
                previewImage.removeAttribute("src");
                return;
            }

            const allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp"
            ];

            const maxSize = 5 * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                alert("Please select a JPEG, PNG, GIF, or WEBP image.");
                this.value = "";
                previewContainer.style.display = "none";
                previewImage.removeAttribute("src");
                return;
            }

            if (file.size > maxSize) {
                alert("The image must not exceed 5 MB.");
                this.value = "";
                previewContainer.style.display = "none";
                previewImage.removeAttribute("src");
                return;
            }

            previewImage.src = URL.createObjectURL(file);
            previewContainer.style.display = "block";
        });
    }
</script>

</body>
</html>
