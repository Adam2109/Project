<?php include('layouts/header.php'); ?>
<?php
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
<section class="container my-5 py-5 wishlist-section">
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
    <div class="row mx-auto container">
        <?php while ($item = $wishlist->fetch_assoc()): ?>
            <div class="product text-center col-lg-3 col-md-4 col-sm-12 mb-4" style="position: relative;">
                <!-- Кнопка wishlist (сердечко) -->
               <button type="button"
        class="wishlist-btn active"
        data-product-id="<?php echo $item['product_id']; ?>"
        title="Remove from wishlist"
        style="position:absolute;top:10px;right:10px;z-index:2;">
        <i class="far fa-heart"></i>
        <i class="fas fa-heart"></i>
    </button>
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $item['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES); ?></h5>
                <h4 class="p-price">$<?php echo $item['product_price']; ?></h4>
                <a class="btn buy-btn" href="single_product.php?product_id=<?php echo $item['product_id']; ?>">Buy Now</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="text-center">Your wish list is empty.</p>
<?php endif; ?>
<script src="assets/js/wishlist.js"></script>
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
