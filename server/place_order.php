<?php
    session_start();
    include('connection.php');
    //if user is not logged in
    if(!isset($_SESSION['logged_in'])){
        header('location: ../checkout.php?message=Please login/register to place an order');
        exit;
       //if user is logged in
    }else{


            if(isset($_POST['place_order'])){

                //get user info and store it in db

                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $city = $_POST['city'];
                $address = $_POST['address'];
                $order_cost = $_SESSION['total'];
                $order_status = "not paid";
                $user_id = $_SESSION['user_id'];
                $order_date = date('Y-m-d H:i:s');

                $stmt =  $conn->prepare("INSERT INTO orders (order_cost,order_status,user_id,user_phone,user_city,user_address,order_date)
                                VALUES (?,?,?,?,?,?,?); ");

                $stmt->bind_param('isiisss',$order_cost,$order_status,$user_id,$phone,$city,$address,$order_date);
                $stmt_status = $stmt->execute();

                if(!$stmt_status){
                    header('location: index.php');
                    exit;
                }

                //issue new order and store order info in db
                $order_id = $stmt->insert_id;
            
                //get prodects from cart
                foreach($_SESSION['cart'] as $key => $value){
                    $product = $_SESSION['cart'][$key];
                    $product_id = $product['product_id'];
                    $product_name = $product['product_name'];
                    $product_image = $product['product_image'];
                    $product_price = $product['product_price'];
                    $product_quantity = $product['product_quantity'];
                    $product_size = $product['product_size'];
                //store each single item in order_items
                    $stmt1 = $conn->prepare("INSERT INTO order_items (order_id,product_id,product_name,product_image,product_price,product_quantity,user_id,order_date, product_size)
                                    VALUES (?,?,?,?,?,?,?,?,?)");
                    
                    $stmt1->bind_param('iissiiiss',$order_id,$product_id,$product_name,$product_image,$product_price,$product_quantity,$user_id,$order_date,$product_size);
                    $stmt1->execute();
                }
                
                //remove everthing --> delay until payment is done
                // unset($_SESSION['cart']);
                
                $_SESSION['order_id'] = $order_id;

                //inform user whether everything is fine or not
                header('location: ../payment.php?order_status=order placed successfully');

            }

}
?>