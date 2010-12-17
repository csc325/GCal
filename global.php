<?php
    session_start();
    require_once 'functions/functions.php';
    
    /*  -----------------------------------------------------------------------
        IMPORTANT
        
        Please change $_DIR to properly reflect the URL path to this deployment 
        of GCal in your diretory.  This will affect all links, redirects, and 
        calls so that you don't accidentally get linked, redirected, or call 
        things that are in my (Jing's) directory.
        
        This is important so that you can see modifications to your own code 
        and not be confused by why things aren't working.  
        
        Also, PLEASE USE the function below, ed(), whenever you're making a 
        link or redirect.

     *  Function:   ed($e)
     *  Purpose:    echo or return the directory that this deployment of GCal is 
     *              currently using.  VERY IMPORTANT.
     */
    
    $_DIR = '/~frantzch/CSC325/GCal/';  // CHANGE ME
    
    function ed($e=true) {
        global $_DIR;
        if($e) 
            echo $_DIR;
        else
            return $_DIR;
    }
?>
