<?php
/*
 * categories.php: Original Version of category management feature
 *                categoryAdmin2.php is current version
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
 * @version 1.0
 * @deprecated File deprecated since version 3.0 categoryAdmin2.php
 */

/*Require html header and global variables*/
require_once 'global.php';
require_once 'header.php';
    
echo '<div class="body">';
echo '<div class="col large">';

if ($_POST) {
  $query_delete = 'UPDATE categories SET permanent=0 WHERE categoryID = ';
  $query_add    = 'UPDATE categories SET permanent=1 WHERE categoryID = ';
  $add_array    = array_keys($_POST, 'add');
  $delete_array = array_keys($_POST, 'delete');
        
  if ($addArray) {
    $query_add .= implode(' OR categoryID = ', $add_array);    
    $result_add = mysql_query($query_add);
    echo mysql_error();
    /* To do: error check */
  }

  if ($delete_array) {    
    $query_delete .= implode(' OR categoryID = ', $delete_array); 
    $result_delete = mysql_query($query_delete);
    echo mysql_error();
    /* To do: error check */
  }
}

$query_perm = "SELECT * FROM categories 
                  WHERE permanent=1 
                  ORDER BY requestCount DESC, 
                           categoryName ASC";
    
$result_perm = mysql_query($query_perm);

$query_temp = "SELECT * FROM categories 
                  WHERE permanent=0 
                  ORDER BY requestCount DESC, 
                           categoryName ASC";
    
$result_temp = mysql_query($query_temp);
?>
    
<form name="categoryPerm" action="categories.php" method="post">
  <table>
  <tr>
  <td colspan="3">
  <h1>Permanent Categories</h1>
  </td>
  </tr>
        
  <tr>
  <th>Category</th>
  <th>Requests</th>
  <th>Delete</th>
  </tr>
            
  <?php while ($row = mysql_fetch_assoc($resultPerm)) : ?>
            
  <tr>
  <td><?php echo $row['categoryName']; ?></td>
  <td><?php echo $row['requestCount']; ?></td>
  <td><input type="checkbox" name="<?php echo $row['categoryID']; ?>" value="delete"></td>
  </tr>
            
  <?php endwhile; ?>
            
  <tr>
  <td colspan="3">
  <h1>Temporary Categories</h1>
  </td>
  </tr>
            
  <tr>
  <th>Category</th>
  <th>Requests</th>
  <th>Add</th>
  </tr>
            
  <?php while ($row = mysql_fetch_assoc($resultTemp)) : ?>
            
  <tr>
  <td><?php echo $row['categoryName']; ?></td>
  <td><?php echo $row['requestCount']; ?></td>
  <td><input type="checkbox" name="<?php echo $row['categoryID']; ?>" value="add"></td>
  </tr>
            
  <?php endwhile; ?>
            
  </table>
        
  <input type="submit" value="update">
  </form>
    
  <style type="text/css">
  table {
margin: 10px 10px 10px 0;
}
        
td, th {
padding: 5px;
border: 1px solid #ccc;
}
        
th {
  text-align: left;
  font-size: 14px;
color: #555;
}
</style>
    
<?php
echo '</div>';
require_once 'sidebar.php';
echo '</div>';
require_once 'footer.php';
?>
