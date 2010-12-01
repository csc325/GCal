<div class="col small side">
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
    
    <div class="unit happening_links">
        <h3>What's Happening...</h3>
        <ul>
            <li><a href="<?php ed(); ?>results.php?t=a&start_date=<?php echo date('Y-m-d'); ?>&end_date=<?php echo date('Y-m-d'); ?>">Today</a></li>
            <li><a href="<?php ed(); ?>results.php?t=a&start_date=<?php echo date('Y-m-d'); ?>&end_date=<?php echo date('Y-m-d',strtotime('next week')); ?>">This Week</a></li>
            <li><a href="<?php ed(); ?>results.php?t=a&start_date=<?php echo date('Y-m-d'); ?>&end_date=<?php echo date('Y-m-d',strtotime('next month')); ?>">This Month</a></li>
        </ul>
    </div>
    
    <div class="unit">
        <h3>Categories</h3>
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
        <h3>Locations</h3>
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
    
    <div class="unit tag_cloud">
        <h3>Tag Cloud</h3>
        <div class="tags">
        <?php
            $tags_q = 'SELECT COUNT(*), tag FROM tags GROUP BY tag';
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
</div>
