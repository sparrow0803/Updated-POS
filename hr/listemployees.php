<?php 
if (!isset($_SESSION)) {
  session_start();
} 

if (isset($_SESSION['UserLogin'])) {
  $username = $_SESSION['UserLogin'];
  $userrole = $_SESSION['UserRole'];
} else {
  header("Location: login.php");
  exit();
}
require 'connection/conn.php';

    try{
    $pdo = new PDO('mysql:host=localhost;dbname=hr', "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    echo 'Connection Failed' .$e->getMessage();
    }

    if(isset($_POST['deletedata']))
    {
      $emp_id = $_POST['emp_id'];

      $stmt = $pdo->prepare("DELETE from employees where emp_id=:emp_id");
      $stmt->bindParam(':emp_id', $emp_id);
      if($stmt->execute()){
        $_SESSION['deletesucc'] = "Data Deleted Successfully!";
    }
    else{
        $_SESSION['deletefail'] = "Failed To Delete Data!";
    }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HUMAN RESOURCES</title>
  <link rel="stylesheet" href="styles/employee.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
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
      <h1> <i class='bx bx-user-pin icon'>&nbsp</i> HUMAN RESOURCES </h1>
      <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>


          
<!----- INPUT CODE HERE :) ----->

<!----- NAVBAR ----->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">

      <a class="navbar-brand" href="employee.php">HUMAN RESOURCES</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Employees
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="listemployees.php">List of Employees</a></li>
              <li><a class="dropdown-item" href="listrecruits.php">List of Applicants</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="attendance.php">Attendance</a></li>
              <li><a class="dropdown-item" href="performance.php">Performance</a></li>
              <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource"){?>
              <li><a class="dropdown-item" href="leave.php">Leave</a></li>
              <li><a class="dropdown-item" href="payroll.php">Payroll</a></li>
              <li><a class="dropdown-item" href="access.php">Access Rights</a></li>
              <?php }?>
            </ul>
          </li>
        </ul>
    </div>
  </nav>


<!-- SUCESS AND ERROR MESSAGE -->
<div class="p-3">
<?php
            if (isset($_SESSION['empadd'])) {
            echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
            .$_SESSION['empadd'].
            '</div>'; }
            unset($_SESSION['empadd']);

            if (isset($_SESSION['picsucc'])) {
              echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
              .$_SESSION['picsucc'].
              '</div>'; }
              unset($_SESSION['picsucc']);

            if (isset($_SESSION['editsucc'])) {
              echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
              .$_SESSION['editsucc'].
              '</div>'; }
              unset($_SESSION['editsucc']);

                if (isset($_SESSION['deletesucc'])) {
                  echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
                  .$_SESSION['deletesucc'].
                  '</div>'; }
                  unset($_SESSION['deletesucc']);

            if (isset($_SESSION['empfail'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
              .$_SESSION['empfail'].
              '</div>'; }
              unset($_SESSION['empfail']);

              if (isset($_SESSION['picfail'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                .$_SESSION['picfail'].
                '</div>'; }
                unset($_SESSION['picfail']);

              if (isset($_SESSION['editfail'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                .$_SESSION['editfail'].
                '</div>'; }
                unset($_SESSION['editfail']);

                    if (isset($_SESSION['deletefail'])) {
                      echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                      .$_SESSION['deletefail'].
                      '</div>'; }
                      unset($_SESSION['deletefail']);
?>


<!----- NAVBAR ----->
<div class="row g-0 text-center">
<div class="text-start col-sm-6 col-md-8">LIST OF EMPLOYEES</div>
<div class="col-6 col-md-4">

<?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource"){?>
<form action="addemployee.php" method="POST">
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
<button type="submit" class="btn btn-warning">
Create New
</button>
</div>
</form>
<?php }?>

</div>
</div>

  <!----- TABLE ----->

  <table id="myTable" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">Employee #</th>
        <th scope="col">Name</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from employees');
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['emp_id']; ?></td>
  <td><?= $row['firstname']." ".$row['lastname'];?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <form method="GET" action="viewemployee.php">
    <button type="submit" class="btn btn-warning viewbtn" name="viewemployee" value="<?= $row['emp_id']; ?>">View</button>
    </form>
    </div>
    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource"){?>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <form method="GET" action="editemployee.php">
    <button type="submit" class="btn btn-warning editbtn" name="editemployee" value="<?= $row['emp_id']; ?>">Edit</button>
    </form>
    </div>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="button" class="btn btn-danger deletebtn" data-bs-target="#deletemodal">Delete</button>
    </div>    
    <?php }?>                    
  </td>
  </tr>
  <?php } ?>
</table>

</div>

</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script>
  new DataTable('#myTable', {
    paging: false,
    scrollCollapse: true,
    scrollY: '50vh'
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

        $('#emp_id').val(data[0]);

    });
  });
</script>
          
      

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
</body>
  <!-- DELETE MODAL -->

  <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deletemodalLabel">Delete Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="listemployees.php" method="POST">

      <div class="modal-body">
        
        <input type="hidden" name="emp_id" id="emp_id">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Remove This Employee?</label>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" name="deletedata" class="btn btn-warning">Yes</button>
      </div>
      </form>
      </div>
    </div>
  </div>  
</html>