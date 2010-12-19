<?php
include 'functions/connection.php';
// echo 'hello <br />';
// print_r($_POST);
if ( $_POST )
  {
     $admintype = $_GET['admintype'];
     if(($admintype == 1) || (!isset($admintype))) {
         $table = "categories";
         $field1 = "categoryName";
         $field2 = "categoryID";
     }
     if($admintype == 2) {
         $table = "locations";
         $field1 = "categoryName";
         $field2 = "locationID";
     }
    /* Rename/merge */
    $queryCheckName = 'SELECT $field1, $field2, requestCount'
      . ' FROM $table';
    $queryLock = 'LOCK TABLES $table WRITE'
      . ', $table READ';
    
    // echo "POST: ";
    // print_r($_POST);
    // echo "<br />";
    
    /*function renameFilter($var)
    {
      return( strpos($var,'rename_') == 0 );
    }
    $tempArray = array_filter($_POST, 'renameFilter');*/
    $tempArray = $_POST;
    // echo "TEMP: ";
    // print_r($tempArray);
    // echo "<br />";
    $renameArray = array();
    foreach( $_POST as $catID => $newName )
      {
        $renameArray[$catID] = $newName;
      }
    
    mysql_query($queryLock);
    $resultCheckName = mysql_query($queryCheckName);
    $currentCats = array();
    while($row = mysql_fetch_assoc($resultCheckName))
      {
        $currentCatIDs[$row[$field1]] = $row[$field2];
        $currentCatRequests[$row[$field2]] = $row['requestCount'];
      }
    // echo "currentCatIDs: ";
    // print_r($currentCatIDs);
    // echo "<br />";
    // echo "currentCatRequests: ";
    // print_r($currentCatRequests);
    // echo "<br />";
    // echo "RENAME: ";
    /* print_r($renameArray);
       echo "<br />"; */
    foreach ($renameArray as $catID => $newName)
      {
        $queryRename = '';
        $queryMerge = '';
        
        /* If new name exists, merge, else, change name */
        if ( array_key_exists($newName,$currentCatIDs) )
          {
            if ($currentCatIDs[$newName] != $catID)
              {
                $queryMerge = 'UPDATE $table SET requestCount=requestCount+'
                  . $currentCatRequests[$catID]
                  . ' WHERE $field1 = "' . $newName . '"';
                
                $queryUpdateID = 'UPDATE events SET $field2 = '
                  . $currentCatIDs[$newName]
                  . ' WHERE $field2 = '
                  .  $catID;
                
                $queryDeleteOld = 'DELETE FROM $table WHERE $field2 = ' 
                  . $catID;
                
                /* echo $queryMerge . "<br />";
                echo $queryUpdateID . "<br />";
                echo $queryDeleteOld . "<br />"; */
                mysql_query($queryMerge);
                mysql_query($queryUpdateID);
                mysql_query($queryDeleteOld);
              }
          }
        else
          {
            $queryRename = 'UPDATE $table SET $field1 = "'
              . $newName . '" WHERE $field2 = '
              . $catID;
            // echo $queryRename . '<br />';
            mysql_query($queryRename);
          }
      }
    mysql_query('UNLOCK TABLES');
    
  }
?>