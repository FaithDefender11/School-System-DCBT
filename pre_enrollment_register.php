

<!DOCTYPE html>

    <html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pre-enrollment registration</title>
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js"></script>
        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap"
        rel="stylesheet"
        />
        <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap"
        rel="stylesheet"
        />
        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <link rel="icon" href="assets/images/icons/DCBT-Logo.png" type="image/png">
        <link rel="stylesheet" type="text/css" href="assets/css/home.css">

    </head>

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

    $pending_firstname = "";
    $pending_lastname = "";
    $pending_mi = "";
    $email_address = "";
    // $email_address = "";

    // function hasLeadingOrTrailingSpace($password) {
    //     return $password !== trim($password);
    // }
    // Example usage
    // $password = '@123H Ello@123';  // This password has leading and trailing spaces

    // if (hasLeadingOrTrailingSpace($password)) {
    //     echo "Password:$password has leading or trailing spaces.";
    // } else {
    //     echo 'Password is valid.';
    // }


    if(
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['pending_submit_btn']) 

        && 
        isset($_POST['pending_firstname']) &&
        isset($_POST['pending_lastname']) &&
        isset($_POST['pending_mi']) &&
        isset($_POST['email_address']) &&
        isset($_POST['pending_password'])
        
        ){
        


        $pending_firstname = Helper::ValidateFirstname($_POST['pending_firstname']);

        $pending_lastname = Helper::ValidateLastName($_POST['pending_lastname']);

        $pending_mi = Helper::ValidateMiddlename($_POST['pending_mi']);

        $pending_password = Helper::ValidatePassword($_POST['pending_password'], true);
        // $pending_password = Helper::ValidatePassword($password, true);

        // var_dump($pending_password);
        // return;

        $email_address = Helper::ValidateEnrolleeEmail($_POST['email_address'],
            false, $con);

        # Check if email already in the DB with a token, within semester
        // $hasToken = $pending->CheckEnrolleeHasToken($email_address, $current_school_year_id);

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


    $pending = new Pending($con);

    // Example usage
    // $password = 'Sirios  123#';

    // if ($pending->isStrongPassword($password)) {
    //     echo 'Password is strong.';
    // } else {
    //     // echo 'Password should contains at least one uppercase letter, one number, and one special character..';
    //     echo "Please include at least one uppercase letter, one number, and one special character in your password.";

    //     // echo 'Password contains at least one uppercase letter, one number, and one special character..';
    // }
    // echo "<br>";
    // echo "<br>";
    // echo "<br>";
        
?>

<body>
    <div class="content">
        <div class="login-element">
            <div class="floating">
                <!-- <div class="close-btn">
                    <button><a href="online_application.php">&times;</a></button>
                </div> -->
                <header>
                    <div class="title">
                        <h2>Pre-enrollment registration</h2>
                        <small>Note: To create an applicant account, provide your basic information first, which will be used to verify your email address.</small>
                    </div>
                    <small class="text-center">* All fields are required</small>
                </header>
                <main>
                    <form method="post">
                        
                        <div class="form-element">
                            <label for="pending_firstname">First name</label>
                            <div>
                                <input 
                                    required
                                    
                                    type="text" 
                                    name="pending_firstname" 
                                    id="pending_firstname" 
                                    autocomplete="off"
                                    value="<?php echo Helper::DisplayText('pending_firstname', $pending_firstname); ?>"
                                >
                                <small>
                                    <?php
                                        Helper::EchoErrorField(
                                            Constants::$firstNameRequired,
                                            Constants::$invalidFirstNameCharacters,
                                            Constants::$firstNameIsTooShort,
                                            Constants::$firstNameIsTooLong
                                        );
                                    ?>
                                </small>
                                
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="lastname">Last Name</label>
                            <div>
                                <input 
                                    required
                                    
                                    type="text" 
                                    id="lastname" 
                                    name="pending_lastname" 
                                    autocomplete="off"
                                    value="<?php echo Helper::DisplayText('pending_lastname', $pending_lastname); ?>"
                                >
                                <small>
                                    <?php 
                                        Helper::EchoErrorField(
                                            Constants::$lastNameRequired,
                                            Constants::$invalidLastNameCharacters,
                                            Constants::$lastNameIsTooShort,
                                            Constants::$lastNameIsTooLong
                                        );
                                    ?>
                                </small>
                                
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="middle_name">Middle Name</label>
                            <div>
                                <input  
                                    required
                                    type="text" 
                                    id="middle_name"
                                    name="pending_mi"
                                    autocomplete="off"
                                    value="<?php echo Helper::DisplayText('pending_mi', $pending_mi); ?>"
                                >
                                <small>
                                    <?php
                                        Helper::EchoErrorField(
                                            Constants::$middleNameRequired,
                                            Constants::$invalidMiddleNameCharacters,
                                            Constants::$middleNameIsTooShort,
                                            Constants::$middleNameIsTooLong);
                                    ?>
                                </small>
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="email">Email</label>
                            
                            <div>
                                <input 
                                    required
                                            
                                    type="text" 
                                    id="email" 
                                    name="email_address"
                                    autocomplete="off"
                                    value="<?php echo Helper::DisplayText('email_address', $email_address); ?>"
                                >
                                <small>
                                    <?php 
                                        echo Helper::getError(Constants::$EmailRequired);
                                        echo Helper::getError(Constants::$EmailUnique);
                                        echo Helper::getError(Constants::$invalidEmailCharacters);
                                    ?>
                                </small>
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="password">Password</label>

                            <div>
                                <input 
                                    required
                                            
                                    type="password" id="password"
                                    name="pending_password"
                                    autocomplete="off">
                                <small>
                                    <?php 
                                        echo Helper::getError(Constants::$passwordRequiredField);
                                        echo Helper::getError(Constants::$traillingPassword);
                                        echo Helper::getError(Constants::$weakPassword);
                                    ?>
                                </small>
                            </div>
                        </div>

                        <div class="action">
                            <input type="submit" value="Register" name="pending_submit_btn">
                        </div>

                        <div class="form-element">
                            <div class="col-md-12 row">
                                <small><a href="enrollee_resend_token.php">Resend token here!</a></small>
                            </div>
                            <small><a href="enrollment_login.php">Have an account? Sign in here!</a></small>

                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>
</body>
</html>