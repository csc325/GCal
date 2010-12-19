<?php 
    header('Content-type: application/x-javascript');
    include '../global.php';
?>

$(document).ready ( function () {
    $('a.attend_event').click ( function () {
        attend_event($(this));
    });
    
    $('a.attend_event span.cancel').click ( function () {
        cancel_attend($(this));
    });
    
    $('div.filter h3 span.arrow').toggle ( function () {
        $(this).parent().parent().animate({left:'0px'},250);
        $(this).html("&laquo;");
    }, function () {
        $(this).parent().parent().animate({left:'-160px'},250);
        $(this).html("&raquo;");
    });

    var attend_event = function (this_el) {
        if (this_el.hasClass('attending')) return false;
        
        var this_id = this_el.attr('id');
        this_id = this_id.split('_');
        
        var event_id = this_id[1];
        var user_id = this_id[2];
        
        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>functions/ajax.php",
            data: ({action:'ajax',function:'attend_event',eventID:event_id, userID:user_id}),
            success: function (r) {
                if (r == 1) {
                    var sel = $('a#event_'+event_id+'_'+user_id);
                    sel.addClass('attending').html('Attending <span class="cancel">X</span>');
                    sel.find('span.cancel').click ( function () { cancel_attend($(this)); });
                    
                    var count = parseInt(sel.parent().find('span.val.attend_count').text());
                    sel.parent().find('span.val.attend_count').html(count+1);
                    update_sidebar();
                }
            }
        });
    }

    var cancel_attend = function (this_el) {
        var this_id = this_el.parent().attr('id');
        this_id = this_id.split('_');
        
        var event_id = this_id[1];
        var user_id = this_id[2];
        
        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>functions/ajax.php",
            data: ({action:'ajax',function:'cancel_attend',eventID:event_id, userID:user_id}),
            success: function (r) {
                if (r == 1) {
                    var sel = $('a#event_'+event_id+'_'+user_id);
                    sel.removeClass('attending').html('Attend!');
                    sel.find('span.cancel').remove();
                    
                    var count = parseInt(sel.parent().find('span.val.attend_count').text());
                    sel.parent().find('span.val.attend_count').html(count-1);
                    update_sidebar();
                }
            }
        });
    }

    var update_sidebar = function () {
        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>sidebar.php",
            data: ({action:'update'}),
            success: function (r) {
                $('div.side').html(r);
            }
        });
    }

 $('a.hide_event').click ( function () {
        hide_event($(this));
    });

    var hide_event = function (this_el) {
        var this_id = this_el.attr('id');
        this_id = this_id.split('_');

        var event_id = this_id[1];
        var user_id = this_id[2];

        $.ajax({
            type: "POST",
            url: "<?php ed(); ?>functions/ajax.php",
            data: ({action:'ajax',function:'hide_event',eventID:event_id,
            userID:user_id}),
            success: function (r) {
                if (r == 1) {
                    var sel = $('a#hidden_'+event_id+'_'+user_id);
                    sel.click ( function () {
            hide_event($(this)); });
                    update_events();
                }
            }
        });
    }

    var update_events = function () {
        $.ajax({
            type: "POST",
            url: window.location.pathname,
            data: ({action:'update'}),
            success: function (r) {
                $('div.event_listing').html(r);
            }
        });
    }
});
