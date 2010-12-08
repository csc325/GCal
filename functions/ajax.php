<?php
    /*  AJAX CALLS -----------------------------------------------------------
    
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
        
        echo $result; exit();
    }
?>
