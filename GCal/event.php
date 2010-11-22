<?php
   include('functions/connection.php');
   include('functions/searchfunctions.php');

$eventID = htmlspecialchars($_REQUEST["eventID"]);

detailedEvent($eventID);

mysql_close();
?>