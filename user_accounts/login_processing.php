<?php
    $page_form = isset($_POST['ref']);
    $static = isset($_POST['static']);
    
    if(isset($_POST['username']) && isset($_POST['password'])) {
        require_once '../global.php';
        
        $user = trim(addslashes($_POST['username']));
        $pass = $static ? $_POST['password'] : md5($_POST['password']);
        
        $query = "SELECT * FROM users ";
        $query .= "WHERE displayName = '$user' AND password = '$pass'";
        $result = mysql_query ($query);
        $row = mysql_fetch_array($result);
        $count = mysql_num_rows($result);
        
        if ($count == 1 && $row[confirmed] == null) {
            // Username and password match, continue login
            $_SESSION['sid'] = session_id();
	    $_SESSION['email'] = $row['email'];
            $_SESSION['displayName'] = $row['displayName'];
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['css'] = $row['css']; 
            $return = 1;
        } elseif ($row[confirmed] != null) {
            $return = 2;
        }else {
            // Username and password were not found, error
            $return = 0;
        }
        
        if ($page_form) {
            header('Location: '.$_POST['ref']);
        } elseif ($static) {
            return true;
        } else {
            echo $return;
            exit();
        }
    }
?>
