<?php
session_start();

// View Cart shortcut: if logged in go to cart, else go to login

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
