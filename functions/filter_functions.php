<?php
    /*  Things to filter by:
        * Category
        * Location
        * Given date range
        * Time of day
    */

    function pr ($arr) {
        echo '<pre>'; print_r($arr); echo '</pre>';
    }
    
    
    //  FILTER MAIN FUNCTIONS
    function get_current_filters () {
        if (count($_GET) < 1) return array();
        
        foreach($_GET as $field_name => $value) {
            if (strlen($value) < 1) continue;
            $filters[$field_name] = addslashes($value);
        }
        
        unset($filters[w]);
        /* unset($filters[t]);
        unset($filters[submit]);
        unset($filters[input]); */
        
        return $filters;
    }
    
    function translate_filter ($filter,$dir='db') {
        /*  Changes all $key values to proper database equivalents or vice
            versa depending on the $dir parameter which specifies the direction
            of the translation  */
        
        $url = array("category", "location", "start_date", "end_date");
        $db = array("categories", "locations", "startDate", "endDate");
        $return = array();
        
        foreach ($filter as $key=>$val) {
            if ($dir == 'db') $key = str_replace($url,$db,$key);
            if ($dir == 'url') $key = str_replace($db,$url,$key);
            $return[$key] = $val;
        }
        
        return $return;
    }
    
    function remove_filter ($filter,$remove) {
        /*  Given the filter key, unsets it from the array  */
        unset($filter[$remove]);
        return $filter;
    }
    
    function modify_filters ($filter,$mods) {
        /*  $filter is an array of the current filters produced by the function
            get_current_filters(), and $mods is an array of modifications to the filter
            where $key=>$value are direct replacements or additions to the original 
            filter list $filter.  */
        
        $new_filter = array_merge($filter,$mods);
        return $new_filter;
    }
    
    function set_filters ($filter) {
        foreach ($filter as $key=>$val) $_GET[$key] = $val;
        return $_GET;
    }
    
    function filter_to_url ($filter) {
        /*  Turns a filter into a usable URL, must provide url formatted $filter in 
            order to work properly.  Use the translate_filter function.  */
        
        foreach ($filter as $key=>$param) $url[] = $key.'='.$param;
        $url = implode('&',$url);
        return '?'.$url;
    }
    
    
    //  MISC FILTER FUNCTIONS
    function create_w ($filter) {
        /*  Given a filter, creates an appropriate w variable (see results.php line ~100)
            to describe the filters that are set.  Must use URL translated filter in 
            order to work properly.  Use the translate_filter function.  */
        
        if (isset($filter)) { }
        
        //  probably not necessary...
    }
    
    
    //  GENERAL DATABASE FUNCTIONS
    function get_category_list ($perm = 1) {
        /*  Get list of categories from database, filter by permanent or not with 
            parameter $perm, where 1 is permanent and 0 is not.  */
        
        $query = 'SELECT * FROM categories WHERE permanent = '.$perm;
        $result = mysql_query ($query);
        while ($row = mysql_fetch_array($result)) $return[] = $row;
        return $return;
    }
    
    function get_location_list ($perm = 1) {
        /*  Same as get_category_list function except for loctions.  */
        
        $query = 'SELECT * FROM locations WHERE permanent = '.$perm;
        $result = mysql_query ($query);
        while ($row = mysql_fetch_array($result)) $return[] = $row;
        return $return;
    }
?>
