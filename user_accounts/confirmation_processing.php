<?php
    include '../functions/connection.php';
    include '../global.php';
    include '../header.php';
    include 'functions.php';

    echo "<div class = 'body'>";
    echo "<div class = 'col large'>";
      
    $conf_num = mysql_real_escape_string($_POST["confirm"]);
    $conf_num = str_replace(" ","", $conf_num);
    $query = "SELECT confirmed, displayName FROM users WHERE users.confirmed = $conf_num;";
    $result = mysql_query($query);
    if(!have_error($result, $query)) {
        $row = mysql_fetch_row($result);
        if($row[0] == $conf_num) {
            echo "Registration for $row[1] confirmed.";
            $result = mysql_query("UPDATE users SET confirmed = null WHERE confirmed = $conf_num;");
        } else {
            echo "Wrong confirmation number entered.";
        }
    }
    
    echo "</div>";    
    include '../sidebar.php';
    echo "</div>";
    include '../footer.php'; 
?>
