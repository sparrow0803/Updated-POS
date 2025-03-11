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

if (!isset($_SESSION['id']) && !isset($_SESSION['employee'])){
  header('location:open.php');
}

try{
  $pdo = new PDO('mysql:host=localhost;dbname=pos', "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
  echo 'Connection Failed' .$e->getMessage();
  }
  

  date_default_timezone_set("Asia/Taipei");
  $invoice_id = $_SESSION['invoice_id'];

  if(isset($_POST['refund']))
  {

    $stmt = $pdo->prepare('SELECT * from refund');
    $stmt->execute();
    if($stmt->rowCount() > 0){
    $result = $stmt->fetchAll();
    foreach ($result as $row){}
    $rf = $row['id'] + 1;}

    else{
    $rf = 1;
    }

    $refund_id = $_POST['refund_id'];
    $invoice_id = $_POST['invoice_id'];
    $product = $_POST['product'];
    $refund_quan = $_POST['refund_quan'];
    $rf_id = $invoice_id.'-REF'.$rf;

    $stmt = $pdo->prepare("SELECT * from invoice where invoice_id = :invoice_id");
	$stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row){
        $customer = $row['customer'];
        $mop = $row['mop'];
	}

    $stmt = $pdo->prepare("SELECT * from ticket where ticket_id = :refund_id");
	$stmt->bindParam(':refund_id', $refund_id);
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row){
        $quantity = $row['quantity'];
        $r_quantity = $row['r_quantity'];
        $price = $row['price'];
        $discount = $row['discount'];
	}

    if($refund_quan > $r_quantity){
        $_SESSION['fail'] = "Input Should Not Be Higher Than The Existing Quantity";
    }

    else{
        $refund_amount = (($price - $discount) / $quantity) * $refund_quan;
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $stmt = $pdo->prepare("INSERT into refund(invoice_id, refund_id, employee, customer, product, quantity, refund_amount, mop, date, time) 
        values (:invoice_id, :refund_id, :employee, :customer, :product, :quantity, :refund_amount, :mop, :date, :time)");
        $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->bindParam(':refund_id', $rf_id);
        $stmt->bindParam(':employee', $_SESSION['employee']);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':product', $product);
        $stmt->bindParam(':quantity', $refund_quan);
        $stmt->bindParam(':refund_amount', $refund_amount);
        $stmt->bindParam(':mop', $mop);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        $new_quantity = $r_quantity - $refund_quan;
        $stmt = $pdo->prepare("UPDATE ticket set r_quantity=:new_quantity where ticket_id = :refund_id");
	      $stmt->bindParam(':refund_id', $refund_id);
        $stmt->bindParam(':new_quantity', $new_quantity);
        $stmt->execute();

        $_SESSION['success'] = "Refunded Successfully!";
    }
  }

  if(isset($_POST['refundall']))
  {

  $stmt = $pdo->prepare("SELECT SUM(r_quantity) as ramount from ticket where invoice_id = :invoice_id");
  $stmt->bindParam(':invoice_id', $invoice_id);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
    $ramount = $row['ramount'];
  }
  if($ramount == 0){
    $_SESSION['fail'] = 'No Available Products To Refund';
  }
    
  else{
    $stmt = $pdo->prepare("SELECT * from invoice where invoice_id = :invoice_id");
	$stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row){
        $customer = $row['customer'];
        $mop = $row['mop'];
	}

    $stmt = $pdo->prepare("SELECT * from ticket where invoice_id = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row){

    if($row['r_quantity'] == 0){
      continue;
    }

    $stmt2 = $pdo->prepare('SELECT * from refund');
    $stmt2->execute();
    if($stmt2->rowCount() > 0){
    $result2 = $stmt2->fetchAll();
    foreach ($result2 as $row2){}
    $rf = $row2['id'] + 1;}
    else{
    $rf = 1;
    }

        $refund_quan = $row['r_quantity'];
        $rf_id = $invoice_id.'-REF'.$rf;
        $product = $row['product'];
        $quantity = $row['quantity'];
        $r_quantity = $row['r_quantity'];
        $price = $row['price'];
        $discount = $row['discount'];
        $refund_amount = (($price - $discount) / $quantity) * $refund_quan;
        $date = date("Y-m-d");
        $time = date("H:i:s");

        $stmt = $pdo->prepare("INSERT into refund(invoice_id, refund_id, employee, customer, product, quantity, refund_amount, mop, date, time) 
        values (:invoice_id, :refund_id, :employee, :customer, :product, :quantity, :refund_amount, :mop, :date, :time)");
        $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->bindParam(':refund_id', $rf_id);
        $stmt->bindParam(':employee', $_SESSION['employee']);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':product', $product);
        $stmt->bindParam(':quantity', $refund_quan);
        $stmt->bindParam(':refund_amount', $refund_amount);
        $stmt->bindParam(':mop', $mop);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        $new_quantity = 0;
        $stmt = $pdo->prepare("UPDATE ticket set r_quantity=:new_quantity where invoice_id = :invoice_id");
	      $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->bindParam(':new_quantity', $new_quantity);
        $stmt->execute();

        $_SESSION['success'] = "Refunded Successfully!";
	}  
}      
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
<div class="p-3 mb-2 bg-secondary text-white">
<div class="row gx-5">
    <div class="col">
     <div class="p-3">PURCHASES</div>
    </div>
    <div class="col d-flex flex-row-reverse">
    <button type="button" class="btn btn-outline-warning btn-sm historybtn" data-bs-target="#historyModal">Refunds</button>
    </div>
  </div>
