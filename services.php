<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
session_name("PHP-PART");
session_start();
//Delete cookies connected to chosen services options
setcookie("trafficSelected", "", time()-3600);
setcookie("cascoSelected", "", time()-3600);
setcookie("lifeSelected", "", time()-3600);
setcookie("homeSelected", "", time()-3600);

include "../insurance_management_system/includes/functions.php";
include_once "DB/connect.db.php"; // credentials for connection to db 

//Check for "remember me" cookie
if (isset($_COOKIE['rememberme'])) { 
    // connection to db 
    $mysqli = mysqli_connect($server,$user,$db_pass,$database);
    if (!$mysqli) {
        die("Connection to DB failed: " . mysqli_connect_error());
    }
    check_rememberme($mysqli);
}
//Connect to DB if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $mysqli = mysqli_connect($server,$user,$db_pass,$database);
    if (!$mysqli) {
        die("Connection to DB failed: " . mysqli_connect_error());
    }
    $username = $_SESSION['username'];
    //Check if user already has life insurance
    $query = "SELECT username FROM Project_Service_Life WHERE username = '$username';";
    $lifeCheck = mysqli_query($mysqli, $query);
    $lifeRows = mysqli_num_rows($lifeCheck);
    //Check if user already has home insurance
    $query = "SELECT username FROM Project_Service_Home WHERE username = '$username';";
    $homeCheck = mysqli_query($mysqli, $query);
    $homeRows = mysqli_num_rows($homeCheck);
}

?>

<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Services | Insurance Management</title>
        <link rel="stylesheet" type="text/css" href="styles/index.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
    </head>
    <body>
        <header>
            <?php render_menu(); ?>
        </header>
        <div>
            <article>
                <h1>What services we provide</h1>
                <div class="services-container">
					<?php
						if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
							echo 'You have to <a href="login.php">login</a> in order to purchase insurance services <br>';
						}
					?>
					<form action="checkout.php" method="POST">
                    <table id="services">
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Buy</th>
                        </tr>
                        <tr>
                            <td>Traffic insurance</td>
                            <td class="describe-service">Some Description About Traffic Insurance</td>
                            <td class="price">25</td>
							<?php
							if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
								echo '<td><input type="checkbox" id="traffic" name="traffic"></td>';
							}
							else {
								echo '<td><input type="checkbox" id="traffic" name="traffic" disabled></td>';
							}
							?>
                        </tr>
                        <tr>
                            <td>Casco insurance</td>
                            <td class="describe-service">Some Description About Casco Insurance</td>
                            <td class="price">40</td>
							<?php
							if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
								echo '<td><input type="checkbox" id="casco" name="casco"></td>';
							}
							else {
								echo '<td><input type="checkbox" id="casco" name="casco" disabled></td>';
							}
							?>
                        </tr>
                        <tr>
                            <td>Life insurance</td>
                            <td class="describe-service">Some Description About Life Insurance</td>
                            <td class="price">4</td>
							<?php
                            //If life is already insured, show tooltip
							if ($lifeRows > 0) {
                                echo '<td><input data-toggle="tooltip" data-placement="bottom" title="You already have your life insured" type="checkbox" id="life" name="life" disabled></td>';
							}
                            elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                echo '<td><input type="checkbox" id="life" name="life"></td>';
                            }
							else {
								echo '<td><input type="checkbox" id="life" name="life" disabled></td>';
							}
							?>
                        </tr>
                        <tr>
                            <td>Home insurance</td>
                            <td class="describe-service">Some Description About Home Insurance</td>
                            <td class="price">13</td>
							<?php
                            //If home is already insured, show tooltip
							if ($homeRows > 0) {
                                echo '<td><input data-toggle="tooltip" data-placement="bottom" title="Your home is already insured" type="checkbox" id="home" name="home" disabled></td>';
							}
                            elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                                echo '<td><input type="checkbox" id="home" name="home"></td>';
                            }
							else {
								echo '<td><input type="checkbox" id="home" name="home" disabled></td>';
							}
							?>                        
						</tr>
                    </table>
					<?php
                    //Display checkout button only if user is logged in
					if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                        echo '<button type="submit" name="submit">Proceed to checkout</button>';
					}
					?>
					</form>
                </div>
            </article>
        </div>
        <footer>
            <p>Insurance Management</p>
        </footer>
        <script src="scripts/script.js"></script>
        <script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>
</html>
