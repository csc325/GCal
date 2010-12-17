<?php
/*
* forgot_password
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
require_once '../header.php';
require_once 'functions.php';
    
/*generate reset password HTML form
 *@return HTML form
 */
function reset_form () 
{
  echo '<p>Enter your username below and a new password will be sent to your email. 
        You can change your password by going to "Settings" after you\'ve logged in.</p>
        
        <form action="forgot_password.php" method="post">
            <label>Your username:</label> <input type="text" name="displayName" />
            <input type="hidden" name="sent" value="1"/>
            <input type="submit" value="Send New Password" />
        </form>';
}
?>
<div class="body">
  <div class="col large">
  <h1 class="head">Forgot Your Password?</h1>
  <?php 
  if($_POST['sent']) :
    $query = "SELECT * 
              FROM users 
              WHERE displayName = '$_POST[displayName]'";
  $result = mysql_query ($query);
  $row = mysql_fetch_array($result);
                
  if (passwordReset($row[email])) {
    echo '<h3>A new password has been sent to your Grinnell e-mail address.</h3>';
  } else {
    echo '<h3>Email address not recognized.</h3>';
    reset_form();
  }
  else :
    reset_form();
  endif;
  ?>
</div>
<?php include '../sidebar.php'; ?>
</div>
<?php include '../footer.php'; ?>

