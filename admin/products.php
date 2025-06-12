<?php
session_start();
include __DIR__ . '/header.php';
include __DIR__ . '/../server/connection.php';
?>

<?php
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header('Location: login.php?error=please log in first');
  exit;
}

$page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();

$total_records_per_page = 5;
$offset = ($page_no - 1) * $total_records_per_page;
$total_no_of_pages = ceil($total_records / $total_records_per_page);

// Отримуємо всі продукти разом з описом і розмірами
$stmt2 = $conn->prepare("
  SELECT p.*, 
         GROUP_CONCAT(DISTINCT ps.size SEPARATOR ', ') AS sizes 
  FROM products p
  LEFT JOIN product_sizes ps ON p.product_id = ps.product_id
  GROUP BY p.product_id
  ORDER BY p.product_id DESC
  LIMIT ?, ?
");
$stmt2->bind_param("ii", $offset, $total_records_per_page);
$stmt2->execute();
$products = $stmt2->get_result();
?>

<div class="container-fluid" style="background:#f9f9f9; min-height:100vh; padding:0;">
  <div class="row" style="min-height:calc(100vh - 48px);">
    <?php include __DIR__ . '/sidemenu.php'; ?>

    <main class="col-md-10 ml-sm-auto px-4" style="padding-top:30px;">
      <h1>Dashboard</h1>
      <h2 style="font-size:2rem; margin:20px 0 15px;">Products</h2>

      <?php foreach ([
        'edit_seccess_message' => 'green',
        'edit_failure_message' => 'red',
        'deleted_successfully' => 'green',
        'deleted_failure' => 'red',
        'product_created' => 'green',
        'product_failed' => 'red',
        'images_updated' => 'green',
        'images_failed' => 'red',
      ] as $msg => $color): ?>
        <?php if (isset($_GET[$msg])): ?>
          <p class="text-center" style="color: <?= $color ?>;"><?php echo $_GET[$msg]; ?></p>
        <?php endif; ?>
      <?php endforeach; ?>

      <table class="table table-bordered table-hover bg-white">
        <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Offer</th>
          <th>Category</th>
          <th>Color</th>
          <th>Sizes</th>
          <th>Sport Type</th>
          <th>Brand</th>
          <th>Material</th>
          <th>Description</th>
          <th>Edit Images</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>

        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td><?= $product['product_id']; ?></td>
              <td><img src="../assets/imgs/<?= $product['product_image']; ?>" style="width: 70px; height:70px"/></td>
              <td><?= htmlspecialchars($product['product_name']); ?></td>
              <td>$<?= $product['product_price']; ?></td>
              <td><?= $product['product_special_offer']; ?>%</td>
              <td><?= $product['product_category']; ?></td>
              <td><?= $product['product_color']; ?></td>
              <td><?= $product['sizes'] ?? '—'; ?></td>
              <td><?= $product['sport_type']; ?></td>
              <td><?= $product['brand']; ?></td>
              <td><?= $product['material']; ?></td>
              <td style="max-width: 200px;"><?= nl2br(htmlspecialchars($product['product_description'])); ?></td>
              <td><a href="edit_images.php?product_id=<?= $product['product_id']; ?>&product_name=<?= urlencode($product['product_name']); ?>" class="btn btn-warning btn-sm">Edit Images</a></td>
              <td><a href="edit_product.php?product_id=<?= $product['product_id']; ?>" class="btn btn-primary btn-sm">Edit</a></td>
              <td><a href="delete_product.php?product_id=<?= $product['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a></td>
            </tr>
          <?php endforeach; ?>
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
