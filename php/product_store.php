<?php

// -------------------------------------------------
// Product store helpers
// - Default product list
// - Session initialization
// - ID and currency helpers
// -------------------------------------------------

<<<<<<< HEAD
=======
// Returns the initial product catalog used to seed the session store.
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27
function getDefaultProducts(): array
{
    // Starter products used when session has no products yet.
    return [
        [
            'id' => 'phone',
            'name' => 'Mobile Phone',
            'price' => 250,
            'image' => '../products/phone.png'
        ],
        [
            'id' => 'camera',
            'name' => 'Camera',
            'price' => 300,
            'image' => '../products/camera.png'
        ],
        [
            'id' => 'laptop',
            'name' => 'Laptop',
            'price' => 800,
            'image' => '../products/laptop.png'
        ],
        [
            'id' => 'earphone',
            'name' => 'Headphones',
            'price' => 150,
            'image' => '../products/earphone.png'
        ],
        [
            'id' => 'shoe',
            'name' => 'Shoes',
            'price' => 200,
            'image' => '../products/shoe.png'
        ]
    ];
}

<<<<<<< HEAD
=======
// Ensures the products session array exists and is initialized.
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27
function ensureProductsSession(): void
{
    // Create products array in session if missing.
    if (!isset($_SESSION['products']) || !is_array($_SESSION['products'])) {
        $_SESSION['products'] = getDefaultProducts();
    }
}

<<<<<<< HEAD
=======
// Generates a URL-safe product id based on name plus a timestamp.
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27
function createProductId(string $name): string
{
    // Convert product name into a simple unique id.
    $base = strtolower(trim($name));
    $base = preg_replace('/[^a-z0-9]+/', '-', $base);
    $base = trim((string) $base, '-');

    if ($base === '') {
        $base = 'product';
    }

    return $base . '-' . time();
}

<<<<<<< HEAD
=======
// Formats a numeric amount as a dollar currency string.
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27
function formatMoney(float $amount): string
{
    // Display value like $250.00
    return '$' . number_format($amount, 2);
}
