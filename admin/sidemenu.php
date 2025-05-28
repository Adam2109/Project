<?php
// admin/sidemenu.php

// Отримуємо назву поточного скрипту
$current = basename($_SERVER['SCRIPT_NAME']);
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

      <li class="nav-item">
        <a 
          href="edit_order.php" 
          class="nav-link<?= $current === 'edit_order.php' ? ' active' : '' ?>" 
          <?= $current === 'edit_order.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="file"></span>
          Orders
        </a>
      </li>

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
          href="customers.php" 
          class="nav-link<?= $current === 'customers.php' ? ' active' : '' ?>" 
          <?= $current === 'customers.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="users"></span>
          Customers
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

      <li class="nav-item">
        <a 
          href="help.php" 
          class="nav-link<?= $current === 'help.php' ? ' active' : '' ?>" 
          <?= $current === 'help.php' ? 'aria-current="page"' : '' ?>>
          <span data-feather="help-circle"></span>
          Help
        </a>
      </li>

    </ul>
  </div>
</nav>
