<?php
session_start();
include('server/connection.php');

// Перевірка на авторизацію
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

<?php include('layouts/header.php'); ?>

<main class="container my-5 pt-5">
    <h2 class="mb-4">My wishlist</h2>

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
                    <div class="card h-100" style="border: 2px solid #333333; border-radius: 10px;"> <!-- Темно-сіра рамка -->
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

                            <!-- Кнопка кошика (переміщена вправо та збільшена) -->
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
        <p>Your wish list is empty.</p>
    <?php endif; ?>
</main>

<?php include('layouts/footer.php'); ?>

<script>
    // Якщо є повідомлення
    const message = document.getElementById("message");
    if (message) {
        // Через 2 секунди прибираємо повідомлення
        setTimeout(() => {
            message.style.display = "none";
        }, 2000); // 2000 мс = 2 секунди
    }
</script>
