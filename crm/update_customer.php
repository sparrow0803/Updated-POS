<?php
if (!isset($_SESSION)) {
  session_start();
} 

if (isset($_SESSION['UserLogin'])) {
  $username = $_SESSION['UserLogin'];
  $userrole = $_SESSION['UserRole'];
} else {
  header("Location: /updated pos/login.php");
  exit();
}
require_once ('connect/dbcon.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CUSTOMER SERVICE</title>
  <link rel="stylesheet" href="styles/customerservices.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
  <nav class="sidebar close" style="overflow: hidden;">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="img/logo5.png" alt="logo" style="width: 75%; margin-top: -40px;" />
        </span>
      </div>
      <i class="bx bx-menu toggle"></i>
    </header>

    <div class="menu-bar">
<div class="menu">

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Cashier" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/pos/cashier.php">
      <i class="bx bx-credit-card icon"></i>
      <span class="text nav-text"> Cashier </span>
      </a>
    </li>
    <?php }?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
      <li class="nav-link">
      <a href="/updated pos/POS_Inventory/inventory.php">
      <i class="bx bxs-package icon"></i> 
      <span class="text nav-text"> Inventory </span>
      </a>
      </li>
     <?php } ?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/hr/employee.php">
      <i class="bx bx bx-group icon"></i>
      <span class="text nav-text"> Human Resources </span>
      </a>
    </li>
    <?php }?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/crm/customer.php">
      <i class="bx bxs-user-voice icon"></i>
      <span class="text nav-text"> Customer Service </span>
      </a>
    </li>
    <?php }?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/sales/sales.php">
      <i class="bx bx-trending-up icon"></i> 
      <span class="text nav-text"> Sales </span>
      </a>
    </li>
    <?php }?>

    <div class="bottom-content">
    <hr style="height: 1px; opacity: 40%; border-width: 0; background-color: #BFEA7C;"/>

    <li class="nav-link">
      <a href="/updated pos/logout.php">
      <i class="bx bx-log-out icon"></i>
      <span class="text nav-text"> Logout </span>
      </a>
    </li>
    </div>
    </div>

</div>
  </nav>

  <section class="home" style="position: fixed;">
    <div class="userheader">
      <h1><i class="bx bxs-user-voice icon">&nbsp</i> CUSTOMER SERVICE </h1>
  <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
    </div>

    <?php
    

if (!empty($_POST["modify"])) {
  $Name = htmlspecialchars($_POST['name']);
  $Phone = htmlspecialchars($_POST['phone']);
  $Email = htmlspecialchars($_POST['email']);
  $Address = htmlspecialchars($_POST['address']);
  $Note = htmlspecialchars($_POST['note']);
  $add = $pdoConnect->prepare("SELECT * FROM customers WHERE email = ?");
  $add->execute([$Email]);

  if ($add->rowCount() > 0) {
    echo '<h1>Customer Email Already Taken</h1>';
  } else {

  $pdoQuery = $pdoConnect->prepare("UPDATE customers SET name = :Name, phone = :Phone, email = :Email, address = :Address, note = :Note WHERE id =:id");
  $pdoResult = $pdoQuery->execute(
    array(
      ':Name' => $Name,
      ':Phone' => $Phone,
      ':Email' => $Email,
      ':Address' => $Address,
      ':Note' => $Note,
      ':id' => $_GET["id"]
    )
  );
  header('location:customer.php');
}
 }
$pdoQuery = $pdoConnect->prepare("SELECT * FROM customers WHERE id= :id");
$pdoQuery->execute(array(':id' => $_GET["id"]));
$pdoResult = $pdoQuery->fetchAll();
$pdoConnect = null;

    ?>



    <!----- INPUT CODE HERE :) ----->
    <div class="container-add-item">
      <section class="add_item" id="add_item">
        <div class="update-title">
          <a class="button-back-update" href="customer.php"><i class="fa-solid fa-arrow-left"></i></a>
          <h2>Update Customer</h2>
        </div>
        <form action="update_customer.php?id=<?php echo $_GET["id"]; ?>" method="post">
          <div class="user-details">
            <div class="form-group">
              <label class="details" for="name">Name:</label>
              <input placeholder="Enter Name" type="text" name="name" value="<?php echo $pdoResult[0]['name']; ?>"
                id="name1" required/>
            </div>
            <div class="form-group">
              <label class="details" for="phone">Phone:</label>
              <input placeholder="Enter Phone" type="text" name="phone" value="<?php echo $pdoResult[0]['phone']; ?>"
                id="phone1" required/>
            </div>
            <div class="form-group">
              <label class="details" for="email">Email:</label>
              <input placeholder="Enter Email" type="text" name="email" value="<?php echo $pdoResult[0]['email']; ?>"
                id="email1" required/>
            </div>
            <div class="form-group">
              <label class="details" for="address">Address:</label>
              <input placeholder="Enter Addres" type="text" name="address"
                value="<?php echo $pdoResult[0]['address']; ?>" id="address1" required/>
            </div>
            <div class="form-group">
              <label for="note">Note:</label>
              <input placeholder="Type notes" type="text" name="note" value="<?php echo $pdoResult[0]['note']; ?>"
                id="note1" >
            </div>
            <input class="button-item" type="submit" name="modify" value="Submit">
          </div>
    </div>
    </form>
  </section>

  <script>
    const body = document.querySelector("body"),
      sidebar = body.querySelector(".sidebar"),
      toggle = body.querySelector(".toggle");
    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>

</html>