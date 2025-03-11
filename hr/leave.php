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

    if(isset($_POST['savedata']))
    {
      $name = $_POST['name'];
      $type = $_POST['type'];
      $start = $_POST['start'];
      $end = $_POST['end'];

      $stmt = $pdo->prepare("INSERT into emp_leave(name, type, start, end) values (:name, :type, :start, :end)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':type', $type);
      $stmt->bindParam(':start', $start);
      $stmt->bindParam(':end', $end);
      if($stmt->execute()){
          $_SESSION['leaveadd'] = "Leave Application Added Successfully!";
      }

      else{
          $_SESSION['leavefail'] = "Failed To Add Leave Application!";
      }
      
    }


    if(isset($_POST['editdata']))
    {
      $leave_id = $_POST['leave_id'];
      $name = $_POST['name'];
      $type = $_POST['type'];
      $start = $_POST['start'];
      $end = $_POST['end'];

      $stmt = $pdo->prepare("UPDATE emp_leave SET name=:name, type=:type, start=:start, end=:end  where leave_id=:leave_id");
      $stmt->bindParam(':leave_id', $leave_id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':type', $type);
      $stmt->bindParam(':start', $start);
      $stmt->bindParam(':end', $end);
      if($stmt->execute()){
        $_SESSION['editsucc'] = "Leave Application Has Been Edited Successfully!";
    }

    else{
        $_SESSION['editfail'] = "Failed To Edit Leave Application!";
    }
    }


    if(isset($_POST['deletedata']))
    {
      $leave_id2 = $_POST['leave_id2'];

      $stmt = $pdo->prepare("DELETE from emp_leave where leave_id=:leave_id2");
      $stmt->bindParam(':leave_id2', $leave_id2);
      if($stmt->execute()){
        $_SESSION['deletesucc'] = "Leave Application Deleted Successfully!";
    }
    else{
        $_SESSION['deletefail'] = "Failed To Delete Leave Application!";
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
  <link rel="stylesheet" href="styles/cashier.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/jquery.dataTables.min.js"></script>
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
  </nav>


<!-- SUCESS AND ERROR MESSAGE -->
<div class="p-3">
<?php
            if (isset($_SESSION['leaveadd'])) {
            echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
            .$_SESSION['leaveadd'].
            '</div>'; }
            unset($_SESSION['leaveadd']);

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

            if (isset($_SESSION['leavefail'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
              .$_SESSION['leavefail'].
              '</div>'; }
              unset($_SESSION['leavefail']);

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
<div class="text-start col-sm-6 col-md-8">LEAVE APPLICATION</div>
<div class="col-6 col-md-4">

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#insertdata">
Create New
</button>
</div>

</div>
</div>

  <!----- TABLE ----->

  <table id="myTable" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">Leave #</th>
        <th scope="col">Name</th>
        <th scope="col">Type of Leave</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare("SELECT * from emp_leave");
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['leave_id']; ?></td>
  <td><?= $row['name']; ?></td>
  <td><?= $row['type']; ?></td>
  <td><?= $row['start']; ?></td>
  <td><?= $row['end']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="button" class="btn btn-warning editbtn" data-bs-target="#editmodal">Edit</button>
    <button type="button" class="btn btn-danger deletebtn" data-bs-target="#deletemodal">Delete</button>
    </div>                        
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
    scrollY: '50vh',
    order: [[0, 'desc']]
});
</script>

<script>
  $(document).ready(function () {
    $('.deletebtn').on('click', function() {
        $('#deletemodal').modal('show');
        var data = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();
        $('#leave_id2').val(data[0]);

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.editbtn').on('click', function() {
        $('#editmodal').modal('show');
        var data = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();
        $('#leave_id').val(data[0]);
        $('#name').val(data[1]);
        $('#type').val(data[2]);
        $('#start').val(data[3]);
        $('#end').val(data[4]);

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
<!-- CREATE NEW MODAL -->
<div class="modal fade" id="insertdata" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="insertdataLabel">Add New Leave Application</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="leave.php" method="POST">
      <div class="modal-body">
        

      <div class="form-group mb-3">
      <label for="">Employee</label>
      <?php
        $stmt2 = $pdo->prepare('SELECT * from employees');
        $stmt2->execute();
        if($stmt2->rowCount() > 0){
        $dropdown = "<select class='form-select' name='name'>";
        foreach($stmt2 as $row){
        $dropdown .= "\r\n<option value='{$row['firstname']} {$row['lastname']}'>{$row['firstname']} {$row['lastname']}</option>";
        }
        $dropdown .= "\r\n</select>";
        echo $dropdown;
        echo '</select>';
        }   
        else{
        $dropdown2 = "<select class='form-select' name='name'>";
        $dropdown2 .= "\r\n<option value='No Existing Employee'>No Existing Employee</option>";
        $dropdown2 .= "\r\n</select>";
        echo $dropdown2;
        echo '</select>';
        }
        ?>
      </div>

        <div class="form-group mb-3">
      <label for="">Type of Leave</label>
            <select class="form-select" name="type">
            <option selected>Type of Leave</option>
            <option value="Maternity Leave">Maternity Leave</option>
            <option value="Sick Leave">Sick Leave</option>
            <option value="Paternity Leave">Paternity Leave</option>
            <option value="Unpaid Leave">Unpaid Leave</option>
            <option value="Compensatory Leave">Compensatory Leave</option>
            <option value="Casual Leave">Casual Leave</option>
            <option value="Emergency Leave">Emergency Leave</option>
            <option value="Holidays">Holidays</option>
        </select>
        </div>

        <div class="form-group mb-3">
          <label for="">Start Date</label>
          <input type="date" name="start" class="form-control" placeholder="Enter Start Date" required>
        </div>

        <div class="form-group mb-3">
          <label for="">End Date</label>
          <input type="date" name="end" class="form-control" placeholder="Enter End Date" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="savedata" class="btn btn-warning">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- EDIT MODAL -->

<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editmodalLabel">Edit Leave Application</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="leave.php" method="POST">
      
      <div class="modal-body">
        
      <input type="hidden" name="leave_id" id="leave_id">

      <div class="form-group mb-3">
      <label for="">Employee</label>
      <?php
        $stmt2 = $pdo->prepare('SELECT * from employees');
        $stmt2->execute();
        if($stmt2->rowCount() > 0){
        $dropdown = "<select id='name' class='form-select' name='name'>";
        foreach($stmt2 as $row){
        $dropdown .= "\r\n<option value='{$row['firstname']} {$row['lastname']}'>{$row['firstname']} {$row['lastname']}</option>";
        }
        $dropdown .= "\r\n</select>";
        echo $dropdown;
        echo '</select>';
        }   
        else{
        $dropdown2 = "<select class='form-select' name='dorm'>";
        $dropdown2 .= "\r\n<option value='No Existing Employee'>No Existing Employee</option>";
        $dropdown2 .= "\r\n</select>";
        echo $dropdown2;
        echo '</select>';
        }
        ?>
      </div>

        <div class="form-group mb-3">
      <label for="">Type of Leave</label>
            <select class="form-select" name="type" id="type">
            <option selected>Type of Leave</option>
            <option value="Maternity Leave">Maternity Leave</option>
            <option value="Sick Leave">Sick Leave</option>
            <option value="Paternity Leave">Paternity Leave</option>
            <option value="Unpaid Leave">Unpaid Leave</option>
            <option value="Compensatory Leave">Compensatory Leave</option>
            <option value="Casual Leave">Casual Leave</option>
            <option value="Emergency Leave">Emergency Leave</option>
            <option value="Holidays">Holidays</option>
        </select>
        </div>

        <div class="form-group mb-3">
          <label for="">Start Date</label>
          <input type="date" name="start" id="start" class="form-control" placeholder="Enter Start Date" required>
        </div>

        <div class="form-group mb-3">
          <label for="">End Date</label>
          <input type="date" name="end" id="end" class="form-control" placeholder="Enter End Date" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="editdata" class="btn btn-warning">Update</button>
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
        <h1 class="modal-title fs-5" id="deletemodalLabel">Remove</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="leave.php" method="POST">
      <div class="modal-body">
        
        <input type="hidden" name="leave_id2" id="leave_id2">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Delete This Leave Application?</label>
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