<?php
    session_start();
    $session_user = "";
    if (isset($_SESSION["session_user"])) {
        $session_user = $_SESSION["session_user"];
    }
    $target_user;
    $target_media;
?>