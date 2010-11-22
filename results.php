<?php

   include('functions/searchfunctions.php');
   include('functions/connection.php');

$event = htmlspecialchars($_REQUEST["event"]);
$location = htmlspecialchars($_REQUEST["location"]);
$category = htmlspecialchars($_REQUEST["category"]);
$user = htmlspecialchars($_REQUEST["user"]);

$input = array();
$input[] = "locations.locationID = events.locationID";
$input[] = "categories.categoryID = events.categoryID";
$input[] = "users.userID = events.userID";

if(strlen($event) > 0)
  $input[] = "events.eventName = '$event'";
if(strlen($location) > 0)
  $input[] = "locations.locationName = '$location'";
if(strlen($category) > 0)
  $input[] = "categories.categoryName = '$category'";
if(strlen($user) > 0)
  $input[] = "users.displayName = '$user'";

$query = "SELECT events.eventID FROM events, locations, categories, users ";
$querypt2 = "WHERE " . implode(" AND ", $input) ;
$query .= $querypt2;

$resource = mysql_query($query);
if(!$resource)
  mysql_error();

$eventIDs = array();
while($row = mysql_fetch_row($resource))
  $eventIDs[] = $row[0];

listEvents($eventIDs);

mysql_close();
?>