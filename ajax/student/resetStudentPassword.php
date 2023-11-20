<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['student_id'])){


        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminName = ucwords($user->getFirstName());

        $student_id = $_POST['student_id'];

        $student = new Student($con, $student_id);

        $student_email = $student->GetEmail();
        $student_username = $student->GetUsername();


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