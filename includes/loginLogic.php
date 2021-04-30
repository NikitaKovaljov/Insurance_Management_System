<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
// session name, and session start
session_name("PHP-PART");
session_start();

include "functions.php"; // include file with pre-written logic for user input
include_once "../DB/connect.db.php";// variables with credentials for connection to db
//Redirect if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: ../index.php");
}
//Connect to DB
include_once "../DB/connect.db.php";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: ../index.php");
}
// connection to db 
$mysqli = mysqli_connect($server,$user,$db_pass,$database);
if (!$mysqli) {
    die("Connection to DB failed: " . mysqli_connect_error());
}

// checking method, if button "submit" is pressed, check if $_SESSION['loggedin'] is set, if not,then check user input "username", "password"
if (isset($_POST["submit"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
            echo "<script>window.location = '../account.php';</script>";
            die;
        }
        else {
            if (empty($_POST['username'])) {
                echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Empty Username!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "../login.php";
                            })
                        });
                    </script>';
                die;
            }
            else {
                if (preg_match("/^\w{5,}$/",$_POST['username']) != 1) {
                    echo '<script>
                        $(document).ready(function(){
                            swal({ title: "Username input is not valid!",   
                                text: "Look precisely to input value",   
                                icon: "error",   
                                button: "OK"}).then(function(){
                                    window.location.href = "../login.php";
                                })
                            });
                        </script>';
                    die;
                } 
                else {
                    $username = clean_string($mysqli, $_POST["username"]);
                }
            }
            if (empty($_POST['pwd'])) {
                echo '<script>
                        $(document).ready(function(){
                            swal({ title: "Password is empty!",   
                                text: "Look precisely to input value",   
                                icon: "error",     
                                button: "OK"}).then(function(){
                                    window.location.href = "../login.php";
                                })
                            });
                    </script>';
                die;
            }
            else {
                if (preg_match("/^[0-9A-Za-z@#\-_$%^&+=!\?]{8,}$/",$_POST['pwd']) != 1) {
                    echo '<script>
                            $(document).ready(function(){
                                swal({ title: "Password input is not valid!",   
                                    text: "Look precisely to input value",   
                                    icon: "error",      
                                    button: "OK"}).then(function(){
                                        window.location.href = "../login.php";
                                })
                            });
                        </script>';
                    die;
                } 
                else {
                    $password = filter_var($_POST['pwd'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }   
    }

    // checking if username and password exists in "database", and then if it in, give access to pages only for veryfied users.
    if (!empty($username) && !empty($password)) {
        // mysql database
        $_SESSION['loggedin'] = false;
        $verify = mysqli_prepare($mysqli, "SELECT username, user_pass FROM Project_Users WHERE username = BINARY ?");

        mysqli_stmt_bind_param($verify, "s", $username);
        mysqli_stmt_execute($verify);
        mysqli_stmt_bind_result($verify, $username, $pass);
        $test = mysqli_stmt_fetch($verify);
        mysqli_stmt_close($verify);

        // checking if user exists, throw an error if no user found
        if ($test != true){
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "No username found!",   
                            text: "Username does not exist in database",   
                            icon: "warning",      
                            button: "OK"}).then(function(){
                                window.location.href = "../login.php";
                            })
                        });
                </script>';
            die; 
        }

        if(password_verify($password, $pass) == true){    
            //Generate token for remember me if the checkbox is ticked
            if(isset($_POST['rememberMe'])) {
                $token = bin2hex(random_bytes(16));
                $cookie = $username . ':' . $token;
                $secKey = 'Zk*nxeL1Oh1I$6i';
                $cookieHash = hash_hmac('sha256', $cookie, $secKey);
                $cookie .= ':' . $cookieHash;
                //Set cookie "rememberme" that consists of username, token and a hash of both
                setcookie('rememberme', $cookie, time() + 7200);
                //Write token to DB
                $query = mysqli_prepare($mysqli, "UPDATE Project_Users SET Project_Users.token = '$token' WHERE username = BINARY ?;");
                mysqli_stmt_bind_param($query, "s", $username);
                mysqli_stmt_execute($query);
            }   
                $_SESSION['session_id'] = session_id();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("Location: ../account.php");
                die;
        }else{
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Invalid password",   
                            text: "Password does not match the username!",   
                            icon: "warning",     
                            button: "OK"}).then(function(){
                                window.location.href = "../login.php";
                            })
                        });
                </script>';
            die; 
        }
    }
}
else {
    header("Location: ../index.php");
}
?>
