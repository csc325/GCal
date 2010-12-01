<?php
    include 'functions.php';
    include '../global.php';
    include '../header.php';
    include '../functions/connection.php';
?>

<div class="body">
    <div class="col large">
        <form method="post" action="login_processing.php">
            <div class="form-unit long">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username">
            </div>
            <div class="form-unit long">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-unit long">
                <label for="empty">&nbsp;</label>
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
    <?php include '../sidebar.php'; ?>
</div>

<?php include '../footer.php'; ?>
