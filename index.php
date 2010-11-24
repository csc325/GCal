<?php
    include 'functions/connection.php';
    include 'functions/listView.php';
    include 'global.php';
    include 'header.php';
    
    $self = $_SERVER['REQUEST_URI'];
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        $self = str_replace('?sort='.$sort,'',$self);
    } else {
        $sort = 'time';
    }
    
    unset($_GET);
    $_GET['start_date'] = date('Y-m-d');
    $eventIDs = get_advance_search_ids();
?>
    <div class="body">
        <div class="col large">
        
        <div class="sortby">
            <span style="float: left;">Showing <?php echo count($eventIDs); ?> upcoming events</span>
            Sort by: 
            <a href="<?php echo $self; ?>?sort=time">Time</a>
            <a href="<?php echo $self; ?>?sort=category">Category</a>
            <a href="<?php echo $self; ?>?sort=location">Location</a>
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
