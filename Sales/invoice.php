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
  use PHPMAILER\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'phpmailer/src/Exception.php';
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';
  $unique_id = $_SESSION['unique_id'];

    $stmt2 = $pdo->prepare("SELECT * from invoice where unique_id = :unique_id");
	$stmt2->bindParam(':unique_id', $unique_id);
    $stmt2->execute();
	$result2 = $stmt2->fetchAll();
	foreach($result2 as $row2){
	}

    $prod='';
  $stmt3 = $pdo->prepare('SELECT * from ticket where unique_id=:unique_id');
  $stmt3->bindParam(':unique_id', $unique_id);
  $stmt3->execute();
  $result3 = $stmt3->fetchAll();
  foreach($result3 as $row3){
    $prod .= '<p>x' .$row3['quantity']. ' '.$row3['product']. ' ₱'.$row3['price']. ' ('.$row3['discount']. ')'. '</p>';
  }

    if(isset($_POST['print']))
    {
        $_SESSION['unique_id'] = $unique_id;
        header('location:receipt.php');
    }

    if(isset($_POST['send'])){

    $body='';
    $body.= '
    <html>
    <head></head>
    <body>
    
    <p>BANANA IS YELLOW</p>
    <p>DHVSU</p>
    <p>Bacolor, Pampanga, 2001</p>
    <p>Phone: 0999-999-9999</p>
    <hr>

    <p>INVOICE</p>
    <p>Invoice ID: ' .$row2['invoice_id']. '</p>
    <p>Date: ' .$row2['date']. '</p>
    <p>Time: ' .$row2['time']. '</p>
    <p>Bill To: ' .$row2['customer']. '</p>
    <hr>'

    .$prod.'

    <hr>

    <p>Subtotal: ' .'₱'.$row2['subtotal']. '</p>
    <p>Discount: ' .'₱'.$row2['discount']. '</p>
    <p>VAT: ' .$row2['vat'].'%'. '</p>
    <p>VAT Amount: ' .'₱'.$row2['vat_amount']. '</p>
    <p>Total Amount: ' .'₱'.$row2['totalamount']. '</p>
    <p>Amount Paid: ' .'₱'.$row2['amountpaid']. '</p>
    <p>Change: ' .'₱'.$row2['cash_change']. '</p>
    <p>MOP: ' .$row2['mop']. '</p>

    <hr>

    </body>
    </html>
    ';

    $email = $_POST['email'];
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '2021305373@dhvsu.edu.ph';
    $mail->Password = 'dgbufzucpktlppve';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('2021305373@dhvsu.edu.ph','BANANA IS YELLOW');
    $mail->addAddress($email);
    $mail->addReplyTo('2021305373@dhvsu.edu.ph','BANANA IS YELLOW');

    $mail->isHTML(true);
    $mail->Subject = 'Invoice';
    $mail->Body = $body;

    if($mail->send()){
      $_SESSION['success'] = "Invoice Sent Succesfully  ";
    }
    else{
      $_SESSION['fail'] = "Failed To Send Email  ";
    }
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
  <link rel="stylesheet" href="styles/cashier.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
<link rel="stylesheet" href="styles/sales.css" />
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
            <a href="../Sales/export.php">Reports Export</a>
        </div>
    </div>
  </div>


          
<!----- INPUT CODE HERE :) ----->



<div class="p-3">

<div class="container">
<div class="p-3 mb-2  text-white" style="background-color: #F4CE14">
<p class="fs-2 text-center">RECEIPT</p>
</div>
</div>

<div class="container">
<div class="p-3 mb-2 bg-body-tertiary">
<div class="row">

<div class="col">
<p class="fs-5">BANANA IS YELLOW</p>
<p class="fs-6">DHVSU</p>
<p class="fs-6">Bacolor, Pampanga, 2001</p>
<p class="fs-6">Phone: 0999-999-9999</p>
</div>

<div class="col">
<p class="fs-5">INVOICE</p>
<p class="fs-6">Invoice ID: <?php echo $row2['invoice_id']; ?></p>
<p class="fs-6">Date: <?php echo $row2['date']; ?></p>
<p class="fs-6">Time: <?php echo $row2['time']; ?></p>
<p class="fs-6">Bill To: <?php echo $row2['customer']; ?></p>
</div>
</div>
<hr>

<div class="row">

<div class="col">
<p class="fs-6">Product</p>
<?php 
  $stmt = $pdo->prepare('SELECT * from ticket where unique_id=:unique_id');
  $stmt->bindParam(':unique_id', $unique_id);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <p class="fs-6">x<?php echo $row['quantity']. ' ' .$row['product']; ?></p>
  <?php } ?>
</div>

<div class="col">
<p class="fs-6">Price (Discount)</p>
<?php 
  $stmt = $pdo->prepare('SELECT * from ticket where unique_id=:unique_id');
  $stmt->bindParam(':unique_id', $unique_id);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
  ?>
  <p class="fs-6"><?php echo '₱'.$row['price']. ' (' .$row['discount']. ')'; ?></p>
  <?php } ?>
</div>

</div>
<hr>

<div class="row">
    <div class="col"></div>
<div class="col">
<p class="fs-6">Subtotal: <?php echo '₱'.$row2['subtotal']; ?></p>
<p class="fs-6">Discount: <?php echo '₱'.$row2['discount']; ?></p>
<p class="fs-6">VAT: <?php echo $row2['vat'].'%'; ?></p>
<p class="fs-6">VAT Amount: <?php echo '₱'.$row2['vat_amount']; ?></p>
<p class="fs-6">Total Amount: <?php echo '₱'.$row2['totalamount']; ?></p>
<p class="fs-6">Amount Paid: <?php echo '₱'.$row2['amountpaid']; ?></p>
<p class="fs-6">Change: <?php echo '₱'.$row2['cash_change']; ?></p>
<p class="fs-6">Mode of Payment: <?php echo $row2['mop']; ?></p>
</div>
</div>


        <br>

        
        <div class="modal-footer">
        <?php
              if (isset($_SESSION['success'])) {
              echo '<div class="badge text-bg-warning text-wrap" style="width: 15rem;">'
              .$_SESSION['success'].
              '</div>'; }
              unset($_SESSION['success']);

              if (isset($_SESSION['fail'])) {
                echo '<div class="badge text-bg-danger text-wrap" style="width: 15rem;">'
                .$_SESSION['fail'].
                '</div>'; }
                unset($_SESSION['fail']);
                ?>

                </div>
                <form method="POST">
      <div class="btn-group" style="width:100%">
        
        <button type="submit" name="print" class="btn btn-warning">Generate Receipt</button>
        </div>
        </form>

    
   
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



// Function to export the report
  function exportReport() {
    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Invoice, Date, Time, Customer, subtotal, discount, VAT, VAT total, Amount Paid, Change, Mode of Payment\n";

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
    link.setAttribute("download", "Receipt_history.csv");
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
