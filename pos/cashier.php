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

if (!isset($_SESSION['id'])){
  header('location:open.php');
}

$shift_id = $_SESSION['id'];
$employee = $_SESSION['employee'];

try{
  $pdo = new PDO('mysql:host=localhost;dbname=pos', "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
  echo 'Connection Failed' .$e->getMessage();
  }

  try{
    $pdo2 = new PDO('mysql:host=localhost;dbname=crm', "root", "");
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    echo 'Connection Failed' .$e->getMessage();
    }

    try{
      $pdo3 = new PDO('mysql:host=localhost;dbname=inventory_pos', "root", "");
      $pdo3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
      echo 'Connection Failed' .$e->getMessage();
      }

  date_default_timezone_set("Asia/Taipei");

  $barcode = '';

  if(isset($_POST['add']))
    {
      $product = $_POST['product'];
      $quantity = $_POST['quantity'];

      $stmt = $pdo->prepare("SELECT * from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      if($stmt->rowCount() == 0){
      $stmt = $pdo3->prepare("SELECT * from products where name=:product");
      $stmt->bindParam(':product', $product);
      $stmt->execute();
      if($stmt->rowCount() > 0){
          $result = $stmt->fetchAll();
          foreach($result as $row){
            $category = $row['category'];
            $brand = $row['brand'];
            $price = $row['price'];
            $stocks = $row['stocks'];
            $discount = 0;
            $barcodediscount = 'None';
            $total = $row['price'] * $quantity;
          }
      $stmt = $pdo->prepare("INSERT into temp_add(employee, product, category, brand, price, stocks, quantity, total, barcodediscount, discount) 
      values (:employee, :product, :category, :brand, :price, :stocks, :quantity, :total, :barcodediscount, :discount)");
      $stmt->bindParam(':product', $product);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':category', $category);
      $stmt->bindParam(':brand', $brand);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':stocks', $stocks);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':discount', $discount);
      $stmt->bindParam(':barcodediscount', $barcodediscount);
      $stmt->bindParam(':total', $total);
      $stmt->execute();
      }
      else{
          $_SESSION['invalid1'] = "Invalid";
      }
      }
      else{
      $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();

      $stmt = $pdo3->prepare("SELECT * from products where name=:product");
      $stmt->bindParam(':product', $product);
      $stmt->execute();
      if($stmt->rowCount() > 0){
          $result = $stmt->fetchAll();
          foreach($result as $row){
            $category = $row['category'];
            $brand = $row['brand'];
            $price = $row['price'];
            $stocks = $row['stocks'];
            $discount = 0;
            $barcodediscount = 'None';
            $total = $row['price'] * $quantity;
          }
      $stmt = $pdo->prepare("INSERT into temp_add(employee, product, category, brand, price, stocks, quantity, total, barcodediscount, discount) 
      values (:employee, :product, :category, :brand, :price, :stocks, :quantity, :total, :barcodediscount, :discount)");
      $stmt->bindParam(':product', $product);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':category', $category);
      $stmt->bindParam(':brand', $brand);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':stocks', $stocks);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':discount', $discount);
      $stmt->bindParam(':barcodediscount', $barcodediscount);
      $stmt->bindParam(':total', $total);
      $stmt->execute();
      }
      else{
          $_SESSION['invalid1'] = "Invalid";
      }

      }
    }


    if(isset($_POST['barsub']))
    {
      $barcode = $_POST['barcode'];
      $quantity = 1;

      $stmt = $pdo->prepare("SELECT * from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      if($stmt->rowCount() == 0){
        $stmt = $pdo3->prepare("SELECT * from products where barcode=:barcode");
        $stmt->bindParam(':barcode', $barcode);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            foreach($result as $row){
              $product = $row['name'];
              $category = $row['category'];
              $brand = $row['brand'];
              $price = $row['price'];
              $stocks = $row['stocks'];
              $discount = 0;
              $barcodediscount = 'None';
              $total = $row['price'] * $quantity;
            }
        $stmt = $pdo->prepare("INSERT into temp_add(employee, product, category, brand, price, stocks, quantity, total, barcodediscount, discount) 
        values (:employee, :product, :category, :brand, :price, :stocks, :quantity, :total, :barcodediscount, :discount)");
        $stmt->bindParam(':product', $product);
        $stmt->bindParam(':employee', $employee);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stocks', $stocks);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':discount', $discount);
        $stmt->bindParam(':barcodediscount', $barcodediscount);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        }
        else{
            $_SESSION['invalid1'] = "Invalid";
            $barcode = '';
        }
        }
        else{
        $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
        $stmt->bindParam(':employee', $employee);
        $stmt->execute();
  
        $stmt = $pdo3->prepare("SELECT * from products where barcode=:barcode");
        $stmt->bindParam(':barcode', $barcode);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            foreach($result as $row){
              $product = $row['name'];
              $category = $row['category'];
              $brand = $row['brand'];
              $price = $row['price'];
              $stocks = $row['stocks'];
              $discount = 0;
              $barcodediscount = 'None';
              $total = $row['price'] * $quantity;
            }
        $stmt = $pdo->prepare("INSERT into temp_add(employee, product, category, brand, price, stocks, quantity, total, barcodediscount, discount) 
        values (:employee, :product, :category, :brand, :price, :stocks, :quantity, :total, :barcodediscount, :discount)");
        $stmt->bindParam(':product', $product);
        $stmt->bindParam(':employee', $employee);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stocks', $stocks);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':discount', $discount);
        $stmt->bindParam(':barcodediscount', $barcodediscount);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        }
        else{
            $_SESSION['invalid1'] = "Invalid";
            $barcode = '';
        }
  
        }
    }

    if(isset($_POST['addticket']))
    {

      if($_POST['stocks'] <= 0){
        $_SESSION['invalid1'] = "Out Of Stock!";
      }

      elseif($_POST['stocks'] < $_POST['quantity']){
        $_SESSION['invalid1'] = "Not Enough Stock!";
      }

      else{
      
      if($_POST['discount'] > $_POST['total']){
      $discount = $_POST['total'];
      }
      else{
      $discount = $_POST['discount'];
      }

      $product = $_POST['product'];
      $quantity = $_POST['quantity'];
      $price = $_POST['total'];
      $barcodediscount = $_POST['barcodediscount'];

      $stmt = $pdo->prepare("INSERT into temp_ticket(employee, product, quantity, price, discount) values (:employee, :product, :quantity, :price, :discount)");
      $stmt->bindParam(':product', $product);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':discount', $discount);
      $stmt->execute();

      if ($barcodediscount != 'None') {
      $stmt = $pdo2->prepare("SELECT * from customers where code=:barcodediscount");
      $stmt->bindParam(':barcodediscount', $barcodediscount);
      $stmt->execute();
      if($stmt->rowCount() > 0){
          $result = $stmt->fetchAll();
          foreach($result as $row){
            $points = $row['points'];
          }

          if ($discount == $price){
            $newpoints = $points - $discount;
          }

          else {
            $newpoints = 0;
          }
          
        }

        $stmt = $pdo2->prepare("UPDATE customers set points=:newpoints where code=:barcodediscount");
        $stmt->bindParam(':newpoints', $newpoints);
        $stmt->bindParam(':barcodediscount', $barcodediscount);
        $stmt->execute();

      }

      $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
    }
  }

    if(isset($_POST['deleteorder']))
    {
      $id = $_POST['id'];

      $stmt = $pdo->prepare("DELETE from temp_ticket where temp_ticket_id=:id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    }

    if(isset($_POST['adddiscount']))
    {
      $barcode2 = $_POST['barcode2'];

      $stmt = $pdo2->prepare("SELECT * from customers where code=:barcode2");
      $stmt->bindParam(':barcode2', $barcode2);
      $stmt->execute();
      if($stmt->rowCount() > 0){
          $result = $stmt->fetchAll();
          foreach($result as $row){
            $discount = $row['points'];
          }
      $stmt = $pdo->prepare("SELECT * from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      if($stmt->rowCount() > 0){
      $stmt = $pdo->prepare("UPDATE temp_add set discount=:discount, barcodediscount=:barcode2 where employee=:employee");
      $stmt->bindParam(':discount', $discount);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':barcode2', $barcode2);
      $stmt->execute();
      }
    else{
      $_SESSION['invalid2'] = "Invalid";
    }
    }

      else{
          $_SESSION['invalid2'] = "Invalid";
      }
    }
    

    if(isset($_POST['removediscount']))
    {
      $stmt = $pdo->prepare("UPDATE temp_add set discount=0.00, barcodediscount='None' where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
    }

    if(isset($_POST['clear2']))
    {
      $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
    }

    if(isset($_POST['checkout']))
    {

      $unique_id = $_POST['invoice'];
      $invoice_id = 'INV' .$unique_id;
      $customer = $_POST['customer'];
      $subtotal = $_SESSION['subtotal'];
	    $discount = $_SESSION['discountamount'];
      $vat_amount = $subtotal * .05;
      $totalamount = $_SESSION['totalamount'];
      $amountpaid = $_POST['amountpaid'];
      $mop = $_POST['mop'];
      $cash_change = $amountpaid - $totalamount;
      $date = date("Y-m-d");
	    $time = date("H:i:s");

      $stmt = $pdo->prepare("SELECT * from temp_ticket");
      $stmt->execute();
      if($stmt->rowCount() == 0){
        $_SESSION['invalid5'] = "No Orders Yet!";
      }

      elseif($totalamount > $amountpaid){
        $_SESSION['invalid4'] = "Not Enough Cash!";
      }

      else{
      $stmt = $pdo->prepare("INSERT into invoice (unique_id, invoice_id, employee, customer, subtotal, discount, vat, vat_amount, totalamount, amountpaid,
      mop, cash_change, date, time) values (:unique_id, :invoice_id, :employee, :customer, :subtotal, :discount, '5', :vat_amount, :totalamount, :amountpaid, :mop, :cash_change, :date, :time)");
      $stmt->bindParam(':unique_id', $unique_id);
      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':customer', $customer);
      $stmt->bindParam(':subtotal', $subtotal);
	    $stmt->bindParam(':discount', $discount);
      $stmt->bindParam(':vat_amount', $vat_amount);
      $stmt->bindParam(':totalamount', $totalamount);
      $stmt->bindParam(':amountpaid', $amountpaid);
      $stmt->bindParam(':mop', $mop);
      $stmt->bindParam(':cash_change', $cash_change);
      $stmt->bindParam(':date', $date);
      $stmt->bindParam(':time', $time);
      $stmt->execute();

      $stmt2 = $pdo->prepare("SELECT * from temp_ticket");
      $stmt2->execute();
      $result = $stmt2->fetchAll();
      foreach($result as $row){
        $product = $row['product'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $discount = $row['discount'];

        $stmt7 = $pdo3->prepare("SELECT * from products where name=:product");
        $stmt7->bindParam(':product', $product);
        $stmt7->execute();
        $result7 = $stmt7->fetchAll();
        foreach($result7 as $row7){
        $updated_stocks = $row7['stocks'] - $quantity;
      }

        $stmt6 = $pdo3->prepare('UPDATE products set stocks=:updated_stocks where name=:product');
        $stmt6->bindParam(':product', $product);
        $stmt6->bindParam(':updated_stocks', $updated_stocks);
        $stmt6->execute();

        $stmt3 = $pdo->prepare("INSERT into ticket (unique_id, invoice_id, employee, product, quantity, price, discount, r_quantity) 
        value (:unique_id, :invoice_id, :employee, :product, :quantity, :price, :discount, :quantity)");
        $stmt3->bindParam(':unique_id', $unique_id);
        $stmt3->bindParam(':invoice_id', $invoice_id);
        $stmt3->bindParam(':employee', $employee);
        $stmt3->bindParam(':product', $product);
        $stmt3->bindParam(':quantity', $quantity);
        $stmt3->bindParam(':price', $price);
        $stmt3->bindParam(':discount', $discount);
        $stmt3->execute();
    }

        $stmt4 = $pdo2->prepare("SELECT * from customers where name = :customer");
        $stmt4->bindParam(':customer', $customer);
        $stmt4->execute();
        $result4 = $stmt4->fetchAll();
        foreach($result4 as $row4){
        $new_points = $row4['points'];
        $totalpoints = $new_points + ($totalamount/20);
        }

        $stmt5 = $pdo2->prepare("UPDATE customers set points=:totalpoints where name = :customer");
        $stmt5->bindParam(':customer', $customer);
        $stmt5->bindParam(':totalpoints', $totalpoints);
        $stmt5->execute();

        $stmt = $pdo->prepare("DELETE from temp_ticket where employee=:employee");
        $stmt->bindParam(':employee', $employee);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
        $stmt->bindParam(':employee', $employee);
        $stmt->execute();

		$_SESSION['unique_id'] = $unique_id;
    header('location:invoice.php');
      
    }
  }

  if(isset($_POST['paylater']))
    {

      $stmt = $pdo->prepare("SELECT * from temp_ticket where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      if($stmt->rowCount() == 0){
        $_SESSION['invalid5'] = "No Orders Yet!";
      }

      else{
      $ticketname = $_POST['ticketname'];
      $description = $_POST['description'];
      $ticketid = 'T'.$_POST['ticketid'];
      $sampleid = $_POST['ticketid'];

      $stmt = $pdo->prepare("SELECT * from temp_ticket where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach($result as $row){
        $product = $row["product"];
        $quantity = $row["quantity"];
        $price = $row["price"];
        $discount = $row["discount"];

      $stmt = $pdo->prepare("INSERT into openticket2(ticket_id, employee, ticketname, product, quantity, price, discount)
      values (:ticketid, :employee, :ticketname, :product, :quantity, :price, :discount)");
      $stmt->bindParam(':ticketid', $ticketid);
      $stmt->bindParam(':ticketname', $ticketname);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':product', $product);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':discount', $discount);
      $stmt->execute();
      }

      $stmt = $pdo->prepare("INSERT into openticket(ticket_id, sample_id, employee, ticketname, description)
      values (:ticketid, :sampleid, :employee, :ticketname, :description)");
      $stmt->bindParam(':ticketid', $ticketid);
      $stmt->bindParam(':sampleid', $sampleid);
      $stmt->bindParam(':employee', $employee);
      $stmt->bindParam(':ticketname', $ticketname);
      $stmt->bindParam(':description', $description);
      $stmt->execute();

      }
    }

    if(isset($_POST['openticket']))
    {
      $ticketid = $_POST['openticket'];

      $stmt = $pdo->prepare("SELECT * from temp_ticket where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      if($stmt->rowCount() > 0){
        $stmt = $pdo->prepare("DELETE from temp_ticket where employee=:employee");
        $stmt->bindParam(':employee', $employee);
        $stmt->execute();

        $stmt = $pdo->prepare("SELECT * from openticket2 where ticket_id=:ticketid");
        $stmt->bindParam(':ticketid', $ticketid);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row){
          $product = $row["product"];
          $quantity = $row["quantity"];
          $price = $row["price"];
          $discount = $row["discount"];

          $stmt = $pdo->prepare("INSERT into temp_ticket(employee, product, quantity, price, discount)
          values (:employee, :product, :quantity, :price, :discount)");
          $stmt->bindParam(':product', $product);
          $stmt->bindParam(':employee', $employee);
          $stmt->bindParam(':quantity', $quantity);
          $stmt->bindParam(':price', $price);
          $stmt->bindParam(':discount', $discount);
          $stmt->execute();

          $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
          $stmt->bindParam(':employee', $employee);
          $stmt->execute();
      }
    }
    else{
      $stmt = $pdo->prepare("SELECT * from openticket2 where ticket_id=:ticketid");
      $stmt->bindParam(':ticketid', $ticketid);
      $stmt->execute();
      $result = $stmt->fetchAll();
      foreach($result as $row){
        $product = $row["product"];
        $quantity = $row["quantity"];
        $price = $row["price"];
        $discount = $row["discount"];

        $stmt = $pdo->prepare("INSERT into temp_ticket(employee, product, quantity, price, discount)
        values (:employee, :product, :quantity, :price, :discount)");
        $stmt->bindParam(':product', $product);
        $stmt->bindParam(':employee', $employee);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':discount', $discount);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
        $stmt->bindParam(':employee', $employee);
        $stmt->execute();
    }
    }
    }

    if(isset($_POST['deleteticket']))
    {
      $ticketid = $_POST['deleteticket'];

      $stmt = $pdo->prepare("DELETE from openticket where ticket_id=:ticketid");
      $stmt->bindParam(':ticketid', $ticketid);
      $stmt->execute();

      $stmt = $pdo->prepare("DELETE from openticket2 where ticket_id=:ticketid");
      $stmt->bindParam(':ticketid', $ticketid);
      $stmt->execute();
    }

    if(isset($_POST['clear']))
    {

      $stmt = $pdo->prepare("DELETE from temp_ticket where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();

      $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
    }

    if(isset($_POST['end']))
    {
      $stmt = $pdo->prepare('UPDATE shift set expected_amount=:expected_amount, end_amount=:end_amount, difference=:difference, end=NOW() where id = :shift_id');
      $stmt->bindParam(':shift_id', $shift_id);
      $stmt->bindParam(':expected_amount', $_POST['expected_amount']);
      $stmt->bindParam(':end_amount', $_POST['end_amount']);
      $stmt->bindParam(':difference', $_POST['difference']);
      $stmt->execute();

      $stmt = $pdo->prepare("DELETE from temp_ticket where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();

      $stmt = $pdo->prepare("DELETE from temp_add where employee=:employee");
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();

      header('location:open.php');
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
  <img src="../pos/img/logo5.png" alt="logo" style=" margin-top: -40px;"/>
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
      <h1> <i class='bx bx-user-pin icon'>&nbsp</i> CASHIER </h1>
      <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>

  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">

  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="cashier.php">Billing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="purchases.php">Purchases</a>
        </li>
  </ul>
  </div>
  </nav>

  


          
<!----- INPUT CODE HERE :) ----->
<div class="p-3">

  <div class="container">
<div class="row equal-height-row">

<!----- BILLING ----->

    <div class="col equal-height-col">
    <div class="text-start fs-2">BILLING 
    <button type="button" class="btn btn-outline-warning btn endbtn" data-bs-target="#endmodal">End Shift</button>
    </div>

<!----- ERROR MESSAGE :) ----->

<?php
if (isset($_SESSION['invalid1'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
              .$_SESSION['invalid1'].
              '</div>'; }
              unset($_SESSION['invalid1']);

              if (isset($_SESSION['invalid2'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                .$_SESSION['invalid2'].
                '</div>'; }
                unset($_SESSION['invalid2']);

                if (isset($_SESSION['invalid3'])) {
                  echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                  .$_SESSION['invalid3'].
                  '</div>'; }
                  unset($_SESSION['invalid3']);

                  if (isset($_SESSION['invalid4'])) {
                    echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                    .$_SESSION['invalid4'].
                    '</div>'; }
                    unset($_SESSION['invalid4']);

                    if (isset($_SESSION['invalid5'])) {
                      echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                      .$_SESSION['invalid5'].
                      '</div>'; }
                      unset($_SESSION['invalid5']);

      $stmt10 = $pdo->prepare("SELECT * from temp_add where employee=:employee");
      $stmt10->bindParam(':employee', $employee);
      $stmt10->execute();
      if($stmt10->rowCount() > 0){
          $result10 = $stmt10->fetchAll();
          foreach($result10 as $row10){
            $dp_product = $row10['product'];
            $dp_category = $row10['category'];
            $dp_brand = $row10['brand'];
            $dp_price = $row10['price'];
            $dp_stocks = $row10['stocks'];
            $dp_quantity = $row10['quantity'];
            $dp_discount = $row10['discount'];
            $dp_total = $row10['total'];
            $dp_barcodediscount = $row10['barcodediscount'];
          }}
          else{
            $dp_product = '';
            $dp_category = '';
            $dp_brand = '';
            $dp_price = '';
            $dp_stocks = '';
            $dp_quantity = '';
            $dp_discount = '';
            $dp_total = '';
            $dp_barcodediscount = '';
          }
?>

    <br>

    <div class="p-3 mb-2 bg-light text-dark">
    <p class="fs-4">ADD</p>

    <form action="cashier.php" method="POST">
    <div class="input-group mb-3">
    <label class="col-sm-2 col-form-label">Barcode</label>
      <input type="text" class="form-control" name="barcode" id="barcode" value="<?php echo $barcode; ?>" autofocus>
      <button type="submit" name="barsub" class="btn btn-outline-warning">Submit</button>
    </div> 
    </form>

    <form action="cashier.php" method="POST">
    <div class="input-group mb-3">
    <label class="col-sm-2 col-form-label">Product</label>
    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#productModal">Select</button>
    <input type="text" class="form-control readonly" name="product" id="product" value="<?php echo $dp_product; ?>" required>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Category</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="category" id="category" value="<?php echo $dp_category; ?>" required>
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Brand</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="brand" id="brand" value="<?php echo $dp_brand; ?>" required>
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Price</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="price" id="price" value="<?php echo $dp_price; ?>" required>
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Stocks</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="stocks" id="stocks" value="<?php echo $dp_stocks; ?>" required>
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Quantity</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="quantity" id="quantity" value="<?php echo $dp_quantity; ?>" required>
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Total</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="total" id="total" value="<?php echo $dp_total; ?>" required>
    </div>
    </div>

    <div class="col-sm-10 input-group mb-3">
    <button class="btn btn-outline-warning discountbtn" type="button" data-bs-target="#discountModal">Apply Discount</button>
      <input type="text" class="form-control readonly" name="barcodediscount" id="barcodediscount" value="<?php echo $dp_barcodediscount; ?>">
      <button class="btn btn-outline-secondary" name="removediscount" type="submit">Remove</button>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Discount</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="discount" id="discount" value="<?php echo $dp_discount; ?>" required>
    </div>
    </div>

    <br>

    <div class="modal-footer">
      <div class="btn-group">
        <button type="submit" name="clear2" class="btn btn-secondary">Clear</button>
        <button type="submit" name="addticket" id="addticket" class="btn btn-warning addticketbtn">Add To Ticket</button>
      </div>
      </div>
    </form>
    </div>
    </div>

<!----- TICKET ----->

    <div class="col equal-height-col">
    <form action="cashier.php" method="POST">
          <br><br><br>
    <div class="p-3 mb-2 bg-light text-dark">
    <p class="fs-4">TICKET</p>
    <button type="button" class="btn btn-warning openticketsbtn" data-bs-target="#openticketsModal">Open Tickets</button>

      <!----- TABLE ----->

  <table id="myTable" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Product</th>
        <th scope="col">Quantity</th>
        <th scope="col">Price</th>
        <th scope="col">Discount</th>
        <th scope="col">Delete</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from temp_ticket where employee=:employee');
  $stmt->bindParam(':employee', $employee);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['temp_ticket_id']; ?></td>
  <td><?= $row['product']; ?></td>
  <td><?= $row['quantity']; ?></td>
  <td><?= $row['price']; ?></td>
  <td><?= $row['discount']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="button" class="btn btn-secondary deletebtn" data-bs-target="#deletemodal"><i class="bi bi-trash3-fill"></i></button>
    </div>                        
  </td>
  </tr>
  <?php } ?>
</table>
<br>

<?php
$stmt = $pdo->prepare("SELECT SUM(price) as subtotal from temp_ticket where employee=:employee");
$stmt->bindParam(':employee', $employee);
$stmt->execute();

  $result = $stmt->fetchAll();
  foreach($result as $row){
    if($row['subtotal'] === null){
    $subtotal = '0.00';
    }
    else{
      $subtotal = $row['subtotal'];
    }
  }
  $_SESSION['subtotal'] = $subtotal;
?>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Subtotal</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="subtotal" id="subtotal" value="₱ <?php echo $subtotal; ?>" required>
    </div>
    </div>

<?php

  $stmt = $pdo->prepare("SELECT SUM(discount) as discountamount from temp_ticket where employee=:employee");
  $stmt->bindParam(':employee', $employee);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  if($row['discountamount'] === null){
  $discountamount = '0.00';
  $totalamount = $subtotal;
}
else{
  $discountamount = $row['discountamount'];
  $totalamount = $subtotal - $discountamount + ($subtotal * 0.05) ;
}
  }
$_SESSION['discountamount'] = $discountamount;
$_SESSION['totalamount'] = $totalamount;
?>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Discount</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="discountamount" id="discountamount" value="₱ <?php echo $discountamount; ?>">
    </div>
    </div>

    <div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Total (w/ 5% VAT)</label>
    <div class="col-sm-10">
      <input type="text" class="form-control readonly" name="totalamount" id="totalamount" value="₱ <?php echo $totalamount; ?>">
    </div>
    </div>


      <div class="modal-footer">
      <div class="btn-group">
        <button type="button" class="btn btn-outline-warning paylaterbtn" data-bs-target="#paylaterModal">Pay Later</button>
        <button type="button" class="btn btn-warning checkoutbtn" data-bs-target="#checkoutModal">Checkout</button>
        <button type="button" class="btn btn-secondary clearbtn" data-bs-target="#clearModal">Clear</button>
      </div>
</form>
      </div>

    </div>
  

    </div>

</div>
</section>


<!----- SCRIPT ----->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script>
new DataTable('#myTable', {
    paging: false,
    scrollCollapse: true,
    searching: false,
    info: false,
    scrollX: true,
    sScrollXInner: "100%",
    scrollY: '200px'
    
});
</script>

<script>
    $(".readonly").on('keydown paste focus mousedown', function(e){
        if(e.keyCode != 9) // ignore tab
            e.preventDefault();
    });
</script>



<script>
  $(document).ready(function () {
    $('.deletebtn').on('click', function() {
      $('#deletemodal').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        console.log(data);

        $('#id').val(data[0]);

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.checkoutbtn').on('click', function() {
      $('#checkoutModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.discountbtn').on('click', function() {
      $('#discountModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.openticketsbtn').on('click', function() {
      $('#openticketsModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.paylaterbtn').on('click', function() {
      $('#paylaterModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.clearbtn').on('click', function() {
      $('#clearModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.endbtn').on('click', function() {

    var total = 0;
    var x = Number($("#expected_amount").val());
    var y = Number($("#end_amount").val());
    var total = x - y;
    $("#difference").val(total)

      $('#endmodal').modal('show');

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
</script>

<!-- ADD MODAL -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="productModalLabel">Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">

        <div class="form-group mb-3">
          <label>Product</label>
          <select class='form-select' name='product' id='product'>
          <option value='Select Product'>Select Product</option>
          <?php
        $stmt = $pdo3->prepare('SELECT * from products ORDER BY name ASC');
        $stmt->execute();
        if($stmt->rowCount() > 0){
        foreach($stmt as $row){?>
        <option value="<?php echo $row['name']; ?>"> <?php echo $row['name']; ?> </option>
        <?php }} ?>

        <?php
        $stmt = $pdo3->prepare('SELECT * from products ORDER BY name ASC');
        $stmt->execute();
        if($stmt->rowCount() == 0){?>
        <option value='No Existing Product'> No Existing Product </option>
    <?php } ?>
    </select>
        </div>

        <div class="form-group mb-3">
          <label>Quantity</label>
          <input type="number" name="quantity" id="quantity" min="1" class="form-control" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="add" class="btn btn-warning">Add</button>
      </div>
      </form>

    </div>
  </div>
</div>

 <!-- DELETE MODAL -->

 <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deletemodalLabel">Delete Order?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">
        
        <input type="hidden" name="id" id="id">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Delete This Order?</label>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" name="deleteorder" class="btn btn-warning">Yes</button>
      </div>
      </form>
      </div>
    </div>
  </div>

  <!-- DISCOUNT MODAL -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="discountModalLabel">Discount</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">

      <div class="mb-3">
      <label for="">Barcode</label>
          <input type="number" name="barcode2" id="barcode2" class="form-control" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="adddiscount" class="btn btn-warning">Apply Discount</button>
      </div>
      </form>

    </div>
  </div>
</div>

  <!-- CHECKOUT MODAL -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="checkoutModalLabel">Checkout</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">

      <?php
      $stmt = $pdo->prepare('SELECT * from invoice');
      $stmt->execute();
      if($stmt->rowCount() > 0){
      $result = $stmt->fetchAll();
      foreach ($result as $row){}
      $invoice = $row['unique_id'] + 1;}

      else{
      $invoice = 1;
      }
      ?>

      <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice; ?>">

      <div class="input-group mb-3">
          <select class='form-select' name='customer' id='customer'>
          <option value='Walk-In Customer'>Walk-In Customer</option>
          <?php
        $stmt = $pdo2->prepare('SELECT * from customers ORDER BY name ASC');
        $stmt->execute();
        if($stmt->rowCount() > 0){
        foreach($stmt as $row){?>
        <option value="<?php echo $row['name']; ?>"> <?php echo $row['name']; ?> </option>
        <?php }} ?>
    </select>
        </div>

        <div class="input-group mb-3">
        <label class="col-sm-2 col-form-label">Amount Paid</label>
        <div class="input-group-text">₱</div>
          <input type="number" step=".01" name="amountpaid" id="amountpaid" class="form-control" required>
        </div>

        <div class="input-group mb-3">
          <label class="col-sm-2 col-form-label">Mode of Payment</label>
          <select class='form-select' name='mop' id='mop'>
          <option value='Cash'>Cash</option>
          <option value='Gcash'>Gcash</option>
          </select>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="checkout" value="<?php $invoice ?>" class="btn btn-warning">Checkout</button>
      </div>
      
      </form>

    </div>
  </div>
</div>

<!-- PAY LATER MODAL -->
<div class="modal fade" id="paylaterModal" tabindex="-1" aria-labelledby="paylaterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="paylaterModalLabel">Pay Later</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">

      <?php
      $stmt = $pdo->prepare('SELECT * from openticket');
      $stmt->execute();
      if($stmt->rowCount() > 0){
      $result = $stmt->fetchAll();
      foreach ($result as $row){}
      $ticketid = $row['sample_id'] + 1;}

      else{
      $ticketid = 1;
      }
      ?>

      <input type="hidden" name="ticketid" id="ticketid" value="<?php echo $ticketid; ?>">

        <div class="mb-3">
        <label class="form-label">Ticket Name</label>
        <input type="text" name="ticketname" id="ticketname" class="form-control" required>
        </div>

        <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="paylater" value="<?php $ticketid ?>" class="btn btn-warning">Save</button>
      </div>
      
      </form> 

    </div>
  </div>
</div>

<!-- OPEN TICKETS MODAL -->
<div class="modal fade modal-lg" id="openticketsModal" tabindex="-1" aria-labelledby="openticketsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="openticketsModalLabel">Open Tickets</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      
      <div class="modal-body">
      <form action="cashier.php" method="POST">
      <table id="myTable2" style="width:100%" class="table table-bordered border-warning">
    <thead>
      <tr>
      <th scope="col">ID</th>
        <th scope="col">Ticket Name</th>
        <th scope="col">Description</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from openticket where employee=:employee');
  $stmt->bindParam(':employee', $employee);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['ticket_id']; ?></td>
  <td><?= $row['ticketname']; ?></td>
  <td><?= $row['description']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="submit" name="openticket" class="btn btn-warning" value="<?php echo $row['ticket_id']; ?>"><i class="bi bi-arrow-right"></i></button>
    <button type="submit" name="deleteticket" class="btn btn-secondary" value="<?php echo $row['ticket_id']; ?>"><i class="bi bi-trash3-fill"></i></button>
    </div>                        
  </td>
  </tr>
  <?php } ?>
</table>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      
      </form>

    </div>
  </div>
</div>

<!-- CLEAR MODAL -->

<div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="clearmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="clearmodalLabel">Clear</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="cashier.php" method="POST">
      <div class="modal-body">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Clear All Fields?</label>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" name="clear" class="btn btn-warning">Yes</button>
      </div>
      </form>
      </div>
    </div>
  </div>

  <!-- END MODAL -->
<div class="modal fade" id="endmodal" tabindex="-1" aria-labelledby="endmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="endmodalLabel">End Shift</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <?php

      $stmt = $pdo->prepare("SELECT * from shift where id = :shift_id");
      $stmt->bindParam(":shift_id", $shift_id);
      $stmt->execute();
      $result = $stmt->fetchAll();
      foreach($result as $row){
        $start = $row['start'];
        $start_amount = $row['start_amount'];
      }

      $end_total = $start_amount;
      $stmt = $pdo->prepare("SELECT * from invoice where CONCAT(date, ' ', time) >= :start AND mop = 'Cash' AND employee=:employee");
      $stmt->bindParam(":start", $start);
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      $result = $stmt->fetchAll();
      foreach($result as $row){
        $end_total = $end_total + $row['totalamount'];
      }

      $total_refund = 0;
      $stmt = $pdo->prepare("SELECT * from refund where CONCAT(date, ' ', time) >= :start AND mop = 'Cash' AND employee=:employee");
      $stmt->bindParam(":start", $start);
      $stmt->bindParam(':employee', $employee);
      $stmt->execute();
      $result = $stmt->fetchAll();
      foreach($result as $row){
        $total_refund = $total_refund + $row['refund_amount'];
      }

      $final = $end_total - $total_refund;

      ?>

      <form action="cashier.php" method="POST">
      <div class="modal-body">

      <label>Expected Amount</label>
        <div class="input-group mb-3">
          <span class="input-group-text">₱</span>
          <input type="number" name="expected_amount" id="expected_amount" min="0" class="form-control" value="<?php echo $final; ?>" readonly required>
        </div>

      <label>Cash in the Drawer</label>
        <div class="input-group mb-3">
        <span class="input-group-text">₱</span>
          <input type="number" step=".01" name="end_amount" id="end_amount" min="0" class="form-control" value="<?php echo $final; ?>" required>
        </div>

      <label>Difference</label>
        <div class="input-group mb-3">
        <span class="input-group-text">₱</span>
          <input type="number" name="difference" id="difference" min="0" class="form-control" readonly required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="end" class="btn btn-warning">End</button>
      </div>
      </form>

    </div>
  </div>
</div>

  <script>
new DataTable('#myTable2', {
    paging: false,
    scrollCollapse: true,
    info: false,
    order: [[0, 'desc']],
});
</script>

<script>

$(document).ready(function(){
  $("#expected_amount,#end_amount").keyup(function(){
    var total = 0;
    var x = Number($("#expected_amount").val());
    var y = Number($("#end_amount").val());
    var total = x - y;
    $("#difference").val(total)
  });
});

</script>

</body>
</html>