<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Teacher.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['teacher_id'])){


        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminName = ucwords($user->getFirstName());

        $teacher_id = $_POST['teacher_id'];

        $teacher = new Teacher($con, $teacher_id);

        $teacher_email = $teacher->GetTeacherEmail();
        $teacher_username = $teacher->GetTeacherusername();


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
                    Alert::success("Email reset password has been sent to: $teacher_email", "");
                } else {
                    echo "Sending reset password via email went wrong";
                }

            } 
            else {
                echo "Invalid email address or password reset failed";
            }

        } catch (Exception $e) {
            // Handle PHPMailer exceptions
            echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
            // Handle other exceptions as needed
        }



    }

?>