<?php
include('server/connection.php');

// Ініціалізація змінних
$products = [];
$categories = $_POST['categories'] ?? [];
$price = $_POST['price'] ?? 1000;
$product_name = trim($_POST['product_name'] ?? "");
$sort_by = $_POST['sort_by'] ?? "";
$page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;

$total_records_per_page = 8;
$offset = ($page_no - 1) * $total_records_per_page;
$params = [];
$types = "";
$conditions = [];

// Пошук
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

    $conditions[] = "product_price <= ?";
    $params[] = $price;
    $types .= "i";

    $where_clause = implode(" AND ", $conditions);

    // Пагінація
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM products WHERE $where_clause");
    $stmt1->bind_param($types, ...$params);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    // Сортування
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

    $stmt2 = $conn->prepare("SELECT * FROM products WHERE $where_clause $order_by LIMIT ?, ?");
    $stmt2->bind_param($types, ...$params);
    $stmt2->execute();
    $products = $stmt2->get_result();
} else {
    // Початкове завантаження
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

<?php include('layouts/header.php'); ?>

<section id="search" class="my-5 py-5 ms-2">
    <div class="container mt-5 py-5">
        <p>Search Products</p>
        <hr>
    </div>
    <form action="shop.php" method="POST">
        <div class="row mx-auto container">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <p>Product Name</p>
                <input type="text" class="form-control w-100" name="product_name" placeholder="Product name"
                    value="<?php echo htmlspecialchars($product_name); ?>" style="max-width: 400px;">
            </div>
        </div>

        <div class="row mx-auto container mt-3">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <p>Sort By</p>
                <select class="form-select w-100" name="sort_by" style="max-width: 400px;">
                    <option value="">Default</option>
                    <option value="price_asc" <?php if ($sort_by == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php if ($sort_by == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
                    <option value="name_asc" <?php if ($sort_by == 'name_asc') echo 'selected'; ?>>Name: A to Z</option>
                    <option value="name_desc" <?php if ($sort_by == 'name_desc') echo 'selected'; ?>>Name: Z to A</option>
                </select>
            </div>
        </div>

        <div class="row mx-auto container mt-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <p>Category</p>
                <?php
                $all_categories = ['shoes', 'coats', 'watches', 'featured'];
                foreach ($all_categories as $cat) {
                    $checked = in_array($cat, $categories) ? "checked" : "";
                    echo <<<HTML
                        <div class="form-check">
                            <input class="form-check-input" name="categories[]" type="checkbox" value="$cat" id="cat_$cat" $checked>
                            <label class="form-check-label" for="cat_$cat">{$cat}</label>
                        </div>
                    HTML;
                }
                ?>
            </div>
        </div>

        <div class="row mx-auto container mt-5">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <p>Price</p>
                <div class="d-flex align-items-center w-50">
                    <input type="range" class="form-range me-3" name="price_slider" id="priceRange" min="1" max="1000"
                        value="<?php echo $price; ?>">
                    <input type="number" class="form-control" name="price" id="priceInput" min="1" max="1000"
                        value="<?php echo $price; ?>" style="width: 100px;">
                </div>
                <div class="w-50">
                    <span style="float: left;">1</span>
                    <span style="float: right;">1000</span>
                </div>
                <script>
                    const priceRange = document.getElementById('priceRange');
                    const priceInput = document.getElementById('priceInput');
                    priceRange.addEventListener('input', () => priceInput.value = priceRange.value);
                    priceInput.addEventListener('input', () => priceRange.value = priceInput.value);
                </script>
            </div>
        </div>

        <div class="form-group my-3 mx-3">
            <input type="submit" name="search" value="Search" class="btn btn-primary">
        </div>
    </form>
</section>

<section id="featured" class="my-5 py-5">
    <div class="container mt-5 py-5">
        <h3>Our Products</h3>
        <hr>
        <p>Here you can check out our featured products</p>
    </div>
    <div class="row mx-auto container">
        <?php while ($row = $products->fetch_assoc()) { ?>
            <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position: relative;">
                <form method="POST" action="add_to_wishlist.php" class="wishlist-btn-container">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
                    <button type="submit" class="wishlist-btn" title="Add to wishlist">
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

<?php include('layouts/footer.php'); ?>
