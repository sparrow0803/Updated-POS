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
include_once("connection/connection.php");
$pdo = connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplierName = $_POST['supplier_name'];
    $storeName = $_POST['store_name'];
    $note = $_POST['note'];
    $date = $_POST['expected_date'];
    $itemsData = json_decode($_POST['items_data'], true);

    try {
        
        $pdo->beginTransaction();

        
        $sql = "INSERT INTO purchase_order (supplier_name, store_name, note, expected_date, item_name, quantity, purchase_cost, amount) VALUES (:supplier_name, :store_name, :note, :expected_date, :item_name, :quantity, :purchase_cost, :amount)";
        $stmt = $pdo->prepare($sql);
        foreach ($itemsData as $item) {
            $stmt->execute([
                ':supplier_name' => $supplierName,
                ':store_name' => $storeName,
                ':note' => $note,
                ':expected_date' => $date,
                ':item_name' => $item['name'],
                ':quantity' => $item['quantity'],
                ':purchase_cost' => $item['purchaseCost'],
                ':amount' => $item['amount']
            ]);
        }

        
        $pdo->commit();

        
        header('Location: inventory_purchase_order.php?success=true');
        exit();
    } catch (Exception $e) {
        
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PURCHASE ORDER</title>
  <link rel="stylesheet" href="styles/inventory_purchase_order.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
    <?php } ?>

    <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <div class="dropdown">
          <a href="inventory.php" class="dropbtn">
              <i class="bx bxs-package icon"></i> 
              <span class="text nav-text"> Inventory </span>
          </a>
          <div class="dropdown-content" id="myDropdown">
              <a class="content" href="../pos_inventory/inventory_purchase_order.php">Purchase Order</a>
              <a class="content" href="../pos_inventory/inventory_add.php">Import Items</a>
              <a class="content" href="../pos_inventory/inventorytransferorder.php">Transfer Orders</a>
              <a class="content" href="../pos_inventory/inventorycounts.php">Inventory Counts</a>
              <a class="content" href="../pos_inventory/inventoryreport.php">Inventory Report</a>
              <a class="content" href="../pos_inventory/inventoryhistory.php">Inventory History</a>
          </div>
      </div>
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
  <?php } ?>

  <?php if($userrole == "Administrator" || $userrole == "Owner" || $userrole == "Manager"){?>
    <li class="nav-link">
      <a href="/updated pos/sales/sales.php">
      <i class="bx bx-trending-up icon"></i> 
      <span class="text nav-text"> Sales </span>
      </a>
    </li>
  <?php } ?>

    <div class="bottom-content">
    <hr style="height: 1px; opacity: 40%; border-width: 0; margin-top: 200px; background-color: #BFEA7C;"/>

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
    <h1><i class="bx bxs-package icon">&nbsp</i> PURCHASE ORDER </h1>
    <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>

  <div class="container">
    <div class="another-box">
      <form id="purchase-form" action="" method="POST">
        <input class="supplier" type="text" id="supplier_name" name="supplier_name" placeholder="Supplier Name..." required>
        <input class="supplier" type="text" id="store_name" name="store_name" placeholder="Store Name..." required>
        <input class="supplier" type="text" id="note" name="note" placeholder="Note..." required>
        <input class="flatpickr" type="text" id="datepicker" name="expected_date" placeholder="Select Date..." required>
        <button type="submit" class="add-button">Submit</button>
        <select id="dropdown">
          <option value="">Select an Item</option>
          <option value="1">Fudgee Barr</option>
          <option value="2">Rebisco</option>
          <option value="3">Whatta Tops</option>
          <option value="4">Coca-Cola</option>
          <option value="5">Banana</option>
        </select>
        <input class="quantity-input" type="number" id="quantity-input" name="quantity-input" placeholder="Quantity..." required>
        <button type="button" class="add-item-button">Add</button>
     
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Purchase Cost</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <input type="hidden" id="items-data" name="items_data">
      </form>
    </div>
  </div>

  <div class="second-container">
    <div class="second-box">
      <form> 
      <input class="flatpickr" type="text" id="searchInput" name="search_date" placeholder="Select Date..." required>
      <button type="submit" class="add-button" id="searchButton">Search</button>
      <button type="submit" class="add-button" id="clearButton">Clear</button><br>
      <h4 class="headd">Recently Order</h4>
      </form> 

        <div class="table-container2">
          <table class="table1">
            <tr>
              <th>Order No.</th>
              <th>Supplier Name</th>
              <th>Store Name</th>
              <th>Note</th>
              <th>Date</th>
              <th>Expected Date</th>
              <th>Item</th>
              <th>Quantity</th>
              <th>Purchase Cost</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>

            <?php
            $sql = "SELECT * FROM purchase_order";
            $stmt = $pdo->query($sql);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>"; 
              echo "<td>" . $row['supplier_name'] . "</td>";
              echo "<td>" . $row['store_name'] . "</td>";
              echo "<td>" . $row['note'] . "</td>";
              echo "<td>" . $row['date'] . "</td>";
              echo "<td>" . $row['expected_date'] . "</td>";
              echo "<td>" . $row['item_name'] . "</td>";
              echo "<td>" . $row['quantity'] . "</td>";
              echo "<td>" . $row['purchase_cost'] . "</td>";
              echo "<td>" . $row['amount'] . "</td>";
              echo "<td>" . ($row['status'] == 0 ? 'Pending' : 'Received') . "</td>";
              echo "<td><button class='update-status-button' data-order-id='" . $row['id'] . "'>Update Status</button></td>";
              echo "</tr>";
          }
          
            ?>
          </table>
        </div> 

    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>

