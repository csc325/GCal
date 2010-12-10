<head>
    <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Grinnell Open Calendar</title>
    <?php
    if($_SESSION['css']){
	switch ($_SESSION['css'])
        {
        case 1:
  	echo '<link rel="stylesheet" type="text/css" href="/~liujingt/css/reset.css">
  	      <link rel="stylesheet" type="text/css" href="';ed();echo 'css/home.css">
  	      <link rel="stylesheet" type="text/css" href="';ed();echo 'css/ui-lightness/jquery-ui-1.8.6.custom.css">';
        break;
        case 2:
  	echo '<link rel="stylesheet" type="text/css" href="/~liujingt/css/reset.css">
  	      <link rel="stylesheet" type="text/css" href="';ed();echo 'css/Space/home.css">
  	      <link rel="stylesheet" type="text/css" href="';ed();echo 'css/Space/ui-lightness/jquery-ui-1.8.6.custom.css">';
        break;
	}
    }
    else {echo '<link rel="stylesheet" type="text/css" href="/~liujingt/css/reset.css">
  	       <link rel="stylesheet" type="text/css" href="';ed();echo 'css/home.css">
  	       <link rel="stylesheet" type="text/css" href="';ed();echo 'css/ui-lightness/jquery-ui-1.8.6.custom.css">';}

    ?>
    <link rel="alternate"  type="application/rss+xml"  title="RSS"  href="generateRSS.php" />
    <script type="text/javascript" src="<?php ed(); ?>js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php ed(); ?>js/jquery-ui-1.8.6.custom.min.js"></script>
    <script type="text/javascript" src="<?php ed(); ?>js/fancy_login.php"></script>
    <script type="text/javascript" src="<?php ed(); ?>js/fancy_tag_add.php"></script>
    <script type="text/javascript" src="<?php ed(); ?>js/main.php"></script>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
</head>
<body>

<div class="wrap">
<div id="main">
    <div class="head">
        <div class="col large">
            <a href="<?php ed(); ?>"><h1><?php if($_SESSION['css']==1) echo 'GRINNELL OPEN CALENDAR';?></h1></a>
        </div>
        
        <div class="col small" id="login">
            <?php 
                if(is_logged_in()) :
                    echo '<h3>Logged in as <span class="user">'.$_SESSION['displayName'].'</span>, ';
                    echo '<a href="'.ed(false).'user_accounts/logout.php?ref='.rawurlencode($_SERVER['REQUEST_URI']).'">log out </a> - ';
                    echo '<a href="'.ed(false).'user_accounts/user_settings.php?"> Settings</a>';
                else :
            ?>
                <h3>You are not logged in, 
                    <a class="fake" id="fancy-login">
                        <span class="word">login</span>
                        <div class="login-form">
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password">
                            <input type="button" value="Login" id="fancy-login-button">
                            <span class="link" id="forgot-password">Forgot password?</span>
                            <span class="warning">Wrong username/password</span>
                        </div>
                    </a>
                    or 
                <a href="<?php ed(); ?>user_accounts/registration_form.php">sign up</a></h3>
            <?php
                endif;
            ?>
        </div>
    </div>
    
