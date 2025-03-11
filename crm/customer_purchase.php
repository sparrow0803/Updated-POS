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

// Check if the GD extension is enabled
if (extension_loaded('gd')) {
} else {
    echo "GD extension is not enabled.";
}

// Check if the Imagick extension is enabled
if (extension_loaded('imagick')) {
} else {
}

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
  <style>
    .popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .popup.open-popup {
      display: block;
    }

    .popup .btn-pr {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .popup .print-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
    }

    .popup .close-btn {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <nav class="sidebar close" style="overflow: hidden;">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="../img/logo2.png" alt="logo" style="width: 75%; margin-top: -40px;" />
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

    <?php

    $pdoQuery = $pdoConnect->prepare("SELECT * FROM customers WHERE id= :id");
    $pdoQuery->execute(array(':id' => $_GET["id"]));
    $pdoResult = $pdoQuery->fetchAll();

    $name = $pdoResult[0]['name'];
    $email = $pdoResult[0]['email'];
    $phone = $pdoResult[0]['phone'];
    $address = $pdoResult[0]['address'];
    $barcode = $pdoResult[0]['code'];
    ?>
    <style>
      @media print {
        body * {
          visibility: hidden;
        }

        .invoice-container,
        .invoice-container * {
          visibility: visible;
        }
      }
    </style>

    <div class="container-modal">

      <div class="purchase-title">
        <a class="button-back-purchase" href="../crm/customer.php"><i class="fa-solid fa-arrow-left"></i></a>
        <script>
  function openPopup() {
    const popup = document.getElementById("popup");
    popup.classList.add("open-popup");
  }
</script>
        <button type="submit" class="btn-print-two" onclick="openPopup()">Card</button>
      </div>
      <div class="popup" id="popup">
        <div class='btn-pr'>
          <button class="print-btn" id="print">Print</button>
          <button type="button" class="close-btn" onclick="closePopup()">Close</button>
        </div>
        <div class="invoice-container">
          <div class="banana-loc">
            <img class="banana-logo-one" src="../crm/banana.png" style="width: 100%; max-width: 100px" ;>
            <h3>Banana is <br> <span>Yellow</h3>
          </div>
          <div class="details-print">
            <?php echo $name; ?> <br />
            <?php echo $email; ?> <br />
            <?php echo $phone; ?><br />
            <?php echo $address; ?><br />
            <?php
            require 'vendor/autoload.php';

            // This will output the barcode as HTML output to display in the browser
// $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
//echo $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
            $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
            $generator->useGd();
            echo '<img src="data:image/jpg;base64,' . base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128)) . '">';
            ?>
          </div>
        </div>

      </div>
    </div>
    <script src="/html2canvas.js"></script>
    <script src="index.js"></script>

    <?php
    $keyword = $name;
    $keyword = preg_replace("#[^0-9a-z]#i", "", $keyword);

    $query = $pdoConnect2->prepare("SELECT COUNT(*) FROM invoice WHERE customer LIKE '$keyword'");
    $query->execute();
    $count = $query->fetchColumn();

    $query = $pdoConnect2->prepare("SELECT * FROM invoice WHERE customer LIKE '$keyword'");
    $query->execute();
    if ($query->rowCount() > 0) {
      ?>

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
        </tr>
      </thead>
      <tbody>
        <?php

        while ($row = $query->fetch()) {
          $date = $row['date'];
          $time = $row['time'];
          $sum += $row['totalamount'];
          $points = $sum * 0.05;
          ?>
        <tr>
          <td>
            <?php echo $id = $row['temp_invoice_id']; ?>
          </td>
          <td>
            <?php echo $row['customer']; ?>
          </td>
          <td>
            <?php echo $barcode; ?>
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

        ?>
      </tbody>
    </table>
    <table class="content-table">
      <thead>
        <tr>
          <th>Total Spent</th>
          <th>Total Points</th>
          <th>Total Visit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <?php echo $sum ?>
          </td>
          <td>
            <?php echo $points ?>
          </td>
          <td>
            <?php echo $count ?>
            <?php } else {
      echo '<h1>No Purchased Yet</h1>';
    } ?>
          </td>
        </tr>
      </tbody>
    </table>
    

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

    <script>
  const popup = document.getElementById("popup");

  function openPopup() {
    popup.classList.add("open-popup");
  }

  function closePopup() {
    popup.classList.remove("open-popup");
  }
</script>

</body>

</html>
