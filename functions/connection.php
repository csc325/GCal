<?php
/*
* connection: establishes connection to database
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category Database connection function
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/

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