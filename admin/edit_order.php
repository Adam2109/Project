<?php
session_start();
include __DIR__ . '/header.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

require_once __DIR__ . '/../server/connection.php';

$order = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_order'])) {
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param('si', $order_status, $order_id);

    if ($stmt->execute()) {
        header('Location: index.php?order_updated=Order updated successfully');
        exit;
    } else {
        header('Location: index.php?order_failed=Error updating order');
        exit;
    }
} elseif (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
} else {
    header('Location: index.php?error=Order not found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order – Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <?php include __DIR__ . '/sidemenu.php'; ?>

        <main class="col-md-10 ms-sm-auto px-4 py-4">
            <h1 class="h3 mb-3">Dashboard</h1>
            <h2 class="h4 mb-4">Edit Order</h2>

            <?php if ($order): ?>
                <form action="edit_order.php" method="post" class="bg-white p-4 rounded shadow-sm" style="max-width:600px;">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                    <input type="hidden" name="edit_order" value="1">

                    <div class="mb-3">
                        <label for="orderId" class="form-label fw-semibold">Order ID</label>
                        <input type="text" id="orderId" name="orderId" readonly
                               class="form-control" value="<?= htmlspecialchars($order['order_id']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="orderPrice" class="form-label fw-semibold">Order Price</label>
                        <input type="text" id="orderPrice" name="orderPrice" readonly
                               class="form-control" value="$<?= number_format((float)$order['order_cost'], 2) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="orderStatus" class="form-label fw-semibold">Order Status</label>
                        <select id="orderStatus" name="order_status" class="form-select">
                            <option value="not_paid" <?= $order['order_status'] === 'not_paid' ? 'selected' : '' ?>>Not Paid</option>
                            <option value="paid" <?= $order['order_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="orderDate" class="form-label fw-semibold">Order Date</label>
                        <input type="text" id="orderDate" name="orderDate" readonly
                               class="form-control" value="<?= htmlspecialchars($order['order_date']) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Edit</button>
                    <a href="index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">Order not found.</div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
