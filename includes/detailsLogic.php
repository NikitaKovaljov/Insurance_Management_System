<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
// session name, and session start
session_name("PHP-PART");
session_start();
// connection to database
include "functions.php"; // include file with pre-written logic for user input
include_once "../DB/connect.db.php";// variables with credentials for connection to db
//Redirect user if already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header("Location: ../index.php");
}
//Connect to DB
$mysqli = mysqli_connect($server,$user,$db_pass,$database);
if (!$mysqli) {
    die("Connection to DB failed: " . mysqli_connect_error());
}
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["newPassSubmit"])) {
        //Validate new password if user pressed the corresponding button on account.php
        if (empty($_POST['newPass'])) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Password is empty!",   
                            text: "Look precisely to input value",   
                            icon: "error",     
                            button: "OK"}).then(function(){
                                window.location.href = "../account.php";
                            })
                        });
                </script>';
            die;
        }
        else {
            if (preg_match("/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/",$_POST['newPass']) != 1) {
                echo '<script>
                        $(document).ready(function(){
                            swal({ title: "Password is not valid!",   
                                text: "Look precisely to input value",   
                                icon: "error",     
                                button: "OK"}).then(function(){
                                    window.location.href = "../account.php";
                                })
                            });
                    </script>';
                die;
            } 
            else {
                //Write new password to DB
                $password = filter_var($_POST['newPass'], FILTER_SANITIZE_SPECIAL_CHARS);
                $password = password_hash($password, PASSWORD_DEFAULT);
                $query = mysqli_prepare($mysqli, "UPDATE Project_Users SET user_pass=? WHERE username='$username';");
                mysqli_stmt_bind_param($query, "s", $password);
                mysqli_stmt_execute($query);
                mysqli_stmt_close($query);
                mysqli_close($mysqli);
                header("Location: ../account.php");
            }
        } 
    }
    //Validate new email if user pressed the corresponding button on account.php
    elseif (isset($_POST["newEmailSubmit"])) {
        if (empty($_POST['newEmail'])) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Email is empty!",   
                            text: "Look precisely to input value",   
                            icon: "error",     
                            button: "OK"}).then(function(){
                                window.location.href = "../account.php";
                            })
                        });
                </script>';
            die;
        }
        else {
            if(filter_var($_POST["newEmail"], FILTER_VALIDATE_EMAIL) == false) {
                echo '<script>
                        $(document).ready(function(){
                            swal({ title: "Email is not valid!",   
                                text: "Look precisely to input value",   
                                icon: "error",     
                                button: "OK"}).then(function(){
                                    window.location.href = "../account.php";
                                })
                            });
                    </script>';
                die;
            } 
            else {
                //Write new email to DB
                $email = clean_email($mysqli, $_POST["newEmail"]);
                $query = mysqli_prepare($mysqli, "UPDATE Project_Users SET email=? WHERE username='$username';");
                mysqli_stmt_bind_param($query, "s", $email);
                mysqli_stmt_execute($query);
                mysqli_stmt_close($query);
                mysqli_close($mysqli);
                header("Location: ../account.php");
            }
        }         
    }
    //Validate new phone number if user pressed the corresponding button on account.php
    elseif (isset($_POST["newNumberSubmit"])) {
        if (empty($_POST['newNumber'])) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Phone number is empty!",   
                            text: "Look precisely to input value",   
                            icon: "error",     
                            button: "OK"}).then(function(){
                                window.location.href = "../account.php";
                            })
                        });
                </script>';
            die;
        }
        else {
            if (preg_match("/^[0-9+-]{1,7}[0-9]{6,9}$/", $_POST["newNumber"]) != 1) {  
                echo '<script>
                        $(document).ready(function(){
                            swal({ title: "Phone number is not valid!",   
                                text: "Look precisely to input value",   
                                icon: "error",     
                                button: "OK"}).then(function(){
                                    window.location.href = "../account.php";
                                })
                            });
                    </script>';
                die;
            } 
            else {
                //Write new phone number to DB
                $pnumber = clean_integer($mysqli, $_POST["newNumber"]);
                $query = mysqli_prepare($mysqli, "UPDATE Project_Users SET phone_number=? WHERE username='$username';");
                mysqli_stmt_bind_param($query, "s", $pnumber);
                mysqli_stmt_execute($query);
                mysqli_stmt_close($query);
                mysqli_close($mysqli);
                header("Location: ../account.php");
            }
        }         
    }
    else {
        header("Location: ../account.php");
    }
}
else {
    header("Location: ../account.php");
}
?>
