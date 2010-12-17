<?php
    session_start();
    require_once 'global.php';
        
    // set table name
    $eventID = addslashes($_GET["eventID"]);

    //check that user is logged in and is either an admin or created the event
    if(is_admin()) {

        //reset flag
        $flag_query = "UPDATE events
                       SET
                       flagged=0
                       WHERE eventID='$eventID'";
        $flag_result = mysql_query($flag_query);

        header('Location: '.ed(false).'flag_admin.php');
        exit();
    } else {
        header('Location: '.ed(false).'index.php');
        exit();
    }
?>