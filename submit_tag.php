<?php
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
            $tags_query = 'INSERT INTO tags (tag,eventID) VALUES ("'.$tag.'",'.$eventID.')';
            echo $tags_query;
            $tag_result = mysql_query($tags_query);
        }
    }
    
    
?>
