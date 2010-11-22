<?php
    include 'functions/connection.php';
    include 'listView.php';
    include 'header.php';
    
    $self = $_SERVER['REQUEST_URI'];
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        $self = str_replace('&sort='.$sort,'',$self);
    } else {
        $sort = 'time';
    }
    
    if($_GET['t'] == 'a') {
        $eventIDs = get_advance_search_ids();
    } elseif ($_GET['t'] == 'b') {
        $eventIDs = get_basic_search_ids();
    } else {
        $eventIds = array();
    }
?>

    <div class="body">
        <div class="col large">
        
        <div class="sortby">
            <span style="float: left;">
            <?php
                if (isset($_GET['w']) && $_GET['w'] != '') {
                    echo urldecode(stripslashes($_GET['w']));
                } else {
                    echo 'Showing '.count($eventIDs).' events';
                }
            ?>
            </span>
            Sort by: 
            <a href="<?php echo $self; ?>&sort=time">Time</a>
            <a href="<?php echo $self; ?>&sort=category">Category</a>
            <a href="<?php echo $self; ?>&sort=location">Location</a>
        </div>

        <?php 
            $events = get_events($eventIDs,$sort);
            display_events_inter($events,$sort);
        ?>
        
        </div>
        
        <?php include 'sidebar.php'; ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>


