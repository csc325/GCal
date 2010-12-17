<?php
    require_once 'global.php';
    require_once 'header.php';
?>

<div class="body">
    <div class="col large">
    <?php
        if (!is_logged_in()) : echo '<h1 class="head">You are not logged in</h1><p>Please log in or sign up to add events</p>';
        else :
          if($_GET['s'] == 'f') {
               echo '<h1 class="head">Missing some fields; event not added</h1><hr>';
          }
          if($_GET['s'] == 'time') {
               echo '<h1 class="head">You cannot add an old event</h1><hr>';
          }
            elseif ($_GET['s'] == 't') {
                echo '<h1 class="head">Your event has been added sucessfully</h1><hr>';
            }
        ?>
    
    <h1 class="head">Add an Event</h1>
    
    <p>Please provide as much information about your event as possible.  An 
    informative description will help users a lot more than a simple one-line.
    Also, remember to mark your events as NOT visible to 
    non-Grinnellians if you do not want people off campus to see.</p>
    <p>(Required fields in red)</p>
    
    <form method="post" action="<?php ed(); ?>submit.php">
        <!-- EVENT NAME -->
        <div class="form-unit long">
            <label for="event_name" id="event_name_label"><font color = "firebrick">Event Name:</font></label>
            <input type="text" id="event_name" name="event_name" tabindex="1">
        </div>
        
        <!-- LOCATIONS -->
        <div class="form-unit long">
            <label for="location" id="location_label"><font color = "firebrick">Location:</label></font>
            <select name="location" id="location" tabindex="2">
                <?php
                    $location_query = "SELECT locationID,locationName FROM locations WHERE permanent=1";
                    $location_result = mysql_query($location_query);
                    while($row = mysql_fetch_array($location_result)){
                        echo '<option value="'.$row['locationName'].'">'.stripslashes($row['locationName']).'</option>';
                    }
                ?>
                <option value="other">Other...</option>
            </select>
        </div>
        
        <!-- CATEGORIES -->
        <div class="form-unit long">
            <label for="category" id="category_label"><font color = "firebrick">Category:</label></font>
            <select name="category" id="category" tabindex=3>
               <?php
                    $category_query = "SELECT categoryName FROM categories WHERE permanent=1";
                    $category_result = mysql_query($category_query);
                    while($row = mysql_fetch_array($category_result)){
                        echo '<option value="'.$row['categoryName'].'">'.stripslashes($row['categoryName']).'</option>';
                    }
                ?> 
                <option value="other">Other...</option>
            </select>
        </div>
        
        <!-- START DATE -->
        <div class="form-unit">
            <span class="lb"><font color = "firebrick">Start Time</span></font>
        
            <label for="start_date" id="start_date_label" class="small">Date:</label>
            <input type="text" id="start_date" name="start_date" class="date" tabindex=4">
            
            <label for="start_time" id="start_time_label" class="small">Time:</label>
            <input type="text" id="start_time" name="start_time" class="time" tabindex="6">
                <span class="tt">(HH:MM AM/PM)</span>
        </div>
        
        <!-- END DATE -->
        <div class="form-unit">
            <span class="lb"><font color = "firebrick">End Time</span></font>
            
            <label for="end_date" id="end_date_label" class="small">Date:</label>
            <input type="text" id="end_date" name="end_date" class="date" tabindex=5">
            
            <label for="end_time" id="end_time_label" class="small">Time:</label>
            <input type="text" id="end_time" name="end_time" class="time" tabindex="7">
                <span id="end_time_alert" class="alert"></span>
                <span class="tt">(HH:MM AM/PM)</span>
        </div>
        
        <!-- DESCRIPTION -->
        <div class="form-unit long">
            <label for="description" id="description_label">Event Description:</label>
            <textarea name="description" id="description" tabindex="8"></textarea>
        </div>
        
        <!-- TAGS -->
        <div class="form-unit long">
            <label for="tags" id="tags_label">Tags:</label>
            <input type="text" id="tags" name="tags" tabindex="9">
                <span class="tt">(Comma separated tags)</span>
        </div>
        
        <!-- VISIBILITY -->
        <div class="form-unit long">
            <label id="pulic_label">Visible to non-Grinnellians?</label>
            
            <label class="radio" for="public_yes">Yes
            <input type="radio" value="yes" name="public" id="public_yes" tabindex="10"></label>
            <label class="radio" for="public_no">No
            <input type="radio" value="no" name="public" id="public_no" tabindex="11" checked></label>
        </div>
        
        <div class="form-unit">
            <label>&nbsp;</label><input type="submit" value="Add Event" name="submit" id="submit">
        </div>
    </form>
    <?php endif; ?>
    
    </div>
    <?php include 'sidebar.php'; ?>
