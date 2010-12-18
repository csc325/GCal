<?php
/*
* functions.php: provides functions relevant to handeling users
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

/*
* checks to see if there is an error in a query
* @param string $q query
* @param query result handle $r
* @return boolean true if error
*/ 
function have_error($q,$r) 
{
  if (!$r) {
    $message = "Error in query ($q) -- mysql_error()";
    return true;
  } else {
    return false;
  }
}
    
/*
* checks to see if a user exists
* @param string $user username
* @return boolean true if error
*/ 
function user_exists ($user) 
{
  $user = addslashes($user);
  $query = "SELECT userID 
            FROM users 
            WHERE displayName = '$user'";
  $result = mysql_query ($query);
  if (!have_error($query,$result)) 
    return (mysql_num_rows($result) > 0) ? true : false;
}

/*
* Check that a username with corresponding password exists in the db
* @param string $user username
* @param string $pw password
* @param string $db database
* @return boolean true if error
*/ 
function chk_user_pw ($user, $pw, $db) 
{
  $query = "SELECT password 
            FROM users
            WHERE email = '$user'
              AND password = '$pw';";
        
  $result = mysql_query($query, $db);

  // exit and send error message if query was unsuccessful
  if (mysql_num_rows($result) == 0){
    echo "Error in query ($query): mysql_error()";
    mysql_free_result($result);
    mysql_close($db);
    return 0;
  }
  
  return (mysql_num_rows($result) > 0) ? true : false;
}

/*
* Function for generating a random password
* @param boolean $alpha
* @return boolean true if error
*/ 
function randomPasswordGen($alpha=true) {
  $salt = '0123456789'.(($alpha) ? 'abchefghjkmnpqrstuvwxyz' : '');
  for($i=0;$i<9; $i++)
    $password .= substr($salt, mt_rand(0,   strlen($salt)), 1);
  return $password; 
}

/* PROCEDURE - bool passwordReset (string $email)
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

function passwordReset ($email)
{
  // create password and connect to database
  $new_password = randomPasswordGen();
  $email = mysql_real_escape_string($email);

  // Query for email in database
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysql_query($query);
        
  // if email exists in database, change their password
  if ($result) {
    $np_md5 = md5($new_password);
    $query  = "UPDATE users SET password = '$np_md5' WHERE email = '$email';";
    $result = mysql_query($query);
  }
        
  // If successful, send e-mail to user informing them of their new password
  if ($result) {
    // Message
    $message = '<html><body>
                        <p>Here is your new password for Grinnell Open Calendar.  Don\'t
                        forget to change it after you\'ve logged in.</p>
                        <p>New password: '.$new_password.'</p>
                        </body></html>';

    // Headers
    $header = 'MIME-Version: 1.0' . "\r\n" .
      'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
      'From: Grinnell Open Calendar <webmaster@grinnellopencalender.com>' . "\r\n" .
      'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

    // Send Message
    $sent = mail( $email, 'Grinnell Open Calendar Password Reset', $message, $header);
    return true;
  }
  return false;
}
/*
* change password
* @param string $email address of recipient user
* @param string $old_pw old password
* @param string $new_pw new password
* @param string $new2_pw second try at the new password
* @return boolean true if error
*/ 
function changePassword($email, $old_pw, $new_pw, $new2_pw)
{
  if ($new2_pw != $new_pw) return 0;
        
  // Connect to database, store variables to prevent sql injections, encrypt
  // password data.
  include '../functions/connection.php';
  $old_pw = md5($old_pw);
  $new_pw = md5($new_pw);
  $email = mysql_real_escape_string(strtolower($email));

  $exists = chk_user_pw($email, $old_pw, $link);
	  

  if ($link) {
    // Store new password if old password and email are correct
    $query2 = "UPDATE users
                       SET password = '$new_pw'
                       WHERE email = '$email' AND password = '$old_pw';";
            
    if ($exists) {
      $result = mysql_query($query2, $link);
                
      if (mysql_affected_rows($link) != 1) {
        // exit and send error message if query2 was unsuccessful
        $message = "Error in query ($query2): " . mysql_error();
        mysql_free_result($result);
        mysql_close($link);
        die($message);
      } elseif (mysql_affected_rows($link) && $exists){
        mysql_close($link);
        return true;
      }
    } else { 
      return false;
    }
  }
}
?>
