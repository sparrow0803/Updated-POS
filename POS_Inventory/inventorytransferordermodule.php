<?php
if (!isset($_SESSION)) {
  session_start();
} 

if (isset($_SESSION['UserLogin'])) {
  $username = $_SESSION['UserLogin'];
  $userrole = $_SESSION['UserRole'];
} else {
  header("Location: /pos/login.php");
  exit();
}
include_once("connection/connection.php");
$pdo = connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = isset($_POST['itemName']) ? $_POST['itemName'] : '';
  $category = isset($_POST['itemCategory']) ? $_POST['itemCategory'] : '';
  $brand = isset($_POST['itemBrand']) ? $_POST['itemBrand'] : '';
  $quantity = isset($_POST['itemQuantity']) ? $_POST['itemQuantity'] : '';
  $destination = isset($_POST['itemDestination']) ? $_POST['itemDestination'] : '';
        

  $stmt = $pdo->prepare("SELECT * FROM products WHERE name = :itemName AND category = :category AND brand = :brand");
  $stmt->bindParam(':itemName', $name);
  $stmt->bindParam(':category', $category);
  $stmt->bindParam(':brand', $brand);
  $stmt->execute();
        
  if ($stmt->rowCount() > 0) {
    $sql_update = "UPDATE products SET stocks = stocks - :itemQuantity WHERE name = :itemName AND stocks >= :itemQuantity";
    $stmt = $pdo->prepare($sql_update);
    $stmt->bindParam(':itemQuantity', $quantity);
    $stmt->bindParam(':itemName', $name);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $pdoQuery = "INSERT INTO outbound (items, quantity, destination) VALUES (:items, :quantity, :destination)";
        $pdoResult = $pdo->prepare($pdoQuery);
        $pdoResult->bindParam(':items', $name);
        $pdoResult->bindParam(':quantity', $quantity);
        $pdoResult->bindParam(':destination', $destination);
        $pdoResult->execute();
    } else {
        echo "<script>alert('Quantity requested is greater than available stocks.');</script>";
    }
} else {
    echo "<script>alert('Product not found in the inventory.');</script>";
} 
}
          
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>INVENTORY TRANSFER ORDER</title>
  <link rel="stylesheet" href="styles/inventory.css" />
  <link rel="stylesheet" href="styles/inventorytransfer.css" />
  <link rel="stylesheet" href="styles/inventory_add.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
<nav class="sidebar close">
<header>
<div class="image-text">
  <span class="image">
  <img src="img/logo2.png" alt="logo" />
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
    <?php } ?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <div class="dropdown">
          <a href="inventory.php" class="dropbtn">
              <i class="bx bxs-package icon"></i> 
              <span class="text nav-text"> Inventory </span>
          </a>
          <div class="dropdown-content" id="myDropdown">
              <a class="content" href="../pos_inventory/inventory_purchase_order.php">Purchase Order</a>
              <a class="content" href="../pos_inventory/inventory_add.php">Import Items</a>
              <a class="content" href="../pos_inventory/inventorytransferorder.php">Transfer Orders</a>
              <a class="content" href="../pos_inventory/inventorycounts.php">Inventory Counts</a>
              <a class="content" href="../pos_inventory/inventoryreport.php">Inventory Report</a>
              <a class="content" href="../pos_inventory/inventoryhistory.php">Inventory History</a>
          </div>
      </div>
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
  <?php } ?>

  <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/sales/sales.php">
      <i class="bx bx-trending-up icon"></i> 
      <span class="text nav-text"> Sales </span>
      </a>
    </li>
  <?php } ?>

    <div class="bottom-content">
    <hr style="height: 1px; opacity: 40%; border-width: 0; margin-top: 200px; background-color: #BFEA7C;"/>

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
  <h1><i class="bx bxs-package icon">&nbsp</i> IMPORT ITEMS</h1>
  <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>

<div class="box0">
    <h2>Transfer items</h2>
    <div class="outbox">
<form method="post" action="" id="addItemForm">
        <div>
        <label for="itemName">Name:</label><br>
        <input class="boxx" type="text" id="itemName" name="itemName" required>
        <div>
            <br>
        <label for="itemCategory">Category:</label><br>   
        <textarea class="boxx" id="itemCategory" name="itemCategory" required></textarea>
        <div>
        <label for="itemBrand">Brand:</label><br>   
        <textarea class="boxx" id="itemBrand" name="itemBrand" required></textarea>
        <div>
            <br>
        <label for="itemQuantity">Quantity:</label><br>
        <input class="boxx" type="number" id="itemQuantity" name="itemQuantity" min="1" required>
        <div>
        <label for="itemDestination">Destination:</label><br>
        <input class="boxx" type="text" id="itemDestination" name="itemDestination" required>
        <div>
            <br>
        
        
        </div>
        </div>
        </div>
        </div>
</div>
        <br>
    </div>
    <div class="but">
<button class="buts" type="submit" onclick="document.location='inventorytransferorder.php'">Transfer</button>
<button class="buts" type="button" onclick="document.location='inventorytransferorder.php'">Cancel</button>
</div>
</div>
          </form>

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
//------------------------Dropdown-----------------------------
document.addEventListener("DOMContentLoaded", function(event) {
    var inventoryHistoryBtn = document.querySelector(".dropdown1");
    var dropdownContent1 = document.querySelector(".dropdown1 .dropdown-content1");
    inventoryHistoryBtn.addEventListener("click", function() {
        dropdownContent1.classList.toggle("show");
    });

    window.addEventListener("click", function(event) {
        if (!event.target.closest(".dropdown1")) {
            dropdownContent1.classList.remove("show");
        }
    });
});
//-------------------Dropdown------------------------------
</script>
</body>
</html>
