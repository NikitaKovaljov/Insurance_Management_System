<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>     

<?php
//session name and session start 
session_name("PHP-PART");
session_start();

include "../insurance_management_system/includes/functions.php";
include_once "DB/connect.db.php";// variables with credentials for connection to db

//Check for "remember me" cookie
if (isset($_COOKIE['rememberme']) && !$_SESSION['loggedin'] == true) { 
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
                <h1>Contacts</h1>
                <h2>Developers</h2>
                <p>Information about developer:</p>
                <ul>
                    <li>Nikita Kovaljov, has strong skills in programming, he's very polite and passionate. You can contact him 24/7.</li>
                </ul>
                <p>Phone number:</p>
                <ul>
                    <li>+372 58845326</li>
                </ul>
                <p>Email:</p>
                <ul>
                    <li>nikiko@ttu.ee</li>
                </ul>
                <hr />
                <p>Information about second developer:</p>
                <ul>
                    <li>Kirill Kurkin, has strong skills in unusual solutions, he's very polite and will always find a way to solve a problem.</li>
                </ul>
                <p>Phone number:</p>
                <ul>
                    <li>+372 59347865</li>
                </ul>
                <p>Email:</p>
                <ul>
                    <li>kikurk@ttu.ee</li>
                </ul>
            </article>
        </div>
        <!-- Footer section of the document-->
        <footer>
            <p>Insurance Management</p>
        </footer>
    </body>
</html>

