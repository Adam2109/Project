<?php include('layouts/header.php'); ?>
<?php
include('server/connection.php');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php?error=To place an order, you need to be logged in.");
    exit();
}

if (empty($_SESSION['cart'])) {
    header('location: index.php');
    exit;
}

// Якщо користувач залогінений – отримуємо його дані
$user_name = '';
$user_email = '';
$user_phone = '';
$user_city = '';
$user_address = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT user_name, user_email, user_phone, user_city, user_address FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_name, $user_email, $user_phone, $user_city, $user_address);
    $stmt->fetch();
    $stmt->close();
}
?>

<!-- Checkout -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Checkout</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="checkout-form" method="POST" action="server/place_order.php">
            <p class="text-center" style="color: red">
                <?php if (isset($_GET['message'])) echo $_GET['message']; ?>
                <?php if (isset($_GET['message'])): ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </p>

            <div class="form-group checkout-small-element">
                <label>Name</label>
                <input type="text" class="form-control" id="checkout-name" name="name"
                       value="<?php echo htmlspecialchars($user_name ?? '', ENT_QUOTES); ?>"
                       placeholder="Name" required />
            </div>

            <div class="form-group checkout-small-element">
                <label>Email</label>
                <input type="email" class="form-control" id="checkout-email" name="email"
                       value="<?php echo htmlspecialchars($user_email ?? '', ENT_QUOTES); ?>"
                       placeholder="Email" required />
            </div>

            <div class="form-group checkout-small-element">
                <label>Phone</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone"
                       value="<?php echo htmlspecialchars($user_phone ?? '', ENT_QUOTES); ?>"
                       placeholder="0501231234 or +380501231234" required />
            </div>

            <div class="form-group checkout-small-element">
                <label>City</label>
                <input type="text" class="form-control" id="checkout-city" name="city"
                       value="<?php echo htmlspecialchars($user_city ?? '', ENT_QUOTES); ?>"
                       placeholder="City" required />
            </div>

            <div class="form-group checkout-large-element">
                <label>Address</label>
                <input type="text" class="form-control" id="checkout-address" name="address"
                       value="<?php echo htmlspecialchars($user_address ?? '', ENT_QUOTES); ?>"
                       placeholder="Address" required />
            </div>

            <div class="form-group checkout-btn-container">
                <p>Total amount: $<?php echo $_SESSION['total']; ?></p>
                <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Place Order" />
            </div>
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>

<script>
  document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const email = document.getElementById('checkout-email').value.trim();
    const phone = document.getElementById('checkout-phone').value.trim();
    const city = document.getElementById('checkout-city').value.trim();
    const address = document.getElementById('checkout-address').value.trim();
    const name = document.getElementById('checkout-name').value.trim();

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^(?:\+380\d{9}|0\d{9})$/;

    if (!name) {
      alert('Name is required.');
      e.preventDefault();
      return;
    }

    if (!emailPattern.test(email)) {
      alert('Please enter a valid email address.');
      e.preventDefault();
      return;
    }

    if (!phonePattern.test(phone)) {
      alert('Please enter a valid phone number (0501231234 or +380501231234).');
      e.preventDefault();
      return;
    }

    if (city.length < 2) {
      alert('Please enter a valid city.');
      e.preventDefault();
      return;
    }

    if (address.length < 5) {
      alert('Please enter a valid address.');
      e.preventDefault();
      return;
    }
  });
</script>
