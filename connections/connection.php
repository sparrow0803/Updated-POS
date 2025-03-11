<?php
function connection(): PDO {

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'hr';
    
    try {
        $con = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }catch (PDOException $e) {
        throw new Exception("Connection Failed: " . $e->getMessage());
    }

}
    ?>