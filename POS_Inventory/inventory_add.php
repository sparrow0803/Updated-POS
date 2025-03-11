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
  $name = $_POST['itemName'];
  $category = $_POST['itemCategory'];
  $brand = $_POST['itemBrand'];
  $quantity = $_POST['itemQuantity'];
  $price = $_POST['itemPrice'];
  $barcode = $_POST['itemBarcode'];

  $stmt = $pdo->prepare("SELECT * FROM products WHERE name = :itemName AND category = :category AND brand = :brand AND barcode = :barcode");
  $stmt->bindParam(':itemName', $name);
  $stmt->bindParam(':category', $category);
  $stmt->bindParam(':brand', $brand);
  $stmt->bindParam(':barcode', $barcode);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $sql_update = "UPDATE products SET stocks = stocks + :itemQuantity WHERE name = :itemName AND category = :category AND brand = :brand AND barcode = :barcode";
    $stmt = $pdo->prepare($sql_update);
    $stmt->bindParam(':itemQuantity', $quantity);
    $stmt->bindParam(':itemName', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':barcode', $barcode);
    $stmt->execute();
    
  } else {
    $sql_insert = "INSERT INTO products (name, category, brand, stocks, price, barcode) VALUES (:itemName, :itemCategory, :itemBrand, :itemQuantity, :itemPrice, :itemBarcode)";
    $stmt = $pdo->prepare($sql_insert);
    $stmt->bindParam(':itemName', $name);
    $stmt->bindParam(':itemCategory', $category);
    $stmt->bindParam(':itemBrand', $brand);
    $stmt->bindParam(':itemQuantity', $quantity);
    $stmt->bindParam(':itemPrice', $price);
    $stmt->bindParam(':itemBarcode', $barcode);
    $stmt->execute();
  }

  $pdoQuery = "INSERT INTO inbound (items, quantity) VALUES (:items, :quantity)";
  $pdoResult = $pdo->prepare($pdoQuery);
  $pdoResult->bindParam(':items', $name);
  $pdoResult->bindParam(':quantity', $quantity);
  $pdoResult->execute();        
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IMPORT ITEMS</title>
  <link rel="stylesheet" href="styles/inventoryhistory.css" />
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
    <h2>Add items</h2>
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
            <br>
        <label for="itemQuantity">Price:</label><br>
        <input class="boxx" type="text" id="itemPrice" name="itemPrice" min="1" required>
            <br>
        <label for="itemQuantity">Barcode:</label><br>
        <input class="boxx" type="number" id="itemBarcode" name="itemBarcode" min="1" required>
        </div>
        </div>
        </div>
        </div>
</div>
        <br>
    </div>
    <div class="but">
<button class="buts" type="submit" onclick="document.location='inventory.php'">Add Items</button>
<button class="buts" type="button" onclick="document.location='inventory.php'">Cancel</button>
</div>
</div>

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
