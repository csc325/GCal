
<?php

$DNform = '
<form method="post" action="user_settings.php">
    <p> Enter your new Display Name <br /><br />
      <input type="text" name="displayName">
      <input type="submit" value="Submit">
    </p>
  </form>';

if(htmlspecialchars($_POST['displayName'])){

include_once "functions.php";
include '../functions/connection.php';

$email = ($_SESSION['email'] != '') ? $_SESSION['email'] : 2;


  $sql = "UPDATE users
	SET displayName = '". mysql_escape_string($_POST['displayName'])."'
	WHERE email = '".$email."'";

  mysql_query($sql, $link);

  if(mysql_affected_rows($link)==1){
    echo "Your display name was set to: "
	  .htmlspecialchars($_POST['displayName']);
    $_SESSION['displayName'] = mysql_escape_string($_POST['displayName']);
  }
  else echo "Failed to change your display name... Refresh page to try again";

  mysql_close($link);
}
echo $DNform;
?>



