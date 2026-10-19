<?php

require_once __DIR__ . "/../../core/core.php";
require_admin();

require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();

$edit_brand = null;

/* =========================
   CHECK IF EDITING
========================= */

if (isset($_GET['edit_id'])) {

    $edit_id = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($edit_id && $edit_id > 0) {

        $edit_brand = $controller->getBrandById($edit_id);

    }
}

/* =========================
   GET ALL BRANDS
========================= */

$brands = $controller->getAllBrands();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $edit_brand ? "Edit Brand" : "Add Brand"; ?> | Shoppn
    </title>

    <link
        rel="stylesheet"
        href="<?php echo base_url(); ?>/css/style.css"
    >

</head>

<body>

<?php require_once __DIR__ . "/../layout/header.php"; ?>


<main class="container">

    <h1>Manage Brands</h1>

    <p>Add and manage product brands.</p>


    <!-- =========================
         SUCCESS MESSAGE
    ========================= -->

    <?php if (isset($_SESSION['success'])): ?>

        <div class="success">

            <?php

            echo htmlspecialchars($_SESSION['success']);

            unset($_SESSION['success']);

            ?>

        </div>

    <?php endif; ?>


    <!-- =========================
         ERROR MESSAGE
    ========================= -->

    <?php if (isset($_SESSION['error'])): ?>

        <div class="error">

            <?php

            echo htmlspecialchars($_SESSION['error']);

            unset($_SESSION['error']);

            ?>

        </div>

    <?php endif; ?>


    <!-- =========================
         ADD / EDIT BRAND FORM
    ========================= -->

    <h2>

        <?php if ($edit_brand): ?>

            Edit Brand

        <?php else: ?>

            Add Brand

        <?php endif; ?>

    </h2>


    <?php if ($edit_brand): ?>

        <form
            action="<?php echo base_url(); ?>/actions/update_brand_action.php"
            method="POST"
        >

            <input
                type="hidden"
                name="brand_id"
                value="<?php echo htmlspecialchars($edit_brand['brand_id']); ?>"
            >


            <label for="brand_name">
                Brand Name
            </label>

            <input
                type="text"
                id="brand_name"
                name="brand_name"
                value="<?php echo htmlspecialchars($edit_brand['brand_name']); ?>"
                required
            >


            <button type="submit">
                Update Brand
            </button>


            <a
                href="<?php echo base_url(); ?>/views/admin/brand.php"
                class="btn btn-secondary"
            >
                Cancel
            </a>

        </form>

    <?php else: ?>

        <form
            action="<?php echo base_url(); ?>/actions/add_brand_action.php"
            method="POST"
        >

            <label for="brand_name">
                Brand Name
            </label>

            <input
                type="text"
                id="brand_name"
                name="brand_name"
                placeholder="Enter brand name"
                required
            >


            <button type="submit">
                Add Brand
            </button>

        </form>

    <?php endif; ?>


    <!-- =========================
         EXISTING BRANDS
    ========================= -->

    <h2 style="margin-top: 40px;">
        Existing Brands
    </h2>


    <?php if (empty($brands)): ?>

        <p>No brands have been added yet.</p>

    <?php else: ?>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Brand Name</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                <?php foreach ($brands as $brand): ?>

                    <tr>

                        <td>
                            <?php
                            echo htmlspecialchars($brand['brand_id']);
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars($brand['brand_name']);
                            ?>
                        </td>


                        <td>

                            <a
                                href="<?php echo base_url(); ?>/views/admin/brand.php?edit_id=<?php echo $brand['brand_id']; ?>"
                                class="btn"
                            >
                                Edit
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</main>

</body>

</html>