<?php
    include 'functions/connection.php';
    
    if (!isset($_SESSION['userID'])) {
        $userID = 2;
    }
    
    // set table name
    $table_name = "events";
    
    // get variables from form page
    foreach($_POST as $field_name => $value){
        $$field_name = addslashes($value);
    }
    
    // process start and end times
    $start_temp = explode(' ',date('Y-m-d H:i:s', strtotime($start_date . " ". $start_time)));
    $end_temp = explode(' ',date('Y-m-d H:i:s', strtotime($end_date . " " . $end_time)));
    
    $start_date = $start_temp[0];
    $start_time = $start_temp[1];
    $end_date = $end_temp[0];
    $end_time = $end_temp[1];
    $start = $start_date.' '.$start_time;
    $end = $end_date.' '.$end_time;
    
    // sanitize description box
    $description = htmlspecialchars($description);
    
    // parse tags
    $tags = explode(',',$tags);
    $tags = array_map('trim',$tags);
    
    // process publicity
    if ($public == "yes") {
        $publicity = 1;
    } else {
        $publicity = 0;
    }
    
    // ** Proper error handling needed **
    
    // Category and Location table query
    if ($location == 'other') {
        $query = 'INSERT INTO locations (locationName) VALUES ("'.$location_other.'")';
        mysql_query($query);
        $location = mysql_insert_id();
    }
    
    if ($category == 'other') {
        $query = 'INSERT INTO categories (categoryName) VALUES ("'.$category_other.'")';
        mysql_query($query);
        $category = mysql_insert_id();
    }
    
    // Events table query
    // ** Need userID **
    $event_query = "INSERT INTO events";
    $event_query .= "( userID, locationID, categoryID, startDate, startTime, endDate, endTime, start, end, public, description, eventName ) ";
    $event_query .= " VALUES ( $userID, $location, $category, '$start_date', '$start_time', '$end_date', '$end_time', '$start', '$end', $publicity, '$description', '$event_name') ";
    $event_result = mysql_query($event_query);
    $eventID = mysql_insert_id($db_connection);
        
    // Tags table query
    foreach ($tags as $tag) {
        $tags_query = 'INSERT INTO tags (tag,eventID) VALUES ("'.$tag.'",'.$eventID.')';
        $tag_result = mysql_query($tags_query);
    }

    header('Location: http://www.cs.grinnell.edu/~liujingt/CSC325/project/forms.php?s=t');
?>
