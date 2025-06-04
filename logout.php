<?php
session_start();

include('server/connection.php');

// Если пользователь авторизован, очистим его корзину в БД
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($delete_cart_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Удаляем корзину из сессии тоже!
unset($_SESSION['cart']);
unset($_SESSION['total']);
unset($_SESSION['quantity']);

// Удаляем все сессионные данные
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