</div>
<?php include 'footer.php'; ?>

<script type="text/javascript">
    $(document).ready ( function () {
        // human_to_unix
        function human_to_unix (str) {
            var human = new Date(str);
            return human.getTime()/1000.0;
        }
        
        //formats time given in string to fit precondition for human_to_unix
        function reformat_time (str) {                
            var bits = str.split(' ');
            
            //check to see if time is already [time] [AM/PM]
            if (bits.length != 2 && bits.length != 0) {
                //time isnt in correct format, reformat
                var am_bits = trim_array(str.split(/(am)|(AM)/));
                var pm_bits = trim_array(str.split(/(pm)|(PM)/));
                
                if (am_bits.length > 1) {
                    return am_bits.join(' ');
                } else if (pm_bits.length > 1) {
                    return pm_bits.join(' ');
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
        
        //get rid of empty blocks in array
        function trim_array(ar) {
            for(var i=0;i<ar.length;i++) {
                ar[i] = $.trim(ar[i]);
                if(ar[i] == false || ar[i] == null || ar[i] == '' || ar[i] == ' ') {
                    ar.splice(i,1);
                }
            }
            return ar;
        }
        
        //initializes calender picker for #start_date
        $('#start_date').datepicker({dateFormat:"M d, yy",
                                     numberOfMonths:1,
                                     showAnim:"blind",
                                     //sets value in #end_date field to be the same as #start_date
                                     onSelect: function () {
                                        $('#end_date').datepicker("option","minDate",$(this).val());
                                        if ($('#end_date').val() == '') {
                                            $('#end_date').val($(this).val());
                                        }
                                     } });
        //initializes calender picker for #end_date
        $('#end_date').datepicker({dateFormat:"M d, yy",
                                   numberOfMonths:1,
                                   showAnim:"blind" });
        
        //blur function for showing improper format in start_time
        $('#start_time').blur( function () {
            if ($(this).val() != '') {
                var reformat = reformat_time($(this).val());
                if (reformat == false) {
                    $(this).css({background:'red'}).click( function () { $(this).css({background:'white'}); });
                } else if (typeof(reformat) == 'string') {
                    $(this).val(reformat);
                }
            }
        });
       
       //blur function for showing improper format in end_time
       //checks to see if start time is before end time as well
        $('#end_time').blur( function () {
            if ($(this).val() != '') {
                var reformat = reformat_time($(this).val());
                if (reformat == false) {
                    $(this).css({background:'red'}).click( function () { $(this).css({background:'white'}); });
                } else if (typeof(reformat) == 'string') {
                    $(this).val(reformat);
                }
                
                var start_final = $('#start_date').val() + ' ' + $('#start_time').val();
                var end_final = $('#end_date').val() + ' ' + $('#end_time').val();
                start_final = human_to_unix (start_final);
                end_final = human_to_unix (end_final);
                
                //apply one time click function; first change background to show improper input and then allow user to
                //revert color back to normal
                if (end_final < start_final) {
                    $(this).css({background:'red'}).click( function () { $(this).css({background:'white'}); });
                    $('#end_time_alert').html('Event ends before it starts, universe ending paradox detected');
                } else {
                    $('#end_time_alert').html('');
                }
            }
        });
        
        //function that adds input field if input for location/category drop down menu is other
        $('#location, #category').change ( function () {
            var loc_cat = $(this).attr('id').substring(0);
            
            if ($(this).val() == 'other') {
                $(this).parent().append('<input type="text" id="'+ loc_cat + '_other" name="'+ loc_cat + '_other" class="other">');
            } else {
                $('#'+ loc_cat + '_other').remove();
            }
        });
    });
</script>

</body>
</html>
