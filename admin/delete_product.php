<?php
session_start();
include('../server/connection.php')

?>

<?php
if(!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header('Location: login.php?error=please log in first');
  exit;
}

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param('i', $_GET['product_id']);
    $stmt->execute();
    if($stmt->execute()){
        header('location: products.php?deleted_successfully=Product has been deleted successfully');
    }else{
        header('location: products.php?deleted_failure=Error deleting product');
    }
}


?>

