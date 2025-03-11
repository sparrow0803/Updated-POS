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
      $bonus = $_POST['bonus'];
      $deduction = $_POST['deduction'];

      $stmt5 = $pdo->prepare('SELECT * from employees where fullname = :name');
      $stmt5->bindParam(':name', $name);
      $stmt5->execute();
      $result = $stmt5->fetchAll();
      foreach($result as $row){
        $whole_salary = $row['monthly_salary'];
      }

      $total_salary = ($whole_salary + $bonus) - $deduction;

      $stmt = $pdo->prepare("INSERT into payroll(name, whole_salary, bonus, deduction, total_salary, date) 
      values (:name, :whole_salary, :bonus, :deduction, :total_salary, NOW())");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':whole_salary', $whole_salary);
      $stmt->bindParam(':bonus', $bonus);
      $stmt->bindParam(':deduction', $deduction);
      $stmt->bindParam(':total_salary', $total_salary);
      if($stmt->execute()){
          $_SESSION['payadd'] = "Payroll Entry Added Successfully!";
      }

      else{
          $_SESSION['payfail'] = "Failed To Add Payroll Entry!";
      }
      
    }


    if(isset($_POST['editdata']))
    {
      $pay_id = $_POST['pay_id'];
      $name = $_POST['name'];
      $bonus = $_POST['bonus'];
      $deduction = $_POST['deduction'];

      $stmt5 = $pdo->prepare('SELECT * from employees where fullname = :name');
      $stmt5->bindParam(':name', $name);
      $stmt5->execute();
      $result = $stmt5->fetchAll();
      foreach($result as $row){
        $whole_salary = $row['monthly_salary'];
      }

      $total_salary = ($whole_salary + $bonus) - $deduction;

      $stmt = $pdo->prepare("UPDATE payroll SET name=:name, whole_salary=:whole_salary, 
      bonus=:bonus, deduction=:deduction, total_salary=:total_salary, date = NOW() where payroll_id=:pay_id");
      $stmt->bindParam(':pay_id', $pay_id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':whole_salary', $whole_salary);
      $stmt->bindParam(':bonus', $bonus);
      $stmt->bindParam(':deduction', $deduction);
      $stmt->bindParam(':total_salary', $total_salary);
      if($stmt->execute()){
        $_SESSION['editsucc'] = "Payroll Entry Has Been Edited Successfully!";
    }

    else{
        $_SESSION['editfail'] = "Failed To Edit Payroll Entry!";
    }
    }


    if(isset($_POST['deletedata']))
    {
      $pay_id2 = $_POST['pay_id2'];

      $stmt = $pdo->prepare("DELETE from payroll where payroll_id=:pay_id2");
      $stmt->bindParam(':pay_id2', $pay_id2);
      if($stmt->execute()){
        $_SESSION['deletesucc'] = "Payroll Entry Deleted Successfully!";
    }
    else{
        $_SESSION['deletefail'] = "Failed To Delete Payroll Entry!";
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


<!-- SUCESS AND ERROR MESSAGE -->
<div class="p-3">
<?php
            if (isset($_SESSION['payadd'])) {
            echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
            .$_SESSION['payadd'].
            '</div>'; }
            unset($_SESSION['payadd']);

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

            if (isset($_SESSION['payfail'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
              .$_SESSION['payfail'].
              '</div>'; }
              unset($_SESSION['payfail']);

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
<div class="text-start col-sm-6 col-md-8">PAYROLL</div>
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
        <th scope="col">Payroll ID</th>
        <th scope="col">Name</th>
        <th scope="col">Whole Salary</th>
        <th scope="col">Bonus</th>
        <th scope="col">Deduction</th>
        <th scope="col">Total Salary</th>
        <th scope="col">Date</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare("SELECT * from payroll");
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['payroll_id']; ?></td>
  <td><?= $row['name']; ?></td>
  <td><?= $row['whole_salary']; ?></td>
  <td><?= $row['bonus']; ?></td>
  <td><?= $row['deduction']; ?></td>
  <td><?= $row['total_salary']; ?></td>
  <td><?= $row['date']; ?></td>
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

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        console.log(data);

        $('#pay_id2').val(data[0]);

    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.editbtn').on('click', function() {
      $('#editmodal').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        console.log(data);

        $('#pay_id').val(data[0]);
        $('#name').val(data[1]);
        $('#whole_salary').val(data[2]);
        $('#bonus').val(data[3]);
        $('#deduction').val(data[4]);
        $('#total_salary').val(data[5]);
        $('#date').val(data[6]);

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
        <h1 class="modal-title fs-5" id="insertdataLabel">Add New Payroll</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="payroll.php" method="POST">
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
        $dropdown2 = "<select class='form-select' name='dorm'>";
        $dropdown2 .= "\r\n<option value='No Existing Employee'>No Existing Employee</option>";
        $dropdown2 .= "\r\n</select>";
        echo $dropdown2;
        echo '</select>';
        }
        ?>
      </div>

        <div class="form-group mb-3">
          <label for="">Bonus</label>
          <input type="number" name="bonus" class="form-control" placeholder="Enter Bonus" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Deduction</label>
          <input type="number" name="deduction" class="form-control" placeholder="Enter Deduction" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="savedata" class="btn btn-warning">Calculate Total Salary</button>
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

      <form action="payroll.php" method="POST">
      
      <div class="modal-body">
        
      <input type="hidden" name="pay_id" id="pay_id">

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
          <label for="">Bonus</label>
          <input type="number" name="bonus" id="bonus" class="form-control" placeholder="Enter Bonus" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Deduction</label>
          <input type="number" name="deduction" id="deduction" class="form-control" placeholder="Enter Deduction" required>
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

      <form action="payroll.php" method="POST">
      <div class="modal-body">
        
        <input type="hidden" name="pay_id2" id="pay_id2">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Delete This Payroll Entry?</label>
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