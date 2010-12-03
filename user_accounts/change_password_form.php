<?php
    require_once '../global.php';
    require_once 'functions.php';

if ($_POST['sent']) {
    if (changePassword("knolldug@grinnell.edu", $_POST['old'], $_POST['new'], $_POST['new2'])) {
        echo "Your password has been successfully changed \n";
    } else { 
    ?>
        <html>
            <body>
                <p> Failed to change password </p>
                <form action="change_password_form.php" method="post">
                    <p>Old password: <input type="password" name="old" /> </p>
                    <p>New password: <input type="password" name="new" /> </p>
                    <p>Confirm new password: <input type="password" name="new2" /> </p>
                    <input type="hidden" name="sent" value="1"/>
                    <input type="submit" value="Change Password" />
                </form>
            </body>
        </html> 
    <?php 
    }
} else { 
?>
    <html>
        <body>
            <p> Enter email address </p>
            <form action="change_password_form.php" method="post">
                <p>Old Password: <input type="password" name="old" /> </p>
                <p>New Password: <input type="password" name="new" /> </p>
                <p>Confirm new Password: <input type="password" name="new2" /> </p>
                <input type="hidden" name="sent" value="1"/>
                <input type="submit" value="Change Password" />
            </form>
        </body>
    </html> 
<?php
}
?>
