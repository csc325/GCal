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
    
    <div class="unit">
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
            <!-- <?php
                $user = get_user_info();
                if ($user[accessLevel] == 3)
                    echo '<a href="'.ed(false).'categories.php">(manage)</a>';
            ?> -->
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
    </div>
            <?php
                $user = get_user_info();
                $flags = get_number_of_flagged();
                if (($user[accessLevel] == 3) && ($flags > 0)){
                    echo '<div class="unit"><h3>';
                    echo '<a href="'.ed(false).'flag_admin.php">'.$flags.' events were flagged '.get_number_of_flaggedCount().' times!<br> Check them!</a>';
                    echo '</h3></div>';}
            ?>
    
    <div class="unit tag_cloud">
        <h3>Tag Cloud</h3>
        <div class="tags">
        <?php
            $tags_q = 'SELECT COUNT(*), tags.tag 
                       FROM tags, events 
                       WHERE tags.eventID = events.eventID 
                         AND events.start >= NOW()
                       GROUP BY tag';
            $tags_r = mysql_query($tags_q);
            $tags_array = array();
            while ($tag = mysql_fetch_array($tags_r)) {
                $fs = min(20,(13 + ($tag[0] * 2)));
                $lh = 20;
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
