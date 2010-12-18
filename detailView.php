<?php
/*
 * detailView: creates detailed view of given eventID
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt. If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category event display feature
 * @author CSC-325 Database and Web Application Fall 2010 Class
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 * @version 3.0
 */
require_once "global.php";
require_once "header.php";
?>
<div class = "body">
  <div class = "col large">

  <?php

  if ($_GET['flag'] == 'true') {
    echo '<h1 class="head">';
    echo 'Your report has been submitted to the administrators...</h1><hr>';
	}

$event_id = htmlspecialchars($_GET['eventID']);
$event_array = get_events(array($event_id));
        
$query = "SELECT tag 
          FROM tags 
          WHERE eventID = $event_id;";
$result = mysql_query($query);
$tags = array();
if($result) 
  while($row = mysql_fetch_array($result)) $tags[] = $row[0];
            
$comments = get_event_comments($event_id);

if ($event_array === false) {       
  echo '<h1 class="head">No events were found</h1>';
  return false;
}
/* Values of $eventArray are:
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
   [10] = eventID */
         
$event = array_map('stripslashes',$event_array[0]);

$startTime = strtotime($event[2].' '.$event[3]);
$endTime   = strtotime($event[4].' '.$event[5]);

$event[2] = date('M j, Y',$startTime);
$event[3] = date('g:i A',$startTime);
$event[4] = date('M j, Y',$endTime);
$event[5] = date('g:i A',$endTime);

$dif_days = ($event[2] != $event[4]) ? true : false;    
$user = get_user_info();
?>
<div class="event_listing" id="<?php echo $event_id; ?>">
  <div class = "top_section">
  <h1>
  <?php echo $event[0]; ?>
  <span class="date">
      <?php echo $event[2].(($dif_days) ? ' to '.$event[4] : ''); ?></span>
  </h1>
  <div class="details">                        
                    
  <span>
  When:
  <span class="val">
  <?php
  echo ((!$dif_days) ? "$event[3] - $event[5]"
        : "$event[3] - $event[5]");
?>
</span>
</span>
<span>What: <span class="val"><?php echo $event[7]; ?></span></span>
<span>Where: <span class="val"><?php echo $event[6]; ?></span></span>
<span>Attending: <span class="val attend_count"><?php echo $event[9]; ?></span>
</span>                    
                    
<?php display_attend($user[userID], $event[10]);  ?>
                     
</div>
</div>
<p><?php echo $event[1]; ?></p>

<div class = "details">
  <span>Tags: <span class = "val tags">
  <?php echo implode(", ", $tags); ?>
  </span></span></div>

  <div class = "details">
  <span>Created by: 
  <span class = "val"> <?php echo $event[8]; ?>
</span></span></div>

<?php  if(is_logged_in()) : ?>
<div class="details" id="addtag">
  <a href="flag_event.php?eventID=<?php echo $event_id; ?>" class='attend_event'>Report!</a>   
  <a class="fake" id="fancy-login">
  <span class="word">Add Tags</span>
  <div class="login-form">
      <label for="tag-list">Tags: (Comma separated tags)</label>
      <input type="text" name="tag-list" id="tag-list">
      <input type="button" value="Add" id="fancy-tag-button">
  </div>
  </a>
 </div>
                          <?php endif; ?>
            
  <?php if(is_owner($event_id || is_admin())) : ?>
  <div class = "details">
                          <a href="edit.php?eventID=<?php echo $event_id; ?>" class="edit" id="edit-event">
                          Edit Event Details 
                          </a>
                          &nbsp;-&nbsp;
<a href="delete_event.php?eventID=<?php echo $event_id; ?>">
                                  Delete Event
                                  </a>
                                  </div>
                                  <?php endif; ?>

                <!-- Display 'like' feature from facebook -->
                <div class="details" id="facebook" padding="20px">
        	        <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		            <fb:like href="http://www.cs.grinnell.edu/~knolldug/GCal/detailView.php?eventID=<?php echo $eventID;?>" show_faces="true" width="450" font="arial"></fb:like>
                </div>
                
                                  <div class="details" id="googleCal">
                                  <?php
                                  //CREATE GOOGLE CAL BUTTON

                                  //Cleans up CGI characters from event input
                                  function encodeURIComponent($str) {
  $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
  return strtr(rawurlencode($str), $revert); }

