<?php
/*Purpose of Script: Generate core RSS feed based on events table
 *Establishes its own connection to database
 */


/////////NOTES/////////////////
//fix the way link information is generated
$NUM_EVENTS = 8;

require_once ( 'functions/connection.php' ) ;

//get events ordered by most recently added
$event_query  = "SELECT * FROM events ORDER BY start DESC";
$event_result = mysql_query ($event_query);

//create opening tags for rss feed
echo "<?xml version=\"1.0\"?>";
echo "<rss version=\"2.0\">";
echo "<channel>";

//get $NUM_EVENTS events to display in rss feed
for ( $i = 0; $i < $NUM_EVENTS; $i++) {
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