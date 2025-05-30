<?php
session_start();
include __DIR__ . '/header.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}
?>

<?php
  if(isset($_GET['product_id'])) {
      $product_id = $_GET['product_id'];
      $product_name = $_GET['product_name'];
  } else {
      header('Location: products.php?error=Product not found');
      exit;
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Product Images – Admin Panel</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f9f9f9; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row min-vh-100">

    <!-- Сайдбар -->
    <?php include __DIR__ . '/sidemenu.php'; ?>

    <!-- Основной контент -->
    <main class="col-md-10 ms-sm-auto px-4 py-4">
      <h1 class="h3">Dashboard</h1>
      <h2 class="h4 mb-4">Update Product Images</h2>

      <form id="edit-image-form" action="update_images.php" method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
        <input type="hidden" name="product_id" value="<?php echo $product_id;?>">
        <input type="hidden" name="product_name" value="<?php echo $product_name;?>">
        <div class="mb-3">
          <label for="image1" class="form-label fw-semibold">Image 1</label>
          <input type="file" class="form-control" id="image1" name="image1">
        </div>

        <div class="mb-3">
          <label for="image2" class="form-label fw-semibold">Image 2</label>
          <input type="file" class="form-control" id="image2" name="image2">
        </div>

        <div class="mb-3">
          <label for="image3" class="form-label fw-semibold">Image 3</label>
          <input type="file" class="form-control" id="image3" name="image3">
        </div>

        <div class="mb-3">
          <label for="image4" class="form-label fw-semibold">Image 4</label>
          <input type="file" class="form-control" id="image4" name="image4">
        </div>

        <button type="submit" class="btn btn-primary" name="update_images">Update</button>
        <a href="products.php" class="btn btn-outline-secondary ms-2">Cancel</a>
      </form>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
