<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
session_name("PHP-PART");
session_start();

include "includes/functions.php";
include_once "DB/connect.db.php";

//Check if user is already loggen in, show warning if so
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
//Connect to DB
$mysqli = mysqli_connect($server,$user,$db_pass,$database);
if (!$mysqli){
    die("Connection to DB failed: " . mysqli_connect_error());
}

//Check for "remember me" cookie
if (isset($_COOKIE['rememberme']) && !$_SESSION['loggedin'] == true) { 
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

$error_code = "";
$error_code_taken = "";
//Sanitize all form fields after validation
if (isset($_POST['submit'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Check if username is empty, validate it
        if (empty($_POST["username"])) {  
            $error_code .= "Username is required<br>";
        }
        else {  
            if (preg_match("/^\w{5,}$/", $_POST["username"]) != 1) {  
                $error_code .= "Username is not valid<br>";
            }
            else {
                $username = clean_string($mysqli, $_POST["username"]);
            }  
        }
        //Check if password is empty, validate it	
        if (!empty($_POST["pwd"])) {  
            if (preg_match("/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/", $_POST["pwd"]) != 1) {  
                $error_code .= "Password is not valid<br>";
            }
            else {
                $pwd = filter_var($_POST["pwd"], FILTER_SANITIZE_SPECIAL_CHARS);
                $pwd = password_hash($pwd, PASSWORD_DEFAULT);
            }
        }  	
        //Check if full name is empty, validate it	
        if (empty($_POST["fname"])) {  
            $error_code .= "Full name is required<br>";
        }
        else {  
            if (preg_match("/^[a-zA-Z'\s-]+$/", $_POST["fname"]) != 1) {  
                $error_code .= "Full name is not valid<br>";
            }  
            else {
                $fname = clean_string($mysqli, $_POST["fname"]);
            }
        }
        //Check if ID code is empty, validate it			
        if (empty($_POST["idcode"])) {
            $error_code .= "Estonian ID-Code is required<br>";
        }
        else {
            if (preg_match("/^[3-6]([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])[0-9]{4}$/", $_POST["idcode"]) != 1) {  
                $error_code .= "ID-Code is not valid<br>";
            }
            else {
                $idcode = clean_integer($mysqli, $_POST["idcode"]);
            }
        }
        //Check if email is empty, validate it				
        if (empty($_POST["email"])) {
            $error_code .= "Email is required<br>";
        }
        else {
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false) {
                $error_code .= "Email is not valid<br>";
            }
            else {
                $email = clean_email($mysqli, $_POST["email"]);
            }
        }
        //Check if phone is empty, validate it
        if (!empty($_POST["pnumber"])) {  
            if (preg_match("/^[0-9+-]{1,7}[0-9]{6,9}$/", $_POST["pnumber"]) != 1) {  
                $error_code .= "Phone is not valid<br>";
            }
            else {
                $pnumber = clean_integer($mysqli, $_POST["pnumber"]);
            }
        }
        //Check duplicates, create "already taken" warnings
        if (empty($error_code)) {
            $user_check = mysqli_prepare($mysqli, "SELECT username, personal_code, email, phone_number FROM Project_Users WHERE username = BINARY ? OR personal_code=? OR email=? OR phone_number=?");
            mysqli_stmt_bind_param($user_check, "siss", $username, $idcode, $email, $pnumber);
            mysqli_stmt_execute($user_check);
            mysqli_stmt_bind_result($user_check, $check_username, $check_id, $check_email, $check_phone);
            while (mysqli_stmt_fetch($user_check)) {
                if ($check_username === $username) {
                    $error_code_taken .= "The username is already taken<br>";
                }
                if ($check_id === $idcode) {
                    $error_code_taken .= "The idcode is already taken<br>";
                }
                if (strtolower($check_email) === strtolower($email)) {
                    $error_code_taken .= "The email is already taken<br>";
                }
                if ($check_phone === $pnumber) {
                    $error_code_taken .= "The phone number is already taken<br>";
                }
            }
        }
        
        //If no error codes, write data to DB
        if (empty($error_code) && empty($error_code_taken)) {
            $query = mysqli_prepare($mysqli, "INSERT INTO Project_Users (username, user_pass, full_name, email, phone_number, personal_code) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($query, "ssssss", $username, $pwd, $fname, $email, $pnumber, $idcode);
            mysqli_stmt_execute($query);
            $error_code = "Your registration was successful. You can now login";
            mysqli_close($mysqli);
        }
        else {
            mysqli_close($mysqli);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Insurance Management</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
    </head>
    <body>
        <!-- Header section of the document -->
        <header>
            <?php render_menu(); ?>
        </header>
        <!-- Content section of the document -->
        <div class="register">
            <article>
                <h1>Registration</h1>
                <form method = "POST" id="login_form" onsubmit="return validate_registration()">
                    <label for="username">Username:</label><br>
                    <input class="regfield" type="text" id="username" name="username" minlength="5" placeholder="Enter a username with minimum length of 5 characters" required><br>
                    <label for="pwd">Password:</label><br>
                    <input class="regfield" type="password" id="pwd" name="pwd" minlength="8" placeholder="Enter a password with minimum length of 8 characters" required><br>
                    <label for="fname">Full name:</label><br>
                    <input class="regfield" type="text" id="fname" name="fname" placeholder="Enter your full name: Name Surname" required><br>
                    <label for="idcode">Estonian ID-Code:</label><br>
                    <input class="regfield" type="number" id="idcode" name="idcode" min="30001010000" max="69912319999" placeholder="Enter a valid Estonian ID-Code" required><br>
                    <label for="email">E-mail:</label><br>
                    <input class="regfield" type="email" id="email" name="email" placeholder="Enter a valid email" required><br>
                    <label for="pnumber">Phone number:</label><br>
                    <input class="regfield" type="tel" id="pnumber" name="pnumber" pattern="[0-9+-]{1,7}[0-9]{6,9}" placeholder="Enter a valid phone number" required><br>
                    <input type="submit" name="submit" value="Register">
                    <?php
                    //Show error codes if they exist
                    if (!empty($error_code)) {
                        echo "<br>", $error_code;
                    }
                    //If no error codes, show "already exists" errors if they exist
                    elseif (!empty($error_code_taken)) {
                        echo "<br>", $error_code_taken;
                    }
                    ?>
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
