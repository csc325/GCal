<?php
    // simple mysql error handling
    function have_error($q,$r) {
        if (!$r) {
            $message = "Error in query ($q) -- mysql_error()";
            return true;
        } else {
            return false;
        }
    }
    
    // Check if is user exists
    function user_exists ($user) {
        $user = addslashes($user);
        $query = "SELECT userID FROM users WHERE displayName = '$user'";
        $result = mysql_query ($query);
        if (!have_error($query,$result)) return (mysql_num_rows($result) > 0) ? true : false;
    }

    // Check that a username with corresponding password exists in the db
    function chk_user_pw ($user, $pw, $db) {
        $query = "SELECT password FROM users
                  WHERE email = '$user'
                  AND password = '$pw';";
        
        $result = mysql_query($query, $db);

        // exit and send error message if query was unsuccessful
        if (mysql_num_rows($result) == 0){
            echo "Error in query ($query): mysql_error()";
            mysql_free_result($result);
	    mysql_close($db);
            return 0;
        }
        
        return (mysql_num_rows($result) > 0) ? true : false;
    }

    // Function for generating a random password
    function randomPasswordGen($alpha=true) {
        $salt = '0123456789'.(($alpha) ? 'abchefghjkmnpqrstuvwxyz' : '');
        for($i=0;$i<9; $i++)
            $password .= substr($salt, mt_rand(0,   strlen($salt)), 1);
        return $password; 
    }

    /* PROCEDURE - bool passwordReset (string $email)
     *
     * parameters - $email: a valid username@grinnell.edu email address
     *
     * purpose - this function assigns a random password to the user
     *	      then sends out an e-mail containing the password.
     *
     * preconditions - the username must have an account in the database
     *
     * postconditions - the user's password is changed and an e-mail is sent
     *
     * produces - a boolean: TRUE if successful, FALSE if unsuccessful
     */

    function passwordReset ($email){
        // create password and connect to database
        $new_password = randomPasswordGen();
	    $email = mysql_real_escape_string($email);

        // Query for email in database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysql_query($query);
        
        // if email exists in database, change their password
        if ($result) {
            $np_md5 = md5($new_password);
            $query  = "UPDATE users SET password = '$np_md5' WHERE email = '$email';";
            $result = mysql_query($query);
        }
        
        // If successful, send e-mail to user informing them of their new password
        if ($result) {
            // Message
            $message = '<html><body>
                        <p>Here is your new password for Grinnell Open Calendar.  Don\'t
                        forget to change it after you\'ve logged in.</p>
                        <p>New password: '.$new_password.'</p>
                        </body></html>';

            // Headers
            $header = 'MIME-Version: 1.0' . "\r\n" .
                      'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                      'From: Grinnell Open Calendar <webmaster@grinnellopencalender.com>' . "\r\n" .
                      'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
                      'X-Mailer: PHP/' . phpversion();

            // Send Message
            $sent = mail( $email, 'Grinnell Open Calendar Password Reset', $message, $header);
            return true;
        }
        return false;
    }

    function changePassword($email, $old_pw, $new_pw, $new2_pw){
        if ($new2_pw != $new_pw) return 0;
        
        // Connect to database, store variables to prevent sql injections, encrypt
        // password data.
        include '../functions/connection.php';
        $old_pw = md5($old_pw);
        $new_pw = md5($new_pw);
        $email = mysql_real_escape_string(strtolower($email));

        $exists = chk_user_pw($email, $old_pw, $link);
	  

        if ($link) {
            // Store new password if old password and email are correct
            $query2 = "UPDATE users
                       SET password = '$new_pw'
                       WHERE email = '$email' AND password = '$old_pw';";
            
            if ($exists) {
                $result = mysql_query($query2, $link);
                
                if (mysql_affected_rows($link) != 1) {
                    // exit and send error message if query2 was unsuccessful
                    $message = "Error in query ($query2): " . mysql_error();
                      mysql_free_result($result);
		      mysql_close($link);
                    die($message);
                } elseif (mysql_affected_rows($link) && $exists){
		      mysql_close($link);
                    return true;
                }
            } else { 
                return false;
            }
        }
    }
?>
