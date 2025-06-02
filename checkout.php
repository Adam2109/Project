<?php include('layouts/header.php'); ?>
<?php
// session_start();

include('server/connection.php');

if (empty($_SESSION['cart'])) {
    header('location: index.php');
    exit;
}

// Якщо користувач залогінений – отримуємо його дані
$user_name = '';
$user_email = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT user_name, user_email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_name, $user_email);
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
                <?php if (isset($_GET['message'])) { echo $_GET['message']; } ?>
                <?php if (isset($_GET['message'])) { ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                <?php } ?>
            </p>

            <div class="form-group checkout-small-element">
                <label>Name</label>
                <input type="text" class="form-control" id="checkout-name" name="name"
                       value="<?php echo htmlspecialchars($user_name, ENT_QUOTES); ?>" placeholder="Name" required/>
            </div>
            <div class="form-group checkout-small-element">
                <label>Email</label>
                <input type="email" class="form-control" id="checkout-email" name="email"
                       value="<?php echo htmlspecialchars($user_email, ENT_QUOTES); ?>" placeholder="Email" required/>
            </div>
            <div class="form-group checkout-small-element">
                <label>Phone</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Phone" required/>
            </div>
            <div class="form-group checkout-small-element">
                <label>City</label>
                <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required/>
            </div>
            <div class="form-group checkout-large-element">
                <label>Address</label>
                <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required/>
            </div>
            <div class="form-group checkout-btn-container">
                <p>Total amount: $ <?php echo $_SESSION['total']; ?></p>
                <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Place Order"/>
            </div>
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
