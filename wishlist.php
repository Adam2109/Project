<?php include('layouts/header.php'); ?>
<?php
// session_start();
include('server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('Location: wishlist_check.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Виведення списку бажань
$stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.product_price, p.product_image 
                        FROM products p 
                        JOIN wishlist w ON p.product_id = w.product_id
                        WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist = $stmt->get_result();
?>

<!-- Wishlist -->
<section class="container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">My Wishlist</h2>
        <hr>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="message" class="alert alert-info">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if ($wishlist->num_rows > 0): ?>
        <div class="row">
            <?php while ($item = $wishlist->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100" style="border: 2px solid #333333; border-radius: 10px;">
                        <img src="assets/imgs/<?php echo $item['product_image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['product_name'], ENT_QUOTES); ?>"/>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES); ?></h5>
                            <p class="card-text"><?php echo $item['product_price']; ?>$ </p>
                            <form method="POST" action="cart.php" class="d-inline">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>"/>
                                <input type="hidden" name="product_image" value="<?php echo $item['product_image']; ?>"/>
                                <input type="hidden" name="product_name" value="<?php echo $item['product_name']; ?>"/>
                                <input type="hidden" name="product_price" value="<?php echo $item['product_price']; ?>"/>
                                <input type="hidden" name="product_quantity" value="1"/>
                                <button class="buy-btn btn-sm" name="add_to_cart" type="submit">
                                    Add to Cart
                                </button>
                            </form>

                            <form method="POST" action="remove_from_wishlist.php" class="d-inline">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>"/>
                                <button class="btn btn-link btn-sm text-danger" style="font-size: 1.5rem; padding: 10px; margin-top: 10px; align-self: flex-end;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center">Your wish list is empty.</p>
    <?php endif; ?>
</section>

<?php include('layouts/footer.php'); ?>

<script>
    const message = document.getElementById("message");
    if (message) {
        setTimeout(() => {
            message.style.display = "none";
        }, 2000);
    }
</script>
