<?php
session_name("PHP-PART"); // session name
session_start(); // session start 
session_unset(); // unset all cookies 
session_destroy(); // destroy session 
setcookie("trafficSelected", "", time()-3600); // delete cookie of trafficSelected
setcookie("cascoSelected", "", time()-3600); // delete cookie of cascoSelected
setcookie("lifeSelected", "", time()-3600); // delete cookie of lifeSelected
setcookie("rememberme", "", time()-3600); // delete "remember me" cookie
setcookie("homeSelected", "", time()-3600); // delete cookie of homeSelected
header("Location: ../index.php"); // redirect user after logout to index.php
die;
?>
