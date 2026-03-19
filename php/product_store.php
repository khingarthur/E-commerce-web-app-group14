<?php

// -------------------------------------------------
// Product store helpers
// - Default product list
// - Session initialization
// - ID and currency helpers
// -------------------------------------------------

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

function ensureProductsSession(): void
{
    // Create products array in session if missing.
    if (!isset($_SESSION['products']) || !is_array($_SESSION['products'])) {
        $_SESSION['products'] = getDefaultProducts();
    }
}
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

function formatMoney(float $amount): string
{
    // Display value like $250.00
    return '$' . number_format($amount, 2);
}
