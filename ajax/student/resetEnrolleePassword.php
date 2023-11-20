<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/UserLog.php");
    require_once("../../includes/classes/SchoolYear.php");

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['pending_enrollees_id'])){

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

        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        $pending = new Pending($con, $pending_enrollees_id);

        $pending_email = $pending->GetPendingEmail();

        $enrolleeName = ucfirst($pending->GetPendingFirstName()) . " " . ucfirst($pending->GetPendingMiddleName()) . " " . ucfirst($pending->GetPendingLastName());

        // echo "pending_email: $pending_email";
        // echo "<br>";

        // echo "student_username: $student_username";
        // echo "<br>";
        // return;

        try {

            $email = new Email();
 
            $successReset = $pending->EnrolleeResetPassword($pending_enrollees_id);

            if (!empty($pending_email) 
                && filter_var($pending_email, FILTER_VALIDATE_EMAIL)
                &&  count($successReset) > 0 
                && $successReset[1] == true) {

                $isEmailSent = $email->SendEnrolleeTemporaryPassword(
                    $pending_email, $successReset[0], $adminName);

                if ($isEmailSent) {

                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "$adminRole ID: $adminUnique_id $adminName had reset password of $enrolleeName ( New Enrollee ) ID $pending_enrollees_id using his email $pending_email at $date_creation";
                    $addStudentLogs = $logs->AddUserLogs($adminRole, $description, $school_year_id);

                    // Alert::success("Email reset password has been sent to: $student_email", "");
                    echo "enrollee_reset_success";
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