<?php
session_start();
include __DIR__ . '/header.php';
?>

<?php
if(!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header('Location: login.php?error=please log in first');
  exit;
}
?>
<?php
if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();

$total_records_per_page = 5;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacent = "2";
$total_no_of_pages = ceil($total_records / $total_records_per_page);

$stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
$stmt2->execute();
$products = $stmt2->get_result();
?>

<!-- Верхняя панель
<nav class="navbar navbar-dark bg-dark px-3" style="height:48px;">
  <span class="navbar-brand mb-0 h1" style="font-weight:600;">Company name</span>
  <a href="logout.php" class="text-white" style="text-decoration:none;">Sign out</a>
</nav> -->

<div class="container-fluid" style="background:#f9f9f9; min-height:100vh; padding:0;">
  <div class="row" style="min-height:calc(100vh - 48px);">
    <!-- Сайдбар -->
    <!-- <nav class="col-md-2 d-none d-md-block sidebar" style="background-color:#e5e5e5; padding-top:30px; min-height:100vh;">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Account</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Add New Product</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
        </ul>
      </div>
    </nav> -->
<?php include __DIR__ . '/sidemenu.php'; ?>

    <!-- Контент -->
    <main class="col-md-10 ml-sm-auto px-4" style="padding-top:30px;">
      <h1>Dashboard</h1>
      <h2 style="font-size:2rem; margin:20px 0 15px;">Products</h2>
      <?php if(isset($_GET['edit_seccess_message'])){?>
        <p class="text-center" style="color: green;"><?php echo $_GET['edit_seccess_message'];?></p>
      <?php } ?>
      
      <?php if(isset($_GET['edit_failure_message'])){?>
        <p class="text-center" style="color: red;"><?php echo $_GET['edit_failure_message'];?></p>
      <?php } ?>

      <?php if(isset($_GET['deleted_successfully'])){?>
        <p class="text-center" style="color: green;"><?php echo $_GET['deleted_successfully'];?></p>
      <?php } ?>
      
      <?php if(isset($_GET['deleted_failure'])){?>
        <p class="text-center" style="color: red;"><?php echo $_GET['deleted_failure'];?></p>
      <?php } ?>  

      <?php if(isset($_GET['product_created'])){?>
        <p class="text-center" style="color: green;"><?php echo $_GET['product_created'];?></p>
      <?php } ?>
      
      <?php if(isset($_GET['product_failed'])){?>
        <p class="text-center" style="color: red;"><?php echo $_GET['product_failed'];?></p>
      <?php } ?>  

        <table class="table table-bordered table-hover bg-white">
        <thead>
          <tr>
            <th>Product Id</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Product Offer</th>
            <th>Product Category</th>
            <th>Product Color</th>
            
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($products as $product){ ?>
            <tr>
              <td><?php echo $product['product_id']; ?></td>
              <td><img src="<?php echo "../assets/imgs/". $product['product_image']; ?>" style="width: 70px; height:70px"/></td>
              <td><?php echo $product['product_name']; ?></td>
              <td><?php echo "$".$product['product_price']; ?></td>
              <td><?php echo $product['product_special_offer']. "%"; ?></td>
              <td><?php echo $product['product_category']; ?></td>
              <td><?php echo $product['product_color']; ?></td>
              <td><a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">Edit</a></td>
              <td><a href="delete_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <!-- Пагинация -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
          <li class="page-item <?= ($page_no <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= ($page_no <= 1) ? '#' : '?page_no=' . ($page_no - 1) ?>">Previous</a>
          </li>
          <?php for ($i = 1; $i <= $total_no_of_pages; $i++): ?>
            <li class="page-item <?= ($page_no == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page_no=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= ($page_no >= $total_no_of_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= ($page_no >= $total_no_of_pages) ? '#' : '?page_no=' . ($page_no + 1) ?>">Next</a>
          </li>
        </ul>
      </nav>
    </main>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">