document.getElementById("searchButton").addEventListener("click", function(event) {
    event.preventDefault(); 
    searchTable();
});

document.getElementById("clearButton").addEventListener("click", function(event) {
    event.preventDefault(); 
    clearSearch();
});

flatpickr("#searchInput", {
        dateFormat: "Y-m-d",
    });

function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector(".table1");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function clearSearch() {
    var input, filter, table, tr, i;
    input = document.getElementById("searchInput");
    input.value = "";
    table = document.querySelector(".table1");
    tr = table.getElementsByTagName("tr");

 
    for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "";
    }
}

const body = document.querySelector("body"),
sidebar = body.querySelector(".sidebar"),
toggle = body.querySelector(".toggle");
toggle.addEventListener("click", () => {
sidebar.classList.toggle("close");
});
if (window.history.replaceState) {
window.history.replaceState(null, null, window.location.href);
}
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.querySelector('.add-item-button');
    const itemsDataInput = document.getElementById('items-data');
    const tableBody = document.querySelector('tbody');
    const totalInput = document.getElementById('total');

    addButton.addEventListener('click', function() {
    const dropdown = document.getElementById('dropdown');
    const quantityInput = document.getElementById('quantity-input');
    const selectedItemValue = dropdown.value;
    const selectedItemText = dropdown.options[dropdown.selectedIndex].text;
    const quantity = parseFloat(quantityInput.value);

    if (!selectedItemValue || isNaN(quantity) || quantity <= 0) {
        alert('Please select a valid item and enter a valid quantity.');
        return;
    }

    let purchaseCost = 0;
    switch(selectedItemValue) {
        case "1":
            purchaseCost = 10.00; // Fudgee Bar
            break;
        case "2":
            purchaseCost = 15.00; // Rebisco
            break;
        case "3":
            purchaseCost = 15.00; // Whatta Tops
            break;
        case "4":
            purchaseCost = 25.00; // Coca-Cola
            break;
        case "5":
            purchaseCost = 20.00; // Banana
            break;
        default:
            purchaseCost = 0;
    }

    const amount = quantity * purchaseCost;
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${selectedItemText}</td>
        <td>${quantity}</td>
        <td>${purchaseCost.toFixed(2)}</td>
        <td>${amount.toFixed(2)}</td>
        <td><button type="button" class="delete-button">Delete</button></td>
    `;
    tableBody.appendChild(newRow);

    const deleteButton = newRow.querySelector('.delete-button');
    deleteButton.addEventListener('click', function() {
        newRow.remove();
        calculateTotal();
    });

    calculateTotal(); 
    updateItemsData();
});


    function calculateTotal() {
        const amountCells = document.querySelectorAll('tbody td:nth-child(4)');
        let totalAmount = 0;
        amountCells.forEach(cell => {
            totalAmount += parseFloat(cell.textContent);
        });
        totalInput.value = totalAmount.toFixed(2);
    }

    function updateItemsData() {
        const rows = tableBody.querySelectorAll('tr');
        const items = [];

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const item = {
                name: cells[0].textContent,
                quantity: cells[1].textContent,
                purchaseCost: cells[2].textContent,
                amount: cells[3].textContent
            };
            items.push(item);
        });

        itemsDataInput.value = JSON.stringify(items);
    }

    document.getElementById('purchase-form').addEventListener('submit', function(event) {
        updateItemsData();
    });

    flatpickr("#datepicker", {
        enableTime: false,
        dateFormat: "Y-m-d",
    });

    <?php if (isset($_GET['success']) && $_GET['success'] == 'true') { ?>
        document.getElementById('success-message').style.display = 'block';
    <?php } ?>
});

document.addEventListener('DOMContentLoaded', function() {
   
    const updateStatusButtons = document.querySelectorAll('.update-status-button');
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = button.dataset.orderId;
            updateStatus(orderId);
        });
    });

   
    function updateStatus(orderId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    
                    console.log('Status updated successfully');
                    window.location.reload();
                } else {
                    
                    console.error('Error updating status');
                }
            }
        };
        xhr.send('order_id=' + orderId);
    }
});
</script>
</body>
</html>
