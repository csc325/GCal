<?php
/*
 * edit.php: provides HTML to edit an event
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt. If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category edit
 * @author CSC-325 Database and Web Application Fall 2010 Class
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 * @version 3.0
 */
require_once 'global.php';

$eventID = $_GET[eventID];

if(!is_owner($eventID) && !is_admin()) 
  header('Location: '.ed(false).'detailView.php?eventID='.$eventID);
    
require_once 'header.php';
?>

<div class="body">
  <div class="col large">
  <?php
  if (!is_logged_in()) :
    echo '<h1 class="head">You are not logged in</h1>';
    echo '<p>Please log in or sign up to add events</p>';
else :
  if ($_GET['s'] == 'f') echo '<h1 class="head">Required fields have been left empty</h1><hr>';
if ($_GET['time'] == 'f') echo '<h1 class="head">Invalid value for time field</h1><hr>';

$eventID = htmlspecialchars($_GET['eventID']);
$eventArray = get_events(array($eventID));
            
$public_query = "SELECT public 
                 FROM events 
                 WHERE eventID = $eventID;";
$public_result = mysql_query($public_query);
$row = mysql_fetch_array($public_result);
$public = $row[0];

$tag_query = "SELECT tag 
              FROM tags 
              WHERE eventID = $eventID;";
$tag_result = mysql_query($tag_query);
$tags = array();
            
if($tag_result)
  while($row = mysql_fetch_array($tag_result)) $tags[] = $row[0];
                   
if ($eventArray === false) {       
  echo '<h1 class="head">No events were found</h1>';
  return false;
}
            
/*  Values of $eventArray are:
    [0] = eventName
    [1] = description
    [2] = startDate
    [3] = startTime
    [4] = endDate
    [5] = endTime
    [6] = locationName
    [7] = categoryName
    [8] = user displayName
    [9] = popularity
    [10] = eventID
    [11] = public */
             
