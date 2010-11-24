<?php

$link = mysql_connect('localhost', 'csc325generic', 'password');
if (!$link) {
    die('Could not connect ' . mysql_error());
}

$db = mysql_select_db('CSC325');

if (!$db) {
    mysql_close($db);
    die('DB Could not connect ' . mysql_error());
}
?>