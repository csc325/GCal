<?php
include_once "functions.php";

if($_POST['sent']) {
    if (passwordReset($_POST['email'])) {
        echo "A new password has been sent to your Grinnell e-mail address. \n";
    } else {
        // echo $_POST['email']; 
    ?>
        <html>
            <body>
                <p>Email address not recognized</p>
                <form action="forgot_password.php" method="post">
                    <p>Your username: <input type="text" name="email" />
                    <input type="hidden" name="sent" value="1"/>
                    <input type="submit" value="Reset Password" />
                </form>
            </body>
        </html> 
    <?php
    }
} else { 
?>
    <html>
        <body>
            <p> Enter email address
            <form action="forgot_password.php" method="post">
                <p>Your username: <input type="text" name="email" />
                <input type="hidden" name="sent" value="1"/>
                <input type="submit" value="Reset Password" />
            </form>
        </body>
    </html> 
<?php
}
?>


