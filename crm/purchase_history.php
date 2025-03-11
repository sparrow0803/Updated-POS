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
  <link rel="stylesheet" href="styles/purchase_history.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
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



    <!----- INPUT CODE HERE :) ----->

    <div>
      <form method="POST" action="">
        <div class="history-title">
          <a class="button-back-history" href="customer.php"><i class="fa-solid fa-arrow-left"></i></a>
          <div class="search-enter-purchase">
            <input class="search-purchase" placeholder="Search here">
            <input class="purchase-enter" type="submit" value="enter" />
          </div>
        </div>
      </form>

    </div>



    <?php

    $output = '';
    $sum = 0;
    if (isset($_POST['search2'])) {

      ?>

    <?php
    $keyword = $_POST['keyword2'];
    $keyword = preg_replace("#[^0-9a-z]#i", "", $keyword);

    $query = $pdoConnect2->prepare("SELECT * FROM invoice WHERE customer LIKE '{$keyword}%' OR date LIKE '{$keyword}%'");
    $query->execute();
    if ($query->rowCount() > 0) { ?>
    <table class="content-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Total Cost</th>
          <th>Date</th>
          <th>Time</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php

        while ($row = $query->fetch()) {
          $date = $row['date'];
          $time = $row['time'];
          ?>
        <tr id="CustomerTable">
          <td>
            <?php echo $id = $row['temp_invoice_id']; ?>
          </td>
          <td>
            <?php echo $row['customer']; ?>
          </td>
          <td>
            <?php echo $row['totalamount']; ?>
          </td>
          <td>
            <?php echo $date; ?>
          </td>
          <td>
            <?php echo $time; ?>
          </td>
          <?php echo "<td><div class='delete-btn'><a href='purchase_delete.php?id=$id';> Delete</a></div></td>"; ?>
        </tr>
        <?php

        }
    } else {
      ?>
        <table class="content-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Total Cost</th>
              <th>Date</th>
              <th>Time</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = $pdoConnect2->prepare("SELECT * FROM invoice");
            $query->execute();
            while ($row = $query->fetch()) {
              $id = $row['temp_invoice_id'];
              $date = $row['date'];
              $time = $row['time'];
              ?>
            <tr id="CustomerTable">
              <td>
                <?php echo $id ?>
              </td>
              <td>
                <?php echo $row['customer']; ?>
              </td>
              <td>
                <?php echo $row['totalamount']; ?>
              </td>
              <td>
                <?php echo $date; ?>
              </td>
              <td>
                <?php echo $time; ?>
              </td>
              <?php echo "<td><div class='delete-btn'><a href='purchase_delete.php?id=$id';> Delete</a></div></td>"; ?>
            </tr>
            <?php
            }
    }
    } else { ?>
            <table class="content-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Customer</th>
                  <th>Code</th>
                  <th>Total Cost</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Delete</th>
              </thead>
              <tbody>
                <?php
                $query = $pdoConnect2->prepare("SELECT * FROM invoice");
                $query->execute();
                while ($row = $query->fetch()) {
                  $id = $row['temp_invoice_id'];
                  $date = $row['date'];
                  $time = $row['time'];
                  ?>
                <tr id="CustomerTable">
                  <td>
                    <?php echo $id ?>
                  </td>
                  <td>
                    <?php echo $row['customer']; ?>
                  </td>
                  <td>
                    <?php echo $row['totalamount']; ?>
                  </td>
                  <td>
                    <?php echo $date; ?>
                  </td>
                  <td>
                    <?php echo $time; ?>
                  </td>
                  <?php echo "<td><div class='delete-btn'><a href='purchase_delete.php?id=$id';> Delete</a></div> </td>"; ?>
                </tr>
                <?php
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


  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>