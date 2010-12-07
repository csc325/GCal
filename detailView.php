<?php
    require_once "global.php";
    require_once "header.php";
?>

    <div class = "body">
        <div class = "col large">

    <?php
        $eventID = htmlspecialchars($_GET['eventID']);
        $eventArray = get_events(array($eventID));
        
        $query = "SELECT tag FROM tags WHERE eventID = $eventID;";
        $result = mysql_query($query);
        $tags = array();
        if($result) {
            while($row = mysql_fetch_array($result))
                $tags[] = $row[0];
        }
               
        $comment_query = "SELECT comments.comment, users.displayName FROM comments, users WHERE comments.userID = users.userID AND comments.eventID=$eventID;";
        $comment_result = mysql_query($comment_query);
        $comments = array();
        if($comment_result) {
            while($row = mysql_fetch_array($comment_result))
                $comments[$row[0]] = $row[1];
        }

        if ($eventArray === false) {       
            echo '<h1 class="head">No events were found</h1>';
                return false;
        }
         /* Values of $eventArray are:
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
         
        $event = array_map('stripslashes',$eventArray[0]);
        $startTime = strtotime($event[2].' '.$event[3]);
        $endTime = strtotime($event[4].' '.$event[5]);
        $event[2] = date('M j, Y',$startTime);
        $event[3] = date('g:i A',$startTime);
        $event[4] = date('M j, Y',$endTime);
        $event[5] = date('g:i A',$endTime);
        $dif_days = ($event[2] != $event[4]) ? true : false;    
        $user = get_user_info();
    ?>
            <div class="event_listing" id="<?php echo $eventID; ?>">
                <div class = "top_section">
                <h1>
                    <?php echo $event[0]; ?>
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
                    <span>Attending: <span class="val attend_count"><?php echo $event[9]; ?></span>
                    </span>
                    
                    <?php
                        display_attend($user[userID], $event[10]); 
                     ?>
                     
                </div>
                </div>
                <p><?php echo $event[1]; ?></p>

                <div class = "details">
                <span>Tags: <span class = "val tags">
                <?php
                    echo implode(", ", $tags);
                ?>
                </span></span></div>

                <div class = "details">
                <span>Created by: 
                <span class = "val"> <?php echo $event[8]; ?>
                </span></span></div>

            <?php 
                if(is_logged_in()) :
            ?>
                 <div class="details" id="addtag">
                 <a class="fake" id="fancy-login">
                 <span class="word">Add Tags</span>
                        <div class="login-form">
                            <label for="tag-list">Tags: (Comma separated tags)</label>
                            <input type="text" name="tag-list" id="tag-list">
                            <input type="button" value="Add" id="fancy-tag-button">
                        </div>
                    </a>
                </div>
            <?php
                endif;
            ?>

            <?php 
                    $user_query = 'SELECT userID FROM events WHERE eventID='. $eventID;
                    $user_result = mysql_query($user_query);
                    $row = mysql_fetch_array($user_result);
                    $owner = $row[0];

                    if(is_owner($owner) || is_admin()) :
            ?>

                <div class = "details">
                <a href="edit.php?eventID=<?php echo $eventID; ?>" class="edit" id="edit-event">
                <span class = "word">Edit Event Details 
                </a></span></div>

            <?php
                endif;
            ?>

                <div class = "details">
                <span>Comments: <span class = "val tags">
                <?php
                   foreach($comments as $comment => $user) 
                    echo '<br><br>'.$comment." [".$user."]";
                ?>
                </span></span></div>

                    <?php 
                if(is_logged_in()) :
                  ?>

                  <form method="post" action="<?php ed(); ?>submit_comment.php?eventID=<?php echo $eventID; ?>">
                  <textarea name="comment" cols="40" rows="3">Enter your comments here...</textarea><br>
                     <input type="submit" value="Add Comment" />
                     </form>

                     <?php
                     endif;
                     ?>
                
            </div>
        </div>
        
<?php 
    include 'sidebar.php';
    echo "</div>";
    include 'footer.php';
    echo "</body></html>";
?>
