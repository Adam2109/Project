<?php

include('server/connection.php');
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result();
} else {
    header('location: index.php');
    exit;
}

?>

<?php include('layouts/header.php'); ?>

<!-- Single product -->
<section class="container single-product my-5 pt-5">
  <div class="row mt-5">
    <?php while($row = $product->fetch_assoc()) { ?>

      <div class="col-lg-5 col-md-6 col-sm-12">
        <img
          class="img-fluid w-100 pb-1"
          src="assets/imgs/<?php echo $row['product_image']; ?>"
          id="mainImg"
        />
        <div class="small-img-group">
          <div class="small-img-col">
            <img
              src="assets/imgs/<?php echo $row['product_image']; ?>"
              width="100%"
              class="small-img"
            />
          </div>
          <div class="small-img-col">
            <img
              src="assets/imgs/<?php echo $row['product_image2']; ?>"
              width="100%"
              class="small-img"
            />
          </div>
          <div class="small-img-col">
            <img
              src="assets/imgs/<?php echo $row['product_image3']; ?>"
              width="100%"
              class="small-img"
            />
          </div>
          <div class="small-img-col">
            <img
              src="assets/imgs/<?php echo $row['product_image4']; ?>"
              width="100%"
              class="small-img"
            />
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-12">
        <h6>Men/Shoes</h6>
        <h3 class="py-4"><?php echo $row['product_name']; ?></h3>
        <h2><?php echo $row['product_price']; ?></h2>

        <div class="product-actions d-flex align-items-center gap-2 mt-3">
          <!-- Add to Cart -->
          <form method="POST" action="cart.php" class="d-inline-block">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
            <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>"/>
            <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
            <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>
            <input type="number" name="product_quantity" value="1" min="1" class="me-2"/>
            <button class="buy-btn" type="submit" name="add_to_cart">Add To Cart</button>
          </form>

          <!-- Add to Wishlist -->
          <form method="POST" action="add_to_wishlist.php" class="d-inline-block">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
            <button type="submit" class="wishlist-btn" title="Add to wishlist">
              <i class="far fa-heart"></i>
              <i class="fas fa-heart"></i>
            </button>
          </form>
        </div>

        <h4 class="mt-5 mb-3">Product details</h4>
        <span><?php echo $row['product_description']; ?></span>

      </div>

    <?php } ?>
  </div>
</section>

<!-- Related products -->
<section id="related-products" class="my-5 pb-5">
  <div class="container text-center mt-5 py-5">
    <h3>Related products</h3>
    <hr class="mx-auto">
  </div>
  <div class="row mx-auto container-fluid">
    <!-- Example of one product -->
    <div class="product text-center col-lg-3 col-md-4 col-sm-12">
      <img class="img-fluid mb-3" src="assets/imgs/featured1.jpeg" />
      <div class="star">
        <i class="fas fa-star"></i><!-- ... -->
      </div>
      <h5 class="p-name">Sports Shoes</h5>
      <h4 class="p-price">$199.99</h4>
      <button class="buy-btn">Buy Now</button>
    </div>
    <!-- More related products ... -->
  </div>
</section>

<script>
  const mainImg = document.getElementById("mainImg");
  const smallImg = document.getElementsByClassName("small-img");
  for (let i = 0; i < smallImg.length; i++) {
    smallImg[i].onclick = function() {
      mainImg.src = smallImg[i].src;
    }
  }
</script>

<?php include('layouts/footer.php'); ?>
