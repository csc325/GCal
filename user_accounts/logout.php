<?php
    require_once '../global.php';
    session_start();
    session_unset();
    session_destroy();
    header('Location: '.ed(false));
?>
