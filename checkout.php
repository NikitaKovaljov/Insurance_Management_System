<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
session_name("PHP-PART");
session_start();
include "../insurance_management_system/includes/functions.php";
include_once "DB/connect.db.php";// variables with credentials for connection to db
//If session does not exist, show a warning to login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    echo '<script>
            $(document).ready(function(){
                swal({ title: "You have to login!",   
                    text: "Please, login in order to purchase insurance services",   
                    icon: "info",      
                    button: "OK"}).then(function(){
                        window.location.href = "login.php";
                })
            });
        </script>';
    die;
}
//Connect to DB
$mysqli = mysqli_connect($server,$user,$db_pass,$database);
if (!$mysqli) {
    die("Connection to DB failed: " . mysqli_connect_error());
}

//If button is pressed on services.php, check if at least one option is picked
elseif (isset($_POST['submit'])) {
    if (!isset($_POST['traffic']) && !isset($_POST['casco']) && !isset($_POST['life']) && !isset($_POST['home'])) {
        echo '<script>
                $(document).ready(function(){
                    swal({ title: "No options selected!",   
                        text: "You have to choose at least one option to purchase insurance services",   
                        icon: "info",      
                        button: "OK"}).then(function(){
                            window.location.href = "services.php";
                    })
                });
            </script>';
        die;
    }
    $trafficSelected = false;
    $cascoSelected = false;
    $lifeSelected = false;
    $homeSelected = false;
    //Bind cookies to selected options to avoid problems with elements rendering
    if (isset($_POST['traffic'])) {
        $trafficSelected = true;
        setcookie("trafficSelected", $trafficSelected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['casco'])) {
        $cascoSelected = true;
        setcookie("cascoSelected", $cascoSelected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['life'])) {
        $lifeSelected = true;
        setcookie("lifeSelected", $lifeSelected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['home'])) {
        $homeSelected = true;
        setcookie("homeSelected", $homeSelected, ['samesite' => 'Strict']);
    }
}

