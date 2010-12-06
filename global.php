<?php
    session_start();
    require_once 'functions/connection.php';
    
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
    
    $_DIR = '/~uysalere/GCal/';  // CHANGE ME
    
    function ed($e=true) {
        global $_DIR;
        if($e) 
            echo $_DIR;
        else
            return $_DIR;
    }
    
    /* SESSION FUNCTIONS -------------------------------------------------- */
    
    function is_logged_in () {
        return ($_SESSION['sid'] == session_id()) ? true : false;
    }
    
    function get_user_info () {
        if (is_logged_in()) {
            $query = 'SELECT *  
                      FROM users 
                      WHERE displayName = "'.$_SESSION['displayName'].'"
                        AND userID = '.$_SESSION['userID'];
            $result = mysql_query($query);
            $row = mysql_fetch_array($result,MYSQL_ASSOC);
            return $row;
        }
        return false;
    }
    
    function on_campus() { 
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $UserIP = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
        else $UserIP = trim($_SERVER['REMOTE_ADDR']);
        
        return (strncmp($UserIP, "132.161", 7) == 0) ? TRUE : FALSE;
    }
    
    function is_attending($userID, $eventID) {
        if(is_logged_in()) {
            $query = "SELECT attendeeID FROM attendees
                      WHERE userID = $userID
                      AND eventID = $eventID";
            $result = mysql_query($query);
            if ($result)
                if(mysql_num_rows($result) > 0) return true;
        }
        return false;
    }
    
    function display_attend ($userID,$eventID) {
        if (is_logged_in()) {
            if (is_attending($userID,$eventID)) {
                echo "<a id='event_{$eventID}_{$userID}' class='attend_event attending'>
                      Attending <span class='cancel'>X</span>";
            } else {
                echo "<a id='event_{$eventID}_{$userID}' class='attend_event'>";
                echo "Attend!";
            }
            
            echo "</a>";
        }
    }
?>
