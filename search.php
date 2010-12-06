<?php
    require_once 'global.php';
    require_once 'header.php';
?>
    <div class="body">
        <div class="col large">
            <form method="get" action="<?php ed(); ?>results.php">
                <!-- EVENT NAME -->
                <div class="form-unit long">
                    <label for="event_name" id="event_name_label">Event Name:</label>
                    <input type="text" id="event_name" name="event_name">
                </div>
                
                <!-- LOCATIONS -->
                <div class="form-unit long">
                    <label for="location" id="location_label">Location:</label>
                    <select name="location" id="location">
                    <option></option>
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
                    <label for="category" id="category_label">Category:</label>
                    <select name="category" id="category">
                    <option></option>
                       <?php
                            $category_query = "SELECT categoryID,categoryName FROM categories WHERE permanent=1";
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
                    <span class="lb">Start Time</span>
                
                    <label for="start_date" id="start_date_label" class="small">Date:</label>
                    <input type="text" id="start_date" name="start_date" class="date">
                    
                </div>
                
                <!-- END DATE -->
                <div class="form-unit">
                    <span class="lb">End Time</span>
                    
                    <label for="end_date" id="end_date_label" class="small">Date:</label>
                    <input type="text" id="end_date" name="end_date" class="date">
                   
                </div>
                
                <div class="form-unit">
                    <input type="hidden" value="a" name="t">
                    <input type="submit" value="Search Events" name="submit" id="submit">
                </div>
            </form>
        </div>
        
        <?php include 'sidebar.php'; ?>
    </div>
    <?php include 'footer.php'; ?>
</div>
</div>

<script type="text/javascript">
    $(document).ready ( function () {
        // human_to_unix
        function human_to_unix (str) {
            var human = new Date(str);
            return human.getTime()/1000.0;
        }
        
        // formats time given in string to fit precondition for human_to_unix
        function reformat_time (str) {                
            var bits = str.split(' ');
            
            // check to see if time is already [time] [AM/PM]
            if (bits.length != 2 && bits.length != 0) {
                // time isnt in correct format, reformat
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
        
        // get rid of empty blocks in array
        function trim_array(ar) {
            for(var i=0;i<ar.length;i++) {
                ar[i] = $.trim(ar[i]);
                if(ar[i] == false || ar[i] == null || ar[i] == '' || ar[i] == ' ') {
                    ar.splice(i,1);
                }
            }
            return ar;
        }
        
        // initializes calender picker for #start_date
        $('#start_date').datepicker({dateFormat:"M d, yy",
                                     numberOfMonths:1,
                                     showAnim:"blind",
                                     showButtonPanel:true,
                                     // sets value in #end_date field to be the same as #start_date
                                     onSelect: function () {
                                        $('#end_date').datepicker("option","minDate",$(this).val());
                                        if ($('#end_date').val() == '') {
                                            $('#end_date').val($(this).val());
                                        }
                                     } });
        // initializes calender picker for #end_date
        $('#end_date').datepicker({dateFormat:"M d, yy",
                                   numberOfMonths:1,
                                   showAnim:"blind",
                                   showButtonPanel:true });
        
        // blur function for showing improper format in start_time
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
       
       // blur function for showing improper format in end_time
       // checks to see if start time is before end time as well
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
                
                // apply one time click function; first change background
                // to show improper input and then allow user to revert 
                // color back to normal
                if (end_final < start_final) {
                    $(this).css({background:'red'}).click( function () { $(this).css({background:'white'}); });
                    $('#end_time_alert').html('Event ends before it starts, universe ending paradox detected');
                } else {
                    $('#end_time_alert').html('');
                }
            }
        });
        
        // function that adds input field if input for location/category drop down menu is other
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
