<?php

require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/ProductController.php";

require_admin();

$controller = new ProductController();

$brands = $controller->getAllBrands();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Brands</title>

</head>

<body>

    <h1>Manage Brands</h1>

    <?php if ($success): ?>

        <p>
            <?php echo htmlspecialchars($success); ?>
        </p>

    <?php endif; ?>


    <?php if ($error): ?>

        <p>
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>


    <h2>Add Brand</h2>

    <form
        method="POST"
        action="../../actions/add_brand_action.php"
    >

        <label for="brand_name">
            Brand Name
        </label>

        <input
            type="text"
            id="brand_name"
            name="brand_name"
            required
        >

        <button type="submit">
            Add Brand
        </button>

    </form>


    <h2>Existing Brands</h2>

    <?php if (empty($brands)): ?>

        <p>No brands have been added yet.</p>

    <?php else: ?>

        <table border="1">

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
                            <?php echo htmlspecialchars($brand['brand_id']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </td>

                        <td>

                            <a
                                href="brand.php?edit_id=<?php echo $brand['brand_id']; ?>"
                            >
                                Edit
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</body>

</html>