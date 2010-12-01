<?php
    session_start();
    session_unset();
    session_destroy();
    header('Location: '.rawurldecode($_GET['ref']));
?>
