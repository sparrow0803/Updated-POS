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

$stmt1 = $pdo->prepare('SELECT * FROM access');
$stmt1->execute();
$result1 = $stmt1->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HUMAN RESOURCES</title>
  <link rel="stylesheet" href="styles/employee.css" />
  <link rel="stylesheet" href="styles/inventory.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DJWFE06Kkp75z54hMPnAeYH/9z/7a9rZsrJ/aHpEXZ8Kl4nQq1t4mSQp3ePFFm49" crossorigin="anonymous">
</head>

<body>
<nav class="sidebar close">
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

<section class="home" style="position: fixed;">
  <div class="userheader">
  <h1><i class="bx bx-home-alt icon">&nbsp</i> HUMAN RESOURCES</h1>
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
  <br>
          
  <div class="container-xl">
    <div class="button-container">
      <a type="button" class="btn btn-warning" href="/updated pos/hr/accessreg.php">+ Add Role </a>
      <div id="deleteButtonContainer"></div>
    </div>
  </div>

  <br><br>

  <div class="container">
    <!-- Content here -->
    <form id="deleteForm" action="delete_roles.php" method="post">
      <input type="hidden" id="selectedRoles" name="selectedRoles">
        <table id="myTable" class="table table-bordered border-warning">
            <thead>
                <tr class="table-light">
                    <th>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" onchange="checkAll(); toggleDeleteButton('flexCheckDefault')">
                            <label class="form-check-label" for="flexCheckDefault">
                                Roles
                            </label>
                        </div>
                    </th>
                    <th> Employee</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result1 as $row1) { ?>
                <tr>
                    <td>
                        <input class="form-check-input" type="checkbox" value="<?= $row1['id']; ?>" id="checkbox<?= $row1['id']; ?>" onchange="toggleDeleteButton('checkbox<?= $row1['id']; ?>')">
                        <label class="form-check-label" for="checkbox<?= $row1['id']; ?>"><?= $row1['role'];?></label>
                    </td>
                    <td><?= $row1['employee'];?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
  </form>
  </div>

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
    function checkAll() {
        var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]');
        var checkAllCheckbox = document.getElementById('flexCheckDefault');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = checkAllCheckbox.checked;
        });
    }

    // Updated toggleDeleteButton function to handle dynamic creation of delete button
function toggleDeleteButton(checkboxId) {
    var checkbox = document.getElementById(checkboxId);
    var container = document.getElementById('deleteButtonContainer');
    var deleteButton = document.getElementById('deleteButton');

    if (checkbox.checked && !deleteButton) {
        deleteButton = document.createElement('button');
        deleteButton.setAttribute('type', 'button');
        deleteButton.setAttribute('class', 'btn btn-danger');
        deleteButton.setAttribute('id', 'deleteButton'); // Add an ID to the button
        deleteButton.innerHTML = 'Delete';

        deleteButton.addEventListener('click', function() {
            // Open the modal
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });

        container.appendChild(deleteButton);
    } else if (!checkbox.checked && deleteButton) {
        container.removeChild(deleteButton);
    }

    // If the checkbox being unchecked is not the flexCheckDefault checkbox, uncheck flexCheckDefault
    if (!checkbox.checked && checkboxId !== 'flexCheckDefault') {
        document.getElementById('flexCheckDefault').checked = false;
    }
}


    function deleteRoles() {
        // Perform delete action here
        var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                // Delete the role associated with this checkbox
                // For demonstration, we will just log the role name to the console
                console.log('Deleting role:', checkbox.parentNode.nextElementSibling.textContent.trim());
            }
        });

        // Hide the modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    }

</script>

