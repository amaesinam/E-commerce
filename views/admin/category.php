<?php

require_once __DIR__ . "/../../core/core.php";
require_admin();

require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();

$edit_category = null;


/* =========================
   CHECK IF EDITING
========================= */

if (isset($_GET['edit_id'])) {

    $edit_id = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($edit_id && $edit_id > 0) {

        $edit_category = $controller->getCategoryById($edit_id);

    }
}


/* =========================
   GET ALL CATEGORIES
========================= */

$categories = $controller->getAllCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $edit_category ? "Edit Category" : "Add Category"; ?> | Shoppn
    </title>

    <link
        rel="stylesheet"
        href="<?php echo base_url(); ?>/css/style.css"
    >

</head>

<body>

<?php require_once __DIR__ . "/../layout/header.php"; ?>


<main class="container">

    <h1>Manage Categories</h1>

    <p>Add and manage product categories.</p>


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
         ADD / EDIT CATEGORY FORM
    ========================= -->

    <h2>

        <?php if ($edit_category): ?>

            Edit Category

        <?php else: ?>

            Add Category

        <?php endif; ?>

    </h2>


    <?php if ($edit_category): ?>

        <!-- EDIT CATEGORY -->

        <form
            action="<?php echo base_url(); ?>/actions/update_category_action.php"
            method="POST"
        >

            <input
                type="hidden"
                name="cat_id"
                value="<?php echo htmlspecialchars($edit_category['cat_id']); ?>"
            >


            <label for="cat_name">
                Category Name
            </label>

            <input
                type="text"
                id="cat_name"
                name="cat_name"
                value="<?php echo htmlspecialchars($edit_category['cat_name']); ?>"
                required
            >


            <button type="submit">
                Update Category
            </button>


            <a
                href="<?php echo base_url(); ?>/views/admin/category.php"
                class="btn btn-secondary"
            >
                Cancel
            </a>

        </form>


    <?php else: ?>

        <!-- ADD CATEGORY -->

        <form
            action="<?php echo base_url(); ?>/actions/add_category_action.php"
            method="POST"
        >

            <label for="cat_name">
                Category Name
            </label>

            <input
                type="text"
                id="cat_name"
                name="cat_name"
                placeholder="Enter category name"
                required
            >


            <button type="submit">
                Add Category
            </button>

        </form>

    <?php endif; ?>


    <!-- =========================
         EXISTING CATEGORIES
    ========================= -->

    <h2 style="margin-top: 40px;">
        Existing Categories
    </h2>


    <?php if (empty($categories)): ?>

        <p>No categories have been added yet.</p>

    <?php else: ?>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Category Name</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                <?php foreach ($categories as $category): ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars($category['cat_id']);

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars($category['cat_name']);

                            ?>

                        </td>


                        <td>

                            <a
                                href="<?php echo base_url(); ?>/views/admin/category.php?edit_id=<?php echo $category['cat_id']; ?>"
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