<?php
    $_GET[start] = date('Y-m-d H:i:s');
    $_GET[end_date] = date('Y-m-d',strtotime('30 days'));
    $_GET[t] = 'a';
    $_GET[current] = 'true';
    
    include 'results.php';
    
    /* require_once 'global.php';
    require_once 'header.php';
    
    $self = $_SERVER['REQUEST_URI'];
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        $self = str_replace('?sort='.$sort,'',$self);
    } else {
        $sort = 'time';
    }
    
    $query = 'SELECT eventID FROM events
              WHERE start >= NOW() OR end >= NOW()';
    $result = mysql_query ($query);
    while ($row = mysql_fetch_array($result)) $eventIDs[] = $row[eventID];
    $events = get_events($eventIDs,$sort);
?>
    <div class="body">
        <div class="col large">
        
        <?php if($_GET['delete'] == 't') : ?>
        <h1 class="head info">Event deleted successfully</h1>
        <?php endif; ?>
        
        <div class="sortby">
            <span style="float: left;">
            <?php
                if (isset($_GET['w']) && $_GET['w'] != '') {
                    echo urldecode(stripslashes($_GET['w']));
                } else {
                    $num_IDs = $events ? count($events) : 0;
                    echo "Showing $num_IDs events";
                }
            ?>
            </span>
            Sort by: 
            <a href="<?php echo $self; ?>?sort=time">Time</a>
            <a href="<?php echo $self; ?>?sort=category">Category</a>
            <a href="<?php echo $self; ?>?sort=location">Location</a>
        </div>
        
        <?php
            display_events_inter($events,$sort);
        ?>
        
        </div>
        
        <?php include 'sidebar.php'; ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
<?php */ ?>
