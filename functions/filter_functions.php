<?php
/*
 * filter_functions: provides functions to filter events
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt. If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category filter functions
 * @author CSC-325 Database and Web Application Fall 2010 Class
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 * @version 3.0
 *
 *  Things to filter by:
 * Category
 * Location
 * Given date range
 * Time of day
 */

/* 
 *places pre tags around parameter
 * @param mixed $arr
 * @return echos <pre> tags around mixed variable
 */
function pr ($arr) 
{
  echo '<pre>'; print_r($arr); echo '</pre>';
}
    
    
/* 
 * accessor function to get the current filters
 * @return associative array of field_name => value
 */
function get_current_filters () 
{
  if (count($_GET) < 1) 
    return array();
        
  foreach ($_GET as $field_name => $value) {
    if (strlen($value) < 1) continue;
    $filters[$field_name] = addslashes($value);
  }
        
  unset($filters[w]);
        
  return $filters;
}

/* 
 * Changes all $key values to proper database equivalents
 * @param associative array $filter stores current filters
 * @param string $dir directory, either url or db
 * @return array $return contains corrected key values
 */
function translate_filter ($filter,$dir='db') 
{        
  $url    = array("category", "location", "start_date", "end_date");
  $db     = array("categories", "locations", "startDate", "endDate");
  $return = array();
        
  foreach ($filter as $key=>$val) {
    if ($dir == 'db') 
      $key = str_replace($url,$db,$key);
    if ($dir == 'url') 
      $key = str_replace($db,$url,$key);
    $return[$key] = $val;
  }
        
  return $return;
}

/* 
 * removes filter from list of current filters
 * @param associative array $filter stores current filters
 * @param string $remove filter to remove
 * @return associative array $filter with $remove unset
 */    
function remove_filter ($filter,$remove) 
{
  unset($filter[$remove]);
  return $filter;
}

/* 
 * merges new modifications with current filters
 * @param associative array $filter stores current filters
 * @param array $mods modifications to filter 
 *   where $key=>$value are direct replacements or additions to the original 
 *   filter list $filter
 * @return associative array $filter with $remove unset
 */      
function modify_filters ($filter,$mods) 
{        
  $new_filter = array_merge($filter,$mods);
  return $new_filter;
}
    
/* 
 * sets filters
 * @param associative array $filter stores current filters
 */  
function set_filters ($filter) 
{
  foreach ($filter as $key=>$val) $_GET[$key] = $val;
  return $_GET;
}

/* 
 * turns filter into a usable URL
 * @param associative array $filter stores current filters
 * @return string $url translated filter
 */    
function filter_to_url ($filter) 
{
  /*  Turns a filter into a usable URL, must provide url formatted $filter in 
      order to work properly.  Use the translate_filter function.  */
        
  foreach ($filter as $key=>$param) $url[] = $key.'='.$param;
  $url = implode('&',$url);
  return '?'.$url;
}
    
    
//  MISC FILTER FUNCTIONS
function create_w ($filter) 
{
  /*  Given a filter, creates an appropriate w variable (see results.php line ~100)
      to describe the filters that are set.  Must use URL translated filter in 
      order to work properly.  Use the translate_filter function.  */
        
  if (isset($filter)) { }
        
  //  probably not necessary...
}
    
    
//  GENERAL DATABASE FUNCTIONS
function get_category_list ($perm = 1) 
{
  /*  Get list of categories from database, filter by permanent or not with 
      parameter $perm, where 1 is permanent and 0 is not.  */
        
  $query = 'SELECT * 
            FROM categories 
            WHERE permanent = '.$perm;
  $result = mysql_query ($query);
  while ($row = mysql_fetch_array($result)) $return[] = $row;
  return $return;
}
    
function get_location_list ($perm = 1) 
{
  /*  Same as get_category_list function except for loctions.  */
        
  $query = 'SELECT * 
            FROM locations 
            WHERE permanent = '.$perm;
  $result = mysql_query ($query);
  while ($row = mysql_fetch_array($result)) $return[] = $row;
  return $return;
}
?>
