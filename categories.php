<?php
    require_once 'global.php';
    require_once 'header.php';
    
    echo '<div class="body">';
    echo '<div class="col large">';

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
            echo mysql_error();
            /* To do: error check */
          }

        if ( $deleteArray )
          {    
            $queryDelete .= implode( ' OR categoryID = ', $deleteArray ); 
            $resultDelete = mysql_query($queryDelete);
            echo mysql_error();
            /* To do: error check */
          }
      }

    $queryPerm = "SELECT * FROM categories 
                  WHERE permanent=1 
                  ORDER BY requestCount DESC, 
                           categoryName ASC";
    
    $resultPerm = mysql_query($queryPerm);

    $queryTemp = "SELECT * FROM categories 
                  WHERE permanent=0 
                  ORDER BY requestCount DESC, 
                           categoryName ASC";
    
    $resultTemp = mysql_query($queryTemp);
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
