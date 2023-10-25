
<?php


    session_start();
    require_once('includes/config.php');

    session_destroy();

    // $url_local = LOCAL_BASE_URL . "/pre_enrollment_login.php";

    $url_local = LOCAL_BASE_URL . "/enrollment_login.php";
    $url_online = 'http://' . $_SERVER['HTTP_HOST'] . "/enrollment_login.php";

    // $url = 'http://' . $_SERVER['HTTP_HOST'] . "/index.php";
    $url = $_SERVER['SERVER_NAME'] === 'localhost' ? $url_local : $url_online;

    header("Location: $url");


?>


