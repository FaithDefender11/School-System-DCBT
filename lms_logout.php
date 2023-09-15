<?php
require_once('includes/config.php');

session_start();

$url_student = LOCAL_BASE_URL . "/student_lms.php";
$home = LOCAL_BASE_URL . "/home.php";
$url_users = LOCAL_BASE_URL . "/teacher_lms.php";

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === "admin" || $_SESSION['role'] === "teacher") {
        header("Location: $url_users");
        exit();
        
    } elseif ($_SESSION['role'] === "student") {
        header("Location: $url_student");
        exit();
    }
}

header("Location: $home");
session_destroy();
exit();
?>
