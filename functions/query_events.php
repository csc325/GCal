<?php
/*
* query_events: provides function to get data from events
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/

/*
* get comments from a given event
* @param int $eventID unique identifier for event
* @return array $comments comments for a given event
*/
function get_event_comments($eventID) 
{
  $comment_query = "SELECT comments.commentID, 
                                 comments.comment, 
                                 users.displayName, 
                                 comments.userID,
                                 comments.timestamp
                          FROM comments, users 
                          WHERE comments.userID = users.userID 
                            AND comments.eventID = $eventID;";
  $comment_result = mysql_query($comment_query);
  $comments = array();
  if(mysql_num_rows($comment_result) != 0)
    while($row = mysql_fetch_array($comment_result)) $comments[] = $row;
        
  return $comments;
}

/*
* accessor method to get number of flagged events
* @return int number of flagged events
*/
function get_number_of_flagged() 
{
  $flagged_query = "SELECT count(*) FROM events 
                                            WHERE events.flagged=1";
  $flagged_result = mysql_query($flagged_query);
  $row = mysql_fetch_array($flagged_result);
        
  return $row[0];
}

/*
* accessor method to get sum of number of flagged events
* @param int $eventID unique identifier for event
* @return array $comments comments for a given event
*/
function get_number_of_flaggedCount() 
{
  $flagged_query = "SELECT sum(flaggedCount) FROM events 
                                            WHERE events.flagged=1";
  $flagged_result = mysql_query($flagged_query);
  $row = mysql_fetch_array($flagged_result);
        
  return $row[0];
}

/*
* accessor method to get tag id from events
* @param string $tag
* @return array $eventIDs all events with given tag
*/ 
function get_tag_ids($tag) 
{
  $query = "SELECT DISTINCT tags.eventID
                  FROM tags, events
                  WHERE tags.tag = '$tag'
                  AND events.eventID = tags.eventID
                  AND events.startDate >= '".date('Y-m-d')."';";
       
  $result = mysql_query($query);
  if (mysql_num_rows($result) != 0) {
    $eventIDs = array();
    while($row = mysql_fetch_row($result)) $eventIDs[] = $row[0];
  } else {
    $eventIDs = false;
  }
           
  return $eventIDs;
}

/*
* accessor method to get ids (called by basic search)
* @return array $eventIDs all events
*/ 
function get_basic_search_ids () 
{
  $criteria = addslashes($_GET["input"]);
  $terms = explode(" ", $criteria);
        
  $input = array();
  $input[] = 'events.endDate >= "'.date('Y-m-d').'"';
  $input[] = "locations.locationID = events.locationID";
  $input[] = "categories.categoryID = events.categoryID";
  $input[] = "users.userID = events.userID";
  $input[] = "tags.eventID = events.eventID";

  $inputpt2 = array();
  foreach ($terms as $term) {
    if(strlen($term) > 0){
      $inputpt2[] = "(events.eventName LIKE '%$term%')";
      $inputpt2[] = "(events.description LIKE '%$term%')";
      $inputpt2[] = "(locations.locationName LIKE '%$term%')";
      $inputpt2[] = "(categories.categoryName LIKE '%$term%')";
      $inputpt2[] = "(users.displayName LIKE '%$term%')";
      $inputpt2[] = "(tags.tag LIKE '%$term%')";
    } else {
      return false;
    }
  }

  $query = "SELECT DISTINCT events.eventID 
            FROM events, locations, categories, users, tags ";
  $query .= "WHERE " . implode(" AND ", $input) ;
  $query .= " AND (" . implode(" OR ", $inputpt2) . ")" ;
       
  $resource = mysql_query($query);
           
  if (mysql_num_rows($resource) != 0) {
    $eventIDs = array();
    while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
  } else {
    $eventIDs = get_basic_search_ids_without_tags();
  }
           
  return $eventIDs;
}   

/*
* accessor method to get ids excluding tags (called by basic search)
* @return array $eventIDs all events
*/   
function get_basic_search_ids_without_tags () 
{
  $criteria = addslashes($_GET["input"]);
  $terms = explode(" ", $criteria);

  $input = array();
  $input[] = 'events.endDate >= "'.date('Y-m-d').'"';
  $input[] = "locations.locationID = events.locationID";
  $input[] = "categories.categoryID = events.categoryID";
  $input[] = "users.userID = events.userID";

  $inputpt2 = array();
  foreach ($terms as $term) {
    if(strlen($term) > 0){
      $inputpt2[] = "(events.eventName LIKE '%$term%')";
      $inputpt2[] = "(events.description LIKE '%$term%')";
      $inputpt2[] = "(locations.locationName LIKE '%$term%')";
      $inputpt2[] = "(categories.categoryName LIKE '%$term%')";
      $inputpt2[] = "(users.displayName LIKE '%$term%')";
    } else {
      return false;
    }
  }

  $query = "SELECT DISTINCT events.eventID 
            FROM events, locations, categories, users ";
  $query .= "WHERE " . implode(" AND ", $input) ;
  $query .= " AND (" . implode(" OR ", $inputpt2) . ")" ;
       
  $resource = mysql_query($query);
           
  if (mysql_num_rows($resource) != 0) {
    $eventIDs = array();
    while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
  } else {
    $eventIDs = false;
  }
           
  return $eventIDs;
}   
   
