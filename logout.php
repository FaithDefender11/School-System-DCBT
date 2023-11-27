
<?php

    include('includes/config.php');
    include('includes/classes/SchoolYear.php');
    include('includes/classes/Enrollment.php');
    include('includes/classes/Pending.php');
    include('includes/classes/UserLog.php');
    include('includes/classes/Student.php');
    include('includes/classes/User.php');

    
    $registrarUserId = isset($_SESSION["registrarUserId"]) 
        ? $_SESSION["registrarUserId"] : "";
    
    $logs = new UserLog($con);
 
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

    
    if(isset($_SESSION['role'])){

        $log_in_role = $_SESSION['role'];

        if($log_in_role == "enrollee" && isset($_SESSION['enrollee_id'])){

            $pending_enrollees_id = $_SESSION['enrollee_id'];

            $pending = new Pending($con, $pending_enrollees_id);

            $enrolleName = ucwords($pending->GetPendingFirstName()) . " " . ucwords($pending->GetPendingMiddleName()) . " " . ucwords($pending->GetPendingLastName());

            # Add Logs.

            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Enrollee ID: $pending_enrollees_id $enrolleName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Enrollee", $description, $current_school_year_id);
        
            // return;
        }
        if($log_in_role == "student" && isset($_SESSION['studentLoggedInId'])){

            $student_id = $_SESSION['studentLoggedInId'];

            $student = new Student($con, $student_id);

            $student_unique_id = $student->GetStudentUniqueId();

            $studentName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetMiddleName()) . " " . ucwords($student->GetLastName());

            # Add Logs.

            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Student ID: $student_unique_id $studentName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Student", $description, $current_school_year_id);
        
        }

        if($log_in_role == "admin" && isset($_SESSION['adminUserId'])){

            $user_id = $_SESSION['adminUserId'];

            $user = new User($con, $user_id);

            $user_unique_id = $user->GetUniqueId();

            $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

            # Add Logs.
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Administrator ID: $user_unique_id $userName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Administrator", $description, $current_school_year_id);

        }

        if($log_in_role == "registrar" && isset($_SESSION['registrarUserId'])){

            $user_id = $_SESSION['registrarUserId'];

            $user = new User($con, $user_id);

            $user_unique_id = $user->GetUniqueId();

            $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

            # Add Logs.
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Registrar ID: $user_unique_id $userName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Registrar", $description, $current_school_year_id);

        }

        if($log_in_role == "cashier" && isset($_SESSION['cashierUserId'])){

            $user_id = $_SESSION['cashierUserId'];

            $user = new User($con, $user_id);

            $user_unique_id = $user->GetUniqueId();

            $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

            # Add Logs.
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Cashier ID: $user_unique_id $userName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Cashier", $description, $current_school_year_id);

        }

        if($log_in_role == "super_admin" && isset($_SESSION['superAdminUserId'])){

            $user_id = $_SESSION['superAdminUserId'];

            $user = new User($con, $user_id);

            $user_unique_id = $user->GetUniqueId();

            $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

            # Add Logs.
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Super Administrator ID: $user_unique_id $userName has log-out in the enrollment at $date_creation";
            $addStudentLogs = $logs->AddUserLogs("Super Administrator", $description, $current_school_year_id);

        }

    }

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


