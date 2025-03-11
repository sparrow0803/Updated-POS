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

        </div>
    </div>
  </div>

<!----- INPUT CODE HERE :) ----->


    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        h3, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        td {
            color: #333;
        }
        .total-row td {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        select, input[type="date"], input[type="checkbox"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .export-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: goldenrod;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <h3>Tax Report</h3>
        <div class="form-group">
            <label for="dateRangeSelect">Date Range:</label>
            <select id="dateRangeSelect" onchange="updateDateRange()">
                <option value="1">Last 7 days</option>
                <option value="2">Last 30 days</option>
                <option value="3">Last 90 days</option>
            </select>
        </div>
        <div class="form-group">
            <label for="reportDate">Report Date:</label>
            <input type="date" id="reportDate">
        </div>
        <div class="form-group">
            <label for="allEmployees">All Employees:</label>
            <input type="checkbox" id="allEmployees">
        </div>
        <button onclick="generateReport()">Generate Report</button>

        <table id="taxReport" style="display: none;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Tax rate</th>
                    <th>Taxable sales</th>
                    <th>Tax amount</th>
                </tr>
            </thead>
            <tbody id="taxReportBody">
                <?php
                try {
                    $sql = "SELECT date, vat, vat_amount, totalamount FROM invoice LIMIT 3";
                    $stmt = $pdoConnect->prepare($sql);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($rows)) {
                        foreach ($rows as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["date"]); ?></td>
                                <td><?php echo htmlspecialchars($row["vat"]); ?></td>
                                <td><?php echo htmlspecialchars($row["vat_amount"]); ?></td>
                                <td><?php echo htmlspecialchars($row["totalamount"]); ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='4'>No data available.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div id="pagination" style="display: none;">
            <button id="prevPage" onclick="prevPage()">Previous</button>
            <span>Page <span id="currentPage">1</span> of <span id="totalPages">1</span></span>
            <button id="nextPage" onclick="nextPage()">Next</button>
        </div>
        <button class="export-button" onclick="exportTaxReport()" style="display: none;">Export Tax Report</button>
    </div>
    
    <div class="box" id="totalsBox" style="display: none;">
        <h2>Totals</h2>
        <table>
            <?php
            try {
                $Netincome = 0;
                $sql = "SELECT totalamount FROM invoice";
                $stmt = $pdoConnect->prepare($sql);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $Netincome += $row['totalamount'];
                }
            ?>
                <tr class="total-row">
                    <td colspan="5">Net Income</td>
                    <td id="totalTaxableSales"><?php echo "P" . number_format($Netincome, 2); ?></td>
                </tr>
            <?php
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
            <?php
            try {
                $totalTaxableSales = 0;
                $sql = "SELECT vat_amount FROM invoice";
                $stmt = $pdoConnect->prepare($sql);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $totalTaxableSales += $row['vat_amount'];
                }
            ?>
                <tr class="total-row">
                    <td colspan="5">Total Taxable Sales</td>
                    <td id="totalTaxableSales"><?php echo "P" . number_format($totalTaxableSales, 2); ?></td>
                </tr>
            <?php
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
            <?php
            $totalSale = $Netincome - $totalTaxableSales;
            ?>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5">Total Net Sales</td>
                    <td id="totalNetSales"><?php echo "P" . number_format($totalSale, 2); ?></td>
                </tr>
            </tfoot>
        </table>
        <button class="export-button" onclick="exportTotals()" style="display: none;">Export Totals</button>
    </div>
</div>

<script>
    var taxReportData = [];
    var currentPage = 1;
    var rowsPerPage = 10;

    function updateDateRange() {
        var select = document.getElementById("dateRangeSelect");
        var selectedOption = select.options[select.selectedIndex].value;

        var endDate = new Date();
        var startDate = new Date();
        if (selectedOption === "1") {
            startDate.setDate(startDate.getDate() - 7);
        } else if (selectedOption === "2") {
            startDate.setDate(startDate.getDate() - 30);
        } else if (selectedOption === "3") {
            startDate.setDate(startDate.getDate() - 90);
        }

        document.getElementById("reportDate").valueAsDate = endDate;
    }

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

    function generateReport() {
        document.getElementById("taxReport").style.display = "table";
        document.getElementById("totalsBox").style.display = "block";
        document.getElementById("pagination").style.display = "block";
        document.querySelector(".export-button[onclick='exportTaxReport()']").style.display = "block";
        document.querySelector(".export-button[onclick='exportTotals()']").style.display = "block";
    }

    function exportTaxReport() {
        var csvContent = "data:text/csv;charset=utf-8,";
        
        // Export Tax Report Table
        csvContent += "Tax Report\n";
        csvContent += "Date,Tax rate,Taxable sales,Tax amount\n";
        var taxTableRows = document.querySelectorAll("#taxReport tbody tr");
        taxTableRows.forEach(function(row) {
            var rowData = [];
            row.querySelectorAll("td").forEach(function(cell) {
                rowData.push(cell.textContent.trim());
            });
            csvContent += rowData.join(",") + "\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "tax_report.csv");
        document.body.appendChild(link);
        link.click();
    }

    function exportTotals() {
        var csvContent = "data:text/csv;charset=utf-8,";
        
        // Export Totals Table
        csvContent += "Total Tax\n";
        csvContent += "Description,Amount\n";
        var totalsTableRows = document.querySelectorAll("#totalsBox table tr");
        totalsTableRows.forEach(function(row) {
            var rowData = [];
            row.querySelectorAll("td").forEach(function(cell) {
                rowData.push(cell.textContent.trim());
            });
            csvContent += rowData.join(",") + "\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "totaltax.csv");
        document.body.appendChild(link);
        link.click();
    }

    window.onload = updateDateRange;
</script>
</body>
</html>
