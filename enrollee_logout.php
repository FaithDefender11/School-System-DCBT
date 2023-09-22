
<?php


    session_start();
    require_once('includes/config.php');

    session_destroy();

    // $url = LOCAL_BASE_URL . "/pre_enrollment_login.php";
    $url = 'http://' . $_SERVER['HTTP_HOST'] . "/pre_enrollment_login.php";
    header("Location: $url");
    // header("Location: /school-system-dcbt/pre_enrollment_login.php");


?>


