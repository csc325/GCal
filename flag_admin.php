<?php
/*
* flag_admin.php
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category admin feature
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
session_start();
require_once 'global.php';
require_once 'header.php';

if(is_admin()) {
  //check that user is admin
        
  echo '<div class = "body">';
  echo '<div class="col large">';
  echo 'Administration page for flagged events<br><br><br>';

  $flagged_query = "SELECT eventID, eventName, flaggedCount 
                    FROM events 
                    WHERE events.flagged=1";
  $flagged_result = mysql_query($flagged_query);
        
  if (mysql_num_rows($flagged_result) != 0) {
    while($row = mysql_fetch_row($flagged_result)) {
      echo '<a href="'.ed(false).'detailView.php?eventID='.$row[0]
        .'">'.$row[1].'</a> has been flagged '.$row[2].' times';
      echo '<br>';
      echo '<a href="'.ed(false).'flag_reset.php?eventID='.$row[0]
        .'"> I checked it dude. It is ok now... </a>';
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