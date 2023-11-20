<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Teacher.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/Alert.php");
    require_once("../../includes/classes/UserLog.php");
    require_once("../../includes/classes/SchoolYear.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['teacher_id'])){

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

        $logs = new UserLog($con);

        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminName = ucwords($user->getFirstName());
        $adminRole = $user->GetRole();
        $adminUnique_id =  $user->GetUniqueId();

        $teacher_id = $_POST['teacher_id'];

        $teacher = new Teacher($con, $teacher_id);

        $teacher_email = $teacher->GetTeacherEmail();
        $teacher_username = $teacher->GetTeacherusername();
        $teacher_school_id = $teacher->GetSchoolTeacherId();
        $teacherFullName = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherMiddleName()) . " " . ucwords($teacher->GetTeacherLastName());


        // echo "teacher_username: $teacher_username";
        // return;

        try {

            $email = new Email();
 
            $successReset = $teacher->TeacherResetPassword($teacher_id);


            if (!empty($teacher_email) 
                && filter_var($teacher_email, FILTER_VALIDATE_EMAIL)
                && $teacher_username != "" 
                &&  count($successReset) > 0 
                && $successReset[1] == true) {

                $isEmailSent = $email->SendTeacherTemporaryPassword(
                    $teacher_email, $teacher_username, $successReset[0] , $adminName);

                if ($isEmailSent) {

                    $role = $user->GetRole();
                    $userNameId = $user->GetUniqueId();
                    $userEmail = $user->GetEmail();

                    $userName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

                    # Add Logs.

                    
                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "$adminRole ID: $adminUnique_id $adminName had reset password of $teacherFullName ( Faculty ) ID $teacher_school_id using his email $teacher_email at $date_creation";
                    $addStudentLogs = $logs->AddUserLogs($adminRole, $description, $school_year_id);

                    // Alert::success("Email reset password has been sent to: $teacher_email", "");

                    echo "$teacher_email";

                } else {
                    // echo "Sending reset password via email went wrong";
                    echo "error semt";
                }

            } 
            else {
                // echo "Invalid email address or password reset failed";
                echo "error semt";

            }

        } catch (Exception $e) {
            // Handle PHPMailer exceptions
            echo "error semt";

            // echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
            // Handle other exceptions as needed
        }



    }

?>