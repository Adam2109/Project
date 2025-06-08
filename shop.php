<?php include('layouts/header.php'); ?>

<?php



$all_categories = ['shoes', 't-shirts', 'accessories', 'bags'];

$sports = ['Football', 'Running', 'Basketball', 'Gym'];

$brands = ['Nike', 'Adidas', 'Puma', 'Reebok'];

$sizes = ['S', 'M', 'L', 'XL'];

$materials = ['Cotton', 'Polyester', 'Nylon', 'Wool'];

?>

<?php

include('server/connection.php');



$products = [];

$categories = $_POST['categories'] ?? [];

$price = $_POST['price'] ?? 1000;

$product_name = trim($_POST['product_name'] ?? "");

$product_color = $_POST['product_color'] ?? [];

$sport_type = $_POST['sport_type'] ?? [];

$brand = $_POST['brand'] ?? [];

$size = $_POST['size'] ?? [];

$material = $_POST['material'] ?? [];

$sort_by = $_POST['sort_by'] ?? "";

$page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;



$total_records_per_page = 8;

$offset = ($page_no - 1) * $total_records_per_page;

$params = [];

$types = "";

$conditions = [];



if (isset($_POST['search'])) {

    if (!empty($categories)) {

        $placeholders = implode(',', array_fill(0, count($categories), '?'));

        $conditions[] = "product_category IN ($placeholders)";

        $params = array_merge($params, $categories);

        $types .= str_repeat("s", count($categories));

    }



    if (!empty($product_name)) {

        $conditions[] = "product_name LIKE ?";

        $params[] = '%' . $product_name . '%';

        $types .= "s";

    }



    if (!empty($product_color)) {
    $placeholders = implode(',', array_fill(0, count($product_color), '?'));
    $conditions[] = "product_color IN ($placeholders)";
    $params = array_merge($params, $product_color);
    $types .= str_repeat("s", count($product_color));
}




    if (!empty($sport_type)) {

        $placeholders = implode(',', array_fill(0, count($sport_type), '?'));

        $conditions[] = "sport_type IN ($placeholders)";

        $params = array_merge($params, $sport_type);

        $types .= str_repeat("s", count($sport_type));

    }



    if (!empty($brand)) {

        $placeholders = implode(',', array_fill(0, count($brand), '?'));

        $conditions[] = "brand IN ($placeholders)";

        $params = array_merge($params, $brand);

        $types .= str_repeat("s", count($brand));

    }


$join_sizes = false; // 

if (!empty($size)) {
    $placeholders = implode(',', array_fill(0, count($size), '?'));
    $conditions[] = "product_sizes.size IN ($placeholders)";
    $params = array_merge($params, $size);
    $types .= str_repeat("s", count($size));
    $join_sizes = true; // 
}
  



    if (!empty($material)) {

        $placeholders = implode(',', array_fill(0, count($material), '?'));

        $conditions[] = "material IN ($placeholders)";

        $params = array_merge($params, $material);

        $types .= str_repeat("s", count($material));

    }



    $conditions[] = "product_price <= ?";

    $params[] = $price;

    $types .= "i";



    $where_clause = implode(" AND ", $conditions);



    $sql1 = "SELECT COUNT(DISTINCT products.product_id) FROM products";
if ($join_sizes) {
    $sql1 .= " JOIN product_sizes ON products.product_id = product_sizes.product_id";
}
$sql1 .= " WHERE $where_clause";
$stmt1 = $conn->prepare($sql1);


    $stmt1->bind_param($types, ...$params);

    $stmt1->execute();

    $stmt1->bind_result($total_records);

    $stmt1->store_result();

    $stmt1->fetch();



    $total_no_of_pages = ceil($total_records / $total_records_per_page);



    $order_by = match ($sort_by) {

        "price_asc" => "ORDER BY product_price ASC",

        "price_desc" => "ORDER BY product_price DESC",

        "name_asc" => "ORDER BY product_name ASC",

        "name_desc" => "ORDER BY product_name DESC",

        default => "",

    };



    $params[] = $offset;

    $params[] = $total_records_per_page;

    $types .= "ii";



   $sql2 = "SELECT DISTINCT products.* FROM products";
if ($join_sizes) {
    $sql2 .= " JOIN product_sizes ON products.product_id = product_sizes.product_id";
}
$sql2 .= " WHERE $where_clause $order_by LIMIT ?, ?";
$stmt2 = $conn->prepare($sql2);


    $stmt2->bind_param($types, ...$params);

    $stmt2->execute();

    $products = $stmt2->get_result();

} else {

    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM products");

    $stmt1->execute();

    $stmt1->bind_result($total_records);

    $stmt1->store_result();

    $stmt1->fetch();



    $total_no_of_pages = ceil($total_records / $total_records_per_page);



    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT ?, ?");

    $stmt2->bind_param("ii", $offset, $total_records_per_page);

    $stmt2->execute();

    $products = $stmt2->get_result();

}

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



<main>

<section id="search" class="my-5 py-5 ms-2">

    <div class="container mt-5 py-5">

        <p>Search Products</p>

        <hr>

    </div>



    <!-- 🔁 АВТОМАТИЧНІ ФІЛЬТРИ -->
