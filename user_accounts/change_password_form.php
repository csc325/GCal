<?php
/*
* change_password_form.php
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category creates password form
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
require_once '../global.php';
require_once 'functions.php';
?>

<form action="change_password_processing.php" method="post">
  <p>Old password: <input type="password" name="old" /> </p>
  <p>New password: <input type="password" name="new" /> </p>
  <p>Confirm new password: <input type="password" name="new2" /> </p>
  <input type="submit" value="Change Password" />
  </form>
