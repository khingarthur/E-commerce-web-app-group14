<?php
session_start();

<<<<<<< HEAD
// View Cart shortcut: if logged in go to cart, else go to login
=======
// -------------------------------------------------
// View Cart shortcut route
// - If logged in: go to cart
// - If logged out: go to login with message
// -------------------------------------------------
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// If not logged in, send user to login page first.
if (!isset($_SESSION['auth_user'])) {
    header('Location: ../login.html?error=view_cart_login_required');
    exit;
}

header('Location: cart.php');
exit;
