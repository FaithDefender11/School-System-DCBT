<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/UserLog.php");
    require_once("../../includes/classes/SchoolYear.php");

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['student_id'])){


        $logs = new UserLog($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');


        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminUnique_id = $user->GetUniqueId();
        $adminRole = $user->GetRole();
        $adminName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
        // $adminName = ucwords($user->getFirstName());

        $student_id = $_POST['student_id'];

        $student = new Student($con, $student_id);

        $student_email = $student->GetEmail();
        $student_username = $student->GetUsername();
        $student_unique_id = $student->GetStudentUniqueId();
        $studentName = ucfirst($student->GetFirstName()) . " " . ucfirst($student->GetMiddleName()) . " " . ucfirst($student->GetLastName());


        // echo "student_email: $student_email";
        // echo "<br>";

        // echo "student_username: $student_username";
        // echo "<br>";
        // return;

        try {

            $email = new Email();
 
            $successReset = $student->StudentResetPassword($student_id);

            if (!empty($student_email) 
                && filter_var($student_email, FILTER_VALIDATE_EMAIL)
                && $student_username != "" 
                &&  count($successReset) > 0 
                && $successReset[1] == true) {

                $isEmailSent = $email->SendTeacherTemporaryPassword(
                    $student_email, $student_username, $successReset[0] , $adminName);

                if ($isEmailSent) {
                    
                    # Add Logs.

                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "$adminRole ID: $adminUnique_id $adminName had reset password of $studentName ( Student ) ID $student_unique_id using his email $student_email at $date_creation";
                    $addStudentLogs = $logs->AddUserLogs($adminRole, $description, $school_year_id);

                    // Alert::success("Email reset password has been sent to: $student_email", "");
                    echo "student_reset_success";
                    return;
                }  
                
            }else{
                echo "Email is invalid.";
            }
        } catch (Exception $e) {
            // Handle PHPMailer exceptions
            echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
            // Handle other exceptions as needed
        }



    }

?>