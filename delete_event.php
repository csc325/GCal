<?php
    session_start();
    require_once 'global.php';
        
    // set table name
    $eventID = addslashes($_GET["eventID"]);

    //check that user is logged in and is either an admin or created the event
    if(is_logged_in()) {
        $userID = $_SESSION['userID'];
        
        //check if the user created the event
        $query = "SELECT userID
                  FROM events
                  WHERE eventID = $eventID;";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        
        //check if the user is an admin
        $query = "SELECT accessLevel
                  FROM users
                  WHERE userID = $userID;";
        $result = mysql_query($query);
        $row2 = mysql_fetch_row($result);
        
        if(($row[0] == $userID) || ($row2[0] == 3)) {
            //delete tags
            $query = "DELETE FROM tags WHERE eventID = $eventID;";
            mysql_query($query);
            //delete comments
            $query = "DELETE FROM comments WHERE eventID = $eventID;";
            mysql_query($query);
            //delete attendees
            $query = "DELETE FROM attendees WHERE eventID = $eventID;";
            mysql_query($query);
            //delete the event
            $query = "DELETE FROM events WHERE eventID = $eventID;";
            mysql_query($query);

            header('Location: '.ed(false).'index.php?delete=t&eventID='.$eventID);
            exit();
        }
    } else {
        header('Location: '.ed(false).'detailView.php?s=f&eventID='.$eventID);
        exit();
    }
?>
