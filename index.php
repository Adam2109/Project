<?php include('layouts/header.php');?>
<?php
include('server/connection.php');
?>

<?php
$wishlist_product_ids = [];

if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $wishlist_query = $conn->prepare("SELECT product_id FROM wishlist WHERE user_id = ?");
    $wishlist_query->bind_param("i", $user_id);
    $wishlist_query->execute();
    $result = $wishlist_query->get_result();
    while ($row_wishlist = $result->fetch_assoc()) {
        $wishlist_product_ids[] = $row_wishlist['product_id'];
    }
}
?>
<!--Home-->
<section id="home">
  <div class="container">
    <h5>NEW ARRIVALS</h5>
    <h1><span>Best Prices</span> This Season</h1>
    <p>Eshop offers the best products for the most affordable prices</p>
    <button>Shop Now</button>
  </div>
</section>

<!--Brand-->
<section id="brand" class="container">
  <div class="row">
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand1.jpeg"/>
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand2.jpeg"/>
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand3.jpeg"/>
    <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand4.jpeg"/>
  </div>
</section>

<!--New-->
<section id="new" class="w-100">
  <div class="row p-0 m-0">
    <!--One-->
    <div class="one col-lg-4 col-md-12 col-sm-12">
      <img class="img-fluid" src="assets/imgs/1.jpeg"/>
      <div class="details">
        <h2>Extreamely Awesome Shoes</h2>
        <button class="text-uppercase">Shop Now</button>
      </div>
    </div>
    <!--Two-->
    <div class="one col-lg-4 col-md-12 col-sm-12">
      <img class="img-fluid" src="assets/imgs/white t-shirt1.jpeg"/>
      <div class="details">
        <h2>Awesome Clothes</h2>
        <button class="text-uppercase">Shop Now</button>
      </div>
    </div>
    <!--Three-->
    <div class="one col-lg-4 col-md-12 col-sm-12">
      <img class="img-fluid" src="assets/imgs/Bag1.jpeg"/>
      <div class="details">
        <h2>50% OFF Accessories</h2>
        <button class="text-uppercase">Shop Now</button>
      </div>
    </div>
  </div>
</section>
  
<!--Featured-->
<section id="featured" class="my-5 pb5">
  <div class="container text-center mt-5 py-5">
    <h3>Our Featured</h3>
    <hr class="mx-auto">
    <p>Here you can check out our featured products</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php include('server/get_featured_products.php');?>
    <?php while($row = $featured_products->fetch_assoc()) { ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
        <!-- Wishlist button -->
        <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
          <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
          <button type="submit" class="wishlist-btn" title="Add to wishlist">
            <i class="far fa-heart"></i>
            <i class="fas fa-heart"></i>
          </button>
        </form>

        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image'];?>"/>
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$<?php echo $row['product_price'];?></h4>
        <a href="single_product.php?product_id=<?php echo $row['product_id'];?>" class="btn buy-btn">Buy Now</a>
      </div>
    <?php } ?>  
  </div>
</section>

<!--Banner-->
<section id="banner" class="my-5 py-5">
  <div class="container">
    <h4>MID SEASON'S SALE</h4>
    <h1>Season Collection <br> UP to 30% OFF</h1>  
    <button class="text-uppercase">shop now</button>
  </div>
</section>

<!--T-shirts-->
<section id="t-shirts" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>T-shirts</h3>
    <hr class="mx-auto">
    <p>Here you can check out our amazing clothes</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php include('server/get_tshirts.php'); ?>
    <?php while($row = $tshirts_products->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
        <!-- Wishlist button -->
        <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
          <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
          <button type="submit" class="wishlist-btn <?php echo in_array($row['product_id'], $wishlist_product_ids) ? 'active' : ''; ?>" title="Add to wishlist">
            <i class="far fa-heart"></i>
            <i class="fas fa-heart"></i>
          </button>
        </form>


        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']?>"/>
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$<?php echo $row['product_price'];?></h4>
        <a href="single_product.php?product_id=<?php echo $row['product_id'];?>" class="btn buy-btn">Buy Now</a>
      </div>
    <?php } ?>
  </div>
</section>

<!--Accessories-->
<section id="accessories" class="my-5" >
  <div class="container text-center mt-5 py-5">
    <h3>Best Accessories</h3>
    <hr class="mx-auto">
    <p>Check out our unique accessories</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php include('server/get_accessories.php'); ?>
    <?php while($row = $accessories->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
       <!-- Wishlist button -->
        <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
          <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
          <button type="submit" class="wishlist-btn <?php echo in_array($row['product_id'], $wishlist_product_ids) ? 'active' : ''; ?>" title="Add to wishlist">
            <i class="far fa-heart"></i>
            <i class="fas fa-heart"></i>
          </button>
        </form>


        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']?>"/>
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$<?php echo $row['product_price'];?></h4>
        <a href="single_product.php?product_id=<?php echo $row['product_id'];?>" class="btn buy-btn">Buy Now</a>
      </div>
    <?php } ?>
  </div>
</section>

<!--Shoes-->
<section id="shoes" class="my-5">
  <div class="container text-center mt-5 py-5">
    <h3>Shoes</h3>
    <hr class="mx-auto">
    <p>Here you can check out our amazing shoes</p>
  </div>
  <div class="row mx-auto container-fluid">
    <?php include('server/get_shoes.php'); ?>
    <?php while($row = $shoes->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
        <!-- Wishlist button -->
        <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
          <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
          <button type="submit" class="wishlist-btn <?php echo in_array($row['product_id'], $wishlist_product_ids) ? 'active' : ''; ?>" title="Add to wishlist">
            <i class="far fa-heart"></i>
            <i class="fas fa-heart"></i>
          </button>
        </form>


        <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']?>"/>
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$<?php echo $row['product_price'];?></h4>
        <a href="single_product.php?product_id=<?php echo $row['product_id'];?>" class="btn buy-btn">Buy Now</a>
      </div>
    <?php } ?>
  </div>
</section>

<?php include('layouts/footer.php');?>
