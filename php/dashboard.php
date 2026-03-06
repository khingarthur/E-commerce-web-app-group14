<?php
session_start();
require_once __DIR__ . '/product_store.php';

// -------------------------------------------------
// Admin dashboard
// - Admin only page
// - Shows products
// - Deletes selected product
// -------------------------------------------------

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Dashboard is only for logged-in admin.
if (!isset($_SESSION['auth_user'])) {
    header('Location: ../index.html');
    exit;
}

if (($_SESSION['auth_user']['role'] ?? '') !== 'admin') {
    header('Location: ../index.html');
    exit;
}

ensureProductsSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $idToDelete = trim($_POST['id'] ?? '');

    if ($idToDelete !== '') {
        // Keep all products except the selected id.
        $_SESSION['products'] = array_values(array_filter(
            $_SESSION['products'],
            // Callback returns true for products that should remain in the list.
            function ($product) use ($idToDelete) {
                return ($product['id'] ?? '') !== $idToDelete;
            }
        ));
    }

    header('Location: dashboard.php?status=deleted');
    exit;
}

$products = $_SESSION['products'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Admin Dashboard - ShopEase</title>
</head>
<body>
    <header class="main-header">
        <div class="logo">ShopEase</div>
    </header>

    <main>
        <nav class="sidebar">
            <a href="dashboard.php">View Products</a>
            <a href="addProduct.php">Add Product</a>
            <a href="logout.php">Logout</a>
        </nav>

        <section class="product">
            <h2>Products</h2>
            <?php if (($_GET['status'] ?? '') === 'added'): ?>
                <p style="color:#166534; font-weight:600;">Product added successfully.</p>
            <?php endif; ?>
            <?php if (($_GET['status'] ?? '') === 'deleted'): ?>
                <p style="color:#b91c1c; font-weight:600;">Product deleted successfully.</p>
            <?php endif; ?>

            <table>
                <tbody>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Delete</th>
                    </tr>

                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="product-cell">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <span class="product-name"><?= htmlspecialchars($product['name']) ?></span>
                            </td>
                            <td><?= htmlspecialchars(formatMoney((float) $product['price'])) ?></td>
                            <td>
                                <form method="post" action="dashboard.php">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="main-footer">
        <p class="copyright">&copy; 2026 ShopEase. All rights reserved.</p>
    </footer>
</body>
</html>
