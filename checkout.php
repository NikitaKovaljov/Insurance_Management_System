<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
session_name("PHP-PART");
session_start();
include "includes/functions.php";
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
    $traffic_selected = false;
    $casco_selected = false;
    $life_selected = false;
    $home_selected = false;
    //Bind cookies to selected options to avoid problems with elements rendering
    if (isset($_POST['traffic'])) {
        $traffic_selected = true;
        setcookie("traffic_selected", $traffic_selected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['casco'])) {
        $casco_selected = true;
        setcookie("casco_selected", $casco_selected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['life'])) {
        $life_selected = true;
        setcookie("life_selected", $life_selected, ['samesite' => 'Strict']);
    }
    if (isset($_POST['home'])) {
        $home_selected = true;
        setcookie("home_selected", $home_selected, ['samesite' => 'Strict']);
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
            $due_traffic = date("Y-m-d", strtotime("+12 month"));
            $payment_traffic = round($_POST['power_traffic'] * 0.4, 2);
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
            $due_casco = date("Y-m-d", strtotime("+9 month"));
            $payment_casco = round($_POST['power_casco'] * 0.65, 2);
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
            $due_life = date("Y-m-d", strtotime("+12 month"));
            $payment_life = round($_POST['income'] * $_POST['age'] / 2000, 2);
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
            $due_home = date("Y-m-d", strtotime("+6 month"));
            $home_coefficient = 0;
            if ($_POST["material"] == "stone_material") {
                $home_coefficient = 0.1;
            }
            elseif ($_POST["material"] == "mixed_material") {
                $home_coefficient = 0.12;
            }
            elseif ($_POST["material"] == "wood_material") {
                $home_coefficient = 0.15;
            }
            $payment_home = round($home_coefficient * $_POST["area"], 2);
        }
    }
    $username = $_SESSION['username'];
    //Check if a car with the given license plate already has traffic insurance
    if (isset($due_traffic)) {
        $traffic_check = mysqli_prepare($mysqli, "SELECT license_plate FROM Project_Service_Traffic WHERE license_plate = ?;");
        mysqli_stmt_bind_param($traffic_check, "s", $_POST['plate_traffic']);
        mysqli_stmt_execute($traffic_check);
        if (mysqli_stmt_fetch($traffic_check) == true) {
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
    if (isset($due_casco)) {   
        $casco_check = mysqli_prepare($mysqli, "SELECT license_plate FROM Project_Service_Casco WHERE license_plate=?;");
        mysqli_stmt_bind_param($casco_check, "s", $_POST['plate_casco']);
        mysqli_stmt_execute($casco_check);
        if (mysqli_stmt_fetch($casco_check) == true) {
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
    if (isset($due_life)) {
        $query = "SELECT username FROM Project_Service_Life WHERE username = '$username';";
        $life_check = mysqli_query($mysqli, $query);
        if(mysqli_num_rows($life_check) > 0) {
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
    if (isset($due_home)) {
        $query = "SELECT username FROM Project_Service_Home WHERE username = '$username';";
        $home_check = mysqli_query($mysqli, $query);
        if(mysqli_num_rows($home_check) > 0) {
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
    if (isset($due_traffic)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Traffic (username, valid_till, payment, license_plate, power) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdsi", $_SESSION['username'], $due_traffic, $payment_traffic, $_POST["plate_traffic"], $_POST["power_traffic"]);
        mysqli_stmt_execute($query);
    }
    if (isset($due_casco)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Casco (username, valid_till, payment, license_plate, power) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdsi", $_SESSION['username'], $due_casco, $payment_casco, $_POST["plate_casco"], $_POST["power_casco"]);
        mysqli_stmt_execute($query);
    }
    if (isset($due_life)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Life (username, valid_till, payment, age, income) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdid", $_SESSION['username'], $due_life, $payment_life, $_POST["age"], $_POST["income"]);
        mysqli_stmt_execute($query);
    }
    if (isset($due_home)) {
        $query = mysqli_prepare($mysqli, "INSERT INTO Project_Service_Home (username, valid_till, payment, area, material) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssdis", $_SESSION['username'], $due_home, $payment_home, $_POST["area"], $_POST["material"]);
        mysqli_stmt_execute($query);
    }
    //When data is successfully written, go to account.php, delete cookies for services selection
    mysqli_close($mysqli);
    header("Location: account.php");
    setcookie("traffic_selected", "", time()-3600);
    $traffic_selected = false;
    setcookie("casco_selected", "", time()-3600);
    $casco_selected = false;
    setcookie("life_selected", "", time()-3600);
    $life_selected = false;
    setcookie("home_selected", "", time()-3600);
    $home_selected = false;
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
                <form onsubmit="return validate_checkout()" method="POST">
                <?php
                //Render services buy options based on selected options
                if ($_COOKIE['traffic_selected'] == true || $traffic_selected == true) {
                    echo '<h2>Traffic insurance</h2>
                    <label for="plate_traffic">Your vehicle license plate</label> 
                    <input type="text" placeholder="123ABC" id="plate_traffic" name="plate_traffic" pattern="[0-9]{3}[A-Z]{3}" required><br>
                    <label for="power_traffic">Your vehicle power(in kW)</label> 
                    <input type="number" id="power_traffic" name="power_traffic" min="40" max="999" required><br>';
                }
                if ($_COOKIE['casco_selected'] == true || $casco_selected == true) {
                    echo '<h2>Casco insurance</h2>
                    <label for="plate_casco">Your vehicle license plate</label> 
                    <input type="text" placeholder="123ABC" id="plate_casco" name="plate_casco" pattern="[0-9]{3}[A-Z]{3}" required><br>
                    <label for="power_casco">Your vehicle power(in kW)</label> 
                    <input type="number" id="power_casco" name="power_casco" min="40" max="999" required><br>';
                }
                if ($_COOKIE['life_selected'] == true || $life_selected == true) {
                    echo '<h2>Life insurance</h2>
                    <label for="age">Your full age</label> 
                    <input type="number" id="age" name="age" min="18" max="120" required><br>
                    <label for="income">Your monthly income(in €)</label> 
                    <input type="number" id="income" name="income" min="0" required><br>';
                }
                if ($_COOKIE['home_selected'] == true || $home_selected == true) {
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
