<?php
include('../server/connection.php');

if (isset($_POST['create_product'])) {
   
    $product_name = $_POST['title'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_special_offer = $_POST['sale'];
    $product_category = $_POST['category'];
    $product_color = $_POST['color'];
    $product_sport_type = $_POST['sport_type'];
    $product_brand = $_POST['brand'];
    $product_material = $_POST['material'];

    
    $image1 = $_FILES['image1']['tmp_name'];
    $image2 = $_FILES['image2']['tmp_name'];
    $image3 = $_FILES['image3']['tmp_name'];
    $image4 = $_FILES['image4']['tmp_name'];

   
    $image_name1 = $product_name . "1.jpeg";
    $image_name2 = $product_name . "2.jpeg";
    $image_name3 = $product_name . "3.jpeg";
    $image_name4 = $product_name . "4.jpeg";

    
    move_uploaded_file($image1, "../assets/imgs/" . $image_name1);
    move_uploaded_file($image2, "../assets/imgs/" . $image_name2);
    move_uploaded_file($image3, "../assets/imgs/" . $image_name3);
    move_uploaded_file($image4, "../assets/imgs/" . $image_name4);

    
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, product_description, product_price, product_special_offer, 
         product_image, product_image2, product_image3, product_image4, 
         product_category, product_color, sport_type, brand, material) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param(
        'sssssssssssss',
        $product_name,
        $product_description,
        $product_price,
        $product_special_offer,
        $image_name1,
        $image_name2,
        $image_name3,
        $image_name4,
        $product_category,
        $product_color,
        $product_sport_type,
        $product_brand,
        $product_material
    );

    if ($stmt->execute()) {
    
    $product_id = $conn->insert_id;
   
    if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
        $ins_stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size) VALUES (?, ?)");
        foreach ($_POST['sizes'] as $size) {
            $ins_stmt->bind_param("is", $product_id, $size);
            $ins_stmt->execute();
        }
    }
    
    header('Location: products.php?product_created=Product has been created successfully');
    exit();
} else {
    header('Location: products.php?product_failed=Error occurred, try again');
    exit();
}
}
?>
``` 

Проверьте также, что в форме add_product.php доступны поля для Sport Type, Brand и Material (они должны передаваться через POST с именами "sport_type", "brand" и "material"). Это обеспечит корректное отображение этих данных при создании товара. 

Если после этого товар ещё дублируется, убедитесь, что файл create_product.php не вставлен два раза или не вызывается дважды из формы.<?php
include('../server/connection.php');

if (isset($_POST['create_product'])) {
  
    $product_name = $_POST['title'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_special_offer = $_POST['sale'];
    $product_category = $_POST['category'];
    $product_color = $_POST['color'];
    $product_sport_type = $_POST['sport_type'];
    $product_brand = $_POST['brand'];
    $product_material = $_POST['material'];

    
    $image1 = $_FILES['image1']['tmp_name'];
    $image2 = $_FILES['image2']['tmp_name'];
    $image3 = $_FILES['image3']['tmp_name'];
    $image4 = $_FILES['image4']['tmp_name'];

    
    $image_name1 = $product_name . "1.jpeg";
    $image_name2 = $product_name . "2.jpeg";
    $image_name3 = $product_name . "3.jpeg";
    $image_name4 = $product_name . "4.jpeg";

   
    move_uploaded_file($image1, "../assets/imgs/" . $image_name1);
    move_uploaded_file($image2, "../assets/imgs/" . $image_name2);
    move_uploaded_file($image3, "../assets/imgs/" . $image_name3);
    move_uploaded_file($image4, "../assets/imgs/" . $image_name4);

    
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, product_description, product_price, product_special_offer, 
         product_image, product_image2, product_image3, product_image4, 
         product_category, product_color, sport_type, brand, material) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param(
        'sssssssssssss',
        $product_name,
        $product_description,
        $product_price,
        $product_special_offer,
        $image_name1,
        $image_name2,
        $image_name3,
        $image_name4,
        $product_category,
        $product_color,
        $product_sport_type,
        $product_brand,
        $product_material
    );

    if ($stmt->execute()) {
       
        $product_id = $conn->insert_id;
        
        if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            $ins_stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size) VALUES (?, ?)");
            foreach ($_POST['sizes'] as $size) {
                $ins_stmt->bind_param("is", $product_id, $size);
                $ins_stmt->execute();
            }
        }
        
        header('Location: products.php?product_created=Product has been created successfully');
    } else {
        header('Location: products.php?product_failed=Error occurred, try again');
    }
}
?>