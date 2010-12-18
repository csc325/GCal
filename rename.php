<?php
include 'functions/connection.php';
// echo 'hello <br />';
// print_r($_POST);
if ( $_POST )
  {
    /* Rename/merge categories */
    $queryCheckName = 'SELECT categoryName, categoryID, requestCount'
      . ' FROM categories';
    $queryLock = 'LOCK TABLES categories WRITE'
      . ', categories READ';
    
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
        $currentCatIDs[$row['categoryName']] = $row['categoryID'];
        $currentCatRequests[$row['categoryID']] = $row['requestCount'];
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
                $queryMerge = 'UPDATE categories SET requestCount=requestCount+'
                  . $currentCatRequests[$catID]
                  . ' WHERE categoryName = "' . $newName . '"';
                
                $queryUpdateID = 'UPDATE events SET categoryID = '
                  . $currentCatIDs[$newName]
                  . ' WHERE categoryID = '
                  .  $catID;
                
                $queryDeleteOld = 'DELETE FROM categories WHERE categoryID = ' 
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
            $queryRename = 'UPDATE categories SET categoryName = "'
              . $newName . '" WHERE categoryID = '
              . $catID;
            // echo $queryRename . '<br />';
            mysql_query($queryRename);
          }
      }
    mysql_query('UNLOCK TABLES');
    
  }
?>