<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    if (!$stmt) {
        error_log("Logout DELETE prepare failed: " . $conn->error);
    } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        error_log("Logout: Rows deleted = " . $stmt->affected_rows);
        if ($stmt->affected_rows === 0) {
            error_log("Logout: No rows deleted. Возможно, в таблице cart установлены триггеры или другая логика обновления вместо удаления.");
        }
    }
}

// Очищаем данные корзины из сессии
unset($_SESSION['cart']);
unset($_SESSION['total']);
unset($_SESSION['quantity']);

session_unset();
session_destroy();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: login.php");
exit();
?>