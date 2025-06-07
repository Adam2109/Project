<?php include('layouts/header.php'); ?>

<?php

include('server/connection.php');

if(!isset($_SESSION['logged_in'])){

  header('location: login.php');

  exit;

}



if(isset($_GET['logout'])){

  if(isset($_SESSION['logged_in'])){

    unset($_SESSION['logged_in']);

    unset($_SESSION['user_email']);

    unset($_SESSION['user_name']);

    header('location: login.php');

    exit;

  }

}



// Update name and email

if (isset($_POST['edit_profile'])) {

  $new_name = $_POST['new_name'];

  $new_email = $_POST['new_email'];

  $user_id = $_SESSION['user_id'];



  $stmt = $conn->prepare("UPDATE users SET user_name=?, user_email=? WHERE user_id=?");

  $stmt->bind_param('ssi', $new_name, $new_email, $user_id);



  if ($stmt->execute()) {

    $_SESSION['user_name'] = $new_name;

    $_SESSION['user_email'] = $new_email;

    header('Location: account.php?message=Profile updated successfully');

    exit;

  } else {

    header('Location: account.php?error=Unable to update profile');

    exit;

  }

}

if (isset($_POST['edit_phone'])) {
  $new_phone = $_POST['new_phone'];
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("UPDATE users SET user_phone=? WHERE user_id=?");
  $stmt->bind_param('si', $new_phone, $user_id);
  if ($stmt->execute()) {
    $_SESSION['user_phone'] = $new_phone;
    header('Location: account.php?message=Phone updated successfully');
    exit;
  }
}

if (isset($_POST['edit_city'])) {
  $new_city = $_POST['new_city'];
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("UPDATE users SET user_city=? WHERE user_id=?");
  $stmt->bind_param('si', $new_city, $user_id);
  if ($stmt->execute()) {
    $_SESSION['user_city'] = $new_city;
    header('Location: account.php?message=City updated successfully');
    exit;
  }
}

if (isset($_POST['edit_address'])) {
  $new_address = $_POST['new_address'];
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("UPDATE users SET user_address=? WHERE user_id=?");
  $stmt->bind_param('si', $new_address, $user_id);
  if ($stmt->execute()) {
    $_SESSION['user_address'] = $new_address;
    header('Location: account.php?message=Address updated successfully');
    exit;
  }
}


// Change password

if(isset($_POST['change_password'])){

  $password = $_POST['password'];

  $confirmPassword = $_POST['confirmPassword']; 

  $user_email = $_SESSION['user_email'];



  if($password !== $confirmPassword){

    header('location: account.php?error=passwords dont match');

  } else if(strlen($password) <6){

    header('location: account.php?error=passwords must be at least 6 charachters');

  } else {

    $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");

    $stmt->bind_param('ss',md5($password),$user_email);



    if($stmt->execute()){

      header('location: account.php?message=password has been updated successfully');

    } else {

      header('location: account.php?error=could not update password');

    }

  }

}



// Get orders

