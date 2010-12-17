<?php
/*
* confirmation_processing.php
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category processing
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
include '../functions/connection.php';
include '../global.php';
include '../header.php';
include 'functions.php';

echo "<div class = 'body'>";
echo "<div class = 'col large'>";
      
$conf_num = mysql_real_escape_string($_POST["confirm"]);
$conf_num = str_replace(" ","", $conf_num);
$query = "SELECT confirmed, displayName 
          FROM users 
          WHERE users.confirmed = $conf_num;";
$result = mysql_query($query);

if (!have_error($result, $query)) {
  $row = mysql_fetch_row($result);
  if ($row[0] == $conf_num) {
    echo "Registration for $row[1] confirmed.";
    $result = mysql_query("UPDATE users 
                           SET confirmed = null 
                           WHERE confirmed = $conf_num;");
  } else {
    echo "Wrong confirmation number entered.";
  }
}
    
echo "</div>";    
include '../sidebar.php';
echo "</div>";
include '../footer.php'; 
?>
