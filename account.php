<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
// session name and session start
session_name("PHP-PART");
session_start();
// connection to mysql database
include "includes/functions.php";
include_once "DB/connect.db.php";// variables with credentials for connection to db

//Connect to DB
$mysqli = mysqli_connect($server,$user,$db_pass,$database);
if (!$mysqli) {
    die("Connection to DB failed: " . mysqli_connect_error());
}

// checking method for is user is valid then allow him to view account page.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if(!isset($_SESSION['Amount'])){
        $_SESSION['Amount'] = 1;
    }
//Display little window with "welcome" only if user is the first time at this page, futher will be replaces with js
    if(isset($_SESSION['Amount']) && $_SESSION['Amount'] == 1){
        $_SESSION['Amount'] += 1;
        //echo '<script>alert("Welcome: '. $_SESSION['username'] .'")</script>';
    }
}
elseif (isset($_COOKIE['rememberme'])){
    //Check for "remember me" cookie
    check_rememberme($mysqli);
}

else {
    session_destroy(); // session is not valid
    echo '<script>
        $(document).ready(function(){
            swal({ title: "Session is not valid!",   
                text: "Please login first",   
                icon: "warning",      
                button: "OK"}).then(function(){
                    window.location.href = "login.php";
                })
            });
        </script>';
    die; 
}
//Get account details from DB for display
$username = $_SESSION['username'];
$query = "SELECT email, full_name, phone_number, personal_code FROM Project_Users WHERE username='$username';";
$result = mysqli_query($mysqli, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Insurance Management</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        
    </head>
    <body>
        <!-- Header section of the document -->
        <header>
            <?php render_menu(); ?>
        </header>
        <!-- Content section of the document -->
        <div>
            <article>
                <p style="text-align:center"> <?php if(isset($_SESSION['username'])){echo "Welcome: " .$_SESSION['username'];} ?></p>
                <button type="button" class="collapsible">Account Details</button>
                <div class="account-details">
                    <div class="current-data">
                        <p>Your full name: <?php echo $data['full_name']?> </p><br>
                        <p>Your ID-Code: <?php echo $data['personal_code']?> </p><br>
                        <p>Your current email: <?php echo $data['email']?> </p><br>
                        <p>Your current phone number: <?php echo $data['phone_number']?> </p>
                    </div>
                    <form onsubmit="return validate_details()" action = "includes/details_logic.php" method = "POST" id="details_form">
                        <label for="new_pass">New Password:</label>
                        <input type="password" id="new_pass" name="new_pass" minlength="8">
                        <input type="submit" name="new_pass_submit" id="new_pass_submit" value="Change Password"><br>
                        <label for="new_email">New Email:</label>
                        <input type="email" id="new_email" name="new_email">
                        <input type="submit" name="new_email_submit" id="new_email_submit" value="Change Email"><br>
                        <label for="new_number">New Phone Number:</label>
                        <input type="tel" id="new_number" name="new_number" pattern="[0-9+-]{1,7}[0-9]{6,9}">
                        <input type="submit" name="new_number_submit" id="new_number_submit" value="Change Phone Number">
                    </form>    
                    </div>                
                <h1>Your Active Services</h1>
                <hr>
                <div class="services-container">
                    <table id="services">
                        <tr>
                            <?php
                            echo '<th>Service</th>';
                            echo '<th>Valid Till</th>';
                            echo '<th>Payment</th>';
                            ?>
                        </tr>
                        <?php
                            // output table with user services
                            $table_casco = "SELECT username, valid_till, payment, license_plate, power FROM Project_Service_Casco WHERE username='$username';";

                            $search_result = mysqli_query($mysqli, $table_casco);

                            if(mysqli_num_rows($search_result) > 0){
                                while($row = mysqli_fetch_assoc($search_result)){
                                    if(!empty($row['valid_till']) && !empty($row['payment'])){
                                        // additional info about user services with js
                                        echo '<tr data-toggle="tooltip" data-placement="bottom" title= "License Plate: '.$row["license_plate"].', Power: '.$row["power"].'kW"><td> Casco Insurance </td>
                                            <td>' . $row['valid_till'] . '</td>
                                            <td class = "payment">' . $row['payment'] . '</td></tr>';
                                    }
                                }
                            }
                            
                            $table_home = "SELECT username, valid_till, payment, area, material FROM Project_Service_Home WHERE username='$username';";

                            $search_result = mysqli_query($mysqli, $table_home);

                            if(mysqli_num_rows($search_result) > 0){
                                while($row = mysqli_fetch_assoc($search_result)){
                                    if(!empty($row['valid_till']) && !empty($row['payment'])){
                                        // additional info about user services with js
                                        echo '<tr data-toggle="tooltip" data-placement="bottom" title= "Area: '.$row["area"].'㎡, Material: '.$row["material"].'"><td> Home Insurance </td>
                                                <td>' . $row['valid_till'] . '</td>
                                                <td class = "payment">' . $row['payment'] . '</td></tr>';
                                    }
                                }
                            }

                            $table_life = "SELECT username, valid_till, payment, age, income FROM Project_Service_Life WHERE username='$username';";

                            $search_result = mysqli_query($mysqli, $table_life);

                            if(mysqli_num_rows($search_result) > 0){
                                while($row = mysqli_fetch_assoc($search_result)){
                                    if(!empty($row['valid_till']) && !empty($row['payment'])){
                                        // additional info about user services with js
                                        echo '<tr data-toggle="tooltip" data-placement="bottom" title= "Age: '.$row["age"].', Income: '.$row["income"].'€"><td> Life Insurance </td>
                                                <td>' . $row['valid_till'] . '</td>
                                                <td class = "payment">' . $row['payment'] . '</td></tr>';
                                    }
                                }   
                            }

                            $table_traffic = "SELECT username, valid_till, payment, license_plate, power FROM Project_Service_Traffic WHERE username='$username';";

                            $search_result = mysqli_query($mysqli, $table_traffic);

                            if(mysqli_num_rows($search_result) > 0){
                                while($row = mysqli_fetch_assoc($search_result)){
                                    if(!empty($row['valid_till']) && !empty($row['payment'])){
                                        // additional info about user services with js
                                        echo '<tr data-toggle="tooltip" data-placement="bottom" title= "License Plate: '.$row["license_plate"].', Power: '.$row["power"].'kW"><td> Traffic Insurance </td>
                                                <td>' . $row['valid_till'] . '</td>
                                                <td class = "payment">' . $row['payment'] . '</td></tr>';
                                    }
                                }
                            }
                            ?>
                    </table>
                    <p>*Additional information about a service can be seen by hovering over the row with the purchased service</p>
                </div>
            </article>
        </div>

        <!-- Footer section of the document-->
        <footer>
            <p>Insurance Management</p>
        </footer>
        <!-- Source for js scripts -->
        <script src="scripts/script.js"></script>
        <script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>
</html>

