<?php
/*
* change_password_processing.php: script called upon submit from password form
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

session_start();
require_once '../global.php';

$user = get_user_info();
$userID = $user['userID'];
  
$old_pass  = md5($_POST['old']);
$new_pass  = md5($_POST['new']);
$new_pass2 = md5($_POST['new2']);

if (strcmp($new_pass , $new_pass2) != 0) {
  header('Location: '.ed(false).'user_accounts/user_settings.php?sent=f');
  exit();
}

$query = "SELECT password 
          FROM users 
          WHERE userID = $userID;";
$result = mysql_query($query);
$row = mysql_fetch_row($result);

if(strcmp($row[0], $old_pass) != 0) {
 header('Location: '.ed(false).'user_accounts/user_settings.php?sent=z');
  exit();
}

$query = "UPDATE users 
          SET password = '$new_pass' 
          WHERE userID = $userID;";
$result = mysql_query($query);

header('Location: '.ed(false).'user_accounts/user_settings.php?sent=t');
?>