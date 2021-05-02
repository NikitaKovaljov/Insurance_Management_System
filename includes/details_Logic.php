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
    if (isset($_POST["new_pass_submit"])) {
        //Validate new password if user pressed the corresponding button on account.php
        if (empty($_POST['new_pass'])) {
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
            if (preg_match("/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/",$_POST['new_pass']) != 1) {
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
                $password = filter_var($_POST['new_pass'], FILTER_SANITIZE_SPECIAL_CHARS);
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
    elseif (isset($_POST["new_email_submit"])) {
        if (empty($_POST['new_email'])) {
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
            if(filter_var($_POST["new_email"], FILTER_VALIDATE_EMAIL) == false) {
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
                $email = clean_email($mysqli, $_POST["new_email"]);
                //Check for email duplicates
                $email_check = mysqli_prepare($mysqli, "SELECT email FROM Project_Users WHERE email=?");
                mysqli_stmt_bind_param($email_check, "s", $email);
                mysqli_stmt_execute($email_check);
                mysqli_stmt_bind_result($email_check, $check_email_result);
                while (mysqli_stmt_fetch($email_check)) {
                    if (strtolower($check_email_result) === strtolower($email)) {
                        echo '<script>
                                $(document).ready(function(){
                                    swal({ title: "Email is taken!",   
                                        text: "This email is already taken by another user",   
                                        icon: "warning",     
                                        button: "OK"}).then(function(){
                                            window.location.href = "../account.php";
                                        })
                                    });
                            </script>';
                        mysqli_stmt_close($email_check);
                        mysqli_close($mysqli);
                        die;                    
                    }
                }
                //Write new email to DB
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
    elseif (isset($_POST["new_number_submit"])) {
        if (empty($_POST['new_number'])) {
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
            if (preg_match("/^[0-9+-]{1,7}[0-9]{6,9}$/", $_POST["new_number"]) != 1) {  
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
                $pnumber = clean_integer($mysqli, $_POST["new_number"]);
                //Check for phone number duplicates
                $pnumber_check = mysqli_prepare($mysqli, "SELECT phone_number FROM Project_Users WHERE phone_number=?");
                mysqli_stmt_bind_param($pnumber_check, "s", $pnumber);
                mysqli_stmt_execute($pnumber_check);
                mysqli_stmt_bind_result($pnumber_check, $check_pnumber_result);
                while (mysqli_stmt_fetch($pnumber_check)) {
                    if ($check_pnumber_result === $pnumber) {
                        echo '<script>
                                $(document).ready(function(){
                                    swal({ title: "Phone number is taken!",   
                                        text: "This phone number is already taken by another user",   
                                        icon: "warning",     
                                        button: "OK"}).then(function(){
                                            window.location.href = "../account.php";
                                        })
                                    });
                            </script>';
                        mysqli_stmt_close($pnumber_check);
                        mysqli_close($mysqli);
                        die;                    
                    }
                }
                //Write new phone number to DB
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
