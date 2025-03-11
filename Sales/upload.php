<?php
// Database connection
$servername = "localhost";
$dbname = 'pos';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"])) {
        
        // Read image data
        $imageData = file_get_contents($_FILES["file"]["tmp_name"]);
        
        // Assuming you have a way to identify the image you want to update, such as its ID
        $imageId = $_POST['image_id']; // Assuming you have a hidden input field named 'image_id' in your form
        
        // Prepare and bind statement
        $stmt = $conn->prepare("UPDATE receipt_history SET picture = ? WHERE number = ?");

        $stmt->bind_param("si", $imageData, $imageId); // Use "si" instead of "bi"

        // Execute statement
        if ($stmt->execute() === TRUE) {
            echo "File updated successfully";
        } else {
            echo "Error updating file: " . $conn->error;
        }

        // Close statement
        $stmt->close();
    }
}
?>
