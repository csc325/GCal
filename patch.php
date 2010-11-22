<?php
    /* Patched 7:20PM 11/21/10 
       Created 'start' 'end' columns in events table, populating fields.
       
    include 'mysql_connect.php';
    
    $query = 'SELECT * FROM events';
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        $startTime = $row[startTime];
        $startDate = $row[startDate];
        $endTime = $row[endTime];
        $endDate = $row[endDate];
        
        $query = 'UPDATE events 
                  SET start = "'.$startDate.' '.$startTime.'", end = "'.$endDate.' '.$endTime.'"
                  WHERE eventID = '.$row[eventID];
        
        $r = mysql_query($query);
        if (!$r) echo mysql_error();
    } */
?>
