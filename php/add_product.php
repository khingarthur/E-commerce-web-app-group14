<?php
session_start();
require_once __DIR__ . '/product_store.php';

// Add Product handler
// - Admin only
// - Validates product input and image upload
// - Saves product into session array

if (!isset($_SESSION['auth_user']) || (($_SESSION['auth_user']['role'] ?? '') !== 'admin')) {
    // Only admin can add products.
    header('Location: ../index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Direct URL access should return to form.
    header('Location: addProduct.php');
    exit;
}

ensureProductsSession();

$name = trim($_POST['product_name'] ?? '');
$priceRaw = trim($_POST['price'] ?? '');
$price = (float) $priceRaw;

// Redirects back to the add-product form with an error and previous inputs.
<<<<<<< HEAD
function redirectAddProductWithError($error, $name, $priceRaw) {
=======
function redirectAddProductWithError(string $error, string $name, string $priceRaw): void
{
    // Redirect back with error + old text values.
>>>>>>> 52f08e5956ddd6d27039dc329b7d2274341f1d27
    $query = http_build_query([
        'error' => $error,
        'product_name' => $name,
        'price' => $priceRaw
    ]);
    header('Location: addProduct.php?' . $query);
    exit;
}

// Name/price validation.
if ($name === '' || $price <= 0) {
    redirectAddProductWithError('invalid_input', $name, $priceRaw);
}

$imagePath = '';

// Validate and process the uploaded product image:
if (isset($_FILES['product_image']) && is_array($_FILES['product_image'])) {
      
    // 1) Ensure an upload was actually sent and has no PHP upload error.
    // 2) Allow only safe image extensions.
    // 3) Enforce a max size of 5MB.
    // 4) Save the file into ../products/ using a generated product-based filename.
    // 5) Store the relative image path for the product record.
    $uploadError = $_FILES['product_image']['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($uploadError === UPLOAD_ERR_OK) {
        $tmpPath = $_FILES['product_image']['tmp_name'];
        $originalName = $_FILES['product_image']['name'] ?? '';
        $fileSize = (int) ($_FILES['product_image']['size'] ?? 0);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed, true)) {
            redirectAddProductWithError('image_type', $name, $priceRaw);
        }

        if ($fileSize > 5 * 1024 * 1024) {
            redirectAddProductWithError('image_too_large', $name, $priceRaw);
        }

        $newName = createProductId($name) . '.' . $extension;
        $targetDiskPath = __DIR__ . '/../products/' . $newName;

        if (!move_uploaded_file($tmpPath, $targetDiskPath)) {
            redirectAddProductWithError('image_upload_failed', $name, $priceRaw);
        }

        $imagePath = '../products/' . $newName;
    } elseif ($uploadError === UPLOAD_ERR_NO_FILE) {
        redirectAddProductWithError('image_required', $name, $priceRaw);
    } elseif ($uploadError !== UPLOAD_ERR_NO_FILE) {
        redirectAddProductWithError('image_upload_failed', $name, $priceRaw);
    }
} else {
    // No file array means no image was submitted.
    redirectAddProductWithError('image_required', $name, $priceRaw);
}

// Save product to session array.
$_SESSION['products'][] = [
    'id' => createProductId($name),
    'name' => $name,
    'price' => $price,
    'image' => $imagePath
];

// redirect
header('Location: dashboard.php?status=added');
exit;
