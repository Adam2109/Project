<?php
include('connection.php');
$term = trim($_GET['term'] ?? '');
$suggestions = [];
if ($term !== '') {
    $stmt = $conn->prepare("SELECT DISTINCT product_name FROM products WHERE product_name LIKE ? LIMIT 10");
    $like = "%$term%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['product_name'];
    }
}
header('Content-Type: application/json');
echo json_encode($suggestions);