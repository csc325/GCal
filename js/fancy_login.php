<?php 
    header('Content-type: application/x-javascript');
    require_once '../global.php';
?>

$(document).ready ( function () {
    // More important stuff ---------------------------------------------------
    
    // Function that makes an AJAX call to check the login information and 
    // take appropriate actions afterwards depending on success or failure
    var submit_login = function () {
        var user = $('input#username').val();
        var pass = $('input#password').val();
        
        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>user_accounts/login_processing.php",
            data: ({username:user,password:pass}),
            success: function (r) {
                if (r == 2) $('div.login-form span.warning').html('Please validate your account').show();
                if (r == 1) window.location = "<?php echo $_SERVER['HTTP_REFERER']; ?>";
                if (r == 0) $('div.login-form span.warning').html('Wrong username/password').show();
            }
        });
    }
    
    // Function that makes an AJAX call to add tags to the database
    var submit_tag = function () {
        var tag = $('input#tag-list').val();
        var eventID = $('.event_listing').attr("id");
        var html = $('.event_listing').attr("id");
        
        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>submit_tag.php",
            data: ({tags:tag,eventID:eventID}),
            success: function (r) {       
              $('span.val.tags').load('<?php echo $_SERVER["HTTP_REFERER"]; ?> span.val.tags').hide().fadeIn("slow");
            }      
     
        });
    }
    
    // When the 'login' button is clicked, call function submit_login
    $('input#fancy-login-button').click ( function () { submit_login(); });
    
    // When the 'add tags' button is clicked, call function submit_tags
    $('input#fancy-tag-button').click ( function () { submit_tag(); });
    
    // When the enter key is pressed in the password field, call submit_login
    $('#password').keypress ( function (e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) submit_login();
    });
    
    $('#tag-list').keypress ( function (e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) submit_tag();
    });
    
    
    // Not important stuff ----------------------------------------------------
    
    // When 'login' is clicked, show login form
    $('#fancy-login').click ( function () {
        $(this).find('div.login-form').fadeIn('fast');
        $('#fancy-login div.login-form').addClass('active');
    }).mouseout ( function () {
        $('#fancy-login div.login-form').removeClass('active');
    });
    
    // Mousing in and out of login form adds and removes 'active' class, 
    // respectively.  This will be used to determine when to fade out the 
    // login form, see below.
    $('#fancy-login div.login-form').hover ( function () {
        $(this).addClass('active');
    }, function () {
        $(this).removeClass('active');
    });
    
    // When any where EXCEPT the login form and 'login' link is clicked check
    // to see if login form is 'active' and fade the form out if it is not
    // do nothing if the form is 'active'.
    $(document).not('#fancy-login, #fancy-login div.login-form').click ( function () {
        if ($('#fancy-login div.login-form').hasClass('active')) return false;
        $('#fancy-login div.login-form').fadeOut('fast');
    });
    
    // Take care of the 'forgot password' link, a normal link (anchor) screws
    // up the style for some reason, js is used to create a mock link.
    $('span.link').hover ( function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    }).click ( function () {
        window.location = "<?php ed(); ?>user_accounts/change_password_form.php";
    });
});
