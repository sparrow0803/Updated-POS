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
<div class="tab-pane fade active in" id="sales">
        <div class="card--container2">
            <h1>Sales Trend</h1>
            <div class="dropdown-container">
                
                <label for="daterange">Date Range:</label>
                <select id="daterange">
                    <option value="day">Day</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                </select>
            </div>
            <div class="dropdown-container">

                <label for="graphType">Select Graph Type:</label>
                <select id="graphType">
                    <option value="line">Line Graph</option>
                    <option value="bar">Bar Graph</option>
                </select>
            </div>
                
            <div class="graph-container">
                <canvas id="lineChart"></canvas>
            </div>
            
            <div class="graph-container">
                <canvas id="barChart"></canvas>
            </div>
<br />
            <h3>Sales Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Sale</th>
                    </tr>
                </thead>
                <tbody>
                <?php

$servername = "localhost";
$dbname = "pos";
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $conn->prepare("SELECT * FROM invoice");
    $stmt->execute();

  

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['date']}</td><td>{$row['totalamount']}</td></tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


$conn = null;
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

<script>
  // Sample tax report data
  var taxReportData = [
      // No data initially
  ];

  var currentPage = 1;
  var rowsPerPage = 10; // Assuming 10 rows per page

  // Function to render tax report data
  function renderTaxReport() {
      var tbody = document.getElementById("taxReportBody");
      var totalTaxableSales = 0;
      var totalTaxAmount = 0;

      tbody.innerHTML = ""; // Clear previous data

      // Show "No Data to Display" message if tax report data is empty
      if (taxReportData.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="no-data">No Data to Display</td></tr>';
      }

      // Update total fields
      document.getElementById("totalTaxableSales").textContent = "₱" + totalTaxableSales.toFixed(2);
      document.getElementById("totalNetSales").textContent = "₱" + (totalTaxableSales + totalTaxAmount).toFixed(2);
  }

  // Function to handle page navigation
  function prevPage() {
      if (currentPage > 1) {
          currentPage--;
          renderTaxReport();
          updatePagination();
      }
  }

  function nextPage() {
      var totalPages = Math.ceil(taxReportData.length / rowsPerPage);
      if (currentPage < totalPages) {
          currentPage++;
          renderTaxReport();
          updatePagination();
      }
  }

  function updatePagination() {
      var totalPages = Math.ceil(taxReportData.length / rowsPerPage);
      document.getElementById("currentPage").textContent = currentPage;
      document.getElementById("totalPages").textContent = totalPages;
  }

  // Function to generate the report
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
    csvContent += "Date,Total Sale\n";

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
    link.setAttribute("download", "sales_report.csv");
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
