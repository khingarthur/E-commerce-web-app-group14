<?php
session_start();

// -------------------------------------------------
// Cart page and cart actions
// - Requires login
// - Handles add/increase/decrease/delete
// - Renders current cart items
// -------------------------------------------------

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

$action = $_POST['action'] ?? '';

// Cart actions and cart page require login.
if (!isset($_SESSION['auth_user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
        header('Location: ../login.html?error=login_required');
        exit;
    }

    header('Location: ../index.html');
    exit;
}

// Product cart session is separate from login session.
if (!isset($_SESSION['cart_items']) || !is_array($_SESSION['cart_items'])) {
    $_SESSION['cart_items'] = [];
}

// One-time checkout feedback messages (Post/Redirect/Get pattern).
$checkoutFlash = $_SESSION['checkout_flash'] ?? null;
$checkoutError = $_SESSION['checkout_error'] ?? '';

if ($checkoutFlash !== null) {
    unset($_SESSION['checkout_flash']);
}

if ($checkoutError !== '') {
    unset($_SESSION['checkout_error']);
}

function formatCurrency($amount) { return '$' . number_format($amount, 2); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read common post values for cart actions.
    $id = $_POST['id'] ?? '';
    $returnTo = $_POST['return_to'] ?? 'cart.php';

    if ($returnTo !== 'cart.php' && $returnTo !== 'shop.php') {
        $returnTo = 'cart.php';
    }

    if ($action === 'add') {
        // Add one product to cart.
        $name = $_POST['name'] ?? '';
        $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
        $image = $_POST['image'] ?? '';
        $addedToCart = false;

        if ($id !== '' && $name !== '' && $price > 0 && $image !== '') {
            if (!isset($_SESSION['cart_items'][$id])) {
                $_SESSION['cart_items'][$id] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => 0
                ];
            }

            $_SESSION['cart_items'][$id]['quantity']++;
            $addedToCart = true;
        }

        if ($returnTo === 'shop.php') {
            if ($addedToCart) {
                $_SESSION['shop_flash'] = 'Product added to cart successfully.';
            } else {
                $_SESSION['shop_flash'] = 'Could not add product to cart.';
            }

            header('Location: shop.php');
            exit;
        }
    }

    if ($action === 'increase' && isset($_SESSION['cart_items'][$id])) {
        // Increase quantity by 1.
        $_SESSION['cart_items'][$id]['quantity']++;
    }

    if ($action === 'decrease' && isset($_SESSION['cart_items'][$id])) {
        // Decrease quantity by 1. Remove item at 0.
        $_SESSION['cart_items'][$id]['quantity']--;
        if ($_SESSION['cart_items'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart_items'][$id]);
        }
    }

    if ($action === 'delete' && isset($_SESSION['cart_items'][$id])) {
        // Remove item from cart.
        unset($_SESSION['cart_items'][$id]);
    }

    if ($action === 'checkout') {
        // Snapshot cart at checkout time to build order details.
        $currentItems = array_values($_SESSION['cart_items']);

        if (count($currentItems) === 0) {
            // Guard against checkout with no items.
            $_SESSION['checkout_error'] = 'Your cart is empty. Add items before checkout.';
            header('Location: cart.php');
            exit;
        }

        // Build order summary data to show after redirect.
        $orderTotal = 0;
        $orderItemsCount = 0;
        $orderItems = [];

        foreach ($currentItems as $item) {
            $quantity = (int) ($item['quantity'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $subtotal = $quantity * $price;

            $orderTotal += $subtotal;
            $orderItemsCount += $quantity;
            $orderItems[] = [
                'name' => $item['name'] ?? '',
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }

        $_SESSION['checkout_flash'] = [
            // Simple order reference for demo purposes.
            'order_id' => 'ORD-' . date('YmdHis') . '-' . random_int(100, 999),
            'item_count' => $orderItemsCount,
            'total' => $orderTotal,
            'items' => $orderItems
        ];

        // Clear cart once checkout succeeds.
        $_SESSION['cart_items'] = [];

        header('Location: cart.php');
        exit;
    }

    header('Location: cart.php');
    exit;
}

$cartItems = array_values($_SESSION['cart_items']);
$total = 0;

foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <header class="main-header">
        <div class="header-actions">
            <a href="shop.php" class="shop-btn">Shop</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="cart-container">
        <h1>Shopping Cart</h1>

        <!-- Checkout error shown once after redirect. -->
        <?php if ($checkoutError !== ''): ?>
            <section class="checkout-alert error">
                <p><?= htmlspecialchars($checkoutError) ?></p>
            </section>
        <?php endif; ?>

        <!-- Checkout success summary shown once after redirect. -->
        <?php if (is_array($checkoutFlash)): ?>
            <section class="checkout-alert success">
                <h2>Checkout Successful</h2>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($checkoutFlash['order_id']) ?></p>
                <p><strong>Items:</strong> <?= (int) $checkoutFlash['item_count'] ?></p>
                <p><strong>Total:</strong> <?= formatCurrency((float) $checkoutFlash['total']) ?></p>
                <div class="order-items">
                    <?php foreach ($checkoutFlash['items'] as $orderItem): ?>
                        <p>
                            <?= htmlspecialchars($orderItem['name']) ?>
                            x <?= (int) $orderItem['quantity'] ?>
                            — <?= formatCurrency((float) $orderItem['subtotal']) ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="cart-card">
            <?php if (count($cartItems) === 0): ?>
                <p class="empty-cart">Your cart is empty. Add items from the shop page.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <span><?= htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </td>
                                <td><?= formatCurrency($item['price']) ?></td>
                                <td>
                                    <div class="qty-area">
                                        <div class="qty-controls">
                                            <form method="post" action="cart.php">
                                                <input type="hidden" name="action" value="decrease">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                                                <button type="submit">-</button>
                                            </form>
                                            <span><?= (int) $item['quantity'] ?></span>
                                            <form method="post" action="cart.php">
                                                <input type="hidden" name="action" value="increase">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                                                <button type="submit">+</button>
                                            </form>
                                        </div>
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-row">
                    <span>Total:</span>
                    <strong><?= formatCurrency($total) ?></strong>
                </div>
            <?php endif; ?>
        </section>

        <!-- Checkout action submits to cart.php with action=checkout. -->
        <?php if (count($cartItems) > 0): ?>
            <form method="post" action="cart.php" class="checkout-form">
                <input type="hidden" name="action" value="checkout">
                <button type="submit" class="checkout-btn">Checkout</button>
            </form>
        <?php endif; ?>
    </main>

    <footer class="main-footer">
        <p class="copyright">&copy; 2026 ShopEase. All rights reserved.</p>
    </footer>
</body>
</html>
