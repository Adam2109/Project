<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Product Images – Admin Panel</title>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }

    .topbar {
      background-color: #1f1f1f;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .container {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 220px;
      background-color: #f3f3f3;
      padding: 20px;
      border-right: 1px solid #ddd;
    }

    .sidebar a {
      display: block;
      margin-bottom: 15px;
      color: #007bff;
      text-decoration: none;
      font-weight: 500;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .content {
      flex: 1;
      padding: 30px;
    }

    .content h1 {
      margin-bottom: 10px;
      font-size: 28px;
    }

    .content h2 {
      font-size: 22px;
      color: #007bff;
      margin-bottom: 25px;
    }

    form {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      max-width: 600px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .form-group input[type="file"] {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 6px;
      font-size: 16px;
    }

    .btn {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div><strong>Company name</strong></div>
    <div><a href="#" style="color:white; text-decoration:none;">Sign out</a></div>
  </div>

  <div class="container">
    <div class="sidebar">
      <a href="index.html">Dashboard</a>
      <a href="#">Orders</a>
      <a href="#">Products</a>
      <a href="#">Account</a>
      <a href="add_product.html">Add New Product</a>
      <a href="#">Help</a>
    </div>

    <div class="content">
      <h1>Dashboard</h1>
      <h2>Update Product Images</h2>

      <form action="#" method="post" enctype="multipart/form-data">
        <!-- При необхідності, додайте<input type="hidden" name="product_id" value="…">-->

        <div class="form-group">
          <label for="image1">Image 1</label>
          <input type="file" id="image1" name="image1">
        </div>

        <div class="form-group">
          <label for="image2">Image 2</label>
          <input type="file" id="image2" name="image2">
        </div>

        <div class="form-group">
          <label for="image3">Image 3</label>
          <input type="file" id="image3" name="image3">
        </div>

        <div class="form-group">
          <label for="image4">Image 4</label>
          <input type="file" id="image4" name="image4">
        </div>

        <button type="submit" class="btn">Update</button>
      </form>
    </div>
  </div>

</body>
</html>
