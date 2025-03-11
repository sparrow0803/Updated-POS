<?php
require_once ("connect/dbcon.php");


if (isset($_GET['id'])) {
    $pdoQuery = "DELETE FROM purchase_history WHERE id = :id";
    $pdoResult = $pdoConnect2->prepare($pdoQuery);
    $pdoResult->execute(array(':id' => $_GET['id']));
    header('location:purchase_history.php');
} else {
    echo "Invalid request. Please provide a valid id.";
}

$pdoConnect = null;
?>