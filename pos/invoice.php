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

if (!isset($_SESSION['id']) && !isset($_SESSION['employee'])){
  header('location:open.php');
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
    <p>Cashier: ' .$row2['employee']. '</p>
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
  <title>CASHIER</title>
  <link rel="stylesheet" href="styles/cashier.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
<nav class="sidebar close">
<header>
<div class="image-text">
  <span class="image">
  <img src="../pos/img/logo5.png" alt="logo" style=" margin-top: -40px;"/>
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
      <h1> <i class='bx bx-user-pin icon'>&nbsp</i> CASHIER </h1>
      <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
  </div>

  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">

  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="cashier.php">Billing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="purchases.php">Purchases</a>
        </li>
  </ul>
  </div>
  </nav>

  


          
<!----- INPUT CODE HERE :) ----->
<div class="p-3">

<div class="container">
<div class="p-3 mb-2 bg-secondary text-white">
<p class="fs-2 text-center">INVOICE</p>
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
<p class="fs-6">Cashier: <?php echo $row2['employee']; ?></p>
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

<div class="modal-footer">
    <form method="POST">
      <div class="btn-group">
        <button type="button" class="btn btn-outline-warning emailbtn" data-bs-target="#emailModal">Send <i class="bi bi-envelope"></i></button>
        <button type="submit" name="print" class="btn btn-warning">Generate PDF & Print</button>
        </div>
        </form>
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


    </div>

</div>
</div>
</section>


<!----- SCRIPT ----->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script>
  $(document).ready(function () {
    $('.emailbtn').on('click', function() {
      $('#emailModal').modal('show');

    });
  });
</script>

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

<!-- SEND EMAIL MODAL -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="emailModalLabel">Send Invoice</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="invoice.php" method="POST">
      <div class="modal-body">

      <div class="mb-3">
      <label class="form-label">Recipient Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="send" class="btn btn-warning">Send</button>
      </div>
      </form>

    </div>
  </div>
</div>

</body>
</html>