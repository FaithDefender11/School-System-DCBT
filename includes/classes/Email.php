<?php 
 
    use PHPMailer\PHPMailer\PHPMailer;

class Email {
    private $mailer;
    private $my_gmail_username = 'hypersirios15@gmail.com';
    private $my_gmail_password = 'etenqjzyinxookzo';
    
    public function __construct() {
        // create mailer object and set its properties
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = "smtp.gmail.com";
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->my_gmail_username;
        $this->mailer->Password = $this->my_gmail_password;
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->Port = 465;
        $this->mailer->setFrom($this->my_gmail_username, "Daehan College of Business & Technology");
        $this->mailer->isHTML(true);
    }
   
    public function SendTemporaryPassword($email_address, $token) {
        // Basic email validation
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        // $email_message = "Please copy the full token that you will use for logging in: $token Note: Please change your password immediately!";
       
        $email_message = "Please copy the full token that you will use for logging in: $token Note: Please change your password immediately!";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "Password has been reset";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function SendTeacherTemporaryPassword(
        $email_address, $username,
        $new_generated_password, $adminName) {
        // Basic email validation
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        $email_message = "Admin has recently reset your password. <br> <br> Note: This serve as your password credentials. <br> <br> Un: $username <br> <br> New Pw: $new_generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "Reset password";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function SendEnrolleeTemporaryPassword(
        $email_address,
        $new_generated_password, $adminName) {

        // Basic email validation
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        $email_message = "Admin has recently reset your password. <br> <br> Note: This serve as your password credentials. <br> <br> Un: $email_address <br> <br> New Pw: $new_generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "Reset password";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function SendTeacherCredentialsAfterCreation(
            $email_address, $username,  $generated_password, $adminName) {
        // Basic email validation
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        $email_message = "Admin has recently created your account. <br> <br> Un: $username <br> <br> Pw: $generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "Teacher successfully creation of account";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function SendNewEnrolleeCredentialsAfterCreation(
            $email_address,  $generated_password, $registrarName) {
        // Basic email validation
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        $email_message = "Registrar has recently created your account. <br> <br> Un: $email_address <br> <br> Pw: $generated_password <br> <br> Yours in Registrar <br> <br> $registrarName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "New enrollee successfully creation of account";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function SendUserCredentialsAfterCreation(
        $email_address, $username,  $generated_password, $adminName, $role) {
        // Basic email validation

        $role = ucfirst($role);
        
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            // echo "email_address: $email_address Invalid email address";
            return false;
        }

        $email_message = "Admin has recently created your account. <br> <br> Un: $username <br> <br> Pw: $generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        try {

            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "$role successfully creation of account";
            $this->mailer->Body = $email_message;

            return $this->mailer->send();
            
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }

    // public function SendTemporaryPassword($email_address, $token) {

 
    //     $email_message = "Please copy the full token that you will use for logging in: $token Note: Please changed your password immediately!";

    //     $this->mailer->addAddress($email_address);
    //     $this->mailer->Subject = "Temporary Password.";
    //     $this->mailer->Body = $email_message;

    //     if ($this->mailer->send()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function sendVerificationEmail($email_address, $token) {

        $link = "";

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $link = domainName . "verify_student.php?token=$token";

        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/';
            // $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';

            $link = 'http://' . $_SERVER['HTTP_HOST'] . '/verify_student.php?token=' . $token;
            // $link = domainName . "verify_student.php?token=$token";
        }

        // $link = domainName . "verify_student.php?token=$token";
       
        // $email_message = "(Verification) click if it was you. $link (The token will lasts only 5 minutes)";

        $linkInEmail = "
            <p><a href='$link' target='_new'>Click Here to Activate Your Account</a></p>
        ";

        $email_message = "Thank you for your pre-registration! We're thrilled to have you on board. To complete the enrollment process and validate your account, simply click on the link below: <br> <br> $linkInEmail";

        $this->mailer->addAddress($email_address);
        $this->mailer->Subject = "Daehan College of Business Technology Enrollment Verification";
        $this->mailer->Body = $email_message;

        if ($this->mailer->send()) {
            return true;
        } else {
            return false;
        }
    }

    public function sendVerificationEmailInsideConfig($email_address, $token) {

        $link = "";

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $link = domainName . "../../verify_student.php?token=$token";

        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/';
            // $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';

            $link = 'http://' . $_SERVER['HTTP_HOST'] . '/../../verify_student.php?token=' . $token;
            // $link = domainName . "verify_student.php?token=$token";
        }

        // $link = domainName . "verify_student.php?token=$token";
       
        // $email_message = "(Verification) click if it was you. $link (The token will lasts only 5 minutes)";

        $linkInEmail = "
            <p><a href='$link' target='_new'>Click Here to Activate Your Account</a></p>
        ";

        $email_message = "Thank you for your pre-registration! We're thrilled to have you on board. To complete the enrollment process and validate your account, simply click on the link below: <br> <br> $linkInEmail";

        $this->mailer->addAddress($email_address);
        $this->mailer->Subject = "Daehan College of Business Technology Enrollment Verification";
        $this->mailer->Body = $email_message;

        if ($this->mailer->send()) {
            return true;
        } else {
            return false;
        }
    }


    public function SendMessageViaEmail($email_address,
        $message, $subject) {


        $this->mailer->addAddress($email_address);
        // $this->mailer->Subject = "Daehan College of Business Technology Enrollment Verification";
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;

        if ($this->mailer->send()) {
            return true;
        } else {
            return false;
        }
    }

  

    public function SendEnrolledSubjectListViaPdf(
            $email_address, $pdfContent, $pdfName,
            
            $username = "", $generated_password = "", $doesValidForCredentials) {

        $subject = "Welcome to Daehan College of Business Technology - Your Enrollment is Complete";

        $credentialsMessage = "";

        if($doesValidForCredentials == true){
            $credentialsMessage = "<br> Un: $username <br> <br> Pw: $generated_password <br> <br> Yours in Administrator <br> <br> Admin name";
        }


        $message = "Here is your Enrollment details <br> $credentialsMessage";
            
        // $email_message = "Admin has recently created your account. <br> <br> Un: $username <br> <br> Pw: $generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

        $this->mailer->addAddress($email_address);
        // $this->mailer->Subject = "Daehan College of Business Technology Enrollment Verification";
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;

        // Attach the PDF content to the email
        $this->mailer->addStringAttachment($pdfContent, "$pdfName", "base64", "application/pdf");

        if ($this->mailer->send()) {
            return true;
        } else {
            return false;
        }
    }


    

    public function ReSendVerificationEmail($email_address, $token) {

        // $link = "http://localhost/dcbt/enrollment/verify_student.php?token=" 
        //     . $token;

        return false;

        $url = dirname("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        // $baseUrl = dirname("http://$_SERVER[HTTP_HOST]");
        

        // $parsedUrl = parse_url($url);
        // $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . dirname($parsedUrl['path']);

        // echo $baseUrl;

        // $url = "http://localhost/sistem//verify_student.php?token=$token";

        $link = LOCAL_BASE_URL . "//" . "verify_student.php?token=$token";
        // echo $link;

        $email_message = "(Resend-Verification) click if it was you. $link (The token will lasts only 5 minutes)";

        $this->mailer->addAddress($email_address);
        $this->mailer->Subject = "Daehan College of Business Technology Enrollment Verification";

        $this->mailer->Body = $email_message;

        if ($this->mailer->send()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function generateToken() {
        return bin2hex(random_bytes(16));
    }




}
 

?>