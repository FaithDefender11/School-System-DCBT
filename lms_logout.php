<?php
require_once('includes/config.php');

// $url_local = LOCAL_BASE_URL . "/pre_enrollment_login.php";
// $url_online = 'http://' . $_SERVER['HTTP_HOST'] . "/pre_enrollment_login.php";


$url_student_local = LOCAL_BASE_URL . "/student_lms.php";
$url_student_online = 'http://' . $_SERVER['HTTP_HOST'] . "/student_lms.php";

$url_users_local = LOCAL_BASE_URL . "/lms_login.php";
$url_users_online = 'http://' . $_SERVER['HTTP_HOST'] . "/lms_login.php";

$home = LOCAL_BASE_URL . "/index.php";

// $url_local = LOCAL_BASE_URL . "/pre_enrollment_login.php";
// $url_online = 'http://' . $_SERVER['HTTP_HOST'] . "/pre_enrollment_login.php";

// // $url = 'http://' . $_SERVER['HTTP_HOST'] . "/index.php";
// $url = $_SERVER['SERVER_NAME'] === 'localhost' ? $url_local : $url_online;


if (isset($_SESSION['role'])) {

    // var_dump($_SESSION['role']);
    // return; 

    if($_SERVER['SERVER_NAME'] === 'localhost'){
        if ($_SESSION['role'] === "admin" 
            || $_SESSION['role'] === "teacher" || $_SESSION['role'] === "student") {


                // echo "url_users_local1: $url_users_local";
                // return;

            header("Location: $url_users_local");
            session_destroy();
            exit();

        } 
        // elseif ($_SESSION['role'] === "student") {
        //     header("Location: $url_student_local");
        //     session_destroy();
        //     exit();
        // }
    }else{
        if ($_SESSION['role'] === "admin" 
            || $_SESSION['role'] === "teacher" || $_SESSION['role'] === "student") {

            // echo "url_users_local2: $url_users_local";
            // return;

            header("Location: $url_users_online");
            session_destroy();

            exit();
        }
        // elseif ($_SESSION['role'] === "student") {
        //     header("Location: $url_student_online");
        //     session_destroy();
        //     exit();
        // }
    }
    
        
    
}


// header("Location: $home");
// session_destroy();
// exit();
?>
