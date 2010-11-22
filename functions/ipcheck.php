 <?php
 
 function IPCheck()
    { 
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $UserIP = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
    else $UserIP = trim($_SERVER['REMOTE_ADDR']);
 

    if(strncmp($UserIP, "132.161", 7) == 0)
        return TRUE;
    else return FALSE;
    }

 
?>