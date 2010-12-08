<?php
    require_once '../global.php';
    require_once '../header.php';
?>
<div class="body">
    <div class="col large">
    
    <h1 class="head">Reset Your Password</h1>


<?php
    require_once 'functions.php';

if($_POST['sent']) {
    if (passwordReset($_POST['email'])) {
        echo "A new password has been sent to your Grinnell e-mail address. \n";
    } else {  
    ?>
             <p>Email address not recognized</p>
                <form action="forgot_password.php" method="post">
                    <p>Your username: <input type="text" name="email" />
                    <input type="hidden" name="sent" value="1"/>
                    <input type="submit" value="Reset Password" />
                </form>
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
    </div>
<?php include '../sidebar.php'; ?>
</div>
<?php include '../footer.php'; ?>

