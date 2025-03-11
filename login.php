<?php 
if (!isset($_SESSION)) {
    session_start();
}

include_once("connections/connection.php");

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
   
    try {
        $pdo = connection();

        $sql = "SELECT username, role FROM access WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
        $count = $stmt->rowCount();

if($count > 0){

        

        $sql = "SELECT * FROM access WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row){
            $role = $row['role'];
            $employee = $row['employee'];
        }

        if($role == 'Cashier'){
            $_SESSION['UserLogin'] = $user;
            $_SESSION['UserRole'] = $role;
            $_SESSION['employee'] = $employee;
            header('location:/updated pos/pos/cashier.php');
        }

        elseif($role == 'Human Resource'){
            $_SESSION['UserLogin'] = $user;
            $_SESSION['UserRole'] = $role;
            header('location:/updated pos/hr/employee.php');
        }

        elseif($role == 'Manager' || $role == 'Administrator' || $role == 'Owner'){
            $_SESSION['UserLogin'] = $user;
            $_SESSION['UserRole'] = $role;
            $_SESSION['employee'] = $employee;
            header('location:/updated pos/sales/sales.php');
        }

        
    }
    else{
        echo '<script language="javascript">';
        echo 'alert(Invalid Credentials")';
        echo '</script>';
    }
        }
        catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } 
     
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="styles/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel="stylesheet">
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Login</div>
                    <div style="font-size: 25px;" class="message">Welcome to <span style="color: #EDAD10;"> YELLOW BANANA</div>
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class='bx bxs-user-circle'></i>
                            <input type="text" placeholder="Username" name="username" required>
                        </div>
                        <div class="input-box">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" placeholder="Password" name="password" id="myInput" required>
                        </div>
                        <input type="checkbox" onclick="myFunction()"> Show Password
                        <script>
                            function myFunction() {
                                var x = document.getElementById("myInput");
                                if (x.type === "password") {
                                    x.type = "text";
                                } else {
                                    x.type = "password";
                                }
                            }
                        </script>
                        <div class="button input-box">
                            <input type="submit" value="Login" name="login">
                        </div>
                        <span class="txt">Not a Member?</span>
                        <a href="register.php" class="register">Sign up</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
