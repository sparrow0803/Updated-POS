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

$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM products WHERE name LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$search%");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>INVENTORY COUNTS</title>
  <link rel="stylesheet" href="styles/inventory.css" />
  <link rel="stylesheet" href="styles/inventorycounts.css" />
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
  <h1><i class="bx bxs-package icon">&nbsp</i> INVENTORY COUNTS</h1>
  <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>
  
  <!-----------------Search------------------------>
  <div class="search-container">
    <form method="GET" action="">
      <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo $search; ?>">
      <button type="submit" class="search-button">Search</button>
    </form>
  </div>

  <div class="table1">
    <div class="tabbox">
    <table>
      <tr>
        <th>Id</th>
        <th>Item</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Barcode</th>
        <th class="th4">Action</th>
      </tr>
      <?php foreach ($items as $row){ ?>
        <tr>
          <td><?= $row['product_Id'];?></td>
          <td><?= $row['name'];?></td>
          <td><?= $row['category'];?></td>
          <td><?= $row['brand'];?></td>
          <td><?= $row['stocks'];?></td>
          <td><?= $row['price'];?></td>
          <td><?= $row['barcode'];?></td>
          <td width="30"><a href="editinventorycounts.php?ID=<?= $row['product_Id'];?>" class="button-small">edit</a></td>
        </tr>
      <?php }?>
    </table>
      </div>
  </div>
 <!-----------------Search------------------------>

    
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