if(isset($_SESSION['logged_in'])){

  $user_id = $_SESSION['user_id'];
   
    // Якщо деякі поля ще не збережені у сесію — підтягуємо їх з БД
    if (!isset($_SESSION['user_phone']) || !isset($_SESSION['user_city']) || !isset($_SESSION['user_address'])) {
        $stmt = $conn->prepare("SELECT user_phone, user_city, user_address FROM users WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($user_phone, $user_city, $user_address);
        if ($stmt->fetch()) {
            $_SESSION['user_phone'] = $user_phone;
            $_SESSION['user_city'] = $user_city;
            $_SESSION['user_address'] = $user_address;
        }
        $stmt->close();
    }
  $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=?");

  $stmt->bind_param('i',$user_id);

  $stmt->execute();

  $orders = $stmt->get_result();

}

?>



<section class="my-5 py-5">

  <div class="row container mx-auto">

    <?php if(isset($_GET['payment_message'])){ ?>

      <p class="mt-5 text-center" style="color:green"><?php echo $_GET['payment_message']; ?></p>

    <?php } ?>



    <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">

      <p class="text-center" style="color: green"><?php if(isset($_GET['register_success'])){ echo $_GET['register_success']; }?></p>

      <p class="text-center" style="color: green"><?php if(isset($_GET['login_success'])){ echo $_GET['login_success']; }?></p>

      <p class="text-center" style="color: red"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>

      <p class="text-center" style="color: green"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>



      <h3 class="font-weight-bold">Account info</h3>

      <hr class="mx-auto">



      <div class="account-info">



        <!-- NAME -->

        <div class="editable-group">

          <label>Name:</label>

          <span id="name-display" class="editable-field"><?php echo $_SESSION['user_name']; ?></span>

          <span class="edit-icon" onclick="editField('name')">✏️</span>

        </div>

        <form id="name-form" class="edit-form" method="POST" action="account.php">

          <input type="text" name="new_name" value="<?php echo $_SESSION['user_name']; ?>" required class="form-control my-2">

          <input type="hidden" name="new_email" value="<?php echo $_SESSION['user_email']; ?>">

          <button type="submit" name="edit_profile" class="btn btn-sm btn-primary">Save Name</button>

        </form>



        <!-- EMAIL -->

        <div class="editable-group">

          <label>Email:</label>

          <span id="email-display" class="editable-field"><?php echo $_SESSION['user_email']; ?></span>

          <span class="edit-icon" onclick="editField('email')">✏️</span>

        </div>

        <form id="email-form" class="edit-form" method="POST" action="account.php">

          <input type="email" name="new_email" value="<?php echo $_SESSION['user_email']; ?>" required class="form-control my-2">

          <input type="hidden" name="new_name" value="<?php echo $_SESSION['user_name']; ?>">

          <button type="submit" name="edit_profile" class="btn btn-sm btn-primary">Save Email</button>

        </form>

        <!-- PHONE -->
        <div class="editable-group">
          <label>Phone:</label>
          <span id="phone-display" class="editable-field"><?php echo isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';
 ?></span>
          <span class="edit-icon" onclick="editField('phone')">✏️</span>
        </div>
        <form id="phone-form" class="edit-form" method="POST" action="account.php">
          <input type="text" name="new_phone" value="<?php echo isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '';
 ?>" required class="form-control my-2">
          <button type="submit" name="edit_phone" class="btn btn-sm btn-primary">Save Phone</button>
        </form>

        <!-- CITY -->
        <div class="editable-group">
          <label>City:</label>
          <span id="city-display" class="editable-field"><?php echo $_SESSION['user_city']; ?></span>
          <span class="edit-icon" onclick="editField('city')">✏️</span>
        </div>
        <form id="city-form" class="edit-form" method="POST" action="account.php">
          <input type="text" name="new_city" value="<?php echo $_SESSION['user_city']; ?>" required class="form-control my-2">
          <button type="submit" name="edit_city" class="btn btn-sm btn-primary">Save City</button>
        </form>

        <!-- ADDRESS -->
        <div class="editable-group">
          <label>Address:</label>
          <span id="address-display" class="editable-field"><?php echo $_SESSION['user_address']; ?></span>
          <span class="edit-icon" onclick="editField('address')">✏️</span>
        </div>
        <form id="address-form" class="edit-form" method="POST" action="account.php">
          <input type="text" name="new_address" value="<?php echo $_SESSION['user_address']; ?>" required class="form-control my-2">
          <button type="submit" name="edit_address" class="btn btn-sm btn-primary">Save Address</button>
        </form>


        <p><a href="#orders" id="orders-btn">Your orders</a></p>

        <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>

      </div>

    </div>



    <div class="col-lg-6 col-md-12 col-sm-12">
  <form id="account-form" method="POST" action="account.php">
    <p class="text-center" style="color: red"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
    <p class="text-center" style="color: green"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
    <h3>Change Password</h3>
    <hr class="mx-auto">
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
    </div>
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Password" required/>
    </div>
    <div class="form-group">
      <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn"/>
    </div>
  </form>
</div>


  </div>

</section>



<section id="orders" class="orders container my-5 py-3">

  <div class="container mt-2">

    <h2 class="font-weight-bolde text-center">Your Orders</h2>    

    <hr class="mx-auto">

  </div>

  <table class="mt-5 pt-5">

    <tr>

      <th>Order id</th>

      <th>Order cost</th>

      <th>Order status</th>

      <th>Order date</th>

      <th>Order details</th>

    </tr>

    <?php while($row = $orders->fetch_assoc()){?>

    <tr>

      <td><span><?php echo $row['order_id']; ?></span></td>

      <td><span><?php echo $row['order_cost']; ?></span></td>

      <td><span><?php echo $row['order_status']; ?></span></td>

      <td><span><?php echo $row['order_date']; ?></span></td>

      <td>

        <form method="POST" action="order_details.php">

          <input type="hidden" value="<?php echo $row['order_status'];?>" name="order_status"/>

          <input type="hidden" value="<?php echo $row['order_id'];?>" name="order_id"/>

          <input class="btn order-details-btn" name="order_details_btn" type="submit" value="details"/>

        </form>

      </td>

    </tr>  

    <?php } ?>           

  </table>

</section>



<!-- JS: Toggle edit forms -->

<script>

function editField(field) {

  if (field === 'name') {

    document.getElementById('name-form').style.display = 'block';

    document.getElementById('name-display').style.display = 'none';

    document.getElementById('email-form').style.display = 'none';

    document.getElementById('email-display').style.display = 'inline';

  } else if (field === 'email') {

    document.getElementById('email-form').style.display = 'block';

    document.getElementById('email-display').style.display = 'none';

    document.getElementById('name-form').style.display = 'none';

    document.getElementById('name-display').style.display = 'inline';

  }

}

</script>

<script>
function editField(field) {
  const fields = ['name', 'email', 'phone', 'city', 'address'];
  fields.forEach(f => {
    document.getElementById(f + '-form').style.display = (f === field) ? 'block' : 'none';
    document.getElementById(f + '-display').style.display = (f === field) ? 'none' : 'inline';
  });
}
</script>


<!-- CSS styles for editing (додай у style.css або тут у <style>) -->

<style>

.editable-group {

  margin-bottom: 10px;

  position: relative;

}

.edit-icon {

  cursor: pointer;

  margin-left: 10px;

  color: gray;

}

.edit-icon:hover {

  color: black;

}

.edit-form {

  display: none;

}

</style>



<?php include('layouts/footer.php'); ?>