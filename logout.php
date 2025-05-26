<?php
session_start();

// Очищення всіх сесійних змінних
session_unset();

// Завершення сесії
session_destroy();

// Перенаправлення на сторінку логіну після виходу
header('Location: login.php');
exit();
?>
