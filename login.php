<?php include('layouts/header.php'); ?>
<?php

include('server/connection.php');

// Якщо вже залогінений — перенаправляємо
if (isset($_SESSION['logged_in'])) {
  header('location: account.php');
  exit;
}

// Обробка логіну
if (isset($_POST['login_btn'])) {

  // Базова перевірка на порожні поля
  if (empty($_POST['email']) || empty($_POST['password'])) {
    header('location: login.php?error=Please fill in all fields');
    exit;
  }

  // Очистка та валідація email і пароля
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('location: login.php?error=Invalid email format');
    exit;
  }

  if (strlen($password) < 6) {
    header('location: login.php?error=Password must be at least 6 characters');
    exit;
  }

  // Хешування пароля (NB: краще використовувати password_hash у майбутньому)
  $password = md5($password);

  // Перевірка користувача
  $stmt = $conn->prepare("SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");
  $stmt->bind_param('ss', $email, $password);

  if ($stmt->execute()) {
    $stmt->bind_result($user_id, $user_name, $user_email, $user_password);
    $stmt->store_result();

    if ($stmt->num_rows() === 1) {
      $stmt->fetch();

      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_name'] = $user_name;
      $_SESSION['user_email'] = $user_email;
      $_SESSION['logged_in'] = true;

      header('location: account.php?login_success=logged in successfully');
    } else {
      header('location: login.php?error=Could not verify your account');
    }
  } else {
    header('location: login.php?error=Something went wrong');
  }
}
?>

<?php
$login_message = '';
if (isset($_GET['message']) && $_GET['message'] === 'login_required') {
  $login_message = 'You need to log in to access your cart.';
}
?>

<!-- Login -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Login</h2>
    <hr class="mx-auto">
  </div>

  <div class="mx-auto container">
    <?php if (!empty($login_message)): ?>
      <div id="login-alert" class="alert alert-warning"><?php echo $login_message; ?></div>
    <?php endif; ?>

    <form id="login-form" method="POST" action="login.php">
      <?php if (isset($_GET['error'])): ?>
  <div id="error-alert" class="alert alert-danger text-center">
    <?php echo htmlspecialchars($_GET['error']); ?>
  </div>
<?php endif; ?>

      <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required />
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required />
      </div>
      <div class="form-group">
        <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login" />
      </div>
      <div class="form-group">
        <a id="register-url" class="btn" href="register.php">Don't have account? Register</a>
      </div>
      
    </form>
  </div>

  <script>
    // Приховати повідомлення через 5 сек
    const loginAlert = document.getElementById('login-alert');
    if (loginAlert) {
      setTimeout(() => {
        loginAlert.style.display = 'none';
      }, 5000);
    }

      // Автоматично ховає повідомлення про помилку
  const errorAlert = document.getElementById('error-alert');
  if (errorAlert) {
    setTimeout(() => {
      errorAlert.style.display = 'none';
    }, 5000);
  }
    // JS-валідація
    document.getElementById('login-form').addEventListener('submit', function (e) {
      const email = document.getElementById('login-email').value.trim();
      const password = document.getElementById('login-password').value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        e.preventDefault();
        return;
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters.');
        e.preventDefault();
        return;
      }
    });
  </script>
  <script>
  // Наприклад, Ctrl + Shift + A для переходу на admin login
  document.addEventListener('keydown', function (e) {
    if (e.ctrlKey && e.shiftKey && e.key === 'X') {
      window.location.href = 'admin/login.php';
    }
  });
</script>

</section>

<?php include('layouts/footer.php'); ?>
