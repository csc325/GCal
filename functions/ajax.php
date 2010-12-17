<?php
/*
* ajax.php: handles ajax calls
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category Ajax functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*
*  AJAX CALLS -----------------------------------------------------------
    
    Point all AJAX calls here with the additional data {action:'ajax',
    function:'YourFunctionName'}.  All other data sent will be stored in 
    an associative array and passed to the function you call.
        
    Therefore, please write all functions that are called in this way to 
    accept 1 and only 1 parameter that is an array with all parameter 
    names and values matching appropriately.
*/
    
session_start();
require_once 'functions.php';
    
if ($_POST['action'] == 'ajax') {
  $function = str_replace(array(';','$'),array('',''),$_POST['function']);
  unset($_POST['action']);
  unset($_POST['function']);
  eval('$result = '.$function.'($_POST);');
        
  echo $result; 
  exit();
}
?>
