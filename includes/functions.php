<?php
//Function to sanitize integers
function clean_integer($link, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    $data = mysqli_real_escape_string($link, $data);
    return $data;
  }
//Function to sanitize strings  
function clean_string($link, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    $data = mysqli_real_escape_string($link, $data);
    return $data;
}
//Function to sanitize email
function clean_email($link, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
    $data = mysqli_real_escape_string($link, $data);
    return $data;
}
//Function to render menu based on if the user is logged in or not
function render_menu() {
  echo '<nav> <ul>
  <li class="menu"><a href="index.php">About</a></li>
  <li class="menu"><a href="services.php">Services</a></li>
  <li class="menu"><a href="contacts.php">Contact</a></li>';

  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true || isset($_COOKIE['username'])) {
  echo '<li class="menu"><a href="account.php">Account</a></li>
  <li class="menu"><a href="includes/logOut.php">Log Out</a></li>';
  }
  else {
  echo '<li class="menu"><a href="login.php">Login</a></li> 
  <li class="menu"><a href="registration.php">Registration</a></li>';
  }
  echo '</ul> </nav>';
}
//Function to check for "remember me" cookie
function check_rememberme($mysqli) {
  $cookie = $_COOKIE['rememberme'];
  list ($userCookie, $token, $cookieHash) = explode(':', $cookie);
  if (!hash_equals(hash_hmac('sha256', $userCookie . ':' . $token, 'Zk*nxeL1Oh1I$6i'), $cookieHash)) {
      setcookie("rememberme", "", time()-3600);
      echo '<script>
              $(document).ready(function(){
                swal({ title: "There occured a problem with a cookie value!",   
                    text: "Please, login manually",   
                    icon: "error",      
                    button: "OK"}).then(function(){
                        window.location.href = "login.php";
                })
              });
          </script>';
      die;
  }
  //Write token to DB
  $query = mysqli_prepare($mysqli, "SELECT token FROM Project_Users WHERE username=?;");
  mysqli_stmt_bind_param($query, "s", $userCookie);
  mysqli_stmt_execute($query);
  mysqli_stmt_bind_result($query, $userToken);
  mysqli_stmt_fetch($query);
  mysqli_stmt_close($query);
  //If token in DB and token from cookie are equal, log user in
  if (hash_equals($userToken, $token)) {
      $_SESSION['session_id'] = session_id();
      $_SESSION['loggedin'] = true;
      $_SESSION['username'] = $userCookie;  
  }
}

?>
