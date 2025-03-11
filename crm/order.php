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
require_once ('connect/dbcon.php');



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> CUSTOMERS </title>
  <link rel="stylesheet" href="styles/customerservices.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">

  
 

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="/img/logo.png" alt="logo">
        </span>
      </div>
      <i class='bx bx-menu toggle'></i>
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
      <h1> <i class='bx bx-user-pin icon'>&nbsp</i> CUSTOMERS </h1>
      <h2><i class="bx bxs-user usericon">&nbsp</i> <?php echo htmlspecialchars($userrole); ?></h2>
    </div>

<?php
/* Selecting Product */
if (isset($_POST["add_p"])) {
  $Product = $_POST["s_product"];
  $Quantity = $_POST['quantity2'];

  $query = $pdoConnect->prepare("SELECT * FROM product_add");
  $query->execute();
  if($query->rowCount() > 0){
    $query = $pdoConnect->prepare("DELETE from product_add");
    $query->execute();
    
  $query = $pdoConnect->prepare("SELECT * FROM product WHERE p_name LIKE '$Product'");
  $query->execute();
  if($query->rowCount() > 0 ){ 
    while ($row = $query->fetch()) {
  $Brand = $row['p_brand'];
  $Price = $row['p_price'];
   } 
   $Total=$Price * $Quantity;
 
    $pdoQuery = "INSERT INTO product_add (t_product,t_brand,t_price,t_quantity,t_total) VALUES (:Product,:Brand,:Price,:Quantity,:Total)";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoExec = $pdoResult->execute([
      ":Product" => $Product,
      ":Brand" => $Brand,
      ":Price" => $Price,
      ":Quantity" => $Quantity,
      ":Total" => $Total,
    ]);
    if ($pdoExec) {
      header("location:order.php");
    } else {
      echo 'Failed to add.';
    }
     }else{
        echo "No Product Name";
     }
  } else{
  $query = $pdoConnect->prepare("SELECT * FROM product WHERE p_name LIKE '$Product'");
  $query->execute();
  if($query->rowCount() > 0 ){ 
    while ($row = $query->fetch()) {
  $Brand = $row['p_brand'];
  $Price = $row['p_price'];
   } 
   $Total=$Price * $Quantity;
 
    $pdoQuery = "INSERT INTO product_add (t_product,t_brand,t_price,t_quantity,t_total) VALUES (:Product,:Brand,:Price,:Quantity,:Total)";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoExec = $pdoResult->execute([
      ":Product" => $Product,
      ":Brand" => $Brand,
      ":Price" => $Price,
      ":Quantity" => $Quantity,
      ":Total" => $Total,
    ]);
    if ($pdoExec) {
      header("location:order.php");
    } else {
      echo 'Failed to add.';
    }
     }else{
        echo "No Product Name";
     }
  }
   }
 

/* Adding medicine to Order */

if (isset($_POST["add_order"])) {
    $P_name = $_POST["product"];
    $P_quantity = $_POST['quantity'];
    $P_price = $_POST['total'];

      $pdoQuery2 = "INSERT INTO product_order (product_name,product_quantity,product_price) VALUES (:P_name,:P_quantity,:P_price)";
      $pdoResult2 = $pdoConnect->prepare($pdoQuery2);
      $pdoExec2 = $pdoResult2->execute([
        ":P_name" => $P_name,
        ":P_quantity" => $P_quantity,
        ":P_price" => $P_price,
      ]);
      $query2 = $pdoConnect->prepare("DELETE from product_add");
      $query2->execute();
      if ($pdoExec2) {
        header("location:order.php");
      } else {
        echo 'Failed to add.';
      }
    }
   
/*checkout*/

