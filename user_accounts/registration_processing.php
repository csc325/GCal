<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Grinnell Open Calendar: Registration</title>
</head>
<body>


<?php
include_once "functions.php";
$db = connect();

// Sanitize input
$username  = mysql_real_escape_string($_POST['username']);
$password  = mysql_real_escape_string($_POST['password']);
$password2 = mysql_real_escape_string($_POST['password2']);

// Test if username is already in the database
$user_exists = user_exists($username);

// check if passwords match, username is alphanumeric, and user does not already exist
// then send e-mail and insert user into database
if ($password == $password2 && ctype_alnum($username) && !$user_exists) {
    $regNo = randomPasswordGen(false);
    
    // Message
    $message = "<html><body>
                <p>Welcome to Grinnell Open Calender! <br /><br />
                To activate your account, you must enter your activation code</p> <br /> <br />
	            Your activation code is: $regNo<p></p>
                </body></html>";

    // Headers
    $header = 'MIME-Version: 1.0' . "\r\n" .
              'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
              'From: webmaster@grinnellopencalender.com' . "\r\n" .
              'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
              'X-Mailer: PHP/' . phpversion();

    // Send Message
    // $sent = mail( $username.'@grinnell.edu', 'Grinnell Open Calender Account Confirmation', $message, $header);
    echo 'Registration mailing currently disabled<br>';
    
    // Insert user into database
    $query = "INSERT INTO users (displayName, email, password, confirmed)
	          VALUES ('$username', '$username@grinnell.edu', '$password', $regNo);";
    
    $result = mysql_query($query, $db);
}

if ($result) {
    echo '<form name="confirm" action="" method="post">
          Confirmation Code: <input type="text" name="confirm">
          <input type="submit" value="Confirm"></form>';
} elseif ($password != $password2) {
    echo "passwords don't match";
} elseif (!ctype_alnum($username)) {
    echo "username must be only numbers and letters";
} elseif (!$user_exists) {
    echo "username already exists";
}
?>
</body>
