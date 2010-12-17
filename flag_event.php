<?php
    session_start();
    require_once 'global.php';
        
    // set table name
    $eventID = addslashes($_GET["eventID"]);

    //check that user is logged in and is either an admin or created the event
    if(is_logged_in()) {
        $userID = $_SESSION['userID'];

            //add flag
        $flag_query = "UPDATE events
                       SET
                       flagged=1, 
                       flaggedCount = flaggedCount + 1 
                       WHERE eventID='$eventID'";
        $flag_result = mysql_query($flag_query);

            header('Location: '.ed(false).'detailView.php?flag=true&eventID='.$eventID);
            exit();
    } else {
        header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
        exit();
    }
?>
