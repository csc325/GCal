<?php
    session_start();
require_once 'global.php';
require_once 'header.php';

    if(is_admin()) {
    //check that user is admin
        
        echo '<div class = "body">';
        echo '<div class="col large">';
        echo 'Administration page for flagged events<br><br><br>';

        $flagged_query = "SELECT eventID, eventName, flaggedCount FROM events 
                                            WHERE events.flagged=1";
        $flagged_result = mysql_query($flagged_query);
        
        if (mysql_num_rows($flagged_result) != 0) {
          while($row = mysql_fetch_row($flagged_result)) {
              echo '<a href="'.ed(false).'detailView.php?eventID='.$row[0].'">'.$row[1].'</a> has been flagged '.$row[2].' times';
              echo '<br>';
              echo '<a href="'.ed(false).'flag_reset.php?eventID='.$row[0].'"> I checked it dude. It is ok now... </a>';
              echo '<br>';
              echo '<br>';
          }
        }

    } else {
        header('Location: '.ed(false).'index.php');
        exit();
    }

     echo '</div>';
     include 'sidebar.php';
 echo '</div>';
 include 'footer.php';
?>