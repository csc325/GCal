<?php
    session_start();
    include 'global.php';
    if (!is_logged_in()) header ('Location : '.ed(false));
    
    // parse COMMENT
    $comment = trim(htmlspecialchars($_REQUEST["comment"]));
    $eventID = addslashes($_REQUEST["eventID"]);
    $userID = $_SESSION['userID'];
    $timestamp = date('Y-m-d H:i:s');
        
    // Comments table query
    if ($comment != '' && comment != ' ') {
        $query = "INSERT INTO comments (comment, eventID, userID, timestamp) 
                  VALUES ('$comment',$eventID,$userID,'$timestamp')";
        $result = mysql_query($query);
    }
    
    header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
?>
