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

  if(isset($_POST['view']))
  {
    $_SESSION['unique_id'] = $_POST['view'];
    header('location:invoice.php');
  }

  if(isset($_POST['return']))
  {
    $_SESSION['invoice_id'] = $_POST['return'];
    header('location:return.php');
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
    <button type="button" class="btn btn-outline-warning btn-sm refundbtn" data-bs-target="#refundModal">Refunds</button>
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
        <th scope="col">Customer</th>
        <th scope="col">Date & Time</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from invoice where employee=:employee');
  $stmt->bindParam(':employee', $_SESSION['employee']);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['invoice_id']; ?></td>
  <td><?= $row['customer']; ?></td>
  <td><?= $row['date']. '  ' .$row['time']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="submit" name="return" class="btn btn-secondary" value="<?php echo $row['invoice_id']; ?>"><i class="bi bi-arrow-return-left"></i></button>
    <button type="submit" name="view" class="btn btn-warning" value="<?php echo $row['unique_id']; ?>"><i class="bi bi-eye"></i></button>
    </div>                        
  </td>
  </tr>
  <?php } ?>
</table>
</form>

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
    order: [[2, 'desc']],
    scrollX: true,
    sScrollXInner: "100%",
    scrollY: '200px'
});
</script>

<script>
  $(document).ready(function () {
    $('.refundbtn').on('click', function() {
      $('#refundModal').modal('show');

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

<!-- REFUND HISTORY MODAL -->
<div class="modal fade modal-lg" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="refundModalLabel">Refund History</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      
      <div class="modal-body">
      <form action="purchases.php" method="POST">
      <table id="myTable2" style="width:100%" class="table table-bordered border-warning">
    <thead>
      <tr>
      <th scope="col">Ticket ID</th>
        <th scope="col">Customer</th>
        <th scope="col">Product</th>
        <th scope="col">Quantity</th>
        <th scope="col">Refund Amount</th>
        <th scope="col">Date & Time</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from refund where employee=:employee');
  $stmt->bindParam(':employee', $_SESSION['employee']);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['invoice_id']; ?></td>
  <td><?= $row['customer']; ?></td>
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
      
      </form>

    </div>
  </div>
</div>

<script>
new DataTable('#myTable2', {
    paging: false,
    scrollCollapse: true,
    info: false,
    order: [[5, 'desc']],
});
</script>

</body>
</html>