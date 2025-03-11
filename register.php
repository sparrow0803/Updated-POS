<?php
include_once("connections/connection.php");
$pdo = connection();

if (isset($_POST['submit'])) {
    $userName = $_POST['username'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $employeeName = $_POST['name'];  // Get the selected employee name from the form

    // Check if the employee name already exists
    $checkEmployee = "SELECT * FROM `access` WHERE employee = :employee";
    $stmtEmployee = $pdo->prepare($checkEmployee);
    $stmtEmployee->bindParam(':employee', $employeeName);
    $stmtEmployee->execute();

    if($employeeName == 'No Existing Employee'){
        echo '<script>alert("No Existing Employee")</script>';
    }

    elseif ($stmtEmployee->rowCount() > 0) {
        // Employee already exists, update the existing entry

        
        if ($pass != $cpass) {
            echo '<script>alert("Password not matched")</script>';
        } else {
            $sql = "UPDATE access SET `username` = :username, `password` = :pass WHERE `employee` = :employee";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':username', $userName);
            $statement->bindParam(':pass', $pass);
            $statement->bindParam(':employee', $employeeName);  // Bind the employee name

            if ($statement->execute()) {
                header("Location: login.php");
                exit;
            } else {
                echo "Update failed.";
            }
        }
    } else {
        // Insert the new user
        if ($pass != $cpass) {
            echo '<script>alert("Password not matched")</script>';
        } else {
            $sql = "INSERT INTO access (`username`, `password`, `employee`) VALUES (:username, :pass, :employee)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':username', $userName);
            $statement->bindParam(':pass', $pass);
            $statement->bindParam(':employee', $employeeName);  // Bind the employee name

            if ($statement->execute()) {
                header("Location: login.php");
                exit;
            } else {
                echo "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
<div class="container">
    <form action="" method="post">
        <div class="form-content">
        <div class="login-form">
        <div class="title">Sign Up</div>

        <div class="form-group mb-3">
            <label style="color: #023047;" for="">Employee</label><br>
            <?php
            $stmt2 = $pdo->prepare('SELECT * FROM employees');
            $stmt2->execute();
            if ($stmt2->rowCount() > 0) {
                $dropdown = "<select class='form-select' name='name'>";
                foreach ($stmt2 as $row) {
                    $fullname = $row['firstname'] . ' ' . $row['lastname'];
                    $dropdown .= "\r\n<option value='{$fullname}'>{$fullname}</option>";
                }
                $dropdown .= "\r\n</select>";
                echo $dropdown;
            } else {
                $dropdown2 = "<select class='form-select' name='name'>";
                $dropdown2 .= "\r\n<option value='No Existing Employee'>No Existing Employee</option>";
                $dropdown2 .= "\r\n</select>";
                echo $dropdown2;
            }
            ?>
        </div>
        <div class="input-boxes">
        <label style="color: #023047;"> Username</label>
        <div class="input-box">
            <input type="text" name="username" id="username" placeholder="Enter Username" required>
        </div>

        <label style="color: #023047;"> Password </label>
        <div class="input-box">
        <input type="password" name="pass" id="pass" placeholder="Enter Password" required>
        </div>

        <label style="color: #023047;"> Confirm Password </label>
        <div class="input-box">
        <input type="password" name="cpass" id="cpass" placeholder="Confirm Password" required>
        </div>
        
        <div class="button input-box">
        <input type="submit" name="submit" value="Sign Up">
        </div>
        <span class="txt">Already have an Account?</span>
        <a href="login.php" class="register">Sign In</a>

        </div>
    </form>

    </div>
</body>
</html>
