<?php
/*
* login_processing: checks login information
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
$page_form = isset($_POST['ref']);
$static = isset($_POST['static']);
    
if(isset($_POST['username']) 
   && isset($_POST['password'])) {
  require_once '../global.php';
        
  $user = trim(addslashes($_POST['username']));
  $pass = $static ? $_POST['password'] : md5($_POST['password']);
        
  $query = "SELECT * FROM users ";
  $query .= "WHERE displayName = '$user' AND password = '$pass'";
  $result = mysql_query ($query);
  $row = mysql_fetch_array($result);
  $count = mysql_num_rows($result);
        
  if ($count == 1 
      && $row[confirmed] == null) {
    // Username and password match, continue login
    $_SESSION['sid'] = session_id();
    $_SESSION['email'] = $row['email'];
    $_SESSION['displayName'] = $row['displayName'];
    $_SESSION['userID'] = $row['userID'];
    $_SESSION['css'] = $row['css']; 
    $return = 1;
  } elseif ($row[confirmed] != null) {
    $return = 2;
  }else {
    // Username and password were not found, error
    $return = 0;
  }
        
  if ($page_form) {
    header('Location: '.$_POST['ref']);
  } elseif ($static) {
    return true;
  } else {
    echo $return;
    exit();
  }
}
?>
