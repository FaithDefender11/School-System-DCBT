<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Email.php");
    
    require "../../vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;

    if (isset($_POST['email'])) {

        $emailObj = new Email();

        $pending = new Pending($con);

        $token = $emailObj->generateToken();

        $tokenExists = $pending->isTokenExistsInDatabase($token);

        if($tokenExists) {
            $token = $pending->generateTokenCompre($token);
        } 

        $email = $_POST['email'];

        $doesConcatenateNewToken = $pending->UpdateAnotherToken($email, $token);

        if ($doesConcatenateNewToken) {

            // echo "success_resend";
            // return;
            # Sent via email again.
            try {
                $isEmailSent = $emailObj->sendVerificationEmailInsideConfig($email, $token);

                if ($isEmailSent) {
                    echo "success_resend";
                    return;
                }
            } catch (Exception $e) {
                // Handle the exception, log it, or perform any necessary action
                echo "failed_resend";
                return;
            }

        } else {
            echo "failed_resend";
            return;
        }


    }
?>