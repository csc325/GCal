<?php
    session_start();
    require_once 'global.php';
    
    $userID = ($_SESSION['userID'] != '') ? $_SESSION['userID'] : 2;
    
    // set table name
    $eventID = $_REQUEST["eventID"];
    $eventID = htmlspecialchars($eventID);

    // get variables from form page
    foreach($_POST as $field_name => $value) $$field_name = addslashes($value);

    //check required fields
    if((strlen($event_name) == 0) || (strlen($start_date) == 0) ||
       (strlen($end_date) == 0) || (strlen($location) == 0) ||
       (strlen($start_time) == 0) || (strlen($end_time) == 0) ||
       (strlen($category) == 0)) {
      header('Location: '.ed(false).'edit.php?s=f&eventID='.$eventID);
      exit();
    }

    // process start and end times
    $start_temp = explode(' ',date('Y-m-d H:i:s', strtotime($start_date . " ". $start_time)));
    $end_temp = explode(' ',date('Y-m-d H:i:s', strtotime($end_date . " " . $end_time)));
    $right_now = date('Y-m-d H:i:s');
    
    $start_date = $start_temp[0];
    $start_time = $start_temp[1];
    $end_date = $end_temp[0];
    $end_time = $end_temp[1];
    $start = $start_date.' '.$start_time;
    $end = $end_date.' '.$end_time;
    if(($start < $right_now) || ($end < $right_now)) {
      header('Location: '.ed(false).'edit.php?time=f&s=t&eventID='.$eventID);
      exit();
    }    


    // sanitize description box
    $description = htmlspecialchars($description);
    
    // parse tags
    $tags = explode(',',$tags);
    $tags = array_map('trim',$tags);
    
    // process publicity
    if ($public == "yes") $publicity = 1;
    else $publicity = 0;
    
    // Category and Location table query
    if ($location == 'other') $location = $location_other;
    $query = 'INSERT INTO locations (locationName) 
              VALUES ("'.$location.'")
              ON DUPLICATE KEY 
              UPDATE requestCount = requestCount';
    mysql_query($query);
    $locationID = mysql_insert_id();
    
    if ($category == 'other') $category = $category_other;
    $query = 'INSERT INTO categories (categoryName) 
              VALUES ("'.$category.'")
              ON DUPLICATE KEY 
              UPDATE requestCount = requestCount';
    mysql_query($query);
    $categoryID = mysql_insert_id();
    
    
    // Events table query
    $event_query = "UPDATE events";
    $event_query .= " SET
                       locationID=$locationID, 
                       categoryID=$categoryID, 
                       startDate='$start_date', startTime='$start_time', 
                       endDate='$end_date', endTime='$end_time', 
                       start='$start', end='$end', 
                       public=$publicity,
                       description='$description', 
                       eventName='$event_name' ";
    $event_query .= " WHERE eventID='$eventID';";
    $event_result = mysql_query($event_query);
        
    // Tags table query
   $tags_clean_query = 'DELETE FROM tags WHERE eventID='.$eventID;
   $tag_clean_result = mysql_query($tags_clean_query);

    if (count($tags) == 1 && ($tags[0] == '' || $tags[0] == ' ')) {
    } else {
        foreach ($tags as $tag) {
            $tags_query = 'INSERT INTO tags (tag,eventID) VALUES ("'.$tag.'",'.$eventID.')';
            $tag_result = mysql_query($tags_query);
        }
    }
    
    header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
    
?>
