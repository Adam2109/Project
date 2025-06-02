<?php
// Старт сессии
session_start();

// Очистить все переменные сессии
$_SESSION = [];

// Удалить cookie сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Удалить саму сессию
session_destroy();

// Перенаправить на страницу входа
header('Location: login.php');
exit();
?>
