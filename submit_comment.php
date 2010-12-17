<?php
/*
* submit_comment: updates database to include new comment
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category user functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
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
  $comment_query = 'INSERT INTO comments (comment, eventID, userID) 
                    VALUES ("'.$comment.'",'.$eventID.','.$userID.')';
  $comment_result = mysql_query($comment_query);
}
    
header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
    
?>
