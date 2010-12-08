<?php
    require_once 'global.php';
    require_once 'header.php';
    
    if($_GET['delete'] == 't') 
      echo '<h1 class="head">Event deleted successfully</h1><hr>';
    $self = $_SERVER['REQUEST_URI'];
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        $self = str_replace('?sort='.$sort,'',$self);
    } else {
        $sort = 'time';
    }
    
    unset($_GET);
    // $_GET['start_date'] = date('Y-m-d');
    $_GET['current'] = 'true';
    $eventIDs = get_advance_search_ids();
    $events = get_events($eventIDs,$sort);
?>
    <div class="body">
        <div class="col large">
        
        <div class="sortby">
            <span style="float: left;">Showing <?php echo $events ? count($events) : 0; ?> upcoming events</span>
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