/*
* accessor method to get ids (called by advanced search)
* @return array $eventIDs all events
*/  
function get_advance_search_ids () 
{
  foreach($_GET as $field_name => $value){
    $$field_name = addslashes($value);
  }
       
  $input = array();

  $input[] = "locations.locationID = events.locationID";
  $input[] = "categories.categoryID = events.categoryID";

  if(strlen($eventName) > 0)
    $input[] = "events.eventName = '$event_name'";

  if(strlen($location) > 0 && $location != 'other')
    $input[] = "locations.locationName = '$location'";
           
  if($location == 'other')
    $input[] = "locations.locationName = '$location_other'";

  if(strlen($category) > 0 && $category != 'other')
    $input[] = "categories.categoryName = '$category'";
       
  if ($category == 'other')
    $input[] = "categories.categoryName = '$category_other'";
            
  if ($current == 'true') {
    $input_current = $input;
    $current_time = date('Y-m-d H:i:s');
    $input_current[] = "events.start <= '$current_time'";
    $input_current[] = "events.end >= '$current_time'";
  }
        
  if (strlen($start_date) > 0 && strlen($start_time) == 0) {

    $start_date = date('Y-m-d',strtotime($start_date));
    $input[] = "events.startDate >= '$start_date'";

  } elseif (strlen($start_time) > 0 && strlen($start_date) == 0) {

    $start_date = date('Y-m-d');
    $start_time = date('H:i:s',strtotime($start_time));
    $input[] = "events.startDate >= '$start_date'";
    $input[] = "events.startTime >= '$start_time'";

  } elseif (strlen($start) > 0) {

    $start = date('Y-m-d H:i:s',strtotime($start));
    $input[] = "events.start >= '$start'";

  } else {
    $start = date('Y-m-d H:i:s');
    $input[] = "events.start >= '$start'";
  }
        
  if (strlen($end_date) > 0 && strlen($end_time) == 0) {

    $end_date = date('Y-m-d',strtotime($end_date));
    $input[] = "events.endDate <= '$end_date'";

  } elseif (strlen($end_time) > 0 && strlen($end_date) == 0) {

    $end_date = date('Y-m-d');
    $end_time = date('H:i:s',strtotime($end_time));
    $input[] = "events.endDate <= '$end_date'";
    $input[] = "events.endTime <= '$end_time'";

  } elseif (strlen($end) > 0) {

    $end = date('Y-m-d H:i:s',strtotime($end));
    $input[] = "events.end <= '$end'";

  } else {

    $end = date('Y-m-d H:i:s');
    $input[] = "events.end >= '$end'";

  }
        
  $query = "SELECT events.eventID 
            FROM events, locations, categories ";
  $query .= "WHERE " . implode(" AND ", $input) . ";";

  $resource = mysql_query($query);
       
  if (mysql_num_rows($resource) != 0) {
    $eventIDs = array();
    while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
  } else {
    $eventIDs = false;
  }
        
  if ($current == 'true') {
    $query = "SELECT events.eventID 
              FROM events, locations, categories ";
    $query .= "WHERE " . implode(" AND ", $input_current) . ";";
    $resource = mysql_query($query);
    if (mysql_num_rows($resource) != 0) while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
  }
           
  return $eventIDs;
}


/*
* queries for all data associated with event id for all event ids provided
* @param array $eventIDs array of eventIds
* @param string $sort type of sort
* @param int $limit how many events to get
* @return array $eventIDs all events
*/  
function get_events($eventIDs,$sort='time',$limit=10) {
  if (!is_logged_in()) $public = "AND events.public = 0";
  if ($eventIDs === false) return false;
  $IDs = array();
  $results = array();
  $orderby = false;
       
  $query = "SELECT events.eventName,
                         events.description,
                         events.startDate,
                         events.startTime,
                         events.endDate,
                         events.endTime,
                         locations.locationName,
                         categories.categoryName,
                         users.displayName,
                         events.popularity,
                         events.eventID,
                         events.visible
                  FROM events, locations, categories, users
                  WHERE events.locationID=locations.locationID
                  AND events.categoryID=categories.categoryID
                  AND events.userID=users.userID
                  $public
                  AND (";
       
  foreach ($eventIDs as $id) {
    $IDs[] = "events.eventID=$id";
  }
       
  $query .= implode(" OR ", $IDs);
  $query .= ") ";
       
  if ($sort == 'time') {
    $query .= 'ORDER BY events.startDate ASC, 
                        events.startTime';
    $orderby = true;
  }
  if ($sort == 'popularity') {
    $query .= 'ORDER BY events.startDate ASC, 
                        events.popularity DESC, 
                        events.startTime';
    $orderby = true;
  }
  if ($sort == 'location') {
    $query .= 'ORDER BY events.startDate ASC, 
                        locations.locationName ASC, 
                        events.startTime';
    $orderby = true;
  }
  if ($sort == 'category') {
    $query .= 'ORDER BY events.startDate ASC, 
                        categories.categoryName ASC, 
                        events.startTime';
    $orderby = true;
  }
  if(!$orderby){
    $query .= 'ORDER BY events.startTime';
  }
       
  $query .= ' LIMIT '.$limit;
  $result = mysql_query($query);
     
  if (mysql_num_rows($result) != 0) {
    while($row = mysql_fetch_row($result))$results[] = $row;
    return $results;
  } else {
    return false;
  }
}

   
  function get_conflicting_event_IDs($locationID, $start, $end) {
	$query = "SELECT events.eventID
			  FROM events
			  WHERE locationID=$locationID
       	          AND (
       	               (start >= '$start' AND start <= '$end')
       	            OR (end >= '$start' AND end <= '$end')
       	              )";
	$result = mysql_query($query);
		
	if (mysql_num_rows($result) != 0) {
		$eventIDs = array();
		while($row = mysql_fetch_row($result)) $eventIDs[] = $row[0];
	} else {
		$eventIDs = false;
	}

		return $eventIDs;
}

?>
