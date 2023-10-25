<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Student.php");
require_once("includes/classes/SchoolYear.php");
require_once("includes/classes/Pending.php");

require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 

$account = new Account($con);
$student = new Student($con);
$pending = new Pending($con);

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

    $back_url = "index.php";



?>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js"></script>

    <!-- Bootstrap 4 JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        .resend-confirm-button{
            background-color: red;
        }

        .custom-confirm-button {
            background-color: #27ae60; /* Change to your desired color */
            color: white;
        }

    </style>
<?php

if($_SERVER['REQUEST_METHOD'] === "POST"
    && isset($_POST["new_enrollee_signin"])) {
    
    $email = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);


    $checkEnrolleAccountExist = $pending->CheckEnrolleAccountExist($email, $password);
    $checkEnrolleeAccountVerified = $pending->CheckEnrolleeAccountVerified($email, $password);
    
    if($checkEnrolleAccountExist == false){

        echo "<script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Wrong credentials',
                    text: 'Email or password is incorrect.',
                    backdrop: false,
                    confirmButtonText: 'Ok', // Customize the 'Yes' button text
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = 'pre_enrollment_login.php';
                    } 
                });
            });
        </script>";
        exit();

    }

    if($checkEnrolleeAccountVerified == false){
        echo "<script>
            $(document).ready(function() {

                var email = '" . $email . "';

                Swal.fire({
                    icon: 'warning',
                    title: 'Resend',
                    text: 'Email Address is not yet verified.',
                    backdrop: false,
                    showCancelButton: true, // Show 'Cancel' button
                    confirmButtonText: 'Yes, send it', // Customize the 'Yes' button text
                    cancelButtonText: 'No', // Customize the 'No' button text
                    customClass: {
                        confirmButton: 'resend-confirm-button' // Add your custom class here
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Resend functionality.
                        $.ajax({
                            url: 'ajax/pending/resend_email.php',
                            type: 'POST',
                            data: {
                                email
                            },

                            // dataType: 'json',

                            success: function(response) {
                                response = response.trim();

                                console.log(response);

                                if(response === 'resend_email_success'){

                                    Swal.fire({

                                    icon: 'success',
                                    title: 'Email Sent',
                                    text: 'Resend token success',
                                    backdrop: false,
                                    showCancelButton: true, // Show 'Cancel' button
                                    confirmButtonText: 'Ok', // Customize the 'Yes' button text
                                    customClass: {
                                        confirmButton: 'custom-confirm-button' // Add your custom class here
                                    },

                                    }).then((result) => {

                                        window.location.href = 'pre_enrollment_login.php';
                                    });
                                } 
                                if(response === 'resend_failed'){
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed Email Sent',
                                        text: 'Please try again.',
                                        backdrop: false,
                                        showCancelButton: true, // Show 'Cancel' button
                                        confirmButtonText: 'Ok', // Customize the 'Yes' button text
                                        customClass: {
                                            confirmButton: 'custom-confirm-button' // Add your custom class here
                                        },
                                    }).then((result) => {

                                        window.location.href = 'pre_enrollment_login.php';
                                    });
                                }
                                

                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                console.log('Status:', status);
                                console.log('Response Text:', xhr.responseText);
                                console.log('Response Code:', xhr.status);
                            }
                        });

                        



                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = 'pre_enrollment_login.php';
                    }
                });
            });
        </script>";
        exit();
    }

    $verifyNewEnrolleeCredentials = $pending->VerifyNewEnrolleeCredentials(
        $email, $password);

    // $wasSuccess = $student->verifyStudentLoginCredentials($email, $password);

    // // if(sizeof($object) > 0 && $object[1] == true){

    if(sizeof($verifyNewEnrolleeCredentials) > 0 
        && $verifyNewEnrolleeCredentials[1] == true 
        && $verifyNewEnrolleeCredentials[2] == "pending"){

        $_SESSION['username'] = $verifyNewEnrolleeCredentials[0];
        $_SESSION['status'] = "pending";
        $_SESSION["enrollee_id"] = $verifyNewEnrolleeCredentials[3];
        $_SESSION["email"] = $verifyNewEnrolleeCredentials[4];
        $_SESSION["studentLoggedIn"] = $verifyNewEnrolleeCredentials[0];

        if(isset($_SESSION['modal_gatepass'])){
            unset($_SESSION['modal_gatepass']);

        }
        
        # If New Enrollee has finished the form.
        if($verifyNewEnrolleeCredentials[5] == 1){

            // echo "1";
            header("Location: student/tentative/profile.php?fill_up_state=finished");
            exit();
        }

        # If New Enrollee hasnt finished the form.
        if($verifyNewEnrolleeCredentials[5] == 0){
            // echo "0";

            header("Location: student/tentative/process.php?new_student=true&step=preferred_course");
            exit();
        }

        // header("Location: student/tentative/profile.php?fill_up_state=finished");
        // exit();
    }
    
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Pre-Enrollment</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/main_style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 

    <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">

</head>
<body>
    <a href="<?php echo $back_url;?>">
        <i class="bi bi-arrow-return-left fa-1x"></i>
        <h3>Back</h3>
    </a>
    <div class="signInContainer">
          
        <div class="column">

            <div class="header">
                <h3 class="text-center">Pre Enrollee Sign In</h3>
            </div>

            <div class="loginForm">

                <form  method="POST">

                    <?php echo $account->getError(Constants::$loginFailed); ?>

                    <input type="text" value='hypersirios15@gmail.com'  name="username" placeholder="Email address" value="<?php getInputValue('username'); ?>" 
                        required autocomplete="off">

                    <input type="password" value="123456" name="password" placeholder="Password" required>

                    <input type="submit" name="new_enrollee_signin" 
                        value="SUBMIT">

                </form>

            </div>
            <a class="signInMessage" href="pre_enrollment_register.php">Need an account? Sign up here!</a>
        </div>
    </div>
</body>
</html>