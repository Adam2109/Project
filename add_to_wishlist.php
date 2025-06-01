<?php
session_start();
include('server/connection.php');

// Перевірка на авторизацію
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// Перевірка, чи товар вже в списку бажань
$query = $conn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND product_id = ?");
$query->bind_param("ii", $user_id, $product_id);
$query->execute();
$query->store_result();

if ($query->num_rows === 0) {
    // Додавання товару в список бажань
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product successfully added to wishlist!";
    } else {
        $_SESSION['message'] = "Error adding product to wishlist.";
    }
} else {
    $_SESSION['message'] = "This product is already on your wishlist.";
}

header('Location: wishlist.php'); // Перенаправлення на сторінку списку бажань
exit;
?>
