<?php
/*
* generateRSS.php:Generate core RSS feed based on events table
* Establishes its own connection to databas
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

//show RSS header
header("Content-Type: application/rss+xml; charset=ISO-8859-1"); 
/////////NOTES/////////////////
//fix the way link information is generated
$NUM_EVENTS = 10;

require_once ( 'functions/connection.php' ) ;

//get events ordered by most recently added
$start_date = date('Y-m-d');
$start_time = date('H:i:s',strtotime($start_time));
$event_query  = "SELECT * FROM events WHERE events.endDate >= '$start_date' ORDER BY startDate ASC, startTime ASC";
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