$event = array_map('stripslashes',$eventArray[0]);
$startTime = strtotime($event[2].' '.$event[3]);
$endTime = strtotime($event[4].' '.$event[5]);
$event[2] = date('M j, Y',$startTime);
$event[3] = date('g:i A',$startTime);
$event[4] = date('M j, Y',$endTime);
$event[5] = date('g:i A',$endTime);
$dif_days = ($event[2] != $event[4]) ? true : false;    
$user = get_user_info();
?>
    
        <h1 class="head">Edit Event</h1>
        
        <p>Change the details of the event as you like.</p>
        
        <form method="post" action="<?php ed(); ?>edit_submit.php?eventID=<?php echo $eventID; ?>">
            <!-- EVENT NAME -->
            <div class="form-unit long">
                <label for="event_name" id="event_name_label">Event Name:</label>
                <input type="text" id="event_name" name="event_name" tabindex="1" value='<?php echo $event[0]; ?>'>
            </div>
            
            <!-- LOCATIONS -->
            <div class="form-unit long">
                <label for="location" id="location_label">Location:</label>
                <select name="location" id="location" class="'<?php echo $event[6]; ?>" tabindex="2">
                    <?php
                        $permanent_query = 'SELECT permanent 
                                            FROM locations 
                                           WHERE locationName="'. $event[6].'"';
                        $permanent_result = mysql_query($permanent_query);
                        $row = mysql_fetch_array($permanent_result);
                        $permanent = $row[0];

                        $location_query = "SELECT locationID,locationName
                                           FROM locations 
                                           WHERE permanent=1";
                        $location_result = mysql_query($location_query);
                        while($row = mysql_fetch_array($location_result)){
                          if($row['locationName']==$event[6])
                            echo '<option value="'.$row['locationName'].'" selected>'.stripslashes($row['locationName']).'</option>';
                          else
                            echo '<option value="'.$row['locationName'].'">'.stripslashes($row['locationName']).'</option>';
                        }

                        if($permanent==0){
                    ?> 
                    <option value="other" selected>Other...</option><input type="text" id="location_other" name="location_other" class="other" value='<?php echo $event[6]; ?>'>
                    <?php
                          } else {
                    ?>
                    <option value="other">Other...</option>
                    <?php
                          } 
                    ?>
                </select>
            </div>
            
            <!-- CATEGORIES -->
            <div class="form-unit long">
                <label for="category" id="category_label">Category:</label>
                <select name="category" id="category"  class="'<?php echo $event[7]; ?>"tabindex=3>
                   <?php
                        $permanent_query = 'SELECT permanent 
                                            FROM categories 
                                           WHERE categoryName="'. $event[7].'"';
                        $permanent_result = mysql_query($permanent_query);
                        $row = mysql_fetch_array($permanent_result);
                        $permanent = $row[0];

                        $category_query = "SELECT categoryName 
                                           FROM categories WHERE permanent=1";
                        $category_result = mysql_query($category_query);
                        while($row = mysql_fetch_array($category_result)){
                          if($row['categoryName']==$event[7])
                            echo '<option value="'.$row['categoryName'].'" selected>'.stripslashes($row['categoryName']).'</option>';
                          else
                            echo '<option value="'.$row['categoryName'].'">'.stripslashes($row['categoryName']).'</option>';
                        }

                        if($permanent==0){
                    ?> 
                    <option value="other" selected>Other...</option><input type="text" id="category_other" name="category_other" class="other" value='<?php echo $event[7]; ?>'>
                    <?php
                          } else {
                    ?>
                    <option value="other">Other...</option>
                    <?php
                          } 
                    ?>
                </select>
            </div>
            
            <!-- START DATE -->
            <div class="form-unit">
                <span class="lb">Start Time</span>
            
                <label for="start_date" id="start_date_label" class="small">Date:</label>
                <input type="text" id="start_date" name="start_date" class="date" tabindex=4  value='<?php echo $event[2]; ?>'>
                
                <label for="start_time" id="start_time_label" class="small">Time:</label>
                <input type="text" id="start_time" name="start_time" class="time" tabindex="6"  value='<?php echo $event[3]; ?>'>
                    <span class="tt">(HH:MM AM/PM)</span>
            </div>
            
            <!-- END DATE -->
            <div class="form-unit">
                <span class="lb">End Time</span>
                
                <label for="end_date" id="end_date_label" class="small">Date:</label>
                <input type="text" id="end_date" name="end_date" class="date" tabindex="5"  value='<?php echo $event[4]; ?>'>
                
                <label for="end_time" id="end_time_label" class="small">Time:</label>
                <input type="text" id="end_time" name="end_time" class="time" tabindex="7"  value='<?php echo $event[5]; ?>'>
                    <span id="end_time_alert" class="alert"></span>
                    <span class="tt">(HH:MM AM/PM)</span>
            </div>
            
            <!-- DESCRIPTION -->
            <div class="form-unit long">
                <label for="description" id="description_label">Event Description:</label>
                <textarea name="description" id="description" tabindex="8"><?php echo $event[1]; ?></textarea>
            </div>
            
            <!-- TAGS -->
            <div class="form-unit long">
                <label for="tags" id="tags_label">Tags:</label>
                <input type="text" id="tags" name="tags" tabindex="9" value='<?php
                        echo implode(", ", $tags);
                    ?>'>
                    <span class="tt">(Comma separated tags)</span>
            </div>
            
            <!-- VISIBILITY -->
                          <div class="form-unit long">
                <label id="public_label">Visible to non-Grinnellians?</label>

                    <?php
                          if($public==1) {
                    ?>
                <label class="radio" for="public_yes">Yes
                <input type="radio" value="yes" name="public" id="public_yes" tabindex="10" checked></label>
                <label class="radio" for="public_no">No
                <input type="radio" value="no" name="public" id="public_no" tabindex="11"></label></label>
                    <?php
                          } else {
                    ?>
                <label class="radio" for="public_yes">Yes
                <input type="radio" value="yes" name="public" id="public_yes" tabindex="10"></label>
                <label class="radio" for="public_no">No
                <input type="radio" value="no" name="public" id="public_no" tabindex="11" checked></label>
                    <?php
                          } 
                    ?>
            </div>
            
            <div class="form-unit">
                <label>&nbsp;</label><input type="submit" value="Edit Event" name="submit" id="submit">
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
            var val = $(this).attr('class').substring(1);
            
            if ($(this).val() == 'other') {
                $(this).parent().append('<input type="text" id="'+ loc_cat + '_other" name="'+ loc_cat + '_other" class="other" value="'+val+'">');
            } else {
                $('#'+ loc_cat + '_other').remove();
            }
        });
    });
</script>

</body>
</html>
