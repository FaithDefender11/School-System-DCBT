<?php

    require_once('includes/config.php');

    require_once('includes/classes/Email.php');
    require_once('includes/classes/Alert.php');
    require_once('includes/classes/Pending.php');


    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
    $baseURL = dirname($currentURL);

    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;

    $mail = new PHPMailer(true);
    $pending = new Pending($con);

    if(isset($_POST['pending_submit_btn']) && isset($_POST['pending_firstname']) &&
        isset($_POST['pending_lastname']) &&
        isset($_POST['pending_mi']) &&
        isset($_POST['email_address']) &&
        isset($_POST['pending_password']) ){

        $pending_firstname = $_POST['pending_firstname'];
        $pending_lastname = $_POST['pending_lastname'];
        $pending_mi = $_POST['pending_mi'];
        $pending_password = $_POST['pending_password'];
        $email_address = $_POST['email_address'];


 
        // Generate a unique token for the user
        // Store the token and user's email address in your database
        try {

            $email = new Email();

            $token = $email->generateToken();

            $isEmailSent = $email->sendVerificationEmail($email_address,
                $token);
            
            if ($isEmailSent) {

                $wasSuccess = $pending->PendingFormEmail($pending_firstname, $pending_lastname, 
                    $pending_mi, $pending_password, $email_address, $token);
                
                if($wasSuccess == true){

                    // Alert::success("Please check your email to proceed.", "");
                    echo "Please Check your email to verify if its you.";
                }
            }
                        
        } catch (Exception $e) {
            echo "Sending email is not working. Please contact the school. {$mail->ErrorInfo}";
        }
    } 

?>
<!DOCTYPE html>

<html>

    <head>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Bootstrap Icons CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Popper.js and Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">

        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <!-- <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png"> -->
        
        <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">

        <link rel="stylesheet" href="assets/css/main_style.css">
    </head>

    <body>
        <div class="signInContainer">
            <div style="width: 520px;" class="column">
                <div class="header">
                    <!-- <img src="assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo"> -->
                    <h3 class="text-center text-muted">Pre Enrollment Form</h3>
                    <span style="font-size: 15px;">NOTE: To create an applicant account, provide your basic information first which ill be used to verify your email address.</span>
                </div>
             
                <div class="loginForm" style="margin-bottom: 15px; padding-bottom: 15px;">
                    <form method="POST">
                        <label for="">Firstname</label>
                        <input  type="text" name="pending_firstname" placeholder="Firstname" autocomplete="off">
                        <label for="">Lastname</label>
                        <input  type="text" name="pending_lastname" placeholder="Lastname" autocomplete="off">
                        
                        <label for="">Middle Name</label>
                        <input  type="text" value="" name="pending_mi" placeholder="Middle Initial" autocomplete="off">
                        <label for="">Email</label>
                        <input  type="text" value="" name="email_address" placeholder="Email" autocomplete="off">

                        <label for="">Password</label>
                        <input type="password" name="pending_password" value="123456" placeholder="Password" autocomplete="off">

                        <div style="margin-top:10px; display: flex;flex-direction: center;align-items: center;justify-content: center;" class="register_div">
                            <button style="width: 180px;" type="submit" name="pending_submit_btn" class="btn btn-success">Register</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

