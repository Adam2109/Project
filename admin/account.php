<?php
session_start();
include __DIR__ . '/header.php';

// Перевірка входу
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

$admin_id = $_SESSION['admin_id'];
$success = '';
$error = '';

// Зміна імені користувача
if (isset($_POST['update_username'])) {
    $new_username = trim($_POST['admin_name']);
    if (!empty($new_username)) {
        $stmt = $conn->prepare("UPDATE admins SET admin_name = ? WHERE admin_id = ?");
        $stmt->bind_param("si", $new_username, $admin_id);
        if ($stmt->execute()) {
            $success = "Username updated successfully.";
        } else {
            $error = "Failed to update username.";
        }
    } else {
        $error = "Username cannot be empty.";
    }
}

// Зміна email
if (isset($_POST['update_email'])) {
    $new_email = trim($_POST['admin_email']);
    if (!empty($new_email)) {
        $stmt = $conn->prepare("UPDATE admins SET admin_email = ? WHERE admin_id = ?");
        $stmt->bind_param("si", $new_email, $admin_id);
        if ($stmt->execute()) {
            $success = "Email updated successfully.";
        } else {
            $error = "Failed to update email.";
        }
    } else {
        $error = "Email cannot be empty.";
    }
}

// Зміна пароля
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password_raw = $_POST['new_password'];

    // Отримуємо поточний хеш пароля з бази
    $stmt = $conn->prepare("SELECT admin_password FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows === 0) {
        $error = "Admin not found.";
    } elseif (password_verify($current_password, $hashed_password)) {
        $stmt->close();

        // Хешуємо новий пароль
        $new_password_hashed = password_hash($new_password_raw, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET admin_password = ? WHERE admin_id = ?");
        $stmt->bind_param("si", $new_password_hashed, $admin_id);
        if ($stmt->execute()) {
            $success = "Password updated successfully.";
        } else {
            $error = "Failed to update password.";
        }
    } else {
        $error = "Current password is incorrect.";
    }

    $stmt->close();
}

// Отримання поточної інформації
$stmt = $conn->prepare("SELECT admin_name, admin_email FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($admin_name, $admin_email);
$stmt->fetch();
$stmt->close();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid" style="background:#f9f9f9; min-height:100vh;">
  <div class="row">

    <?php include __DIR__ . '/sidemenu.php'; ?>

    <main class="col-md-10 px-4" style="padding-top:30px;">
      <h2>Account Settings</h2>

      <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="card mb-4">
        <div class="card-header">Account Information</div>
        <div class="card-body">
          <p><strong>Username:</strong> <?php echo htmlspecialchars($admin_name); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_email); ?></p>
        </div>
      </div>

      <!-- Зміна імені користувача -->
      <div class="card mb-4">
        <div class="card-header">Change Username</div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="admin_name" class="form-label">New Username</label>
              <input type="text" class="form-control" name="admin_name" id="admin_name" required>
            </div>
            <button type="submit" name="update_username" class="btn btn-secondary">Update Username</button>
          </form>
        </div>
      </div>

      <!-- Зміна email -->
      <div class="card mb-4">
        <div class="card-header">Change Email</div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="admin_email" class="form-label">New Email</label>
              <input type="email" class="form-control" name="admin_email" id="admin_email" required>
            </div>
            <button type="submit" name="update_email" class="btn btn-primary">Update Email</button>
          </form>
        </div>
      </div>

      <!-- Зміна пароля -->
      <div class="card">
        <div class="card-header">Change Password</div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="current_password" class="form-label">Current Password</label>
              <input type="password" class="form-control" name="current_password" id="current_password" required>
            </div>
            <div class="mb-3">
              <label for="new_password" class="form-label">New Password</label>
              <input type="password" class="form-control" name="new_password" id="new_password" required>
            </div>
            <button type="submit" name="update_password" class="btn btn-warning">Change Password</button>
          </form>
        </div>
      </div>
    </main>
  </div>
</div>
