<?php
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

$queryPerm = "SELECT * FROM categories WHERE permanent=1 ORDER BY requestCount"
  . " DESC, categoryName ASC";

$resultPerm = mysql_query($queryPerm);
/* To do: error check */

$queryTemp = "SELECT * FROM categories WHERE permanent=0 ORDER BY requestCount"
  . " DESC, categoryName ASC";

$resultTemp = mysql_query($queryTemp);
/* To do: error check */

$categoryNames = array();
$categoryRequests = array();

while ($row = mysql_fetch_assoc($resultPerm)){
  $categoryNames[$row['categoryID']] =  $row['categoryName'];
  $categoryRequests[$row['categoryID']] =  $row['requestCount'];
} 
foreach($categoryNames as $id => $name){
  echo "<tr>\n";
  echo "<td>" . $name . "</td>\n";
  echo "<td align='center'>" . $categoryRequests[$id] . "</td>\n";
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
while ($row = mysql_fetch_assoc($resultTemp)){
  $tempCategoryNames[$row['categoryID']] =  $row['categoryName'];
  $tempCategoryRequests[$row['categoryID']] =  $row['requestCount'];
}
foreach($tempCategoryNames as $id => $name){
  echo "<tr>\n";
  echo "<td>" . $name . "</td>\n";
  echo "<td align='center'>" . $tempCategoryRequests[$id] . "</td>\n";
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
