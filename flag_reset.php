<?php
/*
* flag_reset : resets flag for an event to 0 in database
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
session_start();
require_once 'global.php';
        
// set table name
$eventID = addslashes($_GET["eventID"]);

//check that user is logged in and is either an admin or created the event
if(is_admin()) {

  //reset flag
  $flag_query = "UPDATE events
                       SET
                       flagged=0
                       WHERE eventID='$eventID'";
  $flag_result = mysql_query($flag_query);

  header('Location: '.ed(false).'flag_admin.php');
  exit();
} else {
  header('Location: '.ed(false).'index.php');
  exit();
}
?>