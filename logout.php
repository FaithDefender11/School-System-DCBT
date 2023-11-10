
<?php

    include('includes/config.php');
    include('includes/classes/SchoolYear.php');
    include('includes/classes/Enrollment.php');

    
    $registrarUserId = isset($_SESSION["registrarUserId"]) 
        ? $_SESSION["registrarUserId"] : "";
    
 
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];

    // $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    // $host = $_SERVER['HTTP_HOST'];
    // $current_url = $protocol . '://' . $host . $_SERVER['REQUEST_URI'];

    // $parts = parse_url($current_url);
    // $current_url_without_query = $parts['scheme'] . '://' . $parts['host'] . $parts['path'];
    
    // $file_name = basename($current_url_without_query);
    
    // if($file_name === "logout.php"){
    //     // echo "registrarUserId: $registrarUserId";
    // }

    $logout = "";
    
    $url = web_root . "/enrollment_login.php";

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {
        // header("Location: /index.php");

        $base_url2 = 'http://' . $_SERVER['HTTP_HOST'] . '/enrollment_login.php';
        header("Location: $base_url2");
    }

    else if ($_SERVER['SERVER_NAME'] === 'localhost') {
        header("Location: $url");
    }
    
    // # Remove Registrar Currently Id in the Form
    

    $enrollment = new Enrollment($con);

    $resetCurrentRegistrarIdBaseOnLoggedInRegistrar = $enrollment
        ->GetAllEnrollmentFormWithRegistrarIdAndResetGlobal(
        $registrarUserId, $current_school_year_id);

    session_destroy();
    exit();

?>


