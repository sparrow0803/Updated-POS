<?php
require_once ("connect/dbcon.php");


if (isset($_GET['id'])) {
    $pdoQuery="DELETE FROM customers WHERE id = :id";
    $pdoResult= $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(array(':id' => $_GET['id']));
    header('location:customer.php');
} else{
    echo "Invalid request. Please provide a valid id.";
}

$pdoConnect= null;
?>
