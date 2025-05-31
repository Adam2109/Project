<?php
session_start();
include '../server/connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM help_requests WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: help.php?deleted=1");
    } else {
        header("Location: help.php?delete_failed=1");
    }

    $stmt->close();
    exit;
} else {
    header("Location: help.php");
    exit;
}
