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

    if(isset($_POST['add']))
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $suffix = $_POST['suffix'];
        $fullname = $firstname.' '.$lastname;
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $birthday = $_POST['birthday'];
        $marital_status = $_POST['marital_status'];
        $gender = $_POST['gender'];
        $nationality = $_POST['nationality'];
        $zip_code = $_POST['zip_code'];
        $address = $_POST['address'];
        $hire_date = $_POST['hire_date'];
        $emp_status = $_POST['emp_status'];
        $shift_per_day = $_POST['shift_per_day'];
        $position = $_POST['position'];
        $monthly_salary = $_POST['monthly_salary'];
        $filename = $_FILES['choosefile']['name'];
        $tempfile = $_FILES['choosefile']['tmp_name'];
        $folder = "picture/".$filename;
  
        $stmt = $pdo->prepare("INSERT into employees(firstname, lastname, middlename, suffix, fullname, email, contact,
        birthday, marital_status, gender, nationality, zip_code, address, hire_date, emp_status, shift_per_day, position, monthly_salary, photo) 
        values (:firstname, :lastname, :middlename, :suffix, :fullname, :email, :contact, :birthday, :marital_status, :gender, :nationality,
        :zip_code, :address, :hire_date, :emp_status, :shift_per_day, :position, :monthly_salary, :filename)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':marital_status', $marital_status);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':nationality', $nationality);
        $stmt->bindParam(':zip_code', $zip_code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':hire_date', $hire_date);
        $stmt->bindParam(':emp_status', $emp_status);
        $stmt->bindParam(':shift_per_day', $shift_per_day);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':monthly_salary', $monthly_salary);
        $stmt->bindParam(':filename', $filename);
        if($stmt->execute()){
            $_SESSION['empadd'] = "Employee Added Successfully!";
            move_uploaded_file($tempfile, $folder);
            header("location: listemployees.php");
        }
  
        else{
            $_SESSION['empfail'] = "Failed To Add Employee!";
            header("location: listemployees.php");
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
</head>

<body>
<nav class="sidebar close" style="overflow: hidden; ">
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
  </nav>


<!----- NAVBAR ----->
<div class="p-3">
<div class="row g-0 text-center">
<div class="text-start col-sm-6 col-md-8">ADD NEW EMPLOYEE</div>
</div>

<br>

<form method="POST" action="addemployee.php" enctype="multipart/form-data">

<div class="row">
  <div class="col">
    <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="lastname" placeholder="Last Lame" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="middlename" placeholder="Middle Name" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="suffix" placeholder="Suffix">
  </div>
</div>

<br>

<div class="row">
  <div class="col">
  <div class="input-group">
    <div class="input-group-text">Birthday</div>
    <input type="date" class="form-control" name="birthday" required>
  </div>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="marital_status" placeholder="Marital Status" required>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <input type="email" class="form-control" name="email" placeholder="Email" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="contact" placeholder="Contact #" required>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <input type="text" class="form-control" name="nationality" placeholder="Nationality" required>
  </div>
  <div class="col">
    <input type="number" class="form-control" name="zip_code" placeholder="Zip Code" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="gender" placeholder="Gender" required>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <input type="text" class="form-control" name="address" placeholder="Address" required>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
  <div class="input-group">
    <div class="input-group-text">Date Hired</div>
    <input type="date" class="form-control" name="hire_date" required>
  </div>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="emp_status" placeholder="Employment Status" required>
  </div>
  <div class="col">
    <input type="text" class="form-control" name="shift_per_day" placeholder="Shift Per Day" required>
  </div>
</div>

<br>

<div class="row">
  <div class="col">
    <input type="text" class="form-control" name="position" placeholder="Position" required>
  </div>
  <div class="col">
    <input type="number" class="form-control" name="monthly_salary" placeholder="Monthly Salary" required>
  </div>
</div>

<br>

<div class="row">
<div class="col">
<div class="input-group">
    <div class="input-group-text">2x2 Picture</div>
    <input type="file" name="choosefile" class="form-control" accept="image/jpg, image/jpeg, image/png">
</div>
</div>
</div>

<br>

<div class="modal-footer">
<button type="button" name="back" class="btn btn-secondary" onclick="history.back()">Back</button>
<button type="submit" name="add" class="btn btn-warning">Add</button>
</div>

</form>

</div>

</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<br>

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