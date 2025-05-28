<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
  <style>
   
    * {
      box-sizing: border-box;
    }
    body, html {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      font-family: Arial, sans-serif;
    }

    
    .topbar {
      background-color: #1f1f1f;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .topbar a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }

    .topbar a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="topbar">
    <div><strong>Company name</strong></div>
    <div>
      <?php if (!empty($_SESSION['admin_logged_in'])): ?>
        <a href="logout.php?logout=1">Sign out</a>
      <?php endif; ?>
    </div>
  </div>
