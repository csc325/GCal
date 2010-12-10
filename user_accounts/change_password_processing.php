<?php
    session_start();
    require_once '../global.php';

    $user = get_user_info();
    $userID = $user['userID'];
  
    $old_pass = md5($_POST['old']);
    $new_pass = md5($_POST['new']);
    $new_pass2 = md5($_POST['new2']);

if(strcmp($new_pass , $new_pass2) != 0) {
 header('Location: '.ed(false).'user_accounts/user_settings.php?sent=f');
  exit();
}

$query = "SELECT password FROM users WHERE userID = $userID;";
$result = mysql_query($query);
$row = mysql_fetch_row($result);

if(strcmp($row[0], $old_pass) != 0) {
 header('Location: '.ed(false).'user_accounts/user_settings.php?sent=z');
  exit();
}

$query = "UPDATE users SET password = '$new_pass' WHERE userID = $userID;";
$result = mysql_query($query);

header('Location: '.ed(false).'user_accounts/user_settings.php?sent=t');
?>