<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Email.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/User.php");
    

    require_once __DIR__ . '../../../vendor/autoload.php';

    if (isset($_POST['pending_enrollees_id'])){


        $adminUserId = isset($_SESSION["adminUserId"]) 
            ? $_SESSION["adminUserId"] : "";
        
        $user = new User($con, $adminUserId);

        $adminName = ucwords($user->getFirstName());

        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        $pending = new Pending($con, $pending_enrollees_id);

        $pending_email = $pending->GetPendingEmail();


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