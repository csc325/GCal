
<?php
include_once "functions.php";
if($_POST['sent']){
  if (passwordReset($_POST['email']))
    echo "A new password has been sent to your Grinnell e-mail address. \n";
  else {echo $_POST['email'];
	echo 'Email address not recognized
	  <html>
	  <body>
	  <form action="forgot_password.php" method="post">
	  <p>Your username: <input type="text" name="email" />
	  <input type="hidden" name="sent" value="1"/>
	  <input type="submit" value="Reset Password" />
	  </form>
	  </body>';
	}
  }
else{  
  echo '<html>
        <body>
	<p> Enter email address
        <form action="forgot_password.php" method="post">
	<p>Your username: <input type="text" name="email" />
	<input type="hidden" name="sent" value="1"/>
        <input type="submit" value="Reset Password" />
        </form>
        </body>
        </html>';
}
?>


