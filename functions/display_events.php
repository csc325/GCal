<?php
    function display_attend ($userID,$eventID) {
        if (is_logged_in()) {
            if (is_attending($userID,$eventID)) {
                echo "<a id='event_{$eventID}_{$userID}' class='attend_event attending'>
                      Attending <span class='cancel'>X</span>";
            } else {
                echo "<a id='event_{$eventID}_{$userID}' class='attend_event'>";
                echo "Attend!";
            }
            
            echo "</a>";
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
            // Stripslashes and sanitize output.
            $event = array_map('stripslashes',$event);
            $event = array_map('htmlspecialchars',$event);
            
            $startTime = strtotime($event[2].' '.$event[3]);
            $endTime = strtotime($event[4].' '.$event[5]);
            
            $happening_now = ((time() > $startTime) && (time() < $endTime)) ? true : false;
            
            $event[2] = date('M j, Y',$startTime);
            $event[3] = date('g:i A',$startTime);
            $event[4] = date('M j, Y',$endTime);
            $event[5] = date('g:i A',$endTime);
            
            $dif_days = ($event[2] != $event[4]) ? true : false;
            
            
            /* Display the header only if the sort group isn't already part of
               the array $group that keeps track of all the headers that have
               already been displayed  */
            if(!in_array($event[$check],$group)) {
                if ($sort) {
                    if ($sort == 'time' && $event[2] == $today && !$happening_now) {
                        echo '<h3 class="time_label">Today</h3>';
                    } elseif ($sort == 'time' && $event[2] == $tomorrow && !$happening_now) {
                        echo '<h3 class="time_label">Tomorrow</h3>';
                    } elseif ($happening_now && !$happening_now_display) {
                        echo '<h3 class="time_label now">Happening Now</h3>';
                        $happening_now_display = true;
                    } elseif (!$happening_now) {
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
?>
