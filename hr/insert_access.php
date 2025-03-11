<?php
if (!isset($_SESSION)) {
    session_start();
} 

if (isset($_SESSION['UserLogin'])) {
    $username = $_SESSION['UserLogin'];
    $userrole = $_SESSION['UserRole'];
} else {
    header("Location: login.php");
    exit();
}
require 'connection/conn.php';

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Check if the employee already exists in the access table
    $stmt = $pdo->prepare('SELECT * FROM access WHERE employee = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // If the employee exists, update their role
        $stmt = $pdo->prepare('UPDATE access SET role = :role WHERE employee = :name');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    } else {
        // If the employee does not exist, insert a new record
        $stmt = $pdo->prepare('INSERT INTO access (employee, role) VALUES (:name, :role)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }

    header("Location: access.php"); // Redirect to a success page
    exit();
}
?>
