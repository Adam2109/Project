<?php

session_start();
include('server/connection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'No product selected']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product removed from wishlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing product from wishlist.']);
}
exit;
?>