<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Email.php");

    require_once("../../vendor/autoload.php");

    use PHPMailer\PHPMailer\PHPMailer;

    $mail = new PHPMailer(true);

    $email_obj = new Email();
    
    if(isset($_POST['email'])) {

        $email = $_POST['email'];


        // echo "im not";
        $new_token = bin2hex(random_bytes(16));
        
        // $add_token_update = $con->prepare("UPDATE users
        //     SET token=CONCAT(token,'$new_token,') 
        //     WHERE email=:email");
        
        // $add_token_update->bindParam(":email", $email);
        // $add_token_update->execute();

        // if($add_token_update->rowCount() > 0){

        //     echo "resend_email_success";
        //     return;
        // }

        // $new_token = "789";
        $add_token_update = $con->prepare("UPDATE pending_enrollees
            SET token = CONCAT(token, :newToken) 
            WHERE email = :email");


        try {

            $isEmailSent = $email_obj->ReSendVerificationEmail($email,
                $new_token);

            if($isEmailSent){

                $new_token = ",$new_token";

                $add_token_update->bindParam(":newToken", $new_token, PDO::PARAM_STR);
                $add_token_update->bindParam(":email", $email, PDO::PARAM_STR);
                $add_token_update->execute();

                if ($add_token_update->rowCount() > 0) {
                    echo "resend_email_success";
                    return;
                }

            }else{
                echo "resend_failed";
                return;
            }

        } catch (Exception $e) {

            $errorLog = "Email Sending Error: " . $e->getMessage();
            echo $errorLog;
            return;
        }
    }

?>