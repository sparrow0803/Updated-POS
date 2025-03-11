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
      $email = $_POST['email'];
      $contact = $_POST['contact'];
      $des_pos = $_POST['des_pos'];
      $status = $_POST['status'];
      $filename = $_FILES['choosefile']['name'];
      $tempfile = $_FILES['choosefile']['tmp_name'];
      $folder = "resume/".$filename;

      $stmt = $pdo->prepare("INSERT into applicants(fname, email, contact, des_pos, status, resume) values (:name, :email, :contact, :des_pos, :status, :filename)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':contact', $contact);
      $stmt->bindParam(':des_pos', $des_pos);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':filename', $filename);
      if($stmt->execute()){
          $_SESSION['appadd'] = "Applicant Added Successfully!";
          move_uploaded_file($tempfile, $folder);
      }

      else{
          $_SESSION['appfail'] = "Failed To Add Applicant!";
      }
      
    }

      if(!empty($_GET['file'])){
        $file_name = basename(($_GET['file']));
        $file_path = "resume/".$file_name;

        if(!empty($file_name) && file_exists($file_path)){

          header("Cache-Control: public");
          header("Content-Description: File Transfer");
          header("Content-Disposition: attachment; filename=$file_name");
          header("Content-Type: application/zip");
          header("Content-Transfer-Encoding: binary");

          readfile($file_path);
          exit;
        }

        else{
          $_SESSION['dlfail'] = "File Does Not Exist!";
        }
      }


    if(isset($_POST['editdata']))
    {
      $app_id = $_POST['app_id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $contact = $_POST['contact'];
      $des_pos = $_POST['des_pos'];
      $status = $_POST['status'];

      $stmt = $pdo->prepare("UPDATE applicants SET fname=:name, email=:email, contact=:contact, des_pos=:des_pos, status=:status where app_id=:app_id");
      $stmt->bindParam(':app_id', $app_id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':contact', $contact);
      $stmt->bindParam(':des_pos', $des_pos);
      $stmt->bindParam(':status', $status);
      if($stmt->execute()){
        $_SESSION['editsucc'] = "Applicant Data Has Been Edited Successfully!";
    }

    else{
        $_SESSION['editfail'] = "Failed To Edit Applicant Data!";
    }
    }

    if(isset($_POST['editresume']))
    {
      $app_id2 = $_POST['app_id2'];
      $filename = $_FILES['choosefile']['name'];
      $tempfile = $_FILES['choosefile']['tmp_name'];
      $folder = "resume/".$filename;

      $stmt = $pdo->prepare("UPDATE applicants SET resume=:filename where app_id=:app_id2");
      $stmt->bindParam(':app_id2', $app_id2);
      $stmt->bindParam(':filename', $filename);
      if($stmt->execute()){
        $_SESSION['resumesucc'] = "Applicant's Resume Has Been Updated Successfully!";
        move_uploaded_file($tempfile, $folder);
    }
    else{
        $_SESSION['resumefail'] = "Failed To Update Applicant's Resume!";
    }
    }

    if(isset($_POST['deletedata']))
    {
      $app_id3 = $_POST['app_id3'];

      $stmt = $pdo->prepare("DELETE from applicants where app_id=:app_id3");
      $stmt->bindParam(':app_id3', $app_id3);
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
            if (isset($_SESSION['appadd'])) {
            echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
            .$_SESSION['appadd'].
            '</div>'; }
            unset($_SESSION['appadd']);

            if (isset($_SESSION['editsucc'])) {
              echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
              .$_SESSION['editsucc'].
              '</div>'; }
              unset($_SESSION['editsucc']);

              if (isset($_SESSION['resumesucc'])) {
                echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
                .$_SESSION['resumesucc'].
                '</div>'; }
                unset($_SESSION['resumesucc']);

                if (isset($_SESSION['deletesucc'])) {
                  echo '<div class="badge text-bg-warning text-wrap" style="width: 20rem;">'
                  .$_SESSION['deletesucc'].
                  '</div>'; }
                  unset($_SESSION['deletesucc']);

            if (isset($_SESSION['appfail'])) {
              echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
              .$_SESSION['appfail'].
              '</div>'; }
              unset($_SESSION['appfail']);

              if (isset($_SESSION['editfail'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                .$_SESSION['editfail'].
                '</div>'; }
                unset($_SESSION['editfail']);

                if (isset($_SESSION['resumefail'])) {
                  echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                  .$_SESSION['resumefail'].
                  '</div>'; }
                  unset($_SESSION['resumefail']);

                  if (isset($_SESSION['dlfail'])) {
                    echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                    .$_SESSION['dlfail'].
                    '</div>'; }
                    unset($_SESSION['dlfail']);

                    if (isset($_SESSION['deletefail'])) {
                      echo '<div class="badge text-bg-danger text-wrap" style="width: 20rem;">'
                      .$_SESSION['deletefail'].
                      '</div>'; }
                      unset($_SESSION['deletefail']);
?>


<!----- NAVBAR ----->

<div class="row g-0 text-center">
<div class="text-start col-sm-6 col-md-8">LIST OF APPLICANTS</div>
<div class="col-6 col-md-4">

<?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource"){?>
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#insertdata">
Create New
</button>
</div>
<?php }?>

</div>
</div>

  <!----- TABLE ----->

  <table id="myTable" class="table table-bordered border-warning">
    <thead>
      <tr>
        <th scope="col">Applicant #</th>
        <th scope="col">Full Name</th>
        <th scope="col">Email Address</th>
        <th scope="col">Contact #</th>
        <th scope="col">Desired Position</th>
        <th scope="col">Status</th>
        <th scope="col">Resume</th>
        <th scope="col">Manage</th>
      </tr>
    </thead>
  <?php 
  $stmt = $pdo->prepare('SELECT * from applicants');
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['app_id']; ?></td>
  <td><?= $row['fname']; ?></td>
  <td><?= $row['email']; ?></td>
  <td><?= $row['contact']; ?></td>
  <td><?= $row['des_pos']; ?></td>
  <td><?= $row['status']; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <a href="listrecruits.php?file=<?php echo $row['resume']; ?>"><?php echo $row['resume']; ?></a>
    </div> 
  </td>
  <td>
  <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Human Resource"){?>
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <button type="button" class="btn btn-warning resumebtn" data-bs-target="#resumemodal">Resume</button>
    <button type="button" class="btn btn-success editbtn" data-bs-target="#editmodal">Edit</button>
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

        $('#app_id3').val(data[0]);

    });
  });