if(isset($_POST['checkout']))
    {
      $code=$_POST['code2'];
      $query5 = $pdoConnect->prepare("SELECT * FROM customers WHERE code=:code");
      $query5->bindParam(':code', $code);
      $query5->execute();
      if($query5->rowCount() > 0 ){
        $result2 = $query5->fetch();  
      $code2=$code;  
      $customer=$result2['name'];
      $totalcost=$_POST['Total2'];
      $A_paid=$_POST['a_paid2'];
      $c_amount=$A_paid - $totalcost;
      $Points=$totalcost*0.05;       
      }else{
        $code2=0;
        $customer="Walk In Customer";
        $totalcost=$_POST['Total2'];
        $A_paid=$_POST['a_paid2'];
        $c_amount=$A_paid-$totalcost;
        $Points=0;  
       }

      $stmt4 = $pdoConnect->prepare("SELECT * from product_order");
      $stmt4->execute();
      if($stmt4->rowCount() == 0){
        echo "No Orders Yet!";
      }

      else if($totalcost > $A_paid){
        echo "Not Enough Cash!";
      }

      else{
        $pdoQuery3 = "INSERT INTO purchase_history (customer,code,totalcost,amountpaid,change_amount) VALUES (:customer,:code2,:totalcost,:A_paid,:c_amount)";
        $pdoResult3 = $pdoConnect->prepare($pdoQuery3);
        $pdoExec3 = $pdoResult3->execute([
          ":customer" => $customer,
          ":code2" => $code2,
          ":totalcost" => $totalcost,
          ":A_paid" => $A_paid,
          ":c_amount" => $c_amount,
        ]);
        

        $pdoQuery6 = $pdoConnect->prepare("UPDATE customers SET points = :Points WHERE code=':code2'");
        $pdoResult6 = $pdoQuery6->execute(
          array(
            ':Points' => $Points,
          )
        );


      $stmt3 = $pdoConnect->prepare("SELECT * from product_order");
      $stmt3->execute();
      while ($row4 = $stmt3->fetch()) {
        $product3=$row4['product_name'];
        $quantity3=$row4['product_quantity'];
        $price3=$row4['product_price'];

        $pdoQuery7 = "INSERT INTO product_history (name_product,quantity,total_price,code) VALUES (:product3,:quantity3,:price3,:code2)";
        $pdoResult7 = $pdoConnect->prepare($pdoQuery7);
        $pdoExec7 = $pdoResult7->execute([
          ":product3" => $product3,
          ":quantity3" => $quantity3,
          ":price3" => $price3,
          ":code2" => $code2,
        ]);

      $stmt5 = $pdoConnect->prepare("SELECT * from product");
      $stmt5->execute();
      $result5 = $stmt5->fetch();
        
        $up_quantity=$result5['p_stocks']-$quantity3;
        $pdoQuery6 = $pdoConnect->prepare("UPDATE product SET p_stocks = :up_quantity WHERE p_name=':product3'");
        $pdoResult6 = $pdoQuery6->execute(
          array(
            ':up_quantity' => $up_quantity,
          )
        );

        $stmt7 = $pdoConnect->prepare("DELETE from product_order");
        $stmt7->execute();

        $stmt8 = $pdoConnect->prepare("DELETE from product_add");
        $stmt8->execute();

    header('location:order.php');
       }
    }
  }


