<?php
session_start();
include __DIR__ . '/header.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product – Admin Panel</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- Sidebar -->
        <?php include __DIR__ . '/sidemenu.php'; ?>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-4 py-4">
            <h1 class="h3 mb-3">Dashboard</h1>
            <h2 class="h4 mb-4">Create Product</h2>

            <form id="create-form" action="create_product.php" method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm" style="max-width:600px;">
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Title">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <input type="text" id="description" name="description" class="form-control" placeholder="Description">
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price</label>
                    <input type="text" id="price" step="0.01" name="price" class="form-control" placeholder="Price">
                </div>

                <div class="mb-3">
                    <label for="sale" class="form-label fw-semibold">Special Offer / Sale (%)</label>
                    <input type="text" id="sale" name="sale" class="form-control" placeholder="Sale %">
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="bags">Bags</option>
                        <option value="shoes">Shoes</option>
                        <option value="watches">Watches</option>
                        <option value="clothes">Clothes</option>
                        
                    </select>
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label fw-semibold">Color</label>
                    <input type="text" id="color" name="color" class="form-control" placeholder="Color">
                </div>
                <!-- Sport Type -->
                <div class="mb-3">
                    <label for="sport_type" class="form-label fw-semibold">Sport Type</label>
                    <select id="sport_type" name="sport_type" class="form-select">
                        <option value="Football">Football</option>
                        <option value="Running">Running</option>
                        <option value="Basketball">Basketball</option>
                        <option value="Gym">Gym</option>
                    </select>
                </div>

                <!-- Brand -->
                <div class="mb-3">
                    <label for="brand" class="form-label fw-semibold">Brand</label>
                    <select id="brand" name="brand" class="form-select">
                        <option value="Nike">Nike</option>
                        <option value="Adidas">Adidas</option>
                        <option value="Puma">Puma</option>
                        <option value="Reebok">Reebok</option>
                    </select>
                </div>

                <!-- Material -->
                <div class="mb-3">
                    <label for="material" class="form-label fw-semibold">Material</label>
                    <select id="material" name="material" class="form-select">
                        <option value="Cotton">Cotton</option>
                        <option value="Polyester">Polyester</option>
                        <option value="Nylon">Nylon</option>
                        <option value="Wool">Wool</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image1" class="form-label fw-semibold">Image 1</label>
                    <input type="file" id="image1" name="image1" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="image2" class="form-label fw-semibold">Image 2</label>
                    <input type="file" id="image2" name="image2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="image3" class="form-label fw-semibold">Image 3</label>
                    <input type="file" id="image3" name="image3" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="image4" class="form-label fw-semibold">Image 4</label>
                    <input type="file" id="image4" name="image4" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary" name="create_product">Create</button>
            </form>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
