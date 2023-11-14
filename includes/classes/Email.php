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

        $email_message = "Please copy the full token that you will use for logging in: $token Note: Please change your password immediately!";

        try {
            $this->mailer->addAddress($email_address);
            $this->mailer->Subject = "Temporary Password";
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

        $email_message = "Admin has recently reset your password. <br> <br> Un: $username <br> <br> New Pw: $new_generated_password <br> <br> Yours in Administrator <br> <br> $adminName, <br> <br> Note: If you have received this email and its not you or in error, please notify the sender immediately and delete this email.";

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

    public function SendTeacherCredentialsAfterCreation($email_address, $username,  $generated_password, $adminName) {
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

    public function SendUserCredentialsAfterCreation($email_address, $username,  $generated_password, $adminName) {
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
            // $base_url = 'http://localhost/school-system-dcbt/student/';
            $link = domainName . "verify_student.php?token=$token";

        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/';
            // $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';

            $link = 'http://' . $_SERVER['HTTP_HOST'] . '/verify_student.php?token=' . $token;
            // $link = domainName . "verify_student.php?token=$token";
        }

        // $link = domainName . "verify_student.php?token=$token";
       
        $email_message = "(Verification) click if it was you. $link (The token will lasts only 5 minutes)";

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
            $email_address, $pdfContent, $pdfName, $username = "", $generated_password = "") {

        $subject = "You have successfully enrolled";
        $message = "Here is your Enrollment details <br> <br> Un: $username <br> <br> Pw: $generated_password <br> <br> Yours in Administrator <br> <br> Admin name";

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

        // $url = "http://localhost/school-system-dcbt//verify_student.php?token=$token";

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