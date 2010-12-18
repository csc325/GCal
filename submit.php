<?php
    session_start();
    require_once 'global.php';
	   	
    $userID = ($_SESSION['userID'] != '') ? $_SESSION['userID'] : 2;
    
    // get variables from form page
    foreach($_POST as $field_name => $value) $$field_name = addslashes($value);
    echo $eventID;
    // Category and Location processing
    if ($location == 'other') {     
      $location = $location_other;
      if(strlen($location) == 0) 
        $location = 'other';
    }

    if ($category == 'other') {
      $category = $category_other;
      if(strlen($category) == 0) 
        $category = 'other';
    }

    //check required fields
    if((strlen($event_name) == 0) || (strlen($start_date) == 0) || 
       (strlen($end_date) == 0) || (strlen($location) == 0) || 
       (strlen($start_time) == 0) || (strlen($end_time) == 0) ||
       (strlen($category) == 0)) {
      header('Location: '.ed(false).'forms.php?s=f');
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
    if(($start < $right_now) || ($end < $right_now) || ($end < $start)) {
      header('Location: '.ed(false).'forms.php?s=time');
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

    //queries for location and category
    $query = 'INSERT INTO locations (locationName) 
              VALUES ("'.$location.'") 
              ON DUPLICATE KEY 
              UPDATE requestCount = requestCount + 1';
    mysql_query($query);
    $locationID = mysql_insert_id();
   
    $query = 'INSERT INTO categories (categoryName) 
              VALUES ("'.$category.'") 
              ON DUPLICATE KEY 
              UPDATE requestCount = requestCount + 1';
    mysql_query($query);
    $categoryID = mysql_insert_id();
    
    
    // Events table query
    $event_query = "INSERT INTO events";
    $event_query .= "( userID, 
                       locationID, 
                       categoryID, 
                       dateAdded, 
                       startDate, startTime, 
                       endDate, endTime, 
                       start, end, 
                       public, 
                       description, 
                       eventName ) ";
    $event_query .= " VALUES ( $userID, 
                       $locationID, 
                       $categoryID, 
                       '$right_now', 
                       '$start_date', '$start_time', 
                       '$end_date', '$end_time', 
                       '$start', '$end', 
                       $publicity, 
                       '$description', 
                       '$event_name');";
    
    $event_result = mysql_query($event_query);
    $eventID = mysql_insert_id($link);
        
    // Tags table query
    if (count($tags) == 1 && ($tags[0] == '' || $tags[0] == ' ')) {
    } else {
        foreach ($tags as $tag) {
            $tags_query = 'INSERT INTO tags (tag,eventID) VALUES ("'.$tag.'",'.$eventID.')';
            $tag_result = mysql_query($tags_query);
        }
    }
    
    // If there are conflicts, alert user
	$conflicting_eventIDs = get_conflicting_event_IDs($locationID, $start, $end);
	//print_r($conflicting_eventIDs);
	if ($conflicting_eventIDs) {
		$header_arr = array("eventID=$eventID");
		foreach ($conflicting_eventIDs as $conflicting_eventID) {
			$header_arr[] = "$conflicting_eventID=conflict";
		}
		header('Location: '.ed(false).'conflicts.php?'.implode('&', $header_arr));
	}
	else {
	    header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
	}
?>
