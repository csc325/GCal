<?php
/*
* Essentially an HTML form to register for an account
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category user functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
include '../functions/connection.php';
include '../global.php';
include '../header.php';
?>

<div class="body">
<div class="col large">
<h1 class="head">Sign Up!</h1>
<p>Register your Grinnell email address with and you'll be able to add
events and see private Grinnell only events. Like with
<a href="http://www.grinnellplans.com">[Plans]</a> your username will be
your Grinnell username.</p>
<?php
        if(isset($_GET['q'])) {
            $q = $_GET['q'];
            
            $out = '<h2 class="warning">';
            if ($q == 'exists') {
                $out .= 'The username "'.$_GET['user'].'" already exists';
            } elseif ($q == 'nomatch') {
                $out .= 'The passwords you entered do not match';
            } elseif ($q == 'chars') {
                $out .= 'The username you provided is not valid, only letters and numbers are allowed';
            }
            $out .= '<h2>';
            
            echo $out;
        }
    ?>
<form method="post" action="<?php ed(); ?>user_accounts/registration_processing.php">
<div class="form-unit long">
<label for="username" id="username_label">Grinnell Email Address:</label>
<input type="text" id="username" name="username" tabindex="1">
<span style="padding: 0 0 0 4px; font-size: 13px; line-height: 20px; color: #888;">@grinnell.edu</span>
<span class="tt warning username"></span>
</div>
<div class="form-unit long">
<label for="password1" id="password1_label">Password:</label>
<input type="password" id="password1" name="password1" tabindex="2">
</div>
<div class="form-unit long">
<label for="password2" id="password2_label">Password:</label>
<input type="password" id="password2" name="password2" tabindex="3">
<span class="tt warning password"></span>
</div>
<div class="form-unit long">
<label>&nbsp;</label><input type="submit" value="Register" id="register_submit">
</div>
</form>
</div>
<?php include '../sidebar.php'; ?>
</div>

<script type="text/javascript">
$(document).ready ( function () {
$('input#username').blur ( function () {
var username = $(this).val();
var patt = new RegExp("[^a-zA-Z0-9]");
if (patt.test(username)) {
$('span.warning.username').html('Invalid email address, only letters and numbers allowed');
$('input#register_submit').attr({disabled:true});
} else {
$('span.warning.username').html('');
if ($('span.warning.password').html() == '') $('input#register_submit').attr({disabled:false});
}
});
$('input#password2, input#password1').keyup ( function () {
var pass_a = $('input#password1').val();
var pass_b = $('input#password2').val();
if (pass_a != pass_b) {
$('span.warning.password').html('Passwords do not match');
$('input#register_submit').attr({disabled:true});
} else {
$('span.warning.password').html('');
if ($('span.warning.username').html() == '') $('input#register_submit').attr({disabled:false});
}
});
});
</script>

<?php include '../footer.php'; ?>