<form action="shop.php" method="POST" id="autoFiltersForm">
    <input type="hidden" name="search" value="1">
    <div class="row">
        <div class="col-md-4">

            <!-- Sort by -->
            <label>Sort by</label>
            <select class="form-select mb-3" name="sort_by">
                <option value="">Default</option>
                <option value="price_asc" <?= ($sort_by == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_desc" <?= ($sort_by == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
                <option value="name_asc" <?= ($sort_by == 'name_asc') ? 'selected' : '' ?>>Name: A to Z</option>
                <option value="name_desc" <?= ($sort_by == 'name_desc') ? 'selected' : '' ?>>Name: Z to A</option>
            </select>

            <!-- Product Name -->
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control mb-3" value="<?= htmlspecialchars($product_name) ?>">

            <!-- Search Button -->
            <input type="submit" name="search" class="btn btn-primary mb-3" value="Search">

            <!-- Color as checkboxes -->
            <h5>Color</h5>
            <?php
            $color_options = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Gray'];
            ?>
            <?php foreach ($color_options as $c): ?>
                <div class="form-check">
                    <input class="form-check-input" name="product_color[]" type="checkbox" value="<?= $c ?>" id="color_<?= $c ?>" <?= is_array($product_color) && in_array($c, $product_color) ? "checked" : "" ?>>
                    <label class="form-check-label" for="color_<?= $c ?>"><?= $c ?></label>
                </div>
            <?php endforeach; ?>

            <!-- Categories -->
            <h5 class="mt-3">Categories</h5>
            <?php foreach ($all_categories as $cat): ?>
                <div class="form-check">
                    <input class="form-check-input" name="categories[]" type="checkbox" value="<?= $cat ?>" id="cat_<?= $cat ?>" <?= in_array($cat, $categories) ? "checked" : "" ?>>
                    <label class="form-check-label" for="cat_<?= $cat ?>"><?= $cat ?></label>
                </div>
            <?php endforeach; ?>

            <!-- Sport Type -->
            <h5 class="mt-3">Sport Type</h5>
            <?php foreach ($sports as $s): ?>
                <div class="form-check">
                    <input class="form-check-input" name="sport_type[]" type="checkbox" value="<?= $s ?>" id="sport_<?= $s ?>" <?= in_array($s, $sport_type) ? "checked" : "" ?>>
                    <label class="form-check-label" for="sport_<?= $s ?>"><?= $s ?></label>
                </div>
            <?php endforeach; ?>

            <!-- Brand -->
            <h5 class="mt-3">Brand</h5>
            <?php foreach ($brands as $b): ?>
                <div class="form-check">
                    <input class="form-check-input" name="brand[]" type="checkbox" value="<?= $b ?>" id="brand_<?= $b ?>" <?= in_array($b, $brand) ? "checked" : "" ?>>
                    <label class="form-check-label" for="brand_<?= $b ?>"><?= $b ?></label>
                </div>
            <?php endforeach; ?>

            <!-- Size -->
            <h5 class="mt-3">Size</h5>
            <?php foreach ($sizes as $s): ?>
                <div class="form-check">
                    <input class="form-check-input" name="size[]" type="checkbox" value="<?= $s ?>" id="size_<?= $s ?>" <?= in_array($s, $size) ? "checked" : "" ?>>
                    <label class="form-check-label" for="size_<?= $s ?>"><?= $s ?></label>
                </div>
            <?php endforeach; ?>

            <!-- Price -->
            <h5 class="mt-3">Price</h5>
            <input type="range" name="price_slider" id="priceRange" class="form-range" min="1" max="1000" value="<?= $price ?>">
            <input type="number" name="price" id="priceInput" class="form-control mt-2" value="<?= $price ?>" min="1" max="1000">

            <!-- Material -->
            <h5 class="mt-3">Material</h5>
            <?php foreach ($materials as $m): ?>
                <div class="form-check">
                    <input class="form-check-input" name="material[]" type="checkbox" value="<?= $m ?>" id="material_<?= $m ?>" <?= in_array($m, $material) ? "checked" : "" ?>>
                    <label class="form-check-label" for="material_<?= $m ?>"><?= $m ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</form>




    <!-- JavaScript для автосабміту -->

    <script>

        const autoForm = document.getElementById('autoFiltersForm');

        autoForm.querySelectorAll('input, select').forEach(el => {

            el.addEventListener('change', () => autoForm.submit());

        });



        // Price input sync

        const priceRange = document.getElementById('priceRange');

        const priceInput = document.getElementById('priceInput');

        priceRange.addEventListener('input', () => priceInput.value = priceRange.value);

        priceInput.addEventListener('input', () => {

            const value = Math.min(Math.max(priceInput.value, 1), 1000);

            priceRange.value = value;

        });

    </script>

</section>

<section id="featured" class="my-5 py-5">
    <div class="container mt-5 py-5">
        <h3>Our Products</h3>
        <hr>
        <p>Here you can check out our products</p>
    </div>
    <div class="row mx-auto container">
        <?php while ($row = $products->fetch_assoc()) { ?>
            <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
                <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
                    <button type="submit" class="wishlist-btn <?php echo in_array($row['product_id'], $wishlist_product_ids) ? 'active' : ''; ?>" title="Add to wishlist">
                        <i class="far fa-heart"></i>
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">$<?php echo $row['product_price']; ?></h4>
                <a class="btn buy-btn" href="single_product.php?product_id=<?php echo $row['product_id']; ?>">Buy Now</a>
            </div>
        <?php } ?>

        <nav aria-label="Page navigation example" class="mx-auto">
            <ul class="pagination mt-5 mx-auto">
                <li class="page-item <?php if ($page_no <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page_no=<?php echo max(1, $page_no - 1); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_no_of_pages; $i++) { ?>
                    <li class="page-item <?php if ($page_no == $i) echo 'active'; ?>">
                        <a class="page-link" href="?page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php if ($page_no >= $total_no_of_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page_no=<?php echo min($total_no_of_pages, $page_no + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</section>
</main>

<?php include('layouts/footer.php'); ?>
