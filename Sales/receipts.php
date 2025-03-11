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
  <title>SALES</title>
  <link rel="stylesheet" href="styles/sales.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link href="font-awesome.css" rel="stylesheet" />
</head>

<body>
<nav class="sidebar close" style="overflow: hidden;">
<header>
<div class="image-text">
  <span class="image">
  <img src="img/logo2.png" alt="logo" style="width: 75%; margin-top: -20px;"/>
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
  <h1><i class="bx bx-trending-up icon">&nbsp</i> SALES</h1>
  <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  <div class="dropdown">
        <button class="dropbtn"><i class="bx bx-chevron-down dropdown-icon"></i></button>
        <div class="dropdown-content">
            <a href="../Sales/sales.php">Sales Trend</a>
            <a href="../Sales/shift.php">Shifts</a>
            <a href="../Sales/tax.php">Tax Report</a>
            <a href="../Sales/items.php">Popular Items</a>
            <a href="../Sales/receipts.php">Receipts History</a>

        </div>
    </div>
  </div>


          
<!----- INPUT CODE HERE :) ----->

<div class="card--container2" >
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
  $stmt = $pdo->prepare('SELECT * from invoice');
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <tr>
  <td><?= $row['invoice_id']; ?></td>
  <td><?= $row['customer']; ?></td>
  <td><?= $row['date']. '  ' .$row['time']; ?></td>
  <td>
    <button type="submit" style="background-color: #F4CE14; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;" name="view" value="<?php echo $row['unique_id']; ?>">View Receipt</button>
                          
  </td>
  </tr>
  <?php } ?>
</table>
</form>

    </div>
    </div>

</div>


</section>
<script>
/*----------------SIDEBAR JS------------------*/
    const body = document.querySelector("body"),
    sidebar = body.querySelector(".sidebar"),
    toggle = body.querySelector(".toggle");
    toggle.addEventListener("click", () =>{
    sidebar.classList.toggle("close");
    });
    if(window.history.replaceState)
    { window.history.replaceState(null, null, window.location.href); }


    document.addEventListener("DOMContentLoaded", function() {
    var tabLinks = document.querySelectorAll('.nav-tabs li a');

    tabLinks.forEach(function(tabLink) {
        tabLink.addEventListener('click', function(event) {
            event.preventDefault();

            var tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach(function(pane) {
                pane.classList.remove('active', 'in');
            });

            tabLinks.forEach(function(link) {
                link.parentElement.classList.remove('active');
            });

            this.parentElement.classList.add('active');

            var targetPaneId = this.getAttribute('href');
            var targetPane = document.querySelector(targetPaneId);
            targetPane.classList.add('active', 'in');
        });
    });
});


document.addEventListener("DOMContentLoaded", function() {
    var previewLinks = document.querySelectorAll(".preview-link");
    
    previewLinks.forEach(function(link) {
        link.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default link behavior
            
            var imageURL = link.getAttribute("data-image-url");
            displayPicture(imageURL);
        });
    });
});

function displayPicture(imageURL) {
    // Create the modal container
    var modal = document.createElement("div");
    modal.classList.add("modal");

    // Create the modal content
    var modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");

    // Create the image element
    var image = document.createElement("img");
    image.src = imageURL;
    image.alt = "Receipt Image";

    // Create the close button
    var closeButton = document.createElement("span");
    closeButton.classList.add("close");
    closeButton.innerHTML = "&times;";
    closeButton.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Append the image and close button to the modal content
    modalContent.appendChild(image);
    modalContent.appendChild(closeButton);

    // Append the modal content to the modal container
    modal.appendChild(modalContent);

    // Append the modal container to the document body
    document.body.appendChild(modal);

    // Show the modal
    modal.style.display = "block";
}


  
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="scripts.js"></script>

</br>
</section>
</body>
</html>
