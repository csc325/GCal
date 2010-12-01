<?php
/*Purpose of Script: Generate core RSS feed based on events table
 *Establishes its own connection to database
 */

//show RSS header
header("Content-Type: application/rss+xml; charset=ISO-8859-1"); 
/////////NOTES/////////////////
//fix the way link information is generated
$NUM_EVENTS = 6;

require_once ( 'functions/connection.php' ) ;

//get events ordered by most recently added
$event_query  = "SELECT * FROM events ORDER BY start DESC";
$event_result = mysql_query ($event_query);

//create opening tags for rss feed
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
echo "<rss version=\"2.0\">";
echo "<channel>";
echo "<title>GCal</title>";
echo "<description>Grinnell Open Calender</description>";
    ////////////////FIX HERE ADD LINK HERE///////////////
echo "<link>http://trololololololololololo.com/</link>";

//get $NUM_EVENTS events to display in rss feed
for ( $i = 0; $i < $NUM_EVENTS; $i++) {
  echo "<item>";
  echo "<title>"
    . htmlspecialchars (mysql_result ($event_result, $i, "eventName")) 
    . "</title>";

  echo "<description>"
    . htmlspecialchars (mysql_result ($event_result, $i, "description"))
    . "</description>";

  echo "<link>"
    ////////////////FIX HERE ADD LINK HERE///////////////
    . "http://trololololololololololo.com/" 
    . mysql_result ($event_result, $i, "eventID") //////
    . "</link>";

  echo "<guid>"
    ////////////////FIX HERE ADD LINK HERE///////////////
    . "http://trololololololololololo.com/" 
    . mysql_result ($event_result, $i, "eventID") //////
    . "</guid>";

    $category_query  = "SELECT * FROM categories WHERE categoryID=".mysql_result ($event_result, $i, "categoryID");
    $category_result = mysql_query ($category_query);

   echo "<category>"
    . mysql_result ($category_result, 0, "categoryName") 
    . "</category>";

  echo "</item>\n";

}

//finish rss feed
echo "</channel>";
echo "</rss>";

?>