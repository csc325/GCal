<?php
    session_start();
    require_once 'global.php';
    
    $commentID = addslashes($_GET["commentID"]);
    $eventID = addslashes($_GET["eventID"]);
    $owner = addslashes($_GET["owner"]);

    if(is_owner($eventID) || is_admin()) {
        $comment_query = 'DELETE FROM comments WHERE commentID='.$commentID;
        $comment_result = mysql_query($comment_query);
    }
    
    header('Location: '.ed(false).'detailView.php?eventID='.$eventID); 
?>
