<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/User.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['user_id'])){


        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminName = ucwords($user->getFirstName());

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