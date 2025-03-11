<?php
include_once("connection/connection.php");
$pdo = connection();
//--------------------------Update Status---------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_id'])) {
        $orderId = $_POST['order_id'];
        try {
            $sql = "UPDATE purchase_order SET status = 1 WHERE id = :order_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':order_id' => $orderId]);
        
            echo "Status updated successfully";
        } catch (Exception $e) {
            
            echo "Failed to update status: " . $e->getMessage();
        }
    } else {
     
        echo "Order ID is missing";
    }
} else {
  
    echo "Invalid request method";
}
//--------------------------Update stocks----------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $orderId = $_POST['order_id'];

    try {

        $sql = "UPDATE products p
                INNER JOIN purchase_order po ON p.name = po.item_name
                SET p.stocks = p.stocks + po.quantity
                WHERE po.id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);

        $sqlInsert = "INSERT INTO inbound (quantity, items) 
        SELECT po.quantity, po.item_name 
        FROM purchase_order po 
        WHERE po.id = :order_id";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([':order_id' => $orderId]);

    } catch (Exception $e) {
        
        echo "Failed: " . $e->getMessage();
    }
}
?>