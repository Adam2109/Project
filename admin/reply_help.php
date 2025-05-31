<?php
session_start();
include '../server/connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $reply = trim($_POST['reply']);

    $stmt = $conn->prepare("UPDATE help_requests SET reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $id);

    if ($stmt->execute()) {
        header("Location: help.php?reply_sent=1");
    } else {
        header("Location: help.php?reply_failed=1");
    }
    exit;
}
?>
                    