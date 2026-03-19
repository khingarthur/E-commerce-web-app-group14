<?php
session_start();

// Add Product page (admin only)
// - Shows form for adding new products
// - Reads query params to show validation errors

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_SESSION['auth_user']) || (($_SESSION['auth_user']['role'] ?? '') !== 'admin')) {
    header('Location: ../index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <link rel="stylesheet" href="../css/addProduct.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <header class="main-header">
        <div class="logo">ShopEase</div>
    </header>

    <div class="main-container">
        <nav class="sidebar">
            <a href="dashboard.php">View Products</a>
            <a href="addProduct.php">Add Product</a>
            <a href="logout.php">Logout</a>
        </nav>

        <main class="content">
            <div class="card">
                <h2>Add New Product</h2> <br>
                <form action="add_product.php" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" placeholder="Enter product name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" placeholder="Enter price" step="0.01" min="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Product Image</label>
                        <div class="upload-box">
                            <i class="fas fa-image fa-2x"></i>
                            <p>Drag & drop or click to upload</p>
                            <span>JPG, PNG, GIF (Max size: 5MB)</span>
                            <input type="file" id="product-image" name="product_image" accept="image/*" class="upload-input" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Add Product</button>
                    <p id="form-error" style="display:none; color:#dc2626; margin-top:10px; font-weight:600;">Please provide valid product details.</p>
                </form>
            </div>
        </main>
    </div>

    <footer class="main-footer">

        <p class="copyright">
            &copy; 2026 ShopEase. All rights reserved.
        </p>
    </footer>

    <script>
        // Read query parameters to restore old values and show error messages.
        const params = new URLSearchParams(window.location.search);
        const formError = document.getElementById('form-error');
        const productNameInput = document.querySelector('input[name="product_name"]');
        const priceInput = document.querySelector('input[name="price"]');

        const oldProductName = params.get('product_name');
        const oldPrice = params.get('price');

        if (oldProductName && productNameInput) {
            productNameInput.value = oldProductName;
        }

        if (oldPrice && priceInput) {
            priceInput.value = oldPrice;
        }

        if (formError) {
            const error = params.get('error');

            if (error === 'invalid_input') {
                formError.textContent = 'Please provide valid product details.';
                formError.style.display = 'block';
            }

            if (error === 'image_type') {
                formError.textContent = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
                formError.style.display = 'block';
            }

            if (error === 'image_upload_failed') {
                formError.textContent = 'Image upload failed. Please choose the image again and retry.';
                formError.style.display = 'block';
            }

            if (error === 'image_too_large') {
                formError.textContent = 'Image is too large. Please upload a file below 5MB.';
                formError.style.display = 'block';
            }

            if (error === 'image_required') {
                formError.textContent = 'Please choose a product image before submitting.';
                formError.style.display = 'block';
            }
        }
    </script>

</body>
</html>
