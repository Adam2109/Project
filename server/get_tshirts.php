<?php

include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='t-shirts' LIMIT 4");

$stmt->execute();

$tshirts_products = $stmt->get_result();


?>