</div>
  </div>

  <div class="container">
  <div class="p-3 mb-2 bg-body-tertiary">

  <form method="POST">
  <table id="myTable" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Invoice ID</th>
        <th scope="col">Product</th>
        <th scope="col">Quantity</th>
        <th scope="col">Refund</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from ticket where invoice_id = :invoice_id');
  $stmt->bindParam(':invoice_id', $invoice_id);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['ticket_id']; ?></td>
  <td><?= $row['invoice_id']; ?></td>
  <td><?= $row['product']; ?></td>
  <td><?= $row['r_quantity']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="button" class="btn btn-warning refundbtn" data-bs-target="#refundModal">Refund</button>
    </div>                        
  </td>
  </tr>
  <?php } ?>
</table>

<div class="modal-footer">
      <div class="btn-group">
        <button type="button" class="btn btn-outline-warning refundallbtn" data-bs-target="#refundallModal">Refund All</button>
      </div>
      </div>
</form>

<div class="modal-footer">
      <div class="btn-group">
        </div>
        </div>

        <br>

        <div class="modal-footer">
        <?php
              if (isset($_SESSION['success'])) {
              echo '<div class="badge text-bg-warning text-wrap" style="width: 15rem;">'
              .$_SESSION['success'].
              '</div>'; }
              unset($_SESSION['success']);

              if (isset($_SESSION['fail'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                .$_SESSION['fail'].
                '</div>'; }
                unset($_SESSION['fail']);
                ?>
                </div>

    </div>
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
    info: false,
    scrollX: true,
    sScrollXInner: "100%",
    scrollY: '200px'
});
</script>



<script>
  $(document).ready(function () {
    $('.refundbtn').on('click', function() {
      $('#refundModal').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        console.log(data);

        $('#refund_id').val(data[0]);
        $('#invoice_id').val(data[1]);
        $('#product').val(data[2]);
        $('#quantity').val(data[3]);

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.refundallbtn').on('click', function() {
      $('#refundallModal').modal('show');

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.historybtn').on('click', function() {
      $('#historyModal').modal('show');

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

<!-- REFUND ITEM MODAL -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="refundModalLabel">Refund Item</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="return.php" method="POST">
      <div class="modal-body">

      <input type="hidden" name="refund_id" id="refund_id">
      <input type="hidden" name="invoice_id" id="invoice_id">
      <div class="mb-3">
        <input type="text" name="product" id="product" class="form-control" readonly>
          <input type="number" name="refund_quan" id="refund_quan" min="1" class="form-control" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="refund" class="btn btn-warning">Refund</button>
      </div>
      </form>

    </div>
  </div>
</div>

<!-- REFUND ALL MODAL -->

<div class="modal fade" id="refundallModal" tabindex="-1" aria-labelledby="refundallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="refundallModalLabel">Refund Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="return.php" method="POST">
      <div class="modal-body">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Refund All Items?</label>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" name="refundall" class="btn btn-warning">Yes</button>
      </div>
      </form>
      </div>
    </div>
  </div>

  <!-- REFUND HISTORY MODAL -->
<div class="modal fade modal-lg" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="historyModalLabel">Refund History</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      
      <div class="modal-body">

      <table id="myTable2" style="width:100%" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">Product</th>
        <th scope="col">Quantity</th>
        <th scope="col">Refund Amount</th>
        <th scope="col">Date & Time</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from refund where invoice_id = :invoice_id');
  $stmt->bindParam(':invoice_id', $invoice_id);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['product']; ?></td>
  <td><?= $row['quantity']; ?></td>
  <td><?= $row['refund_amount']; ?></td>
  <td><?= $row['date']. ' | ' .$row['time']; ?></td>
  </tr>
  <?php } ?>
</table>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
new DataTable('#myTable2', {
    paging: false,
    scrollCollapse: true,
    info: false,
    order: [[3, 'desc']],
});
</script>

</body>
</html>