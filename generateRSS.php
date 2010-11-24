<?php
/*Purpose of Script: Generate RSS feed based on events table
 */


/////////NOTES///////////////
//fix the way link information is generated
$NUM_EVENTS = 20;


// set certain php.ini variables to exclude server-side session error messages.
ini_set ('session.bug_compat_42', 1) ;
ini_set ('session.bug_compat_warn', 0) ;

//establish persistant connection to database
$db_connection = mysql_pconnect ('localhost', 'csc325generic','password') ;
$db            = mysql_select_db ('CSC325') ;

//get events ordered by most recently added
$event_query  = "SELECT * FROM events ORDER BY start DESC";
$event_result = mysql_query ($event_query);

//create opening tags for rss feed
echo "<?xml version=\"1.0\"?>";
echo "<rss version=\"2.0\">";
echo "<channel>";

//get $NUM_EVENTS events to display in rss feed
for ( $i = 0, $i < $NUM_EVENTS; $i++) {
  echo "<item>";
  echo "<title>"
    . mysql_result ($event_result, $i, "eventName") 
    . "</title>";

  echo "<description>"
    . mysql_result ($event_result, $i, "description") 
    . "</description>";

  echo "<link>"
    ////////////////FIX HERE ADD LINK HERE///////////////
    . "http://trololololololololololo.com/" 
    . "</link>";

  echo "</title>";

}

//finish rss feed
echo "</channel>";
echo "</rss>";

?>