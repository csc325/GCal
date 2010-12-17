<?php
/*
* delete_event: script called to remove event
* Given: eventID
*
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category manage event function
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
session_start();
require_once 'global.php';
        
// set table name
$event_id = addslashes($_GET["eventID"]);

//check that user is logged in and is either an admin or created the event
if(is_logged_in()) {
  $user_id = $_SESSION['userID'];
        
  //check if the user created the event
  $user_query = "SELECT userID
                 FROM events
                 WHERE eventID = $eventID";

  //check if the user is an admin
  $access_query = "SELECT accessLevel
                   FROM users
                   WHERE userID = $userID";

  $user_result   = mysql_query($user_query);
  $access_result = mysql_query($access_query);

  $user_row   = mysql_fetch_row($user_result);
  $access_row = mysql_fetch_row($access_result);
        
  if(($user_row[0] == $user_id) || ($access_row[0] == 3)) {

    //delete tags
    $query = "DELETE FROM tags 
              WHERE eventID = $eventID;";
    mysql_query($query);

    //delete comments
    $query = "DELETE FROM comments 
              WHERE eventID = $eventID;";
    mysql_query($query);

    //delete attendees
    $query = "DELETE FROM attendees 
              WHERE eventID = $eventID;";
    mysql_query($query);

    //delete the event
    $query = "DELETE FROM events 
              WHERE eventID = $eventID;";
    mysql_query($query);

    header('Location: '.ed(false).'index.php?delete=t&eventID='.$eventID);
    exit();
  }
} else {
  header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
  exit();
}
?>