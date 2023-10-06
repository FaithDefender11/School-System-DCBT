<?php

    require_once('includes/config.php');

    require_once('includes/classes/Email.php');
    require_once('includes/classes/Alert.php');
    require_once('includes/classes/Pending.php');
    require_once('includes/classes/SchoolYear.php');
    require_once('includes/classes/Helper.php');
    require_once('includes/classes/Constants.php');
    require_once('includes/classes/Student.php');

    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";

    $baseURL = dirname($currentURL);

    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;

    $mail = new PHPMailer(true);
    $pending = new Pending($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    ?>
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js"></script>

        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <?php

    $pending_firstname = "";
    $pending_lastname = "";
    $pending_mi = "";
    $email_address = "";


    if(
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['pending_submit_btn']) 

        && 
        isset($_POST['pending_firstname']) &&
        isset($_POST['pending_lastname']) &&
        isset($_POST['pending_mi']) &&
        isset($_POST['email_address']) &&
        isset($_POST['pending_password'])){
        


        $pending_firstname = Helper::ValidateFirstname($_POST['pending_firstname']);

        $pending_lastname = Helper::ValidateLastname($_POST['pending_lastname']);

        $pending_mi = Helper::ValidateMiddlename($_POST['pending_mi']);

        $pending_password = $_POST['pending_password'];

        $email_address = Helper::ValidateEnrolleeEmail($_POST['email_address'],
            false, $con);

        $guardianError = false;

 
        // // Generate a unique token for the user
        // // Store the token and user's email address in your database

        if(empty(Helper::$errorArray)){

            // echo "nothing.";
            // return;

            try {

                $email = new Email();

                $token = $email->generateToken();

                $tokenExists = $pending->isTokenExistsInDatabase($token);

                if($tokenExists) {
                    $token = $pending->generateTokenCompre($token);
                } 

                $isEmailSent = $email->sendVerificationEmail($email_address,
                    $token);
                
                if ($isEmailSent) {

                    $wasSuccess = $pending->PendingFormEmail($pending_firstname, $pending_lastname, 
                        $pending_mi, $pending_password, $email_address, $token, $current_school_year_id);
                    
                    if($wasSuccess == true){
                        // Alert::success("Please check your email to proceed.", "");
                        // echo "Please Check your email to verify if its you.";

                        echo "<script>
                        $(document).ready(function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Sent!',
                                text: 'Please check your email to confirm our verification.',
                                backdrop: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'pre_enrollment_register.php';
                                }
                            });
                        });
                        </script>";
                        exit();
                    }
                }
            } catch (Exception $e) {

                $errorLog = "Email Sending Error: " . $e->getMessage();
                echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oh no!',
                            text: 'Sending email is not working. Please contact the school administrator. {$mail->ErrorInfo} {$errorLog}',
                            backdrop: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'pre_enrollment_register.php';
                            }
                        });
                    });
                </script>";
                exit();
                // echo "Sending email is not working. Please contact the school. {$mail->ErrorInfo}";
            }

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
                    <h3 class="text-center text-muted">Pre-enrollment registration</h3>
                    <span style="font-size: 15px;">Note: To create an applicant account, provide your basic information first which we`ll be used to verify your Email Address.</span>
                </div>
             
                <div class="loginForm" style="margin-bottom: 15px; padding-bottom: 15px;">
                    <form method="POST">

                        <?php 
                            Helper::EchoErrorField(
                                Constants::$firstNameRequired,
                                Constants::$invalidFirstNameCharacters,
                                Constants::$firstNameIsTooShort,
                                Constants::$firstNameIsTooLong
                            );
                        ?>
                        
                        <label for="firstname" >Firstname</label>
                        <input  type="text" id="firstname" name="pending_firstname" placeholder="Firstname"
                            autocomplete="off"
                            value="<?php  
                                echo Helper::DisplayText('pending_firstname', $pending_firstname);
                            ?>">
                        
                        <?php 
                            Helper::EchoErrorField(
                                Constants::$lastNameRequired,
                                Constants::$invalidLastNameCharacters,
                                Constants::$lastNameIsTooShort, Constants::$lastNameIsTooLong
                            );
                        ?>

                        <label for="lastname">Lastname</label>
                        <input  type="text" id="lastname" name="pending_lastname" placeholder="Lastname" 
                            autocomplete="off"
                            value="<?php  
                                echo Helper::DisplayText('pending_lastname', $pending_lastname);
                            ?>">
                        
                        <?php
                            Helper::EchoErrorField(
                                Constants::$middleNameRequired,
                                Constants::$invalidMiddleNameCharacters,
                                Constants::$middleNameIsTooShort,
                                Constants::$middleNameIsTooLong);
                        ?>
                        <label for="middle_name">Middle Name</label>
                        
                        <input  type="text" id="middle_name"
                            name="pending_mi" placeholder="Middle Initial" 
                            utocomplete="off"
                            value="<?php  
                                echo Helper::DisplayText('pending_mi', $pending_mi);
                            ?>">

                        <label for="email">Email</label>
                        <?php 
                            echo Helper::getError(Constants::$EmailRequired);
                            echo Helper::getError(Constants::$EmailUnique);
                            echo Helper::getError(Constants::$invalidEmailCharacters);
                        ?>
                        <input  type="text" id="email" name="email_address"
                            placeholder="Email" autocomplete="off"
                            value="<?php  
                                echo Helper::DisplayText('email_address', $email_address);
                            ?>">

                        <label for="password">Password</label>
                        <input type="password" id="password" name="pending_password" value="123456" placeholder="Password" autocomplete="off">

                        <div style="margin-top:10px; display: flex;flex-direction: center;align-items: center;justify-content: center;" class="register_div">
                            <button style="width: 180px;" type="submit" name="pending_submit_btn" class="btn btn-success">Register</button>
                        </div>
                        <br>
                        <a class="signInMessage" href="pre_enrollment_login.php">Have an account? Sign in here!</a>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

