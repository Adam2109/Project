<?php
session_start();
include __DIR__ . '/header.php';

if(!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header('Location: login.php?error=please log in first');
  exit;
}

if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders");
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

$stmt2 = $conn->prepare("SELECT * FROM orders LIMIT $offset, $total_records_per_page");
$stmt2->execute();
$orders = $stmt2->get_result();
?>


<div class="container-fluid" style="background:#f9f9f9; min-height:100vh; padding:0;">
  <div class="row" style="min-height:calc(100vh - 48px);">
    
<?php include __DIR__ . '/sidemenu.php'; ?>

    <!-- Контент -->
    <main class="col-md-10 ml-sm-auto px-4" style="padding-top:30px;">
      <h1>Dashboard</h1>
      <h2 style="font-size:2rem; margin:20px 0 15px;">Orders</h2>
      
      <?php if(isset($_GET['order_updated'])){?>
        <p class="text-center" style="color: green;"><?php echo $_GET['order_updated'];?></p>
      <?php } ?>
      
      <?php if(isset($_GET['order_failed'])){?>
        <p class="text-center" style="color: red;"><?php echo $_GET['order_failed'];?></p>
      <?php } ?>
       <?php if(isset($_GET['deleted_successfully'])){?>
        <p class="text-center" style="color: green;"><?php echo $_GET['deleted_successfully'];?></p>
      <?php } ?>
      
      <?php if(isset($_GET['deleted_failure'])){?>
        <p class="text-center" style="color: red;"><?php echo $_GET['deleted_failure'];?></p>
      <?php } ?>  
      
      <table class="table table-bordered table-hover bg-white">
        <thead>
          <tr>
            <th>Order Id</th>
            <th>Order Status</th>
            <th>User Id</th>
            <th>Order Date</th>
            <th>User Phone</th>
            <th>User Address</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
            <tr>
              <td><?php echo $order['order_id']; ?></td>
              <td><?php echo $order['order_status']; ?></td>
              <td><?php echo $order['user_id']; ?></td>
              <td><?php echo $order['order_date']; ?></td>
              <td><?php echo $order['user_phone']; ?></td>
              <td><?php echo $order['user_address']; ?></td>
              <td><a href="edit_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary btn-sm">Edit</a></td>
              <td><a href="delete_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <!-- Пагінація -->
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