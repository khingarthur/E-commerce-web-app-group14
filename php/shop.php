<?php
session_start();
require_once __DIR__ . '/product_store.php';

// -------------------------------------------------
// Shop page
// - Reads product array from session
// - Displays one card per product
// - Shows one-time message after add-to-cart
// -------------------------------------------------

ensureProductsSession();
$products = $_SESSION['products'];
$shopFlashMessage = $_SESSION['shop_flash'] ?? '';
$isLoggedIn = isset($_SESSION['auth_user']);

if ($shopFlashMessage !== '') {
    unset($_SESSION['shop_flash']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/shop.css">
    <title>Shop</title>
</head>
<body>
    <?php if ($isLoggedIn): ?>
        <header class="main-header2">
        <div class="header-actions2">
            <a href="cart.php" class="shop-btn">Cart</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <?php else: ?>
        <header class="main-header">
            <div class="logo">ShopEase</div>

            <nav class="nav-links">
                <a href="../index.html">Home</a>
                <a href="shop.php">Shop</a>
                <a href="../aboutUs.html">About</a>
                <a href="../contactUs.html">Contact</a>
            </nav>

            <div class="header-actions">
                <a href="go_to_cart.php" class="cart-icon">
                    🛒 <span class="cart-count">View Cart</span>
                </a>
                <a href="../login.html" class="btn-outline">Login</a>
                <a href="../register.html" class="btn-primary">Register</a>
            </div>
        </header>
    <?php endif; ?>

    <main class="shop-container">
        <h1 class="shop-title">Shop Our Products</h1>

        <?php if ($shopFlashMessage !== ''): ?>
            <p style="text-align:center; color:#166534; font-weight:700; margin-bottom:16px;">
                <?= htmlspecialchars($shopFlashMessage) ?>
            </p>
        <?php endif; ?>

        <section class="products-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="product-price"><?= htmlspecialchars(formatMoney((float) $product['price'])) ?></p>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="return_to" value="shop.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars((string) $product['price']) ?>">
                        <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']) ?>">
                        <button class="add-btn" type="submit">Add to Cart</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <footer class="main-footer">
        <p class="copyright">&copy; 2026 ShopEase. All rights reserved.</p>
    </footer>
</body>
</html>
