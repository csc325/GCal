<?php
/*
* user_session.php
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

/*
* checks to see if current user is ownder of event
* @param int $eventID unique identifier of event
* @return boolean true if user is owner, false if otherwise
*/     
function is_owner ($eventID) 
{
  $user_query = 'SELECT userID FROM events WHERE eventID='. $eventID;
  $user_result = mysql_query($user_query);
  $row = mysql_fetch_array($user_result);
  $owner = $row[0];
  return ($_SESSION['userID'] == $owner) ? true : false;
}
  
/*
* adds comment to event
* @param array $args data to aid in addition of comment
* @return int 1 also adds comment
*/   
function add_comment ($args) 
{
  if (!is_logged_in()) return 0;
        
  $comment = trim(htmlspecialchars($args["comment"]));
  $eventID = addslashes($args["eventID"]);
  $userID = $_SESSION['userID'];
  $timestamp = date('Y-m-d H:i:s');
        
  if ($comment != '' && comment != ' ') {
    $query = "INSERT INTO comments (comment, eventID, userID, timestamp) 
                      VALUES ('$comment',$eventID,$userID,'$timestamp')";
    $result = mysql_query($query);
  }
        
  return 1;
}
   
/*
* deletes comment from event
* @param array $args data to aid in removal
* @return int 1, also deletes comment
*/ 
function delete_comment ($args) 
{
  $commentID = addslashes($args["commentID"]);
  $eventID = addslashes($args["eventID"]);
  $owner = addslashes($args["owner"]);

  if(is_owner($eventID) || is_admin()) {
    $query = 'DELETE FROM comments WHERE commentID='.$commentID;
    $result = mysql_query($query);
  }
        
  return 1;
}

/*
* increments popularity and adds user to attendees
* @param array $args data to aid in removal
* @return int 1, sets as attending
*/     
function attend_event($args)
{
  $eventID = addslashes($args[eventID]);
  $userID = addslashes($args[userID]);
  $query = "INSERT INTO attendees (userID, eventID) 
            VALUES ($userID, $eventID);";
  $result = mysql_query($query);
  $query2 = "UPDATE events 
             SET popularity = popularity + 1 
             WHERE eventID = $eventID;";
  $result2 = mysql_query($query2);
  return ($result && $result2) ? 1 : 0;
}
    
/*
* decrements popularity and adds user to attendees
* @param array $args data to aid in removal
* @return int 1 or 0 based on success
*/ 
function cancel_attend($args) 
{
  $eventID = addslashes($args[eventID]);
  $userID = addslashes($args[userID]);
  $query = "DELETE FROM attendees 
            WHERE userID = $userID AND eventID = $eventID;";
  $result = mysql_query($query);
  $query2 = "UPDATE events 
             SET popularity = popularity - 1 
             WHERE eventID = $eventID;";
  $result2 = mysql_query($query2);
  return ($result && $result2) ? 1 : 0;
}

/*
* hides event by adding user and event to hidden, or shows by removing
* @param array $args data to aid in hiding event
* @return int 1, sets as hidden
*/
function hide_event($args)
{
  $eventID = addslashes($args[eventID]);
  $userID = addslashes($args[userID]);
  if(is_hidden($userID, $eventID)
      $query = "INSERT INTO hidden (userID, eventID)
            VALUES ($userID, $eventID);";
  else
      $query = "DELETE FROM hidden 
                WHERE userID = $userID AND eventID = $eventID;";
  $result = mysql_query($query);
  return ($result) ? 1 : 0;
}
 
/*
* sets event as flagged
* @param array $args data to aid in removal
* @return int 1 or 0 based on success
*/    
function flag_event($args) 
{
  $eventID = addslashes($args[eventID]);
  $flagged = intval($args[flagged]) + 1;
  $query = "UPDATE events SET flagged = $flagged WHERE eventID = $eventID";
  mysql_query($query);
  return $result ? 1 : 0;
}
    
    
/*  SESSION & USER ---------------------------------------------------- */
    
/*
* checks if user is logged in
* @return boolean
*/ 
function is_logged_in () 
{
  return ($_SESSION['sid'] == session_id()) ? true : false;
}
    
/*
* gets all data about user from database
* @return user data row result
*/ 
function get_user_info () 
{
  if (is_logged_in()) {
    $query = 'SELECT *  
              FROM users 
              WHERE displayName = "'.$_SESSION['displayName'].'"
                AND userID = '.$_SESSION['userID'];
    $result = mysql_query($query);
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    return $row;
  }
  return false;
}

/*
* checks to see if user is admin
* @return boolean
*/ 
function is_admin () 
{
  $user = get_user_info();
  return ($user["accessLevel"] == 3) ? true : false;
}
  
/*
* checks to see if user is on campus
* @return boolean
*/  
function on_campus() 
{ 
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $UserIP = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
  else $UserIP = trim($_SERVER['REMOTE_ADDR']);
        
  return (strncmp($UserIP, "132.161", 7) == 0) ? TRUE : FALSE;
}
/*
* checks to see if user is attending
* @param int $userID
* @param int $eventID
* @return boolean
*/    
function is_attending($userID, $eventID) {
  if(is_logged_in()) {
    $query = "SELECT attendeeID FROM attendees
                      WHERE userID = $userID
                      AND eventID = $eventID";
    $result = mysql_query($query);
    if ($result)
      if(mysql_num_rows($result) > 0) return true;
  }
  return false;
}
/*
* checks to see if user has hidden the event
* @param int $userID
* @param int $eventID
* @return boolean
*/
function is_hidden($userID, $eventID) {
  if(is_logged_in()) {
    $query = "SELECT hiddenID FROM hidden
                      WHERE userID = $userID
                      AND eventID = $eventID";
    $result = mysql_query($query);
    if ($result)
      if(mysql_num_rows($result) > 0) return true;
  }
  return false;
}
?>
