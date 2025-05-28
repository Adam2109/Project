<?php
// admin/index.php
include __DIR__ . '/header.php';
?>

<?php

  if(!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
  }

?>


<style>
  /* Стили для страницы Dashboard */
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
  }
  .container {
    display: flex;
    min-height: calc(100vh - 60px); /* вычитаем высоту topbar */
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
    margin: 25px 0 15px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 0 8px rgba(0,0,0,0.05);
    border-radius: 6px;
    overflow: hidden;
  }
  th, td {
    text-align: left;
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
  }
  th {
    background-color: #f1f1f1;
    font-weight: bold;
  }
  tr:nth-child(even) {
    background-color: #fafafa;
  }
</style>

<div class="container">
  <?php include __DIR__ . '/sidemenu.php'; ?>

  <main class="content">
    <h1>Dashboard</h1>
    <h2>Section title</h2>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>1,001</td><td>random</td><td>data</td><td>placeholder</td><td>text</td></tr>
        <tr><td>1,002</td><td>placeholder</td><td>irrelevant</td><td>visual</td><td>layout</td></tr>
        <tr><td>1,003</td><td>data</td><td>rich</td><td>dashboard</td><td>tabular</td></tr>
        <!-- … остальные строки … -->
      </tbody>
    </table>
  </main>
</div>

<?php include __DIR__ . '/footer.php'; ?>
