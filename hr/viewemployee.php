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
require 'connection/conn.php';

    try{
    $pdo = new PDO('mysql:host=localhost;dbname=hr', "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    echo 'Connection Failed' .$e->getMessage();
    }

    if(isset($_GET['viewemployee']))
    {
        $emp_id = $_GET['viewemployee'];
        $stmt = $pdo->prepare('SELECT * from employees where emp_id = :emp_id');
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row){
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
              <li><a class="dropdown-item" href="leave.php">Leave</a></li>
              <li><a class="dropdown-item" href="payroll.php">Payroll</a></li>
              <li><a class="dropdown-item" href="access.php">Access Rights</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>


<!----- NAVBAR ----->
<div class="p-3">
<div class="row g-0 text-center">
<div class="text-start col-sm-6 col-md-8">EMPLOYEE INFORMATION</div>
</div>

<br>

<div class="text-center">
<?php
if (empty($row['photo'])){
    echo "<img class='img-thumbnail' width='300rem' src='picture/employee.jpg'>";
}
else
{
    echo "<img class='img-thumbnail' width='300rem' src='picture/" .$row['photo']. "'>";
}
?>
</div>

<br><br>

<div class="row">
  <div class="col">
    <label>First Name</label>
    <input type="text" class="form-control" value="<?php echo $row['firstname']; ?>" readonly>
  </div>
  <div class="col">
    <label>Last Name</label>
    <input type="text" class="form-control" value="<?php echo $row['lastname']; ?>" readonly>
  </div>
  <div class="col">
    <label>Middle Name</label>
    <input type="text" class="form-control" value="<?php echo $row['middlename']; ?>" readonly>
  </div>
  <div class="col">
    <label>Suffix</label>
    <input type="text" class="form-control" value="<?php echo $row['suffix']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
<div class="col">
    <label>Birthday</label>
    <input type="text" class="form-control" value="<?php echo $row['birthday']; ?>" readonly>
  </div>
  <div class="col">
    <label>Marital Status</label>
    <input type="text" class="form-control" value="<?php echo $row['marital_status']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <label>Email</label>
    <input type="text" class="form-control" value="<?php echo $row['email']; ?>" readonly>
  </div>
  <div class="col">
    <label>Contact #</label>
    <input type="text" class="form-control" value="<?php echo $row['contact']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <label>Nationality</label>
    <input type="text" class="form-control" value="<?php echo $row['nationality']; ?>" readonly>
  </div>
  <div class="col">
    <label>Zip Code</label>
    <input type="text" class="form-control" value="<?php echo $row['zip_code']; ?>" readonly>
  </div>
  <div class="col">
    <label>Gender</label>
    <input type="text" class="form-control" value="<?php echo $row['gender']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <label>Address</label>
    <input type="text" class="form-control" value="<?php echo $row['address']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <label>Date Hired</label>
    <input type="text" class="form-control" value="<?php echo $row['hire_date']; ?>" readonly>
  </div>
  <div class="col">
    <label>Employment Status</label>
    <input type="text" class="form-control" value="<?php echo $row['emp_status']; ?>" readonly>
  </div>
  <div class="col">
    <label>Shift Per Day</label>
    <input type="text" class="form-control" value="<?php echo $row['shift_per_day']; ?>" readonly>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <label>Position</label>
    <input type="text" class="form-control" value="<?php echo $row['position']; ?>" readonly>
  </div>
  <div class="col">
    <label>Monthly Salary</label>
    <input type="text" class="form-control" value="<?php echo $row['monthly_salary']; ?>" readonly>
  </div>
</div>

<br>

<div class="modal-footer">
<button type="button" name="back" class="btn btn-secondary" onclick="history.back()">Back</button>
</div>

</div>

</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
      

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
</html>