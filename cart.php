<?php include('layouts/header.php');?>
<?php




if(isset($_POST['add_to_cart'])){
  //if user has already added a product
  if(isset($_SESSION['cart'])){
    $products_array_ids = array_column($_SESSION['cart'], "product_id");
    //if product has already added or not
    if(!in_array($_POST['product_id'], $products_array_ids)){
      $product_id = $_POST['product_id'];   
      $product_array = array(
                        'product_id' => $_POST['product_id'],
                        'product_name' => $_POST['product_name'],
                        'product_price' => $_POST['product_price'],
                        'product_image' => $_POST['product_image'],
                        'product_quantity' => $_POST['product_quantity'],
                        'product_size' => $_POST['product_size']
                        
      );
  
      $_SESSION['cart'][$product_id] = $product_array;
      // product has already added
    }else{
      echo '<script>alert("Product was already added to cart")</script>';
      //echo '<script>window.location="index.php";</script>';
    }
    //if this is the first product
  }else{

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $product_size = $_POST['product_size'];
    $product_array = array(
                      'product_id' => $product_id,
                      'product_name' => $product_name,
                      'product_price' => $product_price,
                      'product_image' => $product_image,
                      'product_quantity' => $product_quantity,
                      'product_size' => $product_size
    );

    $_SESSION['cart'][$product_id] = $product_array;
    
  }
  //calc total
  calculateTotalCart();
//remove product from cart
}elseif(isset($_POST['remove_product'])){
  $product_id = $_POST['product_id'];
  unset($_SESSION['cart'][$product_id]);
  calculateTotalCart();

}elseif(isset($_POST['edit_quantity'])){
  $product_id = $_POST['product_id'];
  $product_quantity = $_POST['product_quantity'];

  $product_array = $_SESSION['cart'][$product_id];
  $product_array['product_quantity'] = $product_quantity;

  $_SESSION['cart'][$product_id] = $product_array;

  calculateTotalCart();
    
}else{
  // header('location" index.php');
}

function calculateTotalCart(){
  $total_price = 0;
  $total_quantity = 0;

  if (isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $key => $product){
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
            <h2 class="font-weight-bolde">Your Cart</h2>    
            <hr>
        </div>
        <table class="mt-5 pt-5">
             <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
             </tr>
             <?php if(isset($_SESSION['cart'])){?>
             <?php foreach($_SESSION['cart'] as $key => $value){ ?>
             <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/<?php echo $value['product_image'];?>"/>
                        <div>
                            <p><?php echo $value['product_name'];?></p>
                            <small><span>$</span><?php echo $value['product_price'];?></small>
                            <br>
                            <small>Size: <?php echo $value['product_size']; ?></small>
                            <form method="POST" action="cart.php">
                              <input type="hidden" name="product_id" value="<?php echo $value['product_id'];?>"/>
                              <input type="submit" name="remove_product" class="remove-btn" value="remove"/>
                            </form>
                            
                        </div>
                    </div>
                </td>

                <td>
                    
                    <form method="POST" action="cart.php">
                      <input type="hidden" name="product_id" value="<?php echo $value['product_id'];?>"/>
                      <input type="number" name="product_quantity" value="<?php echo $value['product_quantity'];?>"/>
                      <input type="submit" class="edit-btn" value="edit" name="edit_quantity"/>
                    </form>
                    
                </td>

                <td>
                    <span>$</span>
                    <span class="product-price"><?php echo $value['product_quantity'] *  $value['product_price'];?></span>
                </td>
             </tr>  
            
             <?php } ?>

             <?php } ?>
        </table>

        <div class="cart-total">
            <table>
                <!--<tr>
                    <td>Subtotal</td>
                    <td>$1950.97</td>
                </tr> -->
                <tr>
                    <td>Total</td>
                    <?php if(isset($_SESSION['cart'])){ ?>
                      <td>$ <?php echo $_SESSION['total'];?></td>
                    <?php } ?>
                </tr>
            </table>
        </div>
            <div class="checkout-container">
              <form method="POST" action="checkout.php">
                <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout"/>
              </form>
                
            </div>
        </section>

<?php include('layouts/footer.php');?>  