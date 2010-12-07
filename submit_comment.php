<?php
    session_start();
    include 'functions/connection.php';
    include 'global.php';

    $userID = ($_SESSION['userID'] != '') ? $_SESSION['userID'] : 2;
    
    // parse COMMENT
    $comment = htmlspecialchars($_REQUEST["comment"]);
    $eventID = htmlspecialchars($_REQUEST["eventID"]);
    $comment = trim($comment);
        
    // Comments table query
    if (($comment == '') || ($comment == ' ')) {
    } else {
            $comment_query = 'INSERT INTO comments (comment, eventID, userID) VALUES ("'.$comment.'",'.$eventID.','.$userID.')';
            $comment_result = mysql_query($comment_query);
    }
    
    header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
    
?>
