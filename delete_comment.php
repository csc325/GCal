<?php
    session_start();
    include 'functions/connection.php';
    include 'global.php';

    // parse COMMENT
    $commentID = htmlspecialchars($_REQUEST["commentID"]);
    $eventID = htmlspecialchars($_REQUEST["eventID"]);
    $owner = htmlspecialchars($_REQUEST["owner"]);

if(is_owner($owner) || is_admin()) :

  $comment_query = 'DELETE FROM comments WHERE commentID='.$commentID;
  $comment_result = mysql_query($comment_query);
    
    header('Location: '.ed(false).'detailView.php?eventID='.$eventID);

endif;
    
?>
