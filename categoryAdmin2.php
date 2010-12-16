<?php
/*
 * categoryAdmin2.php: Current version of category management
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category Administrator Functions
 * @author CSC-325 Database and Web Application Fall 2010 Class
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version 3.0
 */

/*Require html header, global variables and all functions*/
require_once 'functions/connection.php';
require_once 'global.php';
require_once 'header.php';
?>
            
<!-- Permanent Categories Table -->
<div class="body" >
   <div class="col large">
   <form name='downgrade' action='updowngrade.php' method='post'>
   <table>
   <tr>
   <th  class='catcol'>Category</th>
   <th>Requests</th>
   <th>Downgrade</th>
   <th>Rename</th>
   </tr>
<?php
$category_names         = array();
$category_requests      = array();
$temp_category_names    = array();
$temp_category_requests = array();

//Get permanent categories
$query_perm = "SELECT * 
              FROM categories 
              WHERE permanent=1 
              ORDER BY requestCount DESC, categoryName ASC";

//Get nonpermanent categories
$query_temp = "SELECT * 
              FROM categories 
              WHERE permanent=0 
              ORDER BY requestCount DESC, categoryName ASC";

$result_perm = mysql_query($query_perm);
$result_temp = mysql_query($query_temp);
/* To do: error check */

//store all row results
while ($row = mysql_fetch_assoc($result_perm)) {
  $category_names[$row['categoryID']]    =  $row['categoryName'];
  $category_requests[$row['categoryID']] =  $row['requestCount'];
} 

foreach ($category_names as $id => $name) {
  echo "<tr>\n";
  echo "<td>" . $name . "</td>\n";
  echo "<td align='center'>" . $category_requests[$id] . "</td>\n";
  echo "<td align='center'>" . "<input type='checkbox' name=" . $id
    . " value='downgrade' class='updowngrade_box' /><br />" . "</td>\n"; 
  echo "<td align='center'>" . "<input type='checkbox' id=" . $id
  . " value='rename'/><br />" . "</td>\n";
  echo "</tr>\n";
}
?>
</table>
</form>      
</td>
</tr>
<tr>
<td>
   <!-- Temporary Categories Table -->
   <form name='upgrade' action='updowngrade.php' method='post'>
   <table>
   <tr>
   <th class='catcol'>Category</th>
   <th>Requests</th>
   <th>Upgrade</th>
  <th>Rename</th>
   </tr>

<?php
//store all temporary category row results
while ($row = mysql_fetch_assoc($result_temp)) {
  $temp_category_names[$row['categoryID']]    =  $row['categoryName'];
  $temp_category_requests[$row['categoryID']] =  $row['requestCount'];
}

foreach($temp_category_names as $id => $name){
  echo "<tr>\n";
  echo "<td>" . $name . "</td>\n";
  echo "<td align='center'>" . $temp_category_requests[$id] . "</td>\n";
  echo "<td align='center'>" . "<input type='checkbox' name=" . $id
    . " value='upgrade' class='updowngrade_box' /><br />" . "</td>\n"; 
  echo "<td align='center'>" . "<input type='checkbox' id=" . $id
  . " value='rename'/><br />" . "</td>\n";
  echo "</tr>\n";
}
?>
</table>
</form>
<button type='button' id='update'>Update</button>

<script type="text/javascript">
<!--
  //function that adds input field if input for location/category drop down menu is other
  $(document).ready(function(){
      $("input[value='rename']").change ( function () {
          var catID = $(this).attr('id');
          if( this.checked ) {
            $(this).parent().parent().append('<td id = "rename_' + catID + '" > <input type="text" name="' + catID + '" class="rename_field"></td>\n');
          } else {
            $('#rename_' + catID).remove();
          }
        });

      $("#update").click ( function() {
          var updowngrades_str = '';
          $('input.updowngrade_box').each ( function () {
              if ( this.checked ) {
                var catID = $(this).attr('name');
                var value = $(this).val();
                updowngrades_str = updowngrades_str + catID + '=' + value + '&';
              }
            });

          var renames_str = '';
          $('input.rename_field').each ( function () {
              var catID = $(this).attr('name');
              var value = $(this).val();
              renames_str = renames_str + catID + '=' + value + '&';
            });
          var success = 0;
          $.ajax({
            type:"post",
                url:"updowngrade.php",
                data: updowngrades_str,
                success: function (r) {
                if (r == 1) 
                  {
                    window.location = window.location.href;
                  }
              }
            });
          
          $.ajax({
            type:"post",
                url:"rename.php",
                data: renames_str,
                success: function (r) {
                if (r == 1) 
                  { 
                    window.location = window.location.href;
                  }
              }
            });    
        });
    });
-->
</script>
</div>
<?php include 'sidebar.php'; ?>
</div>
<?php include 'footer.php'; ?>
</body>
  
</HTML>