/* Select product */
$query2 = $pdoConnect->prepare("SELECT * from product_add");
$query2->execute();
if($query2->rowCount() > 0){
    $result = $query2->fetchAll();
    foreach($result as $row){
      $dp_product = $row['t_product'];
      $dp_brand = $row['t_brand'];
      $dp_price = $row['t_price'];
      $dp_quantity = $row['t_quantity'];
      $dp_total = $row['t_total'];
    }}
    else{
      $dp_product = '';
      $dp_brand = '';
      $dp_price = '';
      $dp_quantity = '';
      $dp_total = '';
    }



 ?>
 <link href="library/bootstrap-5/bootstrap.min.css" rel="stylesheet" />
 <script src="library/bootstrap-5/bootstrap.bundle.min.js"></script>
  <script src="library/dselect.js"></script>

    <!----- INPUT CODE HERE :) ----->
     <!----- Form for Selecting Medicine :) ----->
    <div class="container-add-item-center">
      <div class="container-add-item">
        <section class="add_item" id="add_item">
          <div class="title-customer">
            <a class="button-back-customer" href="customer.php"><i class="fa-solid fa-arrow-left"></i></a>
            <h2>Add Medicine</h2>
          </div>

          <div>
                <button type="submit" class="btn-print-two" onclick="openPopup()">Select</button>
              </div>
          
          <div class="popup" id="popup">
             <div class='btn-pr'>
                
             <button type="button" onclick="closePopup()">Close</button>
             </div>
             <form action="order.php" method="POST">
              <div class="user-details">

              <div class="form-group">  
                <label class="details" for="Product">Product:</label><br />
                <select name="s_product" class="form-select" id="select_2">
                <option value="">Select Product</option>
                <?php
                $query = $pdoConnect->prepare("SELECT * FROM product");
                $query->execute();
                foreach($query as $row){
                    echo '<option value="'.$row["p_name"].'">'.$row["p_name"].'</option>';
                }
                ?>
                </select>
              </div>
              <div class="form-group">
                <label class="details" for="quantity">quantity:</label>
                <input type="number" name="quantity2" id="quantity3" required/>
              </div>
              <input class="button-item" type="submit" name="add_p" value="Submit">
              </form>
            </div>
            </div>





          <form action="order.php" method="POST">
            <div class="user-details">
              <div class="form-group">
             
                <label class="details" for="name">Product:</label>
                <input placeholder="Product" type="text" name="product" id="product1" value="<?php echo $dp_product?>" />

                
              <div class="form-group">
                <label class="details" for="brand">Brand:</label>
                <input type="text" name="brand" id="brand1" value="<?php echo $dp_brand?>" />
              </div>
              <div class="form-group">
                <label class="details" for="pricel">Price:</label>
                <input type="text" name="price" id="price1" value="<?php echo $dp_price?>" />
              </div>
              <div class="form-group">
                <label class="details" for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity1" value="<?php echo $dp_quantity?>" />
              </div>
              <div class="form-group">
                <label class="details" for="total">Total:</label>
                <input type="text" name="total" id="total1" value="<?php echo $dp_total?>" />
              </div>
              <input class="button-item" type="submit" name="add_order" value="Submit">
            </div>
      </div>
    </div>
    </form>
  </section>



  <!----- Form for Orders Table :) ----->
  <br /> <br /><br /><br /><br /><br /> <br /><br />
  <div class="container-add-item-center">
  <div class="container-add-item">    
    
<?php
$ordertotal=0;
$query3 = $pdoConnect->prepare("SELECT * from product_order");
$query3->execute();?>
  <table class="content-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
<?php
  while ($row = $query3->fetch()) { 
    $id=$row['id'];
    $ordertotal+=$row['product_price'];
    ?>
    
    <tr>
    <td><?php echo $id; ?></td>
     <td><?php echo $row['product_name']; ?></td>
      <td><?php echo $row['product_quantity']; ?></td>
      <td><?php echo $row['product_price']; ?></td>
      <?php echo "<td><div class='delete-btn'><a href='delete.php?id=$id';> Delete</a></div></td>"; ?>
    </tr>
         
   <?php }

   ?>
    </tbody>
  </table>
  <form action="order.php" method="POST">

   <div class="form-group">
     <label class="details" for="subtotal">subtotal:</label>
    <input type="number" name="subtotal2" id="subtotal3" value="<?php echo $ordertotal ?>" />
    </div>
    <div class="form-group">
     <label class="details" for="code">Code:</label>
    <input type="number" name="code2" id="code3" />
    </div>
    <div class="form-group">
     <label class="details" for="Discount">Discount:</label>
    <input type="number" name="Discount2" id="Discount3" />
    </div>
    <div class="form-group">
     <label class="details" for="Total">Total:</label>
    <input type="number" name="Total2" id="Total3" />
    </div>
    <div class="form-group">
     <label class="details" for="a_paid">Amount paid:</label>
    <input type="number" name="a_paid2" id="a_paid3" />
    </div>
    <input class="button-item" type="submit" name="checkout" value="Submit">
    </form>
    </div>
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


    
let popup = document.getElementById("popup");

function openPopup() {
  popup.classList.add("open-popup")
}

function closePopup() {
  popup.classList.remove("open-popup")
}


/* search  */
var select_box_element = document.querySelector('#select_2');

dselect(select_box_element, {
    search: true
});


  </script>
</body>

</html>