<?php
/*
* functions: includes all function related scripts
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
require_once 'connection.php';
require_once 'user_session.php';
require_once 'query_events.php';
require_once 'display_events.php';
require_once 'filter_functions.php';
    
/*
* Checks time $t against current time
* @param string $t 
* @return action from one of the created functions
*/
function time_to_relative ($t)
{
  $t = strtotime($t);
  $c = time();
  $dif = $c - $t;
  //PEAR fix, lines wrap but some arguments are too long to parse
  $conditions = array((60 * 1 + 10) => create_function ('$t', 'return "Just now";'),
                      (60 * 60) => create_function ('$t', 'return round($t/60)." minute".((round($t/60)!=1) ? "s" : "")." ago";'),
                      (60 * 60 * 24 - (60 * 60 * 1)) => create_function ('$t', 'return round($t/(60*60))." hour".((round($t/(60*60))!=1) ? "s" : "")." ago";'),
                      (60 * 60 * 24 * 7 - (60 * 60 * 24 * 1)) => create_function ('$t', 'return round($t/(60*60*24))." day".((round($t/(60*60*24))!=1) ? "s" : "")." ago";'),
                      (60 * 60 * 24 * 30) => create_function ('$t', 'return round($t/(60*60*24*7))." week".((round($t/(60*60*24*7))!=1) ? "s" : "")." ago";'),
                      $dif => create_function ('$t', 'return round($t/(60*60*24))." day".((round($t/(60*60*24))!=1) ? "s" : "")." ago";'));
        
  foreach ($conditions as $test=>$action) {
    if ($dif <= $test) {
      return $action($dif);
    }
  }
}
?>
