<?php 
include_once("connection/connection.php");

$product_Id = $_GET['ID'];
$pdo = connection();

$sql = "SELECT * FROM products WHERE product_Id = :product_Id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':product_Id', $product_Id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $stocks = $_POST['stocks'];
    $price = $_POST['price'];
    $barcode = $_POST['barcode'];

    $sql = "UPDATE products SET name = :name, category = :category, brand = :brand, stocks = :stocks, price = :price, barcode = :barcode
            WHERE product_Id = :product_Id"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':stocks', $stocks);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':barcode', $barcode);
    $stmt->bindParam(':product_Id', $product_Id); 

    if ($stmt->execute()) {
        header("Location: inventorycounts.php?ID=$product_id");
        exit;
    } else {
        echo "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>INVENTORY COUNTS</title>
  <link rel="stylesheet" href="styles/inventory.css" />
  <link rel="stylesheet" href="styles/inventoryhistory.css" />
  <link rel="stylesheet" href="styles/editinventorycounts.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
<nav class="sidebar close" style="overflow: hidden;">
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
  <h1><i class="bx bxs-package icon">&nbsp</i> EDIT ITEM </h1>
  <h2><i class="bx bxs-user usericon">&nbsp</i> Super Admin</h2>
  </div>
  
  
  <form action="editinventorycounts.php?ID=<?php echo $product_Id; ?>" method="post">
  <div class="edit-items">
    <h1 class="head">ITEM</h1>

    <h3>ITEM NAME</h3>
    <input class="search-input" type="text" name="name" id="name" value="<?php echo $row['name'];?>" placeholder="" style="font-size: 25px;">

    <h3>CATEGORY</h3>
    <input class="search-input" type="text" name="category" id="category" value="<?php echo $row['category'];?>" placeholder="" style="font-size: 25px;">

    <h3>BRAND</h3>
    <input class="search-input" type="text" name="brand" id="brand" value="<?php echo $row['brand'];?>" placeholder="" style="font-size: 25px;">
    
    <h3>QUANTITY</h3>
    <input class="search-input" type="number" name="stocks" id="stocks" value="<?php echo $row['stocks'];?>" placeholder="" style="font-size: 25px;">
    
    <h3>PRICE</h3>
    <input class="search-input" type="number" name="price" id="price" value="<?php echo $row['price'];?>" placeholder="" style="font-size: 25px;">

    <h3>BARCODE</h3>
    <input class="search-input" type="number" name="barcode" id="barcode" value="<?php echo $row['barcode'];?>" placeholder="" style="font-size: 25px;"><br>

    <input class="submit-btn" type="submit" name="submit" value="Update">
  </div>
</form>

<div class="bckbtn">
    <a href="inventorycounts.php"><button class="bck">CANCEL</button></a>
</div>
    

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

//--------------------------------Search javascript-----------------
const searchInput = document.querySelector('.search-input');
      searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const searchResults = document.querySelectorAll('.search-results li');
      searchResults.forEach(function(result) {
       
        const text = result.textContent.toLowerCase();
        if (text.includes(query)) {
        
          result.style.display = 'block';
        } else {
       
          result.style.display = 'none';
        }
      });
    });
//--------------------------------Search javascript-----------------
</script>
</body>
</html>
