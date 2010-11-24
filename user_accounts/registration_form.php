<?php
    include '../functions/connection.php';
    include '../global.php';
    include '../header.php';
?>

<div class="body">
    <div class="col large">
    
    <form method="post" action="<?php ed(); ?>user_accounts/registration_processing.php">
        <div class="form-unit long">
            <label for="username" id="username_label">Grinnell Email Address:</label>
            <input type="text" id="username" name="username" tabindex="1"> @grinnell.edu
        </div>
        
        <div class="form-unit long">
            <label for="password" id="password_label">Password:</label>
            <input type="password" id="password" name="password" tabindex="2">
        </div>
        
        <div class="form-unit long">
            <label for="password2" id="password2_label">Password:</label>
            <input type="password" id="password2" name="password2" tabindex="3">
        </div>
        
        <div class="form-unit long">
            <input type="submit" value="Register">
        </div>
    </form>
    
    </div>
    
    <?php include '../sidebar.php'; ?>
</div>

<?php include '../footer.php'; ?>