//Takes the date and converts to the format readable by google calender
function date_to_string($array) {

  $startingDate = explode("-", $array[0][2]);
  $startingTime = explode(":", $array[0][3]);
  $endingDate   = explode("-", $array[0][4]);
  $endingTime   = explode(":", $array[0][5]);

  $googleDate = sprintf("%04d%02d%02dT%02d%02d00Z/%04d%02d%02dT%02d%02d00Z",
                        $startingDate[0], $startingDate[1], $startingDate[2],
                        ($startingTime[0] + 6), $endingTime[1],
                        $endingDate[0], $endingDate[1], $endingDate[2],
                        ($endingTime[0] + 06), $endingTime[1]);
  return $googleDate;
}

//CREATE THE BUTTON BY APPENDING THE ELEMENTS TOGETHER
$url = 'http://www.google.com/calendar/event?action=TEMPLATE';
$url .= "&text=" . encodeURIComponent($event_array[0][0]);
$url .= "&dates=" . date_to_string($event_array);
$url .= "&details=" . encodeURIComponent($event_array[0][1]);
$url .= "&location=" . encodeURIComponent($event_array[0][6]);
$url .= "&trp=false";
$url .= '&sprop=www.grinnellopencalender.com';
$url .= "&sprop=name:" . encodeURIComponent($event_array[0][8]);
$button = "<img src=\"http://www.google.com/calendar/images/ext/gc_button2.gif\" border=0>";
$html = '<a href="' . $url . '" >' . $button . '</a>';
echo $html;
?>
</div>
</div>

<h3 class="comment_label">Comments</h3>
  <div class="event_comments">
  <?php
  if (count($comments) == 0) :
    echo '<h3>No comments yet, be the first!</h3>';
  else :
    foreach ($comments as $row) : ?>
      <div class="comment">
      <div class="meta">
      <span class="user"><?php echo $row[displayName]; ?></span>
      <span class="timestamp"><?php echo time_to_relative($row['timestamp']); ?></span>
<?php
      if (is_owner($event_id) || is_admin()) {
        $href = "owner={$row[3]}";
        $href .= "&commentID={$row[0]}";
        $href .= "&eventID=$event_id";
        echo '<span class="delete">';
        echo '<a class="delete_comment" href="'.$href.'">Delete</a>';
        echo '</span>';
      }
?>
</div>
<div class="content">
  <p><?php echo $row[comment]; ?></p>
  </div>
  </div> <?php
  endforeach;
endif;
?>
                
<?php if(is_logged_in()) : ?>
<div class="comment_form">
  <form id="comment">
  <textarea name="comment" cols="60" rows="2">Enter your comment here...</textarea><br>
  <input type="submit" value="Add Comment" />
  </form>
  </div>
  <?php else : ?>
  <div class="comment_form">  
  <h3 class="info">Log in to leave comments</h3>
  </div>
  <?php endif; ?>
  </div>
  </div>
        
  <script type="text/javascript">
  $(document).ready ( function () {
      var self = window.location.href;
      var textarea = $('textarea[name="comment"]');
      var default_val = textarea.val();
                
      textarea.focus ( function () {
          if ($(this).val() == default_val) $(this).val('');
        }).blur ( function () {
            if ($(this).val() == '' || $(this).val() == ' ') $(this).val(default_val);
          });
                
      $('#comment').submit( function () {
          if (textarea.val() == default_val && textarea.val() != '') return false;
          add_comment(textarea,<?php echo $event_id; ?>);
          return false;
        });
                
      $('.delete_comment').click ( function () {
          var data_str = 'action=ajax&function=delete_comment&'+$(this).attr('href');
          delete_comment(data_str);
          return false;
        });
                
      var add_comment = function (textarea,event_id) {
        var comment_text = textarea.val();
                    
        $.ajax({
          type:'post',
              url:'functions/ajax.php',
              data:({action:'ajax',function:'add_comment',eventID:event_id,comment:comment_text}),
              success: function (r) {
              r = parseInt(r);
              if (r == 1) window.location = self;
              if (r == 0) return false;
            }
          });
                    
        return false;
      }

      var delete_comment = function (dataStr) {
        $.ajax({
          type:'post',
              url:'functions/ajax.php',
              data:dataStr,
              success: function (r) {
              r = parseInt(r);
              if (r == 1) window.location = self;
              if (r == 0) return false;
            }
          });
                    
        return false;
      }
    });
</script>
                
        
<?php 
include 'sidebar.php';
echo "</div>";
include 'footer.php';
echo "</body></html>";
?>