</script>


<script>
  $(document).ready(function () {
    $('.resumebtn').on('click', function() {
      $('#resumemodal').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        console.log(data);

        $('#app_id2').val(data[0]);

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

        $('#app_id').val(data[0]);
        $('#name').val(data[1]);
        $('#email').val(data[2]);
        $('#contact').val(data[3]);
        $('#des_pos').val(data[4]);
        $('#status').val(data[5]);

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
        <h1 class="modal-title fs-5" id="insertdataLabel">Add New Applicant</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="listrecruits.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        
        <div class="form-group mb-3">
          <label for="">Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Enter Email Address" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Contact #</label>
          <input type="text" name="contact" class="form-control" placeholder="Enter Contact Number" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Desired Position</label>
          <input type="text" name="des_pos" class="form-control" placeholder="Enter Desired Position" required>
        </div>

        <div class="form-group mb-3">
        <label for="">Status</label>
        <br>
        <select class="form-select" aria-label="Default select example" name="status">
        <option value="Pending">Pending</option>
        <option value="Hired">Hired</option>
        <option value="Rejected">Rejected</option>
        </select>
        </div>

        <div class="form-group mb-3">
          <label for="">Resume</label>
          <input type="file" name="choosefile" class="form-control" accept="application/pdf">
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
        <h1 class="modal-title fs-5" id="editmodalLabel">Edit Applicant Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="listrecruits.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        
        <input type="hidden" name="app_id" id="app_id">
        <div class="form-group mb-3">
          <label for="">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Contact #</label>
          <input type="text" name="contact" id="contact" class="form-control" placeholder="Enter Contact Number" required>
        </div>

        <div class="form-group mb-3">
          <label for="">Desired Position</label>
          <input type="text" name="des_pos" id="des_pos" class="form-control" placeholder="Enter Desired Position" required>
        </div>

        <div class="form-group mb-3">
        <label for="">Status</label>
        <br>
        <select class="form-select" aria-label="Default select example" name="status" id="status">
        <option value="Pending">Pending</option>
        <option value="Hired">Hired</option>
        <option value="Rejected">Rejected</option>
        </select>
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
    <!-- EDIT RESUME MODAL -->

    <div class="modal fade" id="resumemodal" tabindex="-1" aria-labelledby="resumemodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="resumemodalLabel">Update Resume</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="listrecruits.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        
        <input type="hidden" name="app_id2" id="app_id2">
        
        <div class="form-group mb-3">
          <label for="">Resume</label>
          <input type="file" name="choosefile" class="form-control" accept="application/pdf" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="editresume" class="btn btn-warning">Update</button>
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
        <h1 class="modal-title fs-5" id="deletemodalLabel">Delete Data?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="listrecruits.php" method="POST">
      <div class="modal-body">
        
        <input type="hidden" name="app_id3" id="app_id3">
        
        <div class="form-group mb-3">
          <label for="">Do You Want To Delete This Data?</label>
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