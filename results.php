<?php
    require_once 'global.php';
    require_once 'header.php';
    
    $self = $_SERVER['REQUEST_URI'];
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        $self = str_replace('&sort='.$sort,'',$self);
    } else {
        $sort = 'time';
    }
    if($is_index)
        $self .= "index.php?";
    if($_GET['t'] == 'a') {
        $eventIDs = get_advance_search_ids();
    } elseif ($_GET['t'] == 'b') {
        $eventIDs = get_basic_search_ids();
    } elseif ($_GET['t'] == 't') {
        $eventIDs = get_tag_ids($_GET['tag']);
    } else {
        $eventIDs = array();
    }
    
    $events = get_events($eventIDs,$sort,20);
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
            <a href="<?php echo $self; ?>&sort=time">Time</a>
            <a href="<?php echo $self; ?>&sort=category">Category</a>
            <a href="<?php echo $self; ?>&sort=location">Location</a>
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


