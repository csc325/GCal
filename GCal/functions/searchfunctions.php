<?php

   include('ipcheck.php');

function listView($info){

echo "<table border=1>";
      echo '<tr>';
      echo "<td colspan=3>Event</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>Start Time</td>";
      echo "<td>Location</td>";
      echo "<td>Category</td>";
      echo "</tr>";
  echo '</table>';

echo "<table>";
foreach ($info as $event) {
  if(($event[5]==0) || IPCheck()){
      echo "<tr>";
      echo '<td colspan=3><a target="_blank" href="event.php?eventID='.$event[4].'">'.$event[0].'</a></td>';
      echo "</tr>";
      echo "<tr>";
      echo "<td>".$event[1]."</td>";
      echo "<td>".$event[2]."</td>";
      echo "<td>".$event[3]."</td>";
      echo "</tr>";
  }
  else;
}

echo '</table>';
}

function detailedView($info){
  $size = count($info);

  echo "<table border=1>";
      echo "<tr>";
      echo "<td colspan=4>Event</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td colspan=4>Description</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>Start Time</td>";
      echo "<td>End Time</td>";
      echo "<td>Location</td>";
      echo "<td>Category</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>User</td>";
      echo "<td colspan=3>Mail</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td colspan=4>Tags</td>";
      echo "</tr>";
  echo '</table>';

  if(($info[8]==0) || IPCheck()){
  echo "<table>";
      echo "<tr>";
      echo "<td colspan=4>".$info[0]."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td colspan=4>".$info[1]."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>".$info[2]."</td>";
      echo "<td>".$info[3]."</td>";
      echo "<td>".$info[4]."</td>";
      echo "<td>".$info[5]."</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>".$info[6]."</td>";
      $mail = explode("@", $info[7]);
      echo '<td colspan=3><a href="mailto:'.$info[7].'">['.$mail[0].']</a></td>';
      echo "</tr>";

      echo "<tr>";
      echo "<td colspan=4>";
      for($i = 9; $i < $size; $i++)
        echo $info[$i]."  ";
      echo "</td>";
      echo "</tr>";
  echo '</table>';
  }
}

function listEvents($eventIDs) {
  $IDs = array();
  $results = array();

  $query = "SELECT events.eventName, 
                                  events.startTime,
                                  locations.locationName,
                                  categories.categoryName,
                                  events.eventID,
                                  events.public
                    FROM events, locations, categories
                    WHERE events.locationID=locations.locationID
                          AND events.categoryID=categories.categoryID
                          AND (";

  foreach ($eventIDs as $id) {
    $IDs[] = "events.eventID=".$id;
  }

  $query .= implode(" OR ", $IDs);
  $query .= ")";

  
  $result = mysql_query($query);
     if (!$result) {
       mysql_close();
       die("Sorry... There are no events matching your search criteria... " . mysql_error());
     }

  while($row = mysql_fetch_row($result)){
    $results[] = $row;
  }

  listView($results);
  return $results;
}

function detailedEvent($eventID) {
  $results = array();

  $query = "SELECT events.eventName, 
                                  events.description,
                                  events.startTime,
                                  events.endTime,
                                  locations.locationName,
                                  categories.categoryName,
                                  users.displayName,
                                  users.email,
                                  events.public
                    FROM events, locations, categories, users
                    WHERE events.userID=users.userID
                          AND events.locationID=locations.locationID
                          AND events.categoryID=categories.categoryID
                          AND events.eventID=$eventID";

  $tagQuery = "SELECT tags.tag
                    FROM tags
                    WHERE tags.eventID=$eventID";
  
  $result = mysql_query($query);
     if (!$result) {
       mysql_close();
       die("Query failed when getting event detailed view: " . mysql_error());
     }
  $results = mysql_fetch_row($result);  

  $tagResult = mysql_query($tagQuery);
     if (!$tagResult) {
       mysql_close();
       die("Query failed when getting : " . mysql_error());
     }
  while($tagRow = mysql_fetch_row($tagResult)){
    $results[] = $tagRow[0];
  }

  detailedView($results);
  return $results;
}

function homePageEvents($eventIDs) {
}

?> 

