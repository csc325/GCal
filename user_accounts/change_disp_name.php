<?php
/*
* change_disp_name.php
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
$DNform = '
<form method="post" action="user_settings.php">
    <p> Enter your new Display Name <br /><br />
      <input type="text" name="displayName">
      <input type="submit" value="Submit">
    </p>
  </form>';

if (htmlspecialchars($_POST['displayName'])) {

  include_once "functions.php";
  include '../functions/connection.php';

  $email = ($_SESSION['email'] != '') ? $_SESSION['email'] : 2;


  $sql = "UPDATE users
	  SET displayName = '". mysql_escape_string($_POST['displayName'])."'
	  WHERE email = '".$email."'";

  mysql_query($sql, $link);

  if (mysql_affected_rows($link)==1) {
    echo "Your display name was set to: "
      .htmlspecialchars($_POST['displayName']);
    $_SESSION['displayName'] = mysql_escape_string($_POST['displayName']);
  }
  else echo "Failed to change your display name... Refresh page to try again";

  mysql_close($link);
}
echo $DNform;
?>




