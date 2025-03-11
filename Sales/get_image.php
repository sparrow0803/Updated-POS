<?php
// Establish database connection
$pdo = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

// Retrieve image data from database based on the ID
$stmt = $pdo->prepare("SELECT picture FROM receipt_history WHERE id = :id");
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Set the content type header
header('Content-Type: image/jpeg');

// Output the image data
echo $row['picture'];
?>
