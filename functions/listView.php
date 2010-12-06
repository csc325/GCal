<?php
    function attend_event($eventID, $userID) {
        $query = "INSERT INTO attendees (userID, eventID) VALUES ($userID, $eventID);";
        $result = mysql_query($query);
        
        $query2 = "UPDATE events SET popularity = popularity + 1 WHERE eventID = $eventID;";
        $result2 = mysql_query($query2);
    }
    
    function cancel_attend($eventID, $userID) {
        $query = "DELETE FROM attendees WHERE userID = $userID AND eventID = $eventID;";
        $result = mysql_query($query);
        
        $query2 = "UPDATE events SET popularity = popularity - 1 WHERE eventID = $eventID;";
        $result2 = mysql_query($query2);
    }
    
    function get_tag_ids($tag) {
        $query = "SELECT DISTINCT tags.eventID
                  FROM tags, events
                  WHERE tags.tag = '$tag'
                  AND events.eventID = tags.eventID
                  AND events.startDate >= '".date('Y-m-d')."';";
       
        $result = mysql_query($query);
        if ($result) {
            $eventIDs = array();
            while($row = mysql_fetch_row($result)) $eventIDs[] = $row[0];
        } else {
            $eventIDs = false;
        }
           
        return $eventIDs;
    }
   
    function get_basic_search_ids () {
        $criteria = addslashes($_GET["input"]);
        $terms = explode(" ", $criteria);

        $input = array();
        $input[] = "locations.locationID = events.locationID";
        $input[] = "categories.categoryID = events.categoryID";
        $input[] = "users.userID = events.userID";
        $input[] = "tags.eventID = events.eventID";
        $input[] = "events.startDate >= \"".date('Y-m-d')."\"";

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

        $query = "SELECT events.eventID FROM events, locations, categories, users, tags ";
        $query .= "WHERE " . implode(" AND ", $input) ;
        $query .= " AND (" . implode(" OR ", $inputpt2) . ")" ;
       
        $resource = mysql_query($query);
           
        if ($resource) {
            $eventIDs = array();
            while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
        } else {
            $eventIDs = false;
        }
           
        return $eventIDs;
    }   
   
    function get_advance_search_ids () {
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

        if(strlen($start_date) > 0) {
            $start_date = date('Y-m-d',strtotime($start_date));
            $input[] = "events.startDate >= '$start_date'";
        } else {
            $start_date = date('Y-m-d');
            $input[] = "events.startDate >= '$start_date'";
        }
       
        if(strlen($start_time) > 0) {
            $start_time = date('H:i:s',strtotime($start_time));
            $input[] = "events.startTime >= '$start_time'";
        }

        if(strlen($end_date) > 0) {
            $end_date = date('Y-m-d',strtotime($end_date));
            $input[] = "events.endDate <= '$end_date'";
        } else {
            $end_date = date('Y-m-d');
            $input[] = "events.endDate >= '$end_date'";
        }
       
        if(strlen($end_time) > 0) {
            $end_time = date('H:i:s',strtotime($end_time));
            $input[] = "events.endTime <= '$end_time'";
        }

        $query = "SELECT events.eventID FROM events, locations, categories ";
        $query .= "WHERE " . implode(" AND ", $input) . ";";

        $resource = mysql_query($query);
       
        if ($resource) {
            $eventIDs = array();
            while($row = mysql_fetch_row($resource)) $eventIDs[] = $row[0];
        } else {
            $eventIDs = false;
        }
           
        return $eventIDs;
    }

    function get_events($eventIDs,$sort='time',$limit=10) {
        if (!is_logged_in()) $public = "AND events.public = 0";
        if ($eventIDs === false) return false;
        $IDs = array();
        $results = array();
       
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
                         events.eventID
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
       
        if ($sort == 'time') $query .= 'ORDER BY events.startDate ASC';
        if ($sort == 'popularity') $query .= 'ORDER BY events.popularity DESC';
        if ($sort == 'location') $query .= 'ORDER BY locations.locationName ASC';
        if ($sort == 'category') $query .= 'ORDER BY categories.categoryName ASC';
       
        $query .= ' LIMIT '.$limit;
       
        $result = mysql_query($query);
        if ($result) {
            while($row = mysql_fetch_row($result)){
                $results[] = $row;
            }
       
            return $results;
        } else {
            return false;
        }
    }

    function display_events_inter($info,$sort='time'){
        $today = date('M j, Y');
        $tomorrow = date('M j, Y',strtotime('tomorrow'));
       
        if ($sort == 'time') $check = 2;
        if ($sort == 'popularity') $check = 9;
        if ($sort == 'location') $check = 6;
        if ($sort == 'category') $check = 7;
       
        /* Values of $info are:
           [0] = eventName
           [1] = description
           [2] = startDate
           [3] = startTime
           [4] = endDate
           [5] = endTime
           [6] = locationName
           [7] = categoryName
           [8] = user displayName
           [9] = popularity
           [10] = eventID */
       
        if ($info === false) {
            echo '<h1 class="head">No events were found</h1>';
            return false;
        }
       
        $group = array();
        foreach ($info as $event) :
            $event = array_map('stripslashes',$event);
           
            $startTime = strtotime($event[2].' '.$event[3]);
            $endTime = strtotime($event[4].' '.$event[5]);
            $event[2] = date('M j, Y',$startTime);
            $event[3] = date('g:i A',$startTime);
            $event[4] = date('M j, Y',$endTime);
            $event[5] = date('g:i A',$endTime);
            $dif_days = ($event[2] != $event[4]) ? true : false;
           
            if(!in_array($event[$check],$group)) {
                if ($sort) {
                    if ($sort == 'time' && $event[2] == $today) {
                        echo '<h3 class="time_label">Today</h3>';
                    } elseif ($sort == 'time' && $event[2] == $tomorrow) {
                        echo '<h3 class="time_label">Tomorrow</h3>';
                    } else {
                        echo '<h3 class="time_label">'.$event[$check].'</h3>';
                    }
                }
               
                $group[] = $event[$check];
            }
            
            $user = get_user_info();
            ?>
                <div class="event_listing">
                    <h1>
                     <?php 
                        $path = ed(false);
                        echo "<a href = " . $path . "detailView.php?eventID={$event[10]}>{$event[0]}</a>";
                     ?>
                        <span class="date"><?php echo $event[2].(($dif_days) ? ' to '.$event[4] : ''); ?></span>
                    </h1>
                    <div class="details">                        
                        
                        <span>
                            When:
                            <span class="val">
                            <?php
                                echo ((!$dif_days) ? "$event[3] - $event[5]"
                                                   : "$event[3] - $event[5]");
                            ?>
                            </span>
                        </span>
                        <span>What: <span class="val"><?php echo $event[7]; ?></span></span>
                        <span>Where: <span class="val"><?php echo $event[6]; ?></span></span>
                        <span>Attending: <span class="val attend_count"><?php echo $event[9]; ?></span></span>
                        
                        <?php display_attend($user[userID],$event[10]); ?>
                    </div>
                    
                    <p><?php echo $event[1]; ?></p> 
                </div>
            <?php
        endforeach;
    }
    
    if ($_POST['action'] == 'attend') {
        require_once 'connection.php';
        $eventID = addslashes($_POST['eventID']);
        $userID = addslashes($_POST['userID']);
        attend_event($eventID, $userID);
        echo 1; exit();
    }
    
    if ($_POST['action'] == 'cancel') {
        require_once 'connection.php';
        $eventID = addslashes($_POST['eventID']);
        $userID = addslashes($_POST['userID']);
        cancel_attend($eventID, $userID);
        echo 1; exit();
    }
?>
