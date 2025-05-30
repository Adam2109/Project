<?php
session_start();
include('../server/connection.php');

// Если админ уже вошёл — перенаправление
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';

// Обработка входа
if (isset($_POST['login_btn'])) {
    $email    = $_POST['email'];
    $password = $_POST['password']; // Без md5

    // Получение данных админа по email
    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($admin_id, $admin_name, $admin_email, $hashed_password);
    $stmt->fetch();

    // Проверка пароля
    if ($stmt->num_rows() === 1 && password_verify($password, $hashed_password)) {
        $_SESSION['admin_id']        = $admin_id;
        $_SESSION['admin_name']      = $admin_name;
        $_SESSION['admin_email']     = $admin_email;
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php?login_success=logged in successfully');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<?php include 'header.php'; ?>

<style>
  .login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 60px);
  }
  .login-container {
    background-color: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }
  .login-container h2 {
    margin-bottom: 30px;
    text-align: center;
    font-size: 24px;
    color: #333;
  }
  .form-group {
    margin-bottom: 20px;
  }
  .form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
  }
  .form-group input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  .form-group input:focus {
    outline: none;
    border-color: #007bff;
  }
  .login-button {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
  }
  .login-button:hover {
    background-color: #0056b3;
  }
  .footer-text {
    margin-top: 20px;
    font-size: 14px;
    color: #777;
    text-align: center;
  }
</style>

<div class="login-wrapper">
  <div class="login-container">
    <h2>Admin Login</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required placeholder="admin@example.com">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter password">
      </div>
      <div class="form-group">
        <button type="submit" name="login_btn" class="login-button">Log In</button>
      </div>
    </form>
    <div class="footer-text">
      © 2025 MaxMotion
    </div>
  </div>
</div>

</body>
</html>
