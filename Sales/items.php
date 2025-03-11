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
require_once 'connect/connect.php';


try{
    $sqlQuery = "SELECT product, quantity, SUM(quantity) as total_quantity FROM ticket GROUP BY product ORDER BY total_quantity DESC LIMIT 3";
    $stmt = $pdoConnect->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="card--container2">


    <div class="top-selling-products">
        <h2>Top 3 Best Selling Items</h2>
        <table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Total Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product']); ?></td>
                <td><?php echo htmlspecialchars($row['total_quantity']); ?></td>
            </tr>
        <?php } 
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            }

        ?>
    </tbody>
</table>
<button class="export-button" onclick="exportReport()">Export</button>
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
 //Function to generate the report
  function generateReport() {
      renderTaxReport();
      document.getElementById("taxReport").style.display = "table";
      document.getElementById("totalsBox").style.display = "block";
      document.getElementById("pagination").style.display = "block";
      currentPage = 1; // Reset current page to 1
      updatePagination();
  }

  // Function to export the report
  function exportReport() {
    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Product,Total Quantity\n";

    // Loop through the table rows and add each row to CSV content
    var rows = document.querySelectorAll("table tbody tr");
    rows.forEach(function(row) {
        var columns = row.querySelectorAll("td");
        var rowData = [];
        columns.forEach(function(column) {
            rowData.push(column.textContent);
        });
        csvContent += rowData.join(",") + "\n";
    });

    // Create a download link for the CSV file
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Popular_Products.csv");
    document.body.appendChild(link);

    // Trigger the download
    link.click();
}
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="scripts.js"></script>

</br>
</section>
</body>
</html>
