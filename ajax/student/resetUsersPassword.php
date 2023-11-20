<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/UserLog.php");
    require_once("../../includes/classes/SchoolYear.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['user_id'])){

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

        // $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
        // $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');


        $logs = new UserLog($con);

        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);
        $adminUnique_id = $user->GetUniqueId();
        $adminRole = $user->GetRole();

        $adminName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

        $user_id = $_POST['user_id'];

        $user = new User($con, $user_id);

        $user_email = $user->GetEmail();
        $user_username = $user->getUsername();


        // echo "student_email: $student_email";
        // echo "<br>";

        // echo "userna$user_username: $user_username";
        // echo "<br>";

        // return;



        try {

            $email = new Email();
 
            $successReset = $user->UserResetPassword($user_id);

            if (!empty($user_email) 
                && filter_var($user_email, FILTER_VALIDATE_EMAIL)
                && $user_username != "" 
                &&  count($successReset) > 0 
                && $successReset[1] == true) {

                $isEmailSent = $email->SendTeacherTemporaryPassword(
                    $user_email, $user_username, $successReset[0] , $adminName);

                if ($isEmailSent) {


                    $role = $user->GetRole();
                    $userNameId = $user->GetUniqueId();
                    $userEmail = $user->GetEmail();

                    $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

                    # Add Logs.

                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "$adminRole ID: $adminUnique_id $adminName had reset password of $userName ( $role ) ID $userNameId using his email $userEmail at $date_creation";
                    $addStudentLogs = $logs->AddUserLogs($adminRole, $description, $school_year_id);


                    // Alert::success("Email reset password has been sent to: $user_email", "");
                    echo "user_reset_success";
                    return;
                }  
                    else{
                    echo "email_not_sent_error.";
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