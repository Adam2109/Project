<?php

session_start();
include __DIR__ . '/header.php';
include('../server/connection.php');

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=? ");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $products = $stmt->get_result();
} else if(isset($_POST['edit_btn'])) {
    $product_id = $_POST['product_id'];  
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $sale = $_POST['sale'];
    $sport_type = $_POST['sport_type'];
    $brand = $_POST['brand'];
    $material = $_POST['material'];
   
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : [];

    $stmt = $conn->prepare("UPDATE products SET product_name=?, 
                                            product_description=?,
                                            product_price=?,
                                            product_category=?,
                                            product_color=?,
                                            product_special_offer=?,
                                            sport_type=?,
                                            brand=?,
                                            material=?
                                            WHERE product_id=?");

    $stmt->bind_param('sssssssssi', 
        $title, 
        $description, 
        $price, 
        $category, 
        $color, 
        $sale,
        $sport_type,
        $brand,
        $material,
        $product_id
    );

    if($stmt->execute()) {
     
        $del_stmt = $conn->prepare("DELETE FROM product_sizes WHERE product_id = ?");
        $del_stmt->bind_param("i", $product_id);
        $del_stmt->execute();

       
        if (!empty($sizes)) {
            $ins_stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size) VALUES (?, ?)");
            foreach ($sizes as $sz) {
                $ins_stmt->bind_param("is", $product_id, $sz);
                $ins_stmt->execute();
            }
        }
        header('location: products.php?edit_seccess_message=Product updated successfully');
        exit;
    } else {
        header('location: products.php?edit_failure_message=Error updating product');
        exit;
    }
} else {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product – Admin Panel</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f9f9f9; }
        .sidebar-sticky a.active { font-weight:600; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Сайдбар -->
        <?php include __DIR__ . '/sidemenu.php'; ?>
      
        <main class="col-md-10 ms-sm-auto px-4 py-4">
            <h1 class="h3">Dashboard</h1>
            <h2 class="h4 mb-4">Edit Product</h2>
            <form id="edit-form" method="post" action="edit_product.php"
                  class="bg-white p-4 rounded shadow-sm" style="max-width:600px;">
                <?php foreach ($products as $product) { ?>
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name'] ?>" name="title"
                           placeholder="Title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                           placeholder="Description" value="<?php echo $product['product_description'] ?>">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                           placeholder="Price" required value="<?php echo $product['product_price'] ?>">
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="bags" <?= $product['product_category'] == 'bags' ? 'selected' : '' ?>>Bags</option>
                        <option value="shoes" <?= $product['product_category'] == 'shoes' ? 'selected' : '' ?>>Shoes</option>
                        <option value="t-shirts" <?= $product['product_category'] == 't-shirts' ? 'selected' : '' ?>>T-Shirts</option>
                        <option value="accessories" <?= $product['product_category'] == 'accessories' ? 'selected' : '' ?>>Accessories</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label fw-semibold">Color</label>
                    <input type="text" class="form-control" id="color" name="color"
                           placeholder="Color" value="<?php echo $product['product_color'] ?>">
                </div>
                <div class="mb-3">
                    <label for="sale" class="form-label fw-semibold">Special Offer / Sale (%)</label>
                    <input type="number" step="1" min="0" max="100" class="form-control" id="sale" name="sale"
                           placeholder="Sale %" value="<?php echo $product['product_special_offer'] ?>">
                </div>
                <!-- Sport Type -->
                <div class="mb-3">
                    <label for="sport_type" class="form-label fw-semibold">Sport Type</label>
                    <select class="form-select" id="sport_type" name="sport_type">
                        <option value="Football" <?= $product['sport_type'] == 'Football' ? 'selected' : '' ?>>Football</option>
                        <option value="Running" <?= $product['sport_type'] == 'Running' ? 'selected' : '' ?>>Running</option>
                        <option value="Basketball" <?= $product['sport_type'] == 'Basketball' ? 'selected' : '' ?>>Basketball</option>
                        <option value="Gym" <?= $product['sport_type'] == 'Gym' ? 'selected' : '' ?>>Gym</option>
                    </select>
                </div>
                <!-- Brand -->
                <div class="mb-3">
                    <label for="brand" class="form-label fw-semibold">Brand</label>
                    <select class="form-select" id="brand" name="brand">
                        <option value="Nike" <?= $product['brand'] == 'Nike' ? 'selected' : '' ?>>Nike</option>
                        <option value="Adidas" <?= $product['brand'] == 'Adidas' ? 'selected' : '' ?>>Adidas</option>
                        <option value="Puma" <?= $product['brand'] == 'Puma' ? 'selected' : '' ?>>Puma</option>
                        <option value="Reebok" <?= $product['brand'] == 'Reebok' ? 'selected' : '' ?>>Reebok</option>
                    </select>
                </div>
                <!-- Material -->
                <div class="mb-3">
                    <label for="material" class="form-label fw-semibold">Material</label>
                    <select class="form-select" id="material" name="material">
                        <option value="Cotton" <?= $product['material'] == 'Cotton' ? 'selected' : '' ?>>Cotton</option>
                        <option value="Polyester" <?= $product['material'] == 'Polyester' ? 'selected' : '' ?>>Polyester</option>
                        <option value="Nylon" <?= $product['material'] == 'Nylon' ? 'selected' : '' ?>>Nylon</option>
                        <option value="Wool" <?= $product['material'] == 'Wool' ? 'selected' : '' ?>>Wool</option>
                    </select>
                </div>
                <!-- Available Sizes -->
                <?php 
                    
                    $sizes_stmt = $conn->prepare("SELECT size FROM product_sizes WHERE product_id = ?");
                    $sizes_stmt->bind_param("i", $product['product_id']);
                    $sizes_stmt->execute();
                    $sizes_result = $sizes_stmt->get_result();
                    $available_sizes = [];
                    while($size_row = $sizes_result->fetch_assoc()){
                         $available_sizes[] = $size_row['size'];
                    }
                    $all_sizes = ['S','M','L','XL'];
                ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Available Sizes</label>
                    <div>
                        <?php foreach($all_sizes as $sz): ?>
                            <input type="checkbox" name="sizes[]" id="size_<?php echo $sz; ?>" value="<?php echo $sz; ?>" <?= in_array($sz, $available_sizes) ? 'checked' : ''; ?>>
                            <label for="size_<?php echo $sz; ?>"><?php echo $sz; ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="edit_btn">Save changes</button>
                <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                <?php } ?>
            </form>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>