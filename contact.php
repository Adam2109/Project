<?php include('layouts/header.php'); ?>

<!-- Contact -->
<section id="contact" class="container my-5 py-5">
    <div class="container text-center mt-5">
        <h3>Contact us</h3>
        <hr class="mx-auto">
        <p class="w-50 mx-auto">Phone: <span>+380-1231-213-22</span></p>
        <p class="w-50 mx-auto">Email address: <span>info@gmail.com</span></p>
        <p class="w-50 mx-auto">
            We work 24/7 to answer your questions.
            <button id="helpBtn" class="btn btn-primary ms-3">
                Help <span id="helpIcon"></span>
            </button>
        </p>
    </div>
</section>

<!-- Help Modal -->
<div id="helpModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content bg-white p-4" style="margin: 5% auto; border: 1px solid #888; width: 500px; max-height: 80%; overflow-y: auto; border-radius: 10px;">
        <span id="closeModal" style="float:right; font-size: 28px; cursor: pointer;">&times;</span>
        <h4>Ask for Help</h4>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            include('server/connection.php');
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("SELECT message, reply, created_at FROM help_requests WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    echo "<div class='mb-3' style='max-height: 300px; overflow-y: auto;'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='border rounded p-2 mb-3'>";
                        echo "<p class='text-muted mb-1'><strong>You:</strong><br>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
                        echo "<small class='text-muted'>" . htmlspecialchars($row['created_at']) . "</small><br>";
                        if (!empty($row['reply'])) {
                            echo "<div class='alert alert-info mt-2 mb-0 p-2'><strong>Admin:</strong><br>" . nl2br(htmlspecialchars($row['reply'])) . "</div>";
                        } else {
                            echo "<div class='text-muted fst-italic mt-2'>Awaiting reply...</div>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class='text-muted'>You haven't asked anything yet.</p>";
                }
            }
            $stmt->close();
        }
        ?>

        <form method="POST" action="server/send_help.php">
            <div class="mb-3">
                <label for="message" class="form-label">Your question</label>
                <textarea class="form-control" name="message" rows="3" placeholder="Type your question..." required></textarea>
            </div>
            <input type="submit" class="btn btn-success" value="Send">
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('helpModal');
    const btn = document.getElementById('helpBtn');
    const close = document.getElementById('closeModal');

    btn.onclick = () => {
        modal.style.display = "block";

        // Очистити іконку 🔔
        btn.classList.remove('btn-warning');
        btn.classList.add('btn-primary');
        const icon = document.getElementById('helpIcon');
        if (icon) {
            icon.innerText = '';
        }

        // Позначити повідомлення як прочитані
        fetch('check_new_reply.php?mark_read=1');
    };

    close.onclick = () => modal.style.display = "none";

    window.onclick = event => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    function checkForNewReply() {
        fetch('check_new_reply.php')
            .then(response => response.json())
            .then(data => {
                if (data.new_reply && btn) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-warning');

                    const icon = document.getElementById('helpIcon');
                    if (icon) {
                        icon.innerText = '🔔';
                    }
                }
            })
            .catch(err => console.error('Check reply error:', err));
    }

    // Запустити перевірку кожні 15 секунд
    setInterval(checkForNewReply, 15000);
    checkForNewReply();
</script>

<?php include('layouts/footer.php'); ?>
