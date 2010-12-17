<?php
include 'functions/connection.php';
if ( $_POST )
  {
    /* Change category permanence */
    $queryDelete = 'UPDATE categories SET permanent=0 WHERE categoryID = ';
    $queryAdd = 'UPDATE categories SET permanent=1 WHERE categoryID = ';
    
    $addArray = array_keys( $_POST, 'upgrade' );
    $deleteArray = array_keys( $_POST, 'downgrade' );
    
    if ( $addArray )
      {
        $queryAdd .= implode( ' OR categoryID = ', $addArray );    
        $resultAdd = mysql_query($queryAdd);
        /* To do: error check */
        echo 1; exit();
      }
    
    if ( $deleteArray )
      {    
        $queryDelete .= implode( ' OR categoryID = ', $deleteArray ); 
        $resultDelete = mysql_query($queryDelete);
        /* To do: error check */
        echo 1; exit();
      }
  }
?>