<script>
    function toggleDeleteButton(checkboxId) {
        var checkbox = document.getElementById(checkboxId);
        var container = document.getElementById('deleteButtonContainer');
        var deleteButton = document.getElementById('deleteButton');
        var selectedRolesInput = document.getElementById('selectedRoles');

        if (checkbox.checked && !deleteButton) {
            deleteButton = document.createElement('button');
            deleteButton.setAttribute('type', 'button');
            deleteButton.setAttribute('class', 'btn btn-danger');
            deleteButton.setAttribute('id', 'deleteButton'); // Add an ID to the button
            deleteButton.innerHTML = 'Delete';

            deleteButton.addEventListener('click', function() {
                // Open the modal
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });

            container.appendChild(deleteButton);
        } else if (!checkbox.checked && deleteButton) {
            container.removeChild(deleteButton);
        }

        // Update the hidden input field with the IDs of the checked checkboxes
        var selectedRoles = [];
        var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]:checked');
        checkboxes.forEach(function(checkbox) {
            selectedRoles.push(checkbox.value);
        });
        selectedRolesInput.value = selectedRoles.join(',');
    }

    function deleteRoles() {
        // Submit the form to delete the roles
        document.getElementById('deleteForm').submit();
    }
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
      
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-cWnAcdXoZLJbFdP0tgFN2K5ZvqAXzC4QWt6+9ObJNQfsaFV0P7zkkwxJz1IqMl7" crossorigin="anonymous"></script>
  
</br>
</section>

<script>
    function checkAll() {
        var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]');
        var checkAllCheckbox = document.getElementById('flexCheckDefault');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = checkAllCheckbox.checked;
        });
    }

function toggleDeleteButton(checkboxId) {
    var checkbox = document.getElementById(checkboxId);
    var container = document.getElementById('deleteButtonContainer');
    var deleteButton = document.getElementById('deleteButton');

    if (checkbox.checked && !deleteButton) {
        deleteButton = document.createElement('button');
        deleteButton.setAttribute('type', 'button');
        deleteButton.setAttribute('class', 'btn btn-danger');
        deleteButton.setAttribute('id', 'deleteButton'); // Add an ID to the button
        deleteButton.innerHTML = 'Delete';

        // Attach event listener to the "Delete" button
        deleteButton.addEventListener('click', function() {
            // Open the modal
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();

            // Set the selected roles when the modal is shown
            var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]:checked');
            var selectedRoles = [];
            checkboxes.forEach(function(checkbox) {
                selectedRoles.push(checkbox.value);
            });
            document.getElementById('selectedRoles').value = selectedRoles.join(',');
        });

        container.appendChild(deleteButton);
    } else if (!checkbox.checked && deleteButton) {
        container.removeChild(deleteButton);
    }

    // If the checkbox being unchecked is not the flexCheckDefault checkbox, uncheck flexCheckDefault
    if (!checkbox.checked && checkboxId !== 'flexCheckDefault') {
        document.getElementById('flexCheckDefault').checked = false;
    }
}

deleteButton.addEventListener('click', function() {
    // Open the modal
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();

    // Set the selected roles when the modal is shown
    var checkboxes = document.querySelectorAll('#myTable input[type="checkbox"]:checked');
    var selectedRoles = [];
    checkboxes.forEach(function(checkbox) {
        selectedRoles.push(checkbox.value);
    });
    document.getElementById('selectedRoles').value = selectedRoles.join(',');
});

function deleteRoles() {
    // Get the selected roles from the hidden input field
    var selectedRoles = document.getElementById('selectedRoles').value;

    // Send an AJAX request to delete the selected roles
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_roles.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle successful deletion
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Reload the page to reflect the changes
                location.reload();
            } else {
                // Handle deletion error
                alert('An error occurred while deleting the roles.');
            }
        } else {
            // Handle server error
            alert('An error occurred while communicating with the server.');
        }
    };
    xhr.onerror = function() {
        // Handle network error
        alert('A network error occurred while deleting the roles.');
    };
    xhr.send('selectedRoles=' + selectedRoles);
}
</script>

<script>
  $(document).ready(function() {
    $('#delete-btn').on('click', function() {
      var checkboxes = $('input[type="checkbox"]:checked');
      var ids = [];
      checkboxes.each(function() {
        ids.push($(this).val());
      });
      $.ajax({
        type: 'POST',
        url: 'delete_roles.php',
        data: {ids: ids},
        success: function(data) {
          location.reload();
        }
      });
    });
  });
</script>


</body>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Roles</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="deleteForm" action="delete_roles.php" method="post">
        <input type="hidden" id="selectedRoles" name="selectedRoles">
        <div class="modal-body">
         <p>Are you sure you want to delete the selected roles?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="delete-btn" onclick="deleteRoles()">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
</html>