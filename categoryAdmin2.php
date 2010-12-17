<?php
    require_once 'global.php';
    require_once 'header.php';
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

require_once 'global.php';
require_once 'header.php';
?>

<!-- Permanent Categories Table -->
<div class="body" >
    <div class="col large">
        <form name='downgrade' action='updowngrade.php' method='post'>
            <table>
                <tr>
                    <th class='catcol'>Category</th>
                    <th>Requests</th>
                    <th>Downgrade</th>
                    <th>Rename</th>
                </tr>
                <?php
                    $queryPerm = "SELECT categories.categoryID, 
                                         categories.categoryName, 
                                         COUNT(*) 'requestCount'
                                  FROM categories, events 
                                  WHERE categories.permanent = 1
                                    AND categories.categoryID = events.categoryID
                                  GROUP BY categories.categoryID
                                  ORDER BY requestCount DESC";

                    $resultPerm = mysql_query($queryPerm);

                    $queryTemp = "SELECT categories.categoryID, 
                                         categories.categoryName, 
                                         COUNT(*) 'requestCount'
                                  FROM categories, events 
                                  WHERE categories.permanent = 0
                                    AND categories.categoryID = events.categoryID
                                  GROUP BY categories.categoryID
                                  ORDER BY requestCount DESC";

                    $resultTemp = mysql_query($queryTemp);

                    $categoryNames = array();
                    $categoryRequests = array();

                    while ($row = mysql_fetch_assoc($resultPerm)) {
                        $categoryNames[$row['categoryID']] =  $row['categoryName'];
                        $categoryRequests[$row['categoryID']] =  $row['requestCount'];
                    } 
                    
                    foreach($categoryNames as $id => $name) : ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td align="center"><?php echo $categoryRequests[$id]; ?></td>
                            <td align="center"><input type="checkbox" name="<?php echo $id; ?>"
                                                      value="downgrade" class="updowngrade_box"></td>
                            <td align="center"><input type="checkbox" id="<?php echo $id; ?>"
                                                      value="rename"></td>
                        </tr> <?php 
                    endforeach; 
                ?>
            </table>
        </form>
        
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
                    while ($row = mysql_fetch_assoc($resultTemp)) {
                        $tempCategoryNames[$row['categoryID']] =  $row['categoryName'];
                        $tempCategoryRequests[$row['categoryID']] =  $row['requestCount'];
                    }
                    
                    foreach($tempCategoryNames as $id => $name) : ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td align="center"><?php echo $tempCategoryRequests[$id]; ?></td>
                            <td align="center"><input type="checkbox" name="<?php echo $id; ?>"
                                                      value="upgrade" class="updowngrade_box"></td>
                            <td align="center"><input type="checkbox" id="<?php echo $id; ?>"
                                                      value="rename"></td>
                        </tr> <?php 
                    endforeach; 
                ?>
            </table>
        </form>
        
        <button type='button' id='update'>Update</button>

        <script type="text/javascript">
        
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
                        if (r == 1) {
                            window.location = window.location.href;
                        }
                    }
                });

                $.ajax({
                    type:"post",
                    url:"rename.php",
                    data: renames_str,
                    success: function (r) {
                        if (r == 1) { 
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
