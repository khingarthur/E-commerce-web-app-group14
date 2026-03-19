<?php
session_start();

// -------------------------------------------------
// Auth file
// - Handles register and login actions.
// - Stores users in session (no database).
// -------------------------------------------------

// Keep users in a session array 
// Admin is seeded manually and does not need signup.
if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
    // Seed one default admin account.
    $_SESSION['users'] = [
        [
            'name' => 'Admin',
            'email' => 'admin@shopease.com',
            'password' => 'admin123',
            'role' => 'admin'
        ],
        [
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => 'user1',
            'role' => 'customer'
        ]
    ];
}

$action = $_POST['action'] ?? '';

// Only allow login/register by POST request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($action !== 'login' && $action !== 'register')) {
    header('Location: ../login.html');
    exit;
}

// Finds the index of a user by email, or returns -1 when not found.
function findUserIndexByEmail(array $users, string $email): int
{
    // Return the array index of the matching email.
    foreach ($users as $index => $user) {
        if (($user['email'] ?? '') === $email) {
            return $index;
        }
    }

    return -1;
}




if ($action === 'register') {
    // Read and clean register inputs.
    $name = trim($_POST['fullName'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        // Keep typed name/email so user does not retype.
        header('Location: ../register.html?' . http_build_query([
            'error' => 'missing_fields',
            'fullName' => $name,
            'email' => $email
        ]));
        exit;
    }

    if ($password !== $confirmPassword) {
        // Password confirmation failed.
        header('Location: ../register.html?' . http_build_query([
            'error' => 'password_mismatch',
            'fullName' => $name,
            'email' => $email
        ]));
        exit;
    }

    if (findUserIndexByEmail($_SESSION['users'], $email) !== -1) {
        // Email already exists.
        header('Location: ../register.html?' . http_build_query([
            'error' => 'email_exists',
            'fullName' => $name,
            'email' => $email
        ]));
        exit;
    }

    // Plaintext password is used only for beginner demo.
    $_SESSION['users'][] = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => 'customer'
    ];

    header('Location: ../register.html?status=success');
    exit;
}

if ($action === 'login') {
    // Read and clean login inputs.
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    $userIndex = findUserIndexByEmail($_SESSION['users'], $email);

    if ($userIndex === -1) {
        // Invalid email.
        header('Location: ../login.html?' . http_build_query([
            'error' => 'invalid_credentials',
            'email' => $email
        ]));
        exit;
    }

    $user = $_SESSION['users'][$userIndex];

    if (($user['password'] ?? '') !== $password) {
        // Invalid password.
        header('Location: ../login.html?' . http_build_query([
            'error' => 'invalid_credentials',
            'email' => $email
        ]));
        exit;
    }

    // Save logged-in user info.
    $_SESSION['auth_user'] = [
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];

    if ($user['role'] === 'admin') {
        // Admin goes to dashboard.
        header('Location: dashboard.php');
        exit;
    }

    // Customer goes to cart page.
    header('Location: cart.php');
    exit;
}
