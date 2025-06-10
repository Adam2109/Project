<?php

session_start();
include('server/connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to use wishlist']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'No product selected']);
    exit;
}

$query = $conn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND product_id = ?");
$query->bind_param("ii", $user_id, $product_id);
$query->execute();
$query->store_result();

if ($query->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product successfully added to wishlist!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding product to wishlist.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'This product is already on your wishlist.']);
}
exit;
?>