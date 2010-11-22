<?php
    include 'listView.php';

    $criteria = trim( htmlspecialchars($_REQUEST["input"]));
    $terms = explode(" ", $criteria);

    $input = array();
    $input[] = "locations.locationID = events.locationID";
    $input[] = "categories.categoryID = events.categoryID";
    $input[] = "users.userID = events.userID";
    $input[] = "tags.eventID = events.eventID";

    $inputpt2 = array();
    foreach ($terms as $term) {
        if(strlen($term) > 0){
            $inputpt2[] = "(events.eventName LIKE '%$term%')";
            $inputpt2[] = "(locations.locationName LIKE '%$term%')";
            $inputpt2[] = "(categories.categoryName LIKE '%$term%')";
            $inputpt2[] = "(users.displayName LIKE '%$term%')";
            $inputpt2[] = "(tags.tag LIKE '%$term%')";
        } else {
            echo "Please give some criteria to search for...";
            exit();
        }
    }

    $query = "SELECT events.eventID FROM events, locations, categories, users, tags ";
    $query .= "WHERE " . implode(" AND ", $input) ;
    $query .= " AND (" . implode(" OR ", $inputpt2) . ")" ;

    $resource = mysql_query($query);
    if(!$resource)
        mysql_error();

    $eventIDs = array();
    while($row = mysql_fetch_row($resource))
        $eventIDs[] = $row[0];

    listEvents($eventIDs);
    mysql_close();
?>
