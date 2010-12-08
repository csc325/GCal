<?php
    /*  EVENT & COMMENT --------------------------------------------------- */
    
    function is_owner ($eventID) {
        $user_query = 'SELECT userID FROM events WHERE eventID='. $eventID;
        $user_result = mysql_query($user_query);
        $row = mysql_fetch_array($user_result);
        $owner = $row[0];
        return ($_SESSION['userID'] == $owner) ? true : false;
    }
    
    function add_comment ($args) {
        if (!is_logged_in()) return 0;
        
        $comment = trim(htmlspecialchars($args["comment"]));
        $eventID = addslashes($args["eventID"]);
        $userID = $_SESSION['userID'];
        $timestamp = date('Y-m-d H:i:s');
        
        if ($comment != '' && comment != ' ') {
            $query = "INSERT INTO comments (comment, eventID, userID, timestamp) 
                      VALUES ('$comment',$eventID,$userID,'$timestamp')";
            $result = mysql_query($query);
        }
        
        return 1;
    }
    
    function delete_comment ($args) {
        $commentID = addslashes($args["commentID"]);
        $eventID = addslashes($args["eventID"]);
        $owner = addslashes($args["owner"]);

        if(is_owner($eventID) || is_admin()) {
            $query = 'DELETE FROM comments WHERE commentID='.$commentID;
            $result = mysql_query($query);
        }
        
        return 1;
    }
    
    
    /*  SESSION & USER ---------------------------------------------------- */
    
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

    function is_admin () {
      $user = get_user_info();
      return ($user["accessLevel"] == 3) ? true : false;
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
    
    function attend_event($args) {
        $eventID = addslashes($args[eventID]);
        $userID = addslashes($args[userID]);
        $query = "INSERT INTO attendees (userID, eventID) VALUES ($userID, $eventID);";
        $result = mysql_query($query);
        $query2 = "UPDATE events SET popularity = popularity + 1 WHERE eventID = $eventID;";
        $result2 = mysql_query($query2);
        return ($result && $result2) ? 1 : 0;
    }
    
    function cancel_attend($args) {
        $eventID = addslashes($args[eventID]);
        $userID = addslashes($args[userID]);
        $query = "DELETE FROM attendees WHERE userID = $userID AND eventID = $eventID;";
        $result = mysql_query($query);
        $query2 = "UPDATE events SET popularity = popularity - 1 WHERE eventID = $eventID;";
        $result2 = mysql_query($query2);
        return ($result && $result2) ? 1 : 0;
    }
?>
