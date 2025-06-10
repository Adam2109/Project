<?php include('layouts/header.php'); ?>
<?php
include('server/connection.php');


if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
    $current_user = $_SESSION['user_id'];
    
    if (!isset($_SESSION['cart']) || !isset($_SESSION['cart_user']) || $_SESSION['cart_user'] != $current_user) {
        $_SESSION['cart'] = [];
        $_SESSION['cart_user'] = $current_user;
        
        unset($_SESSION['total']);
        unset($_SESSION['quantity']);
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $current_user);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $_SESSION['cart'][$row['product_id']] = [
                'product_id'       => $row['product_id'],
                'product_name'     => $row['product_name'],
                'product_price'    => $row['product_price'],
                'product_image'    => $row['product_image'],
                'product_quantity' => $row['product_quantity'],
                'product_size'     => $row['product_size']
            ];
        }
        
        calculateTotalCart();
    }
}

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $product_size = $_POST['product_size'];

   
    if(isset($_SESSION['cart'])){
        $products_array_ids = array_column($_SESSION['cart'], "product_id");
        if(!in_array($product_id, $products_array_ids)){
            $product_array = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'product_image' => $product_image,
                'product_quantity' => $product_quantity,
                'product_size' => $product_size
            ];
            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            echo '<script>alert("Product was already added to cart")</script>';
        }
    } else {
        $product_array = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity,
            'product_size' => $product_size
        ];
        $_SESSION['cart'][$product_id] = $product_array;
    }

    if(isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, product_quantity, product_size)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE product_quantity = VALUES(product_quantity), product_size = VALUES(product_size)");
        $stmt->bind_param("iisdsis", $user_id, $product_id, $product_name, $product_price, $product_image, $product_quantity, $product_size);
        $stmt->execute();
    }
    
    calculateTotalCart();

} elseif(isset($_POST['remove_product'])){
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);

    if(isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    
    calculateTotalCart();

} elseif(isset($_POST['edit_quantity'])){
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    $product_array = $_SESSION['cart'][$product_id];
    $product_array['product_quantity'] = $product_quantity;
    $_SESSION['cart'][$product_id] = $product_array;

    if(isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE cart SET product_quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $product_quantity, $user_id, $product_id);
        $stmt->execute();
    }

    calculateTotalCart();
} else {
    
    if (!isset($_SESSION['total'])) {
        calculateTotalCart();
    }
}

function calculateTotalCart(){
    $total_price = 0;
    $total_quantity = 0;

    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $product){
            $price = $product['product_price'];
            $quantity = $product['product_quantity'];
            $total_price += $price * $quantity;
            $total_quantity += $quantity;
        }
    }
    $_SESSION['total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;
}
?>

<!--Cart-->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your Cart</h2>    
        <hr>
    </div>
    <table class="mt-5 pt-5">
         <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
         </tr>
         <?php if(isset($_SESSION['cart'])) { ?>
             <?php foreach($_SESSION['cart'] as $value) { ?>
         <tr>
            <td>
                <div class="product-info">
                    <img src="assets/imgs/<?php echo $value['product_image']; ?>"/>
                    <div>
                        <p><?php echo $value['product_name']; ?></p>
                        <small><span>$</span><?php echo $value['product_price']; ?></small>
                        <br>
                        <small>Size: <?php echo $value['product_size']; ?></small>
                        <form method="POST" action="cart.php">
                          <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                          <input type="submit" name="remove_product" class="remove-btn" value="remove"/>
                        </form>
                    </div>
                </div>
            </td>
            <td>
                <form method="POST" action="cart.php">
                  <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                  <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>"/>
                  <input type="submit" class="edit-btn" value="edit" name="edit_quantity"/>
                </form>
            </td>
            <td>
                <span>$</span>
                <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
            </td>
         </tr>  
         <?php } ?>
         <?php } ?>
    </table>

    <div class="cart-total">
        <table>
            <tr>
                <td>Total</td>
                <td>$ <?php echo isset($_SESSION['total']) ? $_SESSION['total'] : 0; ?></td>
            </tr>
        </table>
    </div>
    <div class="checkout-container">
      <form method="POST" action="checkout.php">
        <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout"/>
      </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>