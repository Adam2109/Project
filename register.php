<?php include('layouts/header.php'); ?>
<?php
include('server/connection.php');

if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $phone    = trim($_POST['phone']);
    $city     = trim($_POST['city']);
    $address  = trim($_POST['address']);

    // Серверна валідація
    if (strlen($name) < 2 || strlen($name) > 50) {
        header('location: register.php?error=Name must be between 2 and 50 characters');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: register.php?error=Invalid email format');
        exit;
    }

    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        header('location: register.php?error=Invalid phone number');
        exit;
    }

    if (strlen($city) < 2) {
        header('location: register.php?error=City name is too short');
        exit;
    }

    if (strlen($address) < 5) {
        header('location: register.php?error=Address is too short');
        exit;
    }

    if ($password !== $confirmPassword) {
        header('location: register.php?error=Passwords do not match');
        exit;
    }

    if (strlen($password) < 6) {
        header('location: register.php?error=Password must be at least 6 characters');
        exit;
    }

    // Перевірка, чи email вже існує
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->store_result();
    $stmt1->fetch();

    if ($num_rows != 0) {
        header('location: register.php?error=User with this email already exists');
        exit;
    } else {
        // У майбутньому краще використовувати password_hash
        $hashedPassword = md5($password);

        $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_phone, user_city, user_address)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $email, $hashedPassword, $phone, $city, $address);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id']    = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name']  = $name;
            $_SESSION['logged_in']  = true;
            header('location: account.php?register_success=You registered successfully');
            exit;
        } else {
            header('location: register.php?error=Could not create account at the moment');
            exit;
        }
    }
}
?>

<!-- Register Form -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Register</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="register-form" method="POST" action="register.php">
            <p style="color: red;"><?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?></p>

            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name" required/>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" placeholder="Email" required/>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" placeholder="Phone number" required/>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" class="form-control" name="city" placeholder="City" required/>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" required/>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required/>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" required/>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" id="register-btn" name="register" value="Register"/>
            </div>
            <div class="form-group">
                <a href="login.php" class="btn" id="login-url">Do you have an account? Login</a>
            </div>
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>

<!-- Клієнтська валідація -->
<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    const name = document.querySelector('[name="name"]').value.trim();
    const email = document.querySelector('[name="email"]').value.trim();
    const phone = document.querySelector('[name="phone"]').value.trim();
    const city = document.querySelector('[name="city"]').value.trim();
    const address = document.querySelector('[name="address"]').value.trim();
    const password = document.querySelector('[name="password"]').value;
    const confirmPassword = document.querySelector('[name="confirmPassword"]').value;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^(?:\+380|0)\d{9}$/;


    if (name.length < 2 || name.length > 50) {
        alert("Name must be between 2 and 50 characters.");
        e.preventDefault();
        return;
    }

    if (!emailPattern.test(email)) {
        alert("Invalid email address.");
        e.preventDefault();
        return;
    }

    if (!phonePattern.test(phone)) {
        alert("Invalid phone number.");
        e.preventDefault();
        return;
    }

    if (city.length < 2) {
        alert("City name is too short.");
        e.preventDefault();
        return;
    }

    if (address.length < 5) {
        alert("Address is too short.");
        e.preventDefault();
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        e.preventDefault();
        return;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        e.preventDefault();
        return;
    }
});
</script>
