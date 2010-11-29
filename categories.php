<?php
include 'functions/connection.php';

if ( $_POST )
  {
    $queryDelete = 'UPDATE categories SET permanent=0 WHERE categoryID = ';
    $queryAdd = 'UPDATE categories SET permanent=1 WHERE categoryID = ';
    
    $addArray = array_keys( $_POST, 'add' );
    $deleteArray = array_keys( $_POST, 'delete' );
    
    if ( $addArray )
      {
        $queryAdd .= implode( ' OR categoryID = ', $addArray );    
        $resultAdd = mysql_query($queryAdd);
        /* To do: error check */
      }

    if ( $deleteArray )
      {    
        $queryDelete .= implode( ' OR categoryID = ', $deleteArray ); 
        $resultDelete = mysql_query($queryDelete);
        /* To do: error check */
      }
  }

$queryPerm = "SELECT * FROM categories WHERE permanent=1 ORDER BY requestCount"
  . " DESC, categoryName ASC";

$resultPerm = mysql_query($queryPerm);
/* To do: error check */

$queryTemp = "SELECT * FROM categories WHERE permanent=0 ORDER BY requestCount"
  . " DESC, categoryName ASC";

$resultTemp = mysql_query($queryTemp);
/* To do: error check */

echo "<p>\n";
echo "<form name='categoryPerm' action='categories.php' method='post'>\n";
echo "Permanent Categories\n";
echo "<table border='1'>\n";
echo "<tr>\n";
echo "<th>Category</th>\n";
echo "<th>Requests</th>\n";
echo "<th>Delete</th>\n";
echo "</tr>\n";
while ($row = mysql_fetch_assoc($resultPerm)){
  echo "<tr>\n";
  echo "<td>" . $row['categoryName'] . "</td>\n";
  echo "<td>" . $row['requestCount'] . "</td>\n";
  echo "<td>" . "<input type='checkbox' name=" . $row['categoryID']
    . " value='delete' /><br />" . "<td>\n";
  echo "</tr>\n";
}
echo "</table>\n";
echo "</p>\n\n";

echo "<p>\n";
echo "Temporary Categories\n";
echo "<table border='1'>\n";
echo "<tr>\n";
echo "<th>Category</th>\n";
echo "<th>Requests</th>\n";
echo "<th>Add</th>\n";
echo "</tr>\n";
while ($row = mysql_fetch_assoc($resultTemp)){
  echo "<tr>\n";
  echo "<td>" . $row['categoryName'] . "</td>\n";
  echo "<td>" . $row['requestCount'] . "</td>\n";
  echo "<td>" . "<input type='checkbox' name=" . $row['categoryID']
    . " value='add' /><br />" . "<td>\n";
  echo "</tr>\n";
}
echo "</table>\n";
echo "<input type='submit' value='Update' />\n";
echo "</form>\n";
echo "</p>\n\n";
?>