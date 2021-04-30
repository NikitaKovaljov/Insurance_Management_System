<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
// session name and session start
session_name("PHP-PART");
session_start();
//connection to mysql database
include "../insurance_management_system/includes/functions.php";
include_once "DB/connect.db.php";// variables with credentials for connection to db

//Check for "remember me" cookie
if (isset($_COOKIE['rememberme']) && !$_SESSION['loggedin'] == true) { 
    // variables for connection to db
    $mysqli = mysqli_connect($server,$user,$db_pass,$database);
    if (!$mysqli) {
        die("Connection to DB failed: " . mysqli_connect_error());
    }
    check_rememberme($mysqli);
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
        <div>
            <article>
                <h1>About</h1>
                <hr>
                <img class = "image1" src="img/img1.jpg" alt="image1">
                <p class = "text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis risus id lorem lobortis maximus nec sit amet dui. Vivamus ligula enim, molestie id lacus porta, dignissim cursus quam. Nullam vitae molestie mauris, vitae vulputate nunc. Etiam interdum elementum turpis, quis fringilla nibh ornare vitae. Fusce fermentum purus et condimentum euismod. Maecenas volutpat consectetur purus, quis tempor mi malesuada eu. In hac habitasse platea dictumst.
                    Cras dignissim volutpat condimentum. Pellentesque tristique dignissim convallis. Suspendisse efficitur varius volutpat. Sed placerat tempor augue, vitae interdum nisl tempor vitae. Nulla fermentum rhoncus enim non dapibus. Nunc sollicitudin at massa at dignissim. Sed lectus nulla, sagittis et risus quis, condimentum vulputate risus. Morbi eu erat porta lectus pellentesque hendrerit eget eu dui. 
                    Aenean tempor ullamcorper accumsan. Ut condimentum ligula vestibulum cursus pretium. Praesent convallis elementum finibus. Donec sollicitudin congue justo, sed viverra quam egestas sed. Vivamus dignissim, lorem quis venenatis maximus, erat urna malesuada tellus, sed efficitur ex ante vestibulum ante. Donec efficitur diam augue, fermentum semper lorem tincidunt non. Donec efficitur nibh eget felis dictum pharetra ac eget elit. Nullam fermentum ex at imperdiet blandit.
                    Aenean eget vehicula nisl. Vivamus euismod feugiat ultricies. Etiam sit amet gravida ligula. Etiam eleifend condimentum aliquet.
                </p>                
                <img class="image2" src="img/img2.png" alt="image2">
                <p class="text2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce gravida massa sed suscipit pretium. Nullam finibus faucibus felis. Proin rutrum tristique ipsum id tristique. Proin eu vulputate lectus, vel iaculis turpis. Morbi finibus vehicula lobortis. Vivamus non tempus elit. Curabitur odio elit, hendrerit sit amet leo ut, iaculis pretium orci. Suspendisse tincidunt at orci vel rutrum. 
                    volutpat accumsan justo, eget faucibus erat viverra quis.</p>
            </article>
        </div>
        <!-- Footer section of the document-->
        <footer>
            <p>Insurance Management</p>
        </footer>
    </body>
</html>

