<?php
session_start();
include(__DIR__ . '/connection.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../contact.php");
    exit();
}

if (isset($_POST['message'])) {
    $user_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO help_requests (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../contact.php");
exit();
?>