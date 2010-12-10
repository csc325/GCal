<?php
    require_once '../global.php';
    require_once 'functions.php';
?>

<form action="change_password_processing.php" method="post">
  <p>Old password: <input type="password" name="old" /> </p>
  <p>New password: <input type="password" name="new" /> </p>
  <p>Confirm new password: <input type="password" name="new2" /> </p>
  <input type="submit" value="Change Password" />
  </form>
