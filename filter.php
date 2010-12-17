<div class="filter">
    <h3>
        <span class="arrow">&raquo;</span>
        <span class="text">Filter Results</span>
    </h3>
    
    <?php
        $user = get_user_info();
        if ($user[accessLevel] == 3) $admin = true;
        $raw_filter = get_current_filters();
        $filter = translate_filter($raw_filter,'db');
        
        // CATEGORY FILTER LIST 
            
        echo '<ul>';
        echo '<li><b>Categories</b> '.($admin ? '<a href="'.ed(false).'categoryAdmin2.php">(manage)</a>' : '').'</li>';
        $categories = get_category_list();
        
        foreach ($categories as $row) {
            $cat = $row[categoryName];
            if ($filter[categories] == $row[categoryName]) {
                $temp_filter = remove_filter($raw_filter,'category');
                $url = filter_to_url($temp_filter);
                
                echo '<li class="cur cat"><div class="indicator"></div>';
                echo $cat.' <a href="'.ed(false).'results.php'.$url.'" class="remove">X</a></li>';
            } else {
                $temp_filter = modify_filters($raw_filter,array('category'=>$cat));
                $url = filter_to_url($temp_filter);
                
                echo '<li class="cat"><div class="indicator"></div>';
                echo '<a href="'.ed(false).'results.php'.$url.'">'.$cat.'</a></li>';
            }
        }
        echo '</ul>';
        
        
        // LOCATION FILTER LIST
        echo '<ul>';
        echo '<li><b>Locations</b></li>';
        $locations = get_location_list();
        
        foreach ($locations as $row) {
            $loc = $row[locationName];
            if ($filter[locations] == $row[locationName]) {
                $temp_filter = remove_filter($raw_filter,'location');
                $url = filter_to_url($temp_filter);
                
                echo '<li class="cur loc"><div class="indicator"></div>';
                echo $loc.' <a href="'.ed(false).'results.php'.$url.'" class="remove">X</a></li>';
            } else {
                $temp_filter = modify_filters($raw_filter,array('location'=>$loc));
                $url = filter_to_url($temp_filter);
                
                echo '<li class="loc"><div class="indicator"></div>';
                echo '<a href="'.ed(false).'results.php'.$url.'">'.$loc.'</a></li>';
            }
        }
        echo '</ul>';
        
        echo '<ul><li><b>Date Range</b></li>';
        if (isset($filter[startDate]) || isset($filter[endDate]) || 
            isset($filter[start]) || isset($filter[end])) {
            $set_start = date('M j, Y', strtotime(isset($filter[startDate]) ? 
                                                  $raw_filter[start_date] :
                                                  $raw_filter[start]));
                                                  
            $set_end = date('M j, Y', strtotime(isset($filter[endDate]) ? 
                                                $raw_filter[end_date] :
                                                $raw_filter[end]));
            
            $temp_filter = remove_filter($raw_filter,'start_date');
            $temp_filter = remove_filter($temp_filter,'end_date');
            $temp_filter = remove_filter($temp_filter,'start');
            $temp_filter = remove_filter($temp_filter,'end');
            $temp_filter = remove_filter($temp_filter,'current');
            $url = filter_to_url($temp_filter);
            
            echo '<input type="hidden" value="'.$url.'" id="date-range-url">';
            echo '<li>Start Date: <input type="text" name="start_date" id="start_date" class="date-range" value="'.$set_start.'" /></li>';
            echo '<li>End Date: <input type="text" name="end_date" id="end_date" class="date-range" value="'.$set_end.'" /></li>';
        }
        echo '<li><button id="date-range">Update</button></li>';
        echo '</ul>';
        
        /* echo '<ul><li><b>Time of Day</b></li>';
        if (isset($filter[startTime]) || isset($filter[endTime])) {
            
        }
        echo '</ul>'; */
    ?>
</div>

<script type="text/javascript">
    $(document).ready ( function () {
        function human_to_unix (str) {
            var human = new Date(str);
            return human.getTime()/1000.0;
        }
        
        $('button#date-range').click ( function () {
            var url = $('input#date-range-url').val();
            var startDate = $('input#start_date').val();
            var endDate = $('input#end_date').val();
            url = url + '&start_date=' + startDate + '&end_date=' + endDate;
            url = '<?php ed(); ?>results.php' + url;
            window.location = url;
        });
        
        // initializes calender picker for #start_date
        $('#start_date').datepicker({dateFormat:"M d, yy",
                                     numberOfMonths:1,
                                     showAnim:"blind",
                                     showButtonPanel:true,
                                     // sets value in #end_date field to be the same as #start_date
                                     onSelect: function () {
                                        $('#end_date').datepicker("option","minDate",$(this).val());
                                        if ($('#end_date').val() == '') {
                                            $('#end_date').val($(this).val());
                                        }
                                     } });
        
        // initializes calender picker for #end_date
        $('#end_date').datepicker({dateFormat:"M d, yy",
                                   numberOfMonths:1,
                                   showAnim:"blind",
                                   showButtonPanel:true });
    });
</script>
