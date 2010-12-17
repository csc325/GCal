<?php
    require_once '../global.php';
    require_once 'functions.php';

    if (isset($_GET['conf'])) {
        $conf_num = addslashes($_GET['conf']);
        $query = "SELECT * FROM users WHERE users.confirmed = $conf_num;";
        $result = mysql_query($query);
        
        if($result) {
            $row = mysql_fetch_array($result,MYSQL_ASSOC);
            
            if($row[confirmed] == $conf_num) {
                $query = "UPDATE users SET confirmed = null WHERE confirmed = $conf_num";
                $result = mysql_query($query);
                
                $_POST['username'] = $row[displayName];
                $_POST['password'] = $row[password];
                $_POST['static'] = 'true';
                require_once 'login_processing.php';
            
                require_once '../header.php';
                echo "<div class = 'body'>";
                echo "<div class = 'col large'>";
                
                echo "<h1 class='head'>Welcome $row[displayName]!</h1>";
                echo '<p>Your account has been successfully activated! Now 
                      you\'ll be able to <a href="'.ed(false).'forms.php">add 
                      events</a> for all to see, and view private Grinnell 
                      only events!</p>';
                echo '<p>As always, remember that SELF GOV IS LOVE.</p>';
            } else {
                require_once '../header.php';
                echo "<div class = 'body'>";
                echo "<div class = 'col large'>";
                
                echo "<h1 class='head'>Wrong confirmation number entered</h1>
                      <p><a href='validate.php'>Please try again</a></p>";
            }
        }
    } else {
        require_once '../header.php';
        echo "<div class = 'body'>";
        echo "<div class = 'col large'>";
        ?>
        
        <h1 class="head">Account Validation</h1>
        <p>Congratulations you have been registered!  However, in order to
        activate your account you need to enter the validation code that has 
        been sent to your Grinnell email account into the form below.</p>
        
        <form action="validate.php" method="get">
            <div class="form-unit long">
                <label for="conf">Validation Code:</label>
                <input type="text" name="conf" id="conf">
            </div>
            <div class="form-unit long">
                <label>&nbsp;</label>
                <input type="submit" value="Validate">
            </div>
        </form>
        
        <?php
    }
    
    echo "</div>";    
    require_once '../sidebar.php';
    echo "</div>";
    require_once '../footer.php'; 
?>
