<?php
/*
* registration_processing: script called to email user and insert user data
* into database
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category user functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
require_once '../global.php';
require_once 'functions.php';

// Sanitize input
$username    = addslashes($_POST['username']);
$password1   = addslashes($_POST['password1']);
$password2   = addslashes($_POST['password2']);
$user_exists = user_exists($_POST['username']);

// Redirect to registration form if something is wrong
$redirect = 'Location: http://www.cs.grinnell.edu'.ed(false)
  .'user_accounts/registration_form.php';
if ($user_exists) { 
  header($redirect.'?q=exists&user='.$_POST['username']); 
  exit(); 
}

if ($password1 != $password2) { 
  header($redirect.'?q=nomatch'); 
  exit(); 
}

if (preg_match('/[^a-zA-Z0-9]/',$username)) { 
  header($redirect.'?q=chars'); 
  exit(); 
}
    

// If all checks are cleared, continue registration
$encoded = md5($_POST['password1']);
$regNo = randomPasswordGen(false);
    
// Message
$message = "<html><body>
                <p>Welcome to Grinnell Open Calender! <br /><br />
                To activate your account, <a href='".ed(false)."user_accounts/validate.php?conf=$regNo'>click here</a>
                or copy and paste the code below into the<br>validation form: 
                <a href='".ed(false)."user_accounts/validate.php?conf=$regNo'>
                http://www.cs.grinnell.edu".ed(false)."user_accounts/validate.php</a>
                 </p> <br /> <br />
	            Your activation code is: $regNo<p></p>
                </body></html>";

// Headers
$header = 'MIME-Version: 1.0' . "\r\n" .
  'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
  'From: Grinnell Open Calendar <webmaster@grinnellopencalender.com>' . "\r\n" .
  'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
  'X-Mailer: PHP/' . phpversion();

// Send Message
$sent = mail( $username.'@grinnell.edu', 'Account Activation', $message, $header);
    
// Insert user into database
$query = "INSERT INTO users (displayName, email, password, confirmed)
	          VALUES ('$username', '$username@grinnell.edu', '$encoded', $regNo);";
    
$result = mysql_query($query);
    
header('Location: '.ed(false).'user_accounts/validate.php');
?>
