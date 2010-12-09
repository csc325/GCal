<?php
    require_once 'functions/connection.php';
    require_once 'global.php';
    require_once 'header.php';
?>

<div class="body" >
    <div class="col large">
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

                while ($row = mysql_fetch_assoc($resultPerm)) {
                    $categoryNames[$row['categoryID']] =  $row['categoryName'];
                    $categoryRequests[$row['categoryID']] =  $row['requestCount'];
                }
            ?>
                
            <?php foreach($categoryNames as $id => $name) : ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td align="center"><?php echo $categoryRequests[$id]; ?></td>
                    <td align="center"><input type="checkbox" name="<?php echo $id; ?>" value="downgrade" class="updowngrade_box" /></td>
                    <td align="center"><input tpye="checkbox" id="<?php echo $id; ?>" value="rename" /></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
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
            ?>
                
            <?php foreach($tempCategoryNames as $id => $name) : ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td align="center"><?php echo $tempCategoryRequests[$id]; ?></td>
                    <td align="center"><input type="checkbox" name="<?php echo $id; ?>" value="downgrade" class="updowngrade_box" /></td>
                    <td align="center"><input tpye="checkbox" id="<?php echo $id; ?>" value="rename" /></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <button type='button' id='update'>Update</button>

        <script type="text/javascript">
        <!--
            //function that adds input field if input for location/category drop down menu is other
            $(document).ready(function(){
                $("input[value='rename']").change ( function () {
                    var catID = $(this).attr('id');
                    if( $(this).checked ) {
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
                    
                    $.ajax({
                        type:"post",
                        url:"updowngrade.php",
                        data: updowngrades_str,
                        success: function (r) {
                            if (r == 1) window.location = window.location.href;
                        }
                    });

                    $.ajax({
                        type:"post",
                        url:"rename.php",
                        data: renames_str,
                        success: function (r) {
                            if (r == 1) window.location = window.location.href;
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
</html>