//Validate all input, save "payment fee" and "valid till" to selected options 
if (isset($_POST['submit_cart'])) {
    $username = $_SESSION['username'];
    if (!empty($_POST["plate_traffic"])) {  
        if (preg_match("/^[0-9]{3}[A-Z]{3}$/", $_POST["plate_traffic"]) != 1) {  
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Vehicle plate number is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        elseif ($_POST["plate_traffic"] < 40 || $_POST["power_traffic"] > 999 || preg_match("/^[0-9]{1,3}$/", $_POST["power_traffic"]) != 1) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Vehicle power is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        else {
            $dueTraffic = date("Y-m-d", strtotime("+12 month"));
            $paymentTraffic = round($_POST['power_traffic'] * 0.4, 2);
        }
    }
    if (!empty($_POST["plate_casco"])) {  
        if (preg_match("/^[0-9]{3}[A-Z]{3}$/", $_POST["plate_casco"]) != 1) {  
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Vehicle plate number is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        elseif ($_POST["power_casco"] < 40 || $_POST["power_casco"] > 999 || preg_match("/^[0-9]{1,3}$/", $_POST["power_casco"]) != 1) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Vehicle power is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        else {
            $dueCasco = date("Y-m-d", strtotime("+9 month"));
            $paymentCasco = round($_POST['power_casco'] * 0.65, 2);
        }
    }
    if (!empty($_POST["age"])) {  
        if ($_POST["age"] < 18 || $_POST["age"] > 120 || preg_match("/^[0-9]{1,3}$/", $_POST["age"]) != 1) {  
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Age is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        elseif ($_POST["income"] < 0 || preg_match("/^[0-9]{1,}$/", $_POST["income"]) != 1) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Income is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        else {
            $dueLife = date("Y-m-d", strtotime("+12 month"));
            $paymentLife = round($_POST['income'] * $_POST['age'] / 2000, 2);
        }
    }
    if (!empty($_POST["area"])) {  
        if ($_POST["area"] < 1 || $_POST["area"] > 999 || preg_match("/^[0-9]{1,3}$/", $_POST["area"]) != 1) {  
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Area is not valid!",   
                            text: "Look precisely to input value",   
                            icon: "error",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
        else {
            $dueHome = date("Y-m-d", strtotime("+6 month"));
            $homeCoefficient = 0;
            if ($_POST["material"] == "stone_material") {
                $homeCoefficient = 0.1;
            }
            elseif ($_POST["material"] == "mixed_material") {
                $homeCoefficient = 0.12;
            }
            elseif ($_POST["material"] == "wood_material") {
                $homeCoefficient = 0.15;
            }
            $paymentHome = round($homeCoefficient * $_POST["area"], 2);
        }
    }
    $username = $_SESSION['username'];
    //Check if a car with the given license plate already has traffic insurance
    if (isset($dueTraffic)) {
        $trafficCheck = mysqli_prepare($mysqli, "SELECT license_plate FROM Project_Service_Traffic WHERE license_plate = ?;");
        mysqli_stmt_bind_param($trafficCheck, "s", $_POST['plate_traffic']);
        mysqli_stmt_execute($trafficCheck);
        if (mysqli_stmt_fetch($trafficCheck) == true) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Car is already insured",   
                            text: "A car with this license plate already has traffic insurance",   
                            icon: "warning",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            die;
        }
    }
    //Check if a car with the given license plate already has casco insurance
    if (isset($dueCasco)) {   
        $cascoCheck = mysqli_prepare($mysqli, "SELECT license_plate FROM Project_Service_Casco WHERE license_plate=?;");
        mysqli_stmt_bind_param($cascoCheck, "s", $_POST['plate_casco']);
        mysqli_stmt_execute($cascoCheck);
        if (mysqli_stmt_fetch($cascoCheck) == true) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Car is already insured",   
                            text: "A car with this license plate already has casco insurance",   
                            icon: "warning",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            mysqli_close($mysqli);
            die;    
        }
    }
    //Check if user already has life insurance
    if (isset($dueLife)) {
        $query = "SELECT username FROM Project_Service_Life WHERE username = '$username';";
        $lifeCheck = mysqli_query($mysqli, $query);
        if(mysqli_num_rows($lifeCheck) > 0) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Your life is already insured",   
                            text: "You already have your life insured",   
                            icon: "warning",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            mysqli_close($mysqli);
            die;   
        }
    }
    //Check if user already has home insurance
    if (isset($dueHome)) {
        $query = "SELECT username FROM Project_Service_Home WHERE username = '$username';";
        $homeCheck = mysqli_query($mysqli, $query);
        if(mysqli_num_rows($homeCheck) > 0) {
            echo '<script>
                    $(document).ready(function(){
                        swal({ title: "Your home is already insured",   
                            text: "You already have your home insured",   
                            icon: "warning",      
                            button: "OK"}).then(function(){
                                window.location.href = "checkout.php";
                        })
                    });
                </script>';
            mysqli_close($mysqli);
            die;    
        }
    }
    //Write all data to DB for a table to be rendered on accounts.php
    if (isset($dueTraffic)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Traffic (username, valid_till, payment, license_plate, power) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdsi", $_SESSION['username'], $dueTraffic, $paymentTraffic, $_POST["plate_traffic"], $_POST["power_traffic"]);
        mysqli_stmt_execute($query);
    }
    if (isset($dueCasco)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Casco (username, valid_till, payment, license_plate, power) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdsi", $_SESSION['username'], $dueCasco, $paymentCasco, $_POST["plate_casco"], $_POST["power_casco"]);
        mysqli_stmt_execute($query);
    }
    if (isset($dueLife)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Life (username, valid_till, payment, age, income) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdid", $_SESSION['username'], $dueLife, $paymentLife, $_POST["age"], $_POST["income"]);
        mysqli_stmt_execute($query);
    }
    if (isset($dueHome)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Home (username, valid_till, payment, area, material) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdis", $_SESSION['username'], $dueHome, $paymentHome, $_POST["area"], $_POST["material"]);
        mysqli_stmt_execute($query);
    }
    //When data is successfully written, go to account.php, delete cookies for services selection
    mysqli_close($mysqli);
    header("Location: account.php");
    setcookie("trafficSelected", "", time()-3600);
    $trafficSelected = false;
    setcookie("cascoSelected", "", time()-3600);
    $cascoSelected = false;
    setcookie("lifeSelected", "", time()-3600);
    $lifeSelected = false;
    setcookie("homeSelected", "", time()-3600);
    $homeSelected = false;
}

?>

<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Checkout | Insurance Management</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
    </head>
    <body>
        <header>
            <?php render_menu(); ?>
        </header>
        <div>
            <article>
                <h1>Checkout</h1>
                <form onsubmit="return validateCheckout()" method="POST">
                <?php
                //Render services buy options based on selected options
                if ($_COOKIE['trafficSelected'] == true || $trafficSelected == true) {
                    echo '<h2>Traffic insurance</h2>
                    <label for="plate_traffic">Your vehicle license plate</label> 
                    <input type="text" placeholder="123ABC" id="plate_traffic" name="plate_traffic" pattern="[0-9]{3}[A-Z]{3}" required><br>
                    <label for="power_traffic">Your vehicle power(in kW)</label> 
                    <input type="number" id="power_traffic" name="power_traffic" min="40" max="999" required><br>';
                }
                if ($_COOKIE['cascoSelected'] == true || $cascoSelected == true) {
                    echo '<h2>Casco insurance</h2>
                    <label for="plate_casco">Your vehicle license plate</label> 
                    <input type="text" placeholder="123ABC" id="plate_casco" name="plate_casco" pattern="[0-9]{3}[A-Z]{3}" required><br>
                    <label for="power_casco">Your vehicle power(in kW)</label> 
                    <input type="number" id="power_casco" name="power_casco" min="40" max="999" required><br>';
                }
                if ($_COOKIE['lifeSelected'] == true || $lifeSelected == true) {
                    echo '<h2>Life insurance</h2>
                    <label for="age">Your full age</label> 
                    <input type="number" id="age" name="age" min="18" max="120" required><br>
                    <label for="income">Your monthly income(in €)</label> 
                    <input type="number" id="income" name="income" min="0" required><br>';
                }
                if ($_COOKIE['homeSelected'] == true || $homeSelected == true) {
                    echo '<h2>Home insurance</h2>
                    <label for="area">Your home total area size(in ㎡)</label> 
                    <input type="number" id="area" name="area" min="1" max="999" required><br>
                    Your home building material
                    <input type="radio" value="stone_material" id="stone_material" name="material" checked required>
                    <label for="stone_material">Stone or similar</label>
                    <input type="radio" value="mixed_material" id="mixed_material" name="material">
                    <label for="mixed_material">Stone and wood</label>
                    <input type="radio" value="wood_material" id="wood_material" name="material">
                    <label for="wood_material">Wood</label>';
                }
                ?>
                <br>
                <input type="submit" name="submit_cart" value="Purchase">
            </form>			
            </article>
        </div>
        <footer>
            <p>Insurance Management</p>
        </footer>
        <script src="scripts/script.js"></script>
    </body>
</html>
