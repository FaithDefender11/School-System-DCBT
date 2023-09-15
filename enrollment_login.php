<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 

$account = new Account($con);

 

if (isset($_SESSION['adminLoggedIn']) 
        || isset($_SESSION['adminUserId'])) {
        
    header("Location: admin/dashboard/index.php");
    exit();
}

if (isset($_SESSION['registrarLoggedIn']) 
        || isset($_SESSION['registrarUserId'])) {
    
    header("Location: registrar/dashboard/index.php");
    exit();
}

if(isset($_POST["enrollment_log_in_btn"])) {
    
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $rememberMe = isset($_POST["remember_me"]) ? $_POST["remember_me"] : false;

    $wasSuccessful = $account->enrollmentLogIn($username, $password);

    if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && trim(strtolower($wasSuccessful[1])) == "administrator"){

        // echo "true admin";
        $_SESSION["adminLoggedIn"] = $username;
        $_SESSION["adminUserId"] = $wasSuccessful[2];

        header("Location: admin/dashboard/index.php");
        exit();
        
    } 
    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && trim(strtolower($wasSuccessful[1])) == "registrar"){

        // echo "true registrar";
        $_SESSION["registrarLoggedIn"] = $username;
        $_SESSION["registrarUserId"] = $wasSuccessful[2];

        header("Location: registrar/dashboard/index.php");

        exit();

    }
    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && trim(strtolower($wasSuccessful[1])) == "cashier"){

        // echo "true cashier";
        $_SESSION["cashierLoggedIn"] = $username;
        // header("Location: cashier_dashboard.php");
        header("Location: cashier/dashboard/index.php");

        exit();
    }

    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && trim(strtolower($wasSuccessful[1])) == "super administrator"){

        $_SESSION["superAdminLoggedIn"] = $username;
        $_SESSION["superAdminUserId"] = $wasSuccessful[2];

        header("Location: super_admin/dashboard/index.php");

        exit();
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

    <title>Authentication Login</title>

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
                <h3 class="text-center">Enrollment Log In</h3>
            </div>

            <div class="loginForm">
                <form  method="POST">

                    <?php 
                        echo $account->getError(Constants::$loginFailed); 
                    ?>

                    <input type="text" value='registrar' name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" 
                        required autocomplete="off">
                    
                    <input type="password" value="123456" name="password" placeholder="Password" required>
 
                    <div class="form-group">
                        <label for="remember_me">Remember me</label>
                        <input type="checkbox" name="remember_me" id="remember_me">
                    </div>
                 
                    <input type="submit" 
                        name="enrollment_log_in_btn" value="SUBMIT">
                        
                </form>
            </div>
            <!-- <a class="signInMessage" href="signUp.php">Need an account? Sign up here!</a> -->
        </div>
    </div>
</body>
</html>