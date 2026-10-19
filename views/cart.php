
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

$controller = new CartController();
$ip = get_ip();

$items = $controller->getCartItems($ip);
$total = $controller->getCartTotal($ip);

$page_url = base_url() . "/views/cart.php";
$remove_url = base_url() . "/actions/remove_from_cart_action.php";
$update_url = base_url() . "/actions/update_qty_action.php";

require_once __DIR__ . "/layout/header.php";

?>

<div class="container my-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($items)): ?>

        <div class="alert alert-info">
            Your shopping cart is empty.
        </div>

        <a href="<?= htmlspecialchars(base_url() . '/index.php') ?>"
           class="btn btn-success">
            Continue Shopping
        </a>

    <?php else: ?>

        <form id="remove-form"
              method="POST"
              action="<?= htmlspecialchars($remove_url) ?>">
        </form>

        <form id="update-form"
              method="POST"
              action="<?= htmlspecialchars($update_url) ?>">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>

                            <?php
                            $product_id = (int) $item['p_id'];
                            $quantity = (int) $item['qty'];
                            $price = (float) $item['product_price'];
                            $subtotal = $price * $quantity;

                            $image_name = basename(
                                $item['product_image'] ?? ''
                            );

                            $image_url = base_url()
                                . "/images/products/"
                                . rawurlencode($image_name);
                            ?>

                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        name="remove[]"
                                        value="<?= $product_id ?>"
                                        form="remove-form"
                                        aria-label="Remove <?= htmlspecialchars($item['product_title']) ?>"
                                    >
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($image_name !== ''): ?>
                                            <img
                                                src="<?= htmlspecialchars($image_url) ?>"
                                                alt="<?= htmlspecialchars($item['product_title']) ?>"
                                                style="width:70px;height:70px;object-fit:contain"
                                            >
                                        <?php endif; ?>

                                        <span>
                                            <?= htmlspecialchars($item['product_title']) ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    GHS <?= number_format($price, 2) ?>
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        name="qty[]"
                                        value="<?= $quantity ?>"
                                        min="1"
                                        step="1"
                                        required
                                        class="form-control"
                                        style="width:90px"
                                        aria-label="Quantity for <?= htmlspecialchars($item['product_title']) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="update[]"
                                        value="<?= $product_id ?>"
                                    >
                                </td>

                                <td>
                                    GHS <?= number_format($subtotal, 2) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">
                                Cart Total
                            </th>
                            <th>
                                GHS <?= number_format($total, 2) ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-success">
                Update Quantity
            </button>

        </form>

        <div class="d-flex flex-wrap gap-2 mt-3">

            <button
                type="submit"
                form="remove-form"
                class="btn btn-danger"
            >
                Remove Selected Items
            </button>

            <a href="<?= htmlspecialchars(base_url() . '/index.php') ?>"
               class="btn btn-secondary">
                Continue Shopping
            </a>

            <a href="<?= htmlspecialchars(base_url() . '/views/checkout.php') ?>"
               class="btn btn-primary">
                Checkout
            </a>

        </div>

        <p class="text-muted mt-3">
            Select the checkbox beside an item to remove it.
            Change a quantity and click Update Quantity to save it.
        </p>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . "/layout/footer.php"; ?>
