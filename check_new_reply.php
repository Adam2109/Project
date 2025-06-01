<?php
session_start();
header('Content-Type: application/json');

include('server/connection.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['new_reply' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['mark_read']) && $_GET['mark_read'] == 1) {
    // Позначаємо всі відповіді як прочитані
    $stmt = $conn->prepare("UPDATE help_requests SET is_read = 1 WHERE user_id = ? AND reply IS NOT NULL");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'read_marked']);
    } else {
        echo json_encode(['error' => 'update_failed']);
    }
    exit;
}

// Перевіряємо, чи є нові відповіді, які ще не прочитані
$stmt = $conn->prepare("SELECT COUNT(*) FROM help_requests WHERE user_id = ? AND reply IS NOT NULL AND is_read = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

echo json_encode(['new_reply' => $count > 0]);
