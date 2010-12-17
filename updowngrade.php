<?php
/*
* updowngrade: script to change category from permanent to non permanent or
* vice versa
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
include 'functions/connection.php';
if ( $_POST )
  {
    /* Change category permanence */
    $queryDelete = 'UPDATE categories SET permanent=0 WHERE categoryID = ';
    $queryAdd    = 'UPDATE categories SET permanent=1 WHERE categoryID = ';    
    $addArray    = array_keys( $_POST, 'upgrade' );
    $deleteArray = array_keys( $_POST, 'downgrade' );
    
    if ( $addArray )
      {
        $queryAdd .= implode( ' OR categoryID = ', $addArray );    
        $resultAdd = mysql_query($queryAdd);
        /* To do: error check */
        echo 1; 
        exit();
      }
    
    if ( $deleteArray )
      {    
        $queryDelete .= implode( ' OR categoryID = ', $deleteArray ); 
        $resultDelete = mysql_query($queryDelete);
        /* To do: error check */
        echo 1; 
        exit();
      }
  }
?>