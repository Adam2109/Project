<?php
// admin/sidemenu.php

// Отримуємо назву поточного скрипту
$current = basename($_SERVER['SCRIPT_NAME']);

// Підключення до бази
include('../server/connection.php');

// Запит до бази: скільки нових звернень без відповіді
$new_requests_count = 0;
$query = "SELECT COUNT(*) AS count FROM help_requests WHERE reply IS NULL";
$result = $conn->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $new_requests_count = $row['count'];
}
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">

      <li class="nav-item">
        <a 
          href="index.php" 
          class="nav-link<?= $current === 'index.php' ? ' active' : '' ?>" 
          <?= $current === 'index.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="home"></span>
          Dashboard
        </a>
      </li>

      <!-- <li class="nav-item">
        <a 
          href="orders.php" 
          class="nav-link<?= $current === 'orders.php' ? ' active' : '' ?>" 
          <?= $current === 'orders.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="file"></span>
          Orders
        </a>
      </li> -->

      <li class="nav-item">
        <a 
          href="products.php" 
          class="nav-link<?= $current === 'products.php' ? ' active' : '' ?>" 
          <?= $current === 'products.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="shopping-cart"></span>
          Products
        </a>
      </li>

      <li class="nav-item">
        <a 
          href="account.php" 
          class="nav-link<?= $current === 'account.php' ? ' active' : '' ?>" 
          <?= $current === 'account.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="user"></span>
          Account
        </a>
      </li>

      <li class="nav-item">
        <a 
          href="add_product.php" 
          class="nav-link<?= $current === 'add_product.php' ? ' active' : '' ?>" 
          <?= $current === 'add_product.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="plus-circle"></span>
          Add New Product
        </a>
      </li>

      <li class="nav-item d-flex align-items-center">
        <a 
          href="help.php" 
          class="nav-link d-flex justify-content-between align-items-center<?= $current === 'help.php' ? ' active' : '' ?>" 
          <?= $current === 'help.php' ? 'aria-current="page"' : '' ?>>
          <span>
            <span data-feather="help-circle"></span>
            Help
          </span>
          <?php if ($new_requests_count > 0): ?>
            <span class="badge bg-danger ms-2"><?= $new_requests_count ?></span>
          <?php endif; ?>
        </a>
      </li>

    </ul>
  </div>
</nav>
