<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Student.php");
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 

$account = new Account($con);
$student = new Student($con);

if(isset($_POST["student_log_in_btn"])) {
    
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    // $wasSuccessful = $account->studentLogIn($username, $password);

    
    // if(sizeof($wasSuccessful) > 0 
    //     && $wasSuccessful[0] == true 
    //     && strtolower($wasSuccessful[1]) === "student"){

    //     // echo "true admin";
    //     $_SESSION["studentLoggedIn"] = $username;
    //     header("Location: student/dashboard/index.php");
    //     exit();
        
    // } 

    $wasSuccess = $student->verifyStudentLoginCredentials($username, $password);

    // if(sizeof($object) > 0 && $object[1] == true){
    if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true 
        && $wasSuccess[2] == "enrolled"){

        $_SESSION['username'] = $wasSuccess[0];
        // $_SESSION['enrollee_id'] = $wasSuccess[2];
        $_SESSION['status'] = "enrolled";

        $_SESSION["studentLoggedIn"] = $username;


        header("Location: student/dashboard/index.php");
        
    }
    
    if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true 
        && $wasSuccess[2] != "enrolled"){
        
        $_SESSION['username'] = $wasSuccess[0];
        $_SESSION['enrollee_id'] = $wasSuccess[3];

        // echo $_SESSION['enrollee_id'];

        $_SESSION['status'] = "pending";
        $_SESSION["studentLoggedIn"] = $username;

        header("Location: student/tentative/profile.php?fill_up_state=finished");
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

    <title>Student Login</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/main_style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 

    <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">

</head>
<body>
    <div class="signInContainer">
        <div class="column">

            <div class="header">
                <h3 class="text-center">Student Log In</h3>
            </div>

            <div class="loginForm">
                <form  method="POST">

                    <?php echo $account->getError(Constants::$loginFailed); ?>

                    <input type="text" value='' name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" 
                        required autocomplete="off">

                    <input type="password" value="123456" name="password" placeholder="Password" required>

                    <input type="submit" name="student_log_in_btn" 
                        value="SUBMIT">

                </form>
            </div>
            <!-- <a class="signInMessage" href="signUp.php">Need an account? Sign up here!</a> -->
        </div>
    </div>
</body>
</html>