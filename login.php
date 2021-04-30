<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>  

<?php
session_name("PHP-PART");
session_start();
// connection to mysql database
include "../insurance_management_system/includes/functions.php";
include_once "DB/connect.db.php";// variables with credentials for connection to db

// checking method, if loggedin is set and equal true
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<script>
            $(document).ready(function(){
                swal({ title: "You are already logged in!",   
                    text: "You will be redirected to your account page",   
                    icon: "info",      
                    button: "OK"}).then(function(){
                        window.location.href = "account.php";
                })
            });
        </script>';
    die;
}

//Check for "remember me" cookie
elseif (isset($_COOKIE['rememberme'])) { 
    // credentials for connection to db
    $mysqli = mysqli_connect($server,$user,$db_pass,$database);
    if (!$mysqli) {
        die("Connection to DB failed: " . mysqli_connect_error());
    }
    check_rememberme($mysqli);
    echo '<script>
            $(document).ready(function(){
                swal({ title: "You are already logged in!",   
                    text: "You will be redirected to your account page",   
                    icon: "info",      
                    button: "OK"}).then(function(){
                        window.location.href = "account.php";
                })
            });
        </script>';
    die;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Insurance Management</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
        <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    </head>
    <body>
        <!-- Header section of the document -->
        <header>
            <?php render_menu(); ?>
        </header>
        <!-- Content section of the document -->
        <div>
            <article>
                <h1>Login</h1>
                <form onsubmit="return validateLogin()" action = "includes/loginLogic.php" method = "POST" id="loginForm">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" class= "regfield" minlength="5" placeholder="Enter your username (minimum length is 5 characters)" required><br>
                    <label for="pwd">Password:</label><br>
                    <input type="password" id="pwd" name="pwd" class="regfield" minlength="8" placeholder="Enter your password (minimum length is 8 characters)" required><br>
                    <input type="checkbox" id="rememberMe" name="rememberMe" value="Remember me">
                    <label for="rememberMe">Remember me</label>
                    <br>
                    <input type="submit" name = "submit" id = "submit" value="Login">
                </form>
            </article>
        </div>
        <!-- Footer section of the document-->
        <footer>
            <p>Insurance Management</p>
        </footer>
        <script src="scripts/script.js"></script>
    </body>
</html>
