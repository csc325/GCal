<?php
    require_once 'connection.php';
    require_once 'user_session.php';
    require_once 'query_events.php';
    require_once 'display_events.php';
    require_once 'filter_functions.php';
    
    function time_to_relative ($t) {
        $t = strtotime($t);
        $c = time();
        $dif = $c - $t;
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
