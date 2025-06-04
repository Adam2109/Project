<?php

include('server/connection.php');
// Обов'язково, якщо не стартувала в server/connection.php

// Перенаправлення неавторизованого користувача
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=login_required');
    
    exit();
}

include('layouts/header.php');

// Тепер твоя логіка ↓ без змін
$user_id = $_SESSION['user_id'];

// Добавление товара в корзину
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $insert_sql = "INSERT INTO cart (user_id, product_id, product_quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $product_quantity);
        $insert_stmt->execute();
    } else {
        echo "<script>alert('Product is already in your cart');</script>";
    }
}

// Удаление товара
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $user_id, $product_id);
    $delete_stmt->execute();
}

// Изменение количества
if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];
    $update_sql = "UPDATE cart SET product_quantity = ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $product_quantity, $user_id, $product_id);
    $update_stmt->execute();
}

// Получаем корзину пользователя с join'ом на products
$sql = "SELECT p.product_name, p.product_price, p.product_image, c.product_quantity, c.product_id
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

// Подсчёт общей суммы и количества
$total_price = 0;
$total_quantity = 0;
foreach ($cart_items as $item) {
    $total_price += $item['product_price'] * $item['product_quantity'];
    $total_quantity += $item['product_quantity'];
}
?>
