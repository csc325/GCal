<?php
    require_once 'global.php';
    require_once 'header.php';
/*
 * categoryAdmin2.php: Current version of category/location management
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

<!-- Permanent Categories/Locations Table -->
<div class="body" >
    <div class="col large">
        <form name='downgrade' action='updowngrade.php' method='post'>
            <table>
                <tr>
                    <th class='catcol'>
                    <?php
                        $admintype = $_GET['admintype'];
                        if(($admintype == 1) || (!isset($admintype))){
                           $name = "Category";
                           $table = "categories";
                           $field1 = "categories.categoryID";
                           $field2 = "categories.categoryName";
                           $field3 = "events.categoryID";
                           $permanent = "categories.permanent";

                           $nm1 = "categoryID";
                           $nm2 = "categoryName";
                        }
                        if($admintype == 2) {
                           $name = "Location";
                           $table = "locations";
                           $field1 = "locations.locationID";
                           $field2 = "locations.locationName";
                           $field3 = "events.locationID";
                           $permanent = "locations.permanent";

                           $nm1 = "locationID";
                           $nm2 = "locationName";
                        }
                        echo $name;
                        echo "</th>
                              <th>Requests</th>
                              <th>Downgrade</th>
                              <th>Rename</th>
                              </tr>";

                    $queryPerm = "SELECT $field1, $field2,
                                         COUNT(*) 'requestCount'
                                  FROM $table, events
                                  WHERE $permanent = 1
                                    AND $field1 = $field3
                                  GROUP BY $field1
                                  ORDER BY requestCount DESC;";

                    $resultPerm = mysql_query($queryPerm);

                    $queryTemp = "SELECT $field1, $field2,
                                         COUNT(*) 'requestCount'
                                  FROM $table, events
                                  WHERE $permanent = 0
                                    AND $field1 = $field3
                                  GROUP BY $field1
                                  ORDER BY requestCount DESC;";

                    $resultTemp = mysql_query($queryTemp);

                    $categoryNames = array();
                    $categoryRequests = array();

                    while ($row = mysql_fetch_assoc($resultPerm)) {
                        $categoryNames[$row[$nm1]] =
                        $row[$nm2];
                        $categoryRequests[$row[$nm1]] =
                        $row['requestCount'];
                    }

                    foreach($categoryNames as $id => $name) :
                        echo "<tr>
                            <td> $name </td>
                            <td align='center'>
                        $categoryRequests[$id] </td>
                            <td align='center'><input type='checkbox'
                        name='$id'
                                                      value='downgrade'
                        class='updowngrade_box'></td>
                            <td align='center'><input type='checkbox'
                        id='$id'
                                                      value='rename'></td>
                        </tr>";
                    endforeach;

               echo "</table>
        </form>";

        //Temporary Categories Table
        echo "<form name='upgrade' action='updowngrade.php' method='post'>
            <table>
                <tr>
                    <th class='catcol'>";

                        if($_GET['admintype'] == 1) echo "Category";
                        if($_GET['admintype'] == 2) echo "Location";
                    echo "</th><th>Requests</th>
                    <th>Upgrade</th>
                    <th>Rename</th>
                </tr>";

                    while ($row = mysql_fetch_assoc($resultTemp)) {
                        $tempCategoryNames[$row[$nm1]] =
        $row[$nm2];
                        $tempCategoryRequests[$row[$nm1]] =
        $row['requestCount'];
                    }

                    foreach($tempCategoryNames as $id => $name) :
                        echo "<tr>
                            <td> $name </td>
                            <td align='center'>
                        {$tempCategoryRequests[$id]} </td>
                            <td align='center'><input type='checkbox'
                        name='$id'
                                                      value='upgrade'
                        class='updowngrade_box'></td>
                            <td align='center'><input type='checkbox'
                        id='$id'
                                                      value='rename'></td>
                        </tr>";
                    endforeach; ?>

            </table>
        </form>

        <button type='button' id='update'>Update</button>

        <script type="text/javascript">

        //function that adds input field if input for location/category
        drop down menu is other
        $(document).ready(function(){
            $("input[value='rename']").change ( function () {
                var catID = $(this).attr('id');
                if( this.checked ) {
                    $(this).parent().parent().append('<td id = "rename_' +
        catID + '" > <input type="text" name="' + catID + '"
        class="rename_field"></td>\n');
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
                        updowngrades_str = updowngrades_str + catID + '=' +
                value + '&';
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
