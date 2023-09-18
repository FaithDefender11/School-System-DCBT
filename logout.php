
<?php

    include('includes/config.php');
    // include('includes/classes/Account.php');

    // $account = new Account($con);



    // if(isset($_SESSION['studentLoggedIn'])){
    //     session_start();
    //     session_destroy();
    //     $url = LOCAL_BASE_URL . "/student_enrollment.php";
    //     // header("Location: /school-system-dcbt/old_enrollment_verification.php");
    //     header("Location: $url");
    // }
    
    // else if(isset($_SESSION['registrarLoggedIn'])
    //         || isset($_SESSION['registrarUserId'])
    //         || isset($_SESSION['adminLoggedIn'])
    //         || isset($_SESSION['adminUserId'])
    //         || isset($_SESSION['cashierLoggedIn'])
    //         ){

    //         if($_SESSION['adminUserId'] != null){
    //             $account->clearRememberMeToken($_SESSION['adminUserId']);
    //         }

    //         if($_SESSION['registrarUserId'] != null){
    //             $account->clearRememberMeToken($_SESSION['registrarUserId']);
    //         }


    //         session_start();
    //         session_destroy();
    //         header("Location: /school-system-dcbt/enrollment_login.php");
    // }else{
    //     // header("Location: /school-system-dcbt/enrollment_login.php");
    //     echo "No Identity.";
    // }


    // session_start();

    // $url = LOCAL_BASE_URL . "/enrollment_login.php";
    
    $logout = "";

    // echo $url;
    // session_destroy();
    // return;
    
    $url = web_root . "/enrollment_login.php";

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {
        // header("Location: /index.php");

        $base_url2 = 'http://' . $_SERVER['HTTP_HOST'] . '/enrollment_login.php';
        header("Location: $base_url2");
    }

    else if ($_SERVER['SERVER_NAME'] === 'localhost') {
        header("Location: $url");
    }
    
    session_destroy();
    exit();
?>


