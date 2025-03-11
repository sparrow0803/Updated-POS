<?php


$output = '';
$sum = 0;



  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'pos';
  


$con2 = new PDO("mysql:host=$host;dbname=pos", 'root', '');
$pdoConnect=new PDO("mysql:host=localhost;dbname=crm","root","");

  $stmt = $con2->prepare("SELECT * from invoice");
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
    $dp_customer = $row['employee'];
    $dp_code = '';
    $dp_totalcost = $row['totalamount'];
    $dp_amountpaid = $row['amountpaid'];
    $dp_change = $row['cash_change'];
    $dp_purchasedate = $row['date']. ' ' .$row['time'];
  $stmt = $pdoConnect->prepare("INSERT into purchase_history (customer, code, totalcost, amountpaid, change_amount, purchase_date)
   values ('$dp_customer', '$dp_code', '$dp_totalcost', '$dp_amountpaid', '$dp_change', '$dp_purchasedate')");
  $stmt->execute();
  }



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
$output = '';
$sum = 0;

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <nav class="sidebar close overflow: scroll;">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="../crm/logo2.png" alt="logo" style="width: 75%; margin-top: -40px;" />
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



    <!----- INPUT CODE HERE :) ----->

    <div class="add-search">
      <div class="add-go-to">
        <a class="add-part" href="add_customer.php">ADD<i class="fa-solid fa-plus"></i></a>
        <a class="purchase-go-to" href="purchase_history.php">Purchase<i class="fa-solid fa-right-to-bracket"></i></a>
      </div>
      <div>
        <form method="POST" action="">
          <input class="box" type="search" name="keyword" placeholder="Search here" />
          <input class="btn-enter" type="submit" name="search" value="enter" />
        </form>
      </div>

    </div>

    <?php


    if (isset($_POST['search'])) {

      $keyword = $_POST['keyword'];
      $keyword = preg_replace("#[^0-9a-z]#i", "", $keyword);

      $query = $pdoConnect->prepare("SELECT * FROM customers WHERE name LIKE '{$keyword}%'");
      $query->execute();
      if ($query->rowCount() > 0) { ?>
    <table class="content-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Contacts</th>
          <th>First Visit</th>
          <th>Last Visit</th>
          <th>Total Visit</th>
          <th>Points Balance</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php

        while ($row = $query->fetch()) {
          $id = $row['id'];
          $code = $row['code'];

          $queryss = $pdoConnect->prepare("SELECT COUNT(*) FROM purchase_history WHERE code LIKE '$code'");
          $queryss->execute();
          $count = $queryss->fetchColumn();
          if (empty($count)) {
            $counts = 0;
          } else {
            $counts = $count;
          }

          $querys = $pdoConnect->prepare("SELECT MAX(purchase_date) FROM purchase_history WHERE code LIKE '$code'");
          $querys->execute();
          $res = $querys->fetchColumn();
          if (empty($res)) {
            $date = '-';
            $time = '-';
          } else {
            $timestamp = strtotime($res);
            $date = date('n.j.Y', $timestamp);
            $time = date('H:i', $timestamp);
          }

          $querys = $pdoConnect->prepare("SELECT MIN(purchase_date) FROM purchase_history WHERE code LIKE '$code'");
          $querys->execute();
          $ros = $querys->fetchColumn();
          if (empty($ros)) {
            $date2 = '-';
            $time2 = '-';
          } else {
            $timestamp2 = strtotime($ros);
            $date2 = date('n.j.Y', $timestamp2);
            $time2 = date('H:i', $timestamp2);
          }
          ?>
        <?php echo "<tr data-href='customer_purchase.php?id=$id'>"; ?>
        <td>
          <?php echo $id; ?>
        </td>
        <td>
          <?php echo $row['name']; ?> <br />
          <?php echo $row['note']; ?>
        </td>
        <td>
          <?php echo $row['email']; ?> <br />
          <?php echo $row['phone']; ?>
        </td>
        <td>
          <?php echo "Date: $date2"; ?> <br />
          <?php echo "Time: $time2" ?>
        </td>
        <td>
          <?php echo "Date: $date"; ?> <br />
          <?php echo "Time: $time" ?>
        </td>
        <td>
          <?php echo $counts; ?>
        </td>
        <td>
          <?php echo $row['points']; ?>
        </td>
        <?php echo "<td><div class='edit-btn'><a href='update_customer.php?id=$id';>Edit</a></div></td>"; ?>
        <?php echo "<td><div class='delete-btn'><a href='delete.php?id=$id';> Delete</a></div></td>"; ?>
        </tr>
        <?php

        }
      } else {
        ?>
        <h1>No Data Found</h1>

        <?php

      }
    } else { ?>
        <table class="content-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Contacts</th>
              <th>First Visit</th>
              <th>Last Visit</th>
              <th>Total Visit</th>
              <th>Points Balance</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = $pdoConnect->prepare("SELECT * FROM customers");
            $query->execute();
            while ($row = $query->fetch()) {
              $id = $row['id'];
              $code = $row['name'];

              $queryss = $pdoConnect->prepare("SELECT COUNT(*) FROM invoice WHERE code LIKE '$code'");
              $queryss->execute();
              $count = $queryss->fetchColumn();
              if (empty($count)) {
                $counts = 0;
              } else {
                $counts = $count;
              }

              $querys = $pdoConnect->prepare("SELECT MAX(purchase_date) FROM invoice WHERE code LIKE '$code'");
              $querys->execute();
              $res = $querys->fetchColumn();
              if (empty($res)) {
                $date = '-';
                $time = '-';
              } else {
                $timestamp = strtotime($res);
                $date = date('n.j.Y', $timestamp);
                $time = date('H:i', $timestamp);
              }

              $querys = $pdoConnect->prepare("SELECT MIN(purchase_date) FROM invoice WHERE code LIKE '$code'");
              $querys->execute();
              $ros = $querys->fetchColumn();
              if (empty($ros)) {
                $date2 = '-';
                $time2 = '-';
              } else {
                $timestamp2 = strtotime($ros);
                $date2 = date('n.j.Y', $timestamp2);
                $time2 = date('H:i', $timestamp2);
              }

              echo "<tr data-href='customer_purchase.php?id=$id'>";
              echo "<td>$id</td>";
              echo "<td>$row[name]<br />$row[note]</td>";
              echo "<td>$row[email]<br />$row[phone]</td>";
              echo "<td>Date: $date2<br />Time: $time2</td>";
              echo "<td>Date: $date<br />Time: $time</td>";
              echo "<td>$counts</td>";
              echo "<td>$row[points]</td>";
              echo "<td><div class='edit-btn'><a href='update_customer.php?id=$id';>Edit</a></div></td>";
              echo "<td><div class='delete-btn'><a href='delete.php?id=$id';>Delete</a></div></td>";
              echo "</tr>";

            }
    }
    ?>
          </tbody>
        </table>

      </tbody>
    </table>
    </br>

    </br>
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



    document.addEventListener("DOMContentLoaded", () => {
      const row = document.querySelectorAll("tr[data-href]");

      row.forEach(row => {
        row.addEventListener("click", () => {
          window.location.href = row.dataset.href;
        })
      })
    })

  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>