<?php
/*
* submit_tag: updates database to include new tag
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category editing tags
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
session_start();
include 'functions/connection.php';
include 'global.php';
    
// get variables from form page
foreach($_POST as $field_name => $value) $$field_name = addslashes($value);

// parse tags
$tags = htmlspecialchars($tags);
$tags = explode(',',$tags);
$tags = array_map('trim',$tags);
        
// Tags table query
if (count($tags) == 1 && ($tags[0] == '' || $tags[0] == ' ')) {
} else {
  foreach ($tags as $tag) {
    $tags_query = 'INSERT INTO tags (tag,eventID) 
                   VALUES ("'.$tag.'",'.$eventID.')';
    echo $tags_query;
    $tag_result = mysql_query($tags_query);
  }
}
    
    
?>
