<?php 
    require_once 'global.php';

    if($_POST['action'] != 'update') echo '<div class="col small side">';
?>
    <div class="unit">
        <h3>Search Events</h3>
        <form method="get" action="<?php ed(); ?>results.php">
            <input type="text" name="input">
            <input type="hidden" name="t" value="b">
            <input type="submit" value="Search"><br>
            <a class = "small" href = "<?php ed(); ?>search.php">Advanced search</a>
        </form>
    </div>
    
    <div class="unit add-event">
        <h3><a href="forms.php">Add Event</a></h3>
    </div>
    
    <?php if(is_logged_in()) { ?>
    <div class="unit my-events">
        <h3>My Upcoming Events</h3>
        <ul>
        <?php
            $user = get_user_info();
            $query = "SELECT events.eventName, events.eventID
                      FROM events, attendees 
                      WHERE attendees.userID = {$user['userID']} 
                      AND events.eventID = attendees.eventID
                      AND events.startDate >= NOW()
                      ORDER BY start ASC LIMIT 10;";
            $result = mysql_query($query);
  
            if($result) {
                while($row = mysql_fetch_row($result)) {
                    $path = ed(false); 
                    echo "<li><a href = " . $path . "detailView.php?eventID={$row[1]}>{$row[0]}</a></li>";
                }
            }
        ?>
        </ul>
    </div>
    <?php } ?>
    
    <div class="unit happening_links">
        <h3>What's Happening...</h3>
        <ul>
            <li><a href="<?php ed(); ?>results.php?t=a&start=<?php echo date('Y-m-d H:i:s'); ?>&end_date=<?php echo date('Y-m-d'); ?>&current=true">Today</a></li>
            <li><a href="<?php ed(); ?>results.php?t=a&start_date=<?php echo date('Y-m-d',strtotime('tomorrow')); ?>&end_date=<?php echo date('Y-m-d',strtotime('tomorrow')); ?>">Tomorrow</a></li>
            <li><a href="<?php ed(); ?>results.php?t=a&start=<?php echo date('Y-m-d H:i:s'); ?>&end_date=<?php echo date('Y-m-d',strtotime('next week')); ?>">Next 7 days</a></li>
            <li><a href="<?php ed(); ?>results.php?t=a&start=<?php echo date('Y-m-d H:i:s'); ?>&end_date=<?php echo date('Y-m-d',strtotime('next month')); ?>">Next 30 days</a></li>
        </ul>
    </div>
    
    <?php include 'filter.php'; ?>
    
    <!-- <div class="unit">
        <h3>
            Categories
            <?php
                $user = get_user_info();
                if ($user[accessLevel] == 3)
                    echo '<a href="'.ed(false).'categoryAdmin2.php">(manage)</a>';
            ?>
        </h3>
        <ul>
            <?php
                $query = 'SELECT * FROM categories WHERE permanent = 1';
                $result = mysql_query ($query);
                while ($row = mysql_fetch_array($result)) {
                    $w = 'Category: '.urlencode(stripslashes($row[categoryName]));
                    echo '<li><a href="'.ed(false).'results.php?category='.$row[categoryName].'&sort=category&t=a&w='.$w.'">'.stripslashes($row[categoryName]).'</a></li>';
                }
            ?>
        </ul>
    </div>
    
    <div class="unit">
        <h3>
            Locations
            <?php
                $user = get_user_info();
                if ($user[accessLevel] == 3)
                    echo '<a href="'.ed(false).'categories.php">(manage)</a>';
            ?>
        </h3>
        <ul>
            <?php
                $query = 'SELECT * FROM locations WHERE permanent = 1';
                $result = mysql_query ($query);
                while ($row = mysql_fetch_array($result)) {
                    $w = 'Location: '.urlencode(stripslashes($row[locationName]));
                    echo '<li><a href="'.ed(false).'results.php?location='.$row[locationName].'&sort=location&t=a&w='.$w.'">'.stripslashes($row[locationName]).'</a></li>';
                }
            ?>
        </ul>
    </div> -->
    
    <div class="unit tag_cloud">
        <h3>Tag Cloud</h3>
        <div class="tags">
        <?php
            $cloud_limit = 20;
            $tags_q = 'SELECT count, tag FROM 

                       (SELECT COUNT(tag) AS count, tags.tag AS tag 
                       FROM tags, events
                       WHERE tags.eventID = events.eventID 
                         AND events.start >= NOW()
                       GROUP BY tag
                       ORDER BY count DESC
                       LIMIT ' . $cloud_limit . ') AS sb
                      
                       ORDER BY tag ASC';
            $tags_r = mysql_query($tags_q);

            /* Get count range for mapping to font size range */
            /* Would be better to get min and max counts from mysql result */
            $max_count = 0;
            $min_count = PHP_INT_MAX;
            while ($tag = mysql_fetch_array($tags_r)) {
                $current_count = $tag[0];
                if ($current_count > $max_count) { 
                    $max_count = $current_count;
                }
                if ($current_count < $min_count) {
                    $min_count = $current_count;
                }
            }      
            $count_range = $max_count - $min_count;              
            mysql_data_seek($tags_r, 0); // reset internal pointer for loop

            /* Map count to font size using the range of each */
            /* Might be better done using standard deviations from mean */
            $min_fs = 14;
            $max_fs = 22;
            $fs_range = $max_fs - $min_fs;
            $tags_array = array();
            while ($tag = mysql_fetch_array($tags_r)) {
                $fs = ((($tag[0] - $min_count) / $count_range) * $fs_range) + $min_fs;
                $lh = $max_fs;
                $w = 'Tag: '.urlencode(stripslashes($tag['tag']));
                $str = '<span class="tag" style="font-size: '.$fs.'px; line-height: '.$lh.'px;">';
                $str .= '<a href="'.ed(false).'results.php?t=t&tag='.$tag['tag'].'&w='.$w.'">'.$tag['tag'].'</a>';
                $str .= '</span>';
                $tags_array[] = $str;
            }
            echo implode(", ", $tags_array);
        ?>
        </div>
    </div>
<?php if($_POST['action'] != 'update') echo '</div>'; ?>
