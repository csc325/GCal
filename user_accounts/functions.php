<?php


//connect to database
function connect(){
  $db = mysql_connect('localhost', 'csc325generic', 'password');
  $ok = mysql_select_db(CSC325, $db);
  if($db && $ok)
      return $db;
  else return FALSE;
}

//disconnect from database and possibly free a result
function disconnect($db, $result){
  if ($result) {
      $freed = mysql_free_result($result);
  }
  $disconn = mysql_close($db);
  if ($disconn)
      return 0;
  else return -1;
}

    // Check that a username with corresponding password exists in the db
function chk_user_pw ($user, $pw) {
    $db = connect();
    if(!$pw)
	$pw='*';
    $query = "SELECT password FROM users
	      WHERE email = '".$user."'
	      AND password = '".$pw."';";
    $result = mysql_query($query, $db);

    // exit and send error message if query was unsuccessful
    if (!$result){
	$message = "Error in query ($query): " . mysql_error();
	disconnect($db, NULL);
	return 0;
    }
    if (mysql_num_rows($result) == 1)
	return 1;
    else return 0;
}

// Function for generating a random password
function randomPasswordGen() {
	  $salt = "abchefghjkmnpqrstuvwxyz0123456789"; 
	  for($i=0;$i<8; $i++) { 
		$num = mt_rand() % 33; 
		$password .= substr($salt, $num, 1);
		}
	  return $password; 
}



/* procedure - bool passwordReset (string $email)
*
* parameters - $email: a valid username@grinnell.edu email address
*
* purpose - this function assigns a random password to the user
*	      then sends out an e-mail containing the password.
*
* preconditions - the username must have an account in the database
*
* postconditions - the user's password is changed and an e-mail is sent
*
* produces - a boolean: TRUE if successful, FALSE if unsuccessful
*/

function passwordReset ($email){

  // create password and connect to database
  $new_password = randomPasswordGen();
  $db		= connect();
  $email	= mysql_real_escape_string($email);

  // Query for email in database
  $query = "SELECT * FROM users WHERE email = '".$email."';";
  $result = mysql_query($query, $db);
  
  //if query was unsuccessful, print error and die
  if(!$result){
      $message = "Error in query ($query): " . mysql_error();
      mysql_close($db);
      disconnect($db, NULL);
      die($message);
  }

  // if email exists in database, change their password
  if($result){
      $query2  = "UPDATE users
		  SET password ='".md5($new_password).
	       "' WHERE email = '".$email."';";
      $result2 = mysql_query($query2, $db);
    }


  // If successful, send e-mail to user informing them of their new password
  if($result && $result2){
      // Message
      $message = ' 
      <html>
      <body>
	<p>Your password for Grinnell Open Calender has been reset <br /><br />
	  Your new password is: '.$new_password.'</p> <br /> <br />
	'.
      // <a href="URL OF PASSWORD UPDATER">login and change Password</a>
     '</body>
      </html>
      ';

      // Headers
      $header = 'MIME-Version: 1.0' . "\r\n" .
		'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
		'From: webmaster@grinnellopencalender.com' . "\r\n" .
		'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

      // Send Message
      $sent = mail( $email, 'GOC Password Reset', $message, $header);
  }
  else $sent = FALSE;

  disconnect($db, $sql_result);  
  return $sent;
}




function changePassword($email, $old_password, $new_password, $new2_password){

  if($new2_password != $new_password){
    return 0;
  }
  // Connect to database, store variables to prevent sql injections, encrypt
  // password data.
  $db = connect();
  $old_password = md5($old_password);
  $new_password = md5($new_password);
  $email = mysql_real_escape_string(strtolower($email));

  chk_user_pw($email, $old_password);

  if($db){
    // Store new password if old password and email are correct
    $query2 = "UPDATE users
	      SET password = '". $new_password. "'
	      WHERE email = '". $email. "'
	      AND password = '". $old_password. "';";
    if(mysql_num_rows($result)){
      $result2 = mysql_query($query2, $db);

      // exit and send error message if query2 was unsuccessful
      if (!$result2){
	  $message = "Error in query ($query2): " . mysql_error();
	  disconnect($db, $result);
	  die($message);
      }
      // exit and return TRUE if password was successfully changed
      else if($result2 && mysql_num_rows($result)){
	disconnect($db, $result);
	return TRUE;
      }
    }
    else return 0;
}}
?>



