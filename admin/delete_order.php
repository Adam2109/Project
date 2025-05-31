<?php
session_start();
include('../server/connection.php');

// Перевірка, чи адмін увійшов у систему
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

// Видалення замовлення
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);

    if ($stmt->execute()) {
        header('Location: index.php?deleted_successfully=Order has been deleted successfully');
    } else {
        header('Location: index.php?deleted_failure=Error deleting order');
    }
    $stmt->close();
}
?>
