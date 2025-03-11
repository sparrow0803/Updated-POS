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

try{
  $pdo = new PDO('mysql:host=localhost;dbname=pos', "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
  echo 'Connection Failed' .$e->getMessage();
  }

  try{
    $pdo2 = new PDO('mysql:host=localhost;dbname=hr', "root", "");
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    echo 'Connection Failed' .$e->getMessage();
    }

  date_default_timezone_set("Asia/Taipei");

  if(isset($_POST['start']))
  {

    $stmt = $pdo->prepare("INSERT into shift(employee, start, start_amount) 
    values (:employee, NOW(), :start_amount)");
    $stmt->bindParam(':employee', $_SESSION['employee']);
    $stmt->bindParam(':start_amount', $_POST['start_amount']);
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * from shift order by id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach($result as $row){
    $id = $row['id'];
    }
    

    $_SESSION['id'] = $id;
    header('location:cashier.php');
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CASHIER</title>
  <link rel="stylesheet" href="styles/cashier.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
<nav class="sidebar close">
<header>
<div class="image-text">
  <span class="image">
  <img src="img/logo5.png" alt="logo" />
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

<section class="home">
  <div class="userheader">
  <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>

  <!-- START MODAL -->
<div class="modal fade" id="startmodal" tabindex="-1" aria-labelledby="startmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="startmodalLabel">Start Shift</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="open.php" method="POST">
      <div class="modal-body">

        <div class="form-group mb-3">
          <label>Specify the cash amount in the drawer</label>
          <input type="number" name="start_amount" id="start_amount" min="1" class="form-control" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="start" class="btn btn-warning">Start</button>
      </div>
      </form>

    </div>
  </div>
</div>


          
<!----- INPUT CODE HERE :) ----->
<div class="p-3">



  <div class="container text-center">
        <br><br><br><br><br><br><br><br><br>
        <p class="fs-2 text-center">
        <button type="button" class="btn btn-warning startbtn btn-lg" data-bs-target="#startmodal">Start Shift</button>

        <?php
              if (isset($_SESSION['invalid'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
              .$_SESSION['invalid'].
              '</div>'; }
              unset($_SESSION['invalid']);
              ?>
        </p>
    </div>

</div>
</section>


<!----- SCRIPT ----->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script>
  $(document).ready(function () {
    $('.startbtn').on('click', function() {
      $('#startmodal').modal('show');

    });
  });
</script>

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
</script>

</body>
</html>