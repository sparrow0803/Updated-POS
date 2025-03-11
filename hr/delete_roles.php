<?php
require 'connection/conn.php';

// Get the selected roles from the request
$selectedRoles = $_POST['selectedRoles'];

// Explode the selected roles into an array
$roles = explode(',', $selectedRoles);

// Delete the roles from the database
foreach ($roles as $roleId) {
    $stmt = $pdo->prepare('DELETE FROM access WHERE id = :id');
    $stmt->bindParam(':id', $roleId);
    $stmt->execute();
}

// Return a JSON response indicating the success of the deletion
echo json_encode(['success' => true]);