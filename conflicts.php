<?php
    require_once 'global.php';
    require_once 'header.php';

	$eventIDs = array_keys($_GET, 'conflict');
	$events = array();
	if ($eventIDs) {
    	$events = get_events($eventIDs);
	}
	
?>    
    <div class="body">
        
        <div class="col large">

        <h1 class="head info">
            <a href="<?php echo ed(false); ?>detailView.php?eventID=<?php echo $_GET['eventID']; ?>">Your event</a> may conflict with the following:</h1>
        
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
            display_events_inter($events);
        ?>
        
        </div>
        
        <?php include 'sidebar.php'; ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
