<?php
session_start();
include __DIR__ . '/header.php';
include '../server/connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php?error=please log in first');
    exit;
}

// Отримуємо всі звернення
$stmt = $conn->prepare("
    SELECT hr.*, u.user_email 
    FROM help_requests hr 
    JOIN users u ON hr.user_id = u.user_id 
    ORDER BY hr.created_at DESC
");
$stmt->execute();
$help_requests = $stmt->get_result();
?>

<div class="container-fluid" style="background:#f9f9f9; min-height:100vh; padding:0;">
    <div class="row" style="min-height:calc(100vh - 48px);">
        
        <!-- Сайдбар -->
        <?php include __DIR__ . '/sidemenu.php'; ?>

        <!-- Основний контент -->
        <main class="col-md-10 ms-auto px-4" style="padding-top:30px;">
            <h1 class="mb-4">User Help Requests</h1>

            <?php if (isset($_GET['reply_sent'])): ?>
                <div class="alert alert-success">Reply sent successfully!</div>
            <?php elseif (isset($_GET['reply_failed'])): ?>
                <div class="alert alert-danger">Failed to send reply.</div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="alert alert-warning">Help request deleted.</div>
            <?php elseif (isset($_GET['delete_failed'])): ?>
                <div class="alert alert-danger">Failed to delete help request.</div>
            <?php endif; ?>

            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>№</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Reply</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($help_requests->num_rows > 0): ?>
                            <?php while ($row = $help_requests->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['user_email']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        <form action="reply_help.php" method="POST" class="d-flex flex-column">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <textarea name="reply" class="form-control mb-2" rows="2" placeholder="Enter reply..." required><?= htmlspecialchars($row['reply']) ?></textarea>
                                            <button type="submit" class="btn btn-sm btn-primary">Send</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="delete_help.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No help requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
