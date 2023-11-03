<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 
require_once("includes/classes/SchoolYear.php"); 

$account = new Account($con);

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

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


#
#

if (isset($_SESSION['registrarUserId']) && $_SESSION['role'] == "registrar") {

    header("Location: registrar/dashboard/index.php");
    exit();
}

if (isset($_SESSION['adminUserId']) && $_SESSION['role'] == "admin") {

    header("Location: admin/dashboard/index.php");
    exit();

}

if (isset($_SESSION['cashierUserId']) && $_SESSION['role'] == "cashier") {

    header("Location: cashier/dashboard/index.php");
    exit();

}

if (isset($_SESSION['superAdminUserId']) && $_SESSION['role'] == "super_admin") {

    header("Location: super_admin/dashboard/index.php");
    exit();

}

if(isset($_POST["enrollment_log_in_btn"])) {
    
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $rememberMe = isset($_POST["remember_me"]) ? $_POST["remember_me"] : false;

    $wasSuccessful = $account->enrollmentLogInForAllUsers($username,
        $password, $current_school_year_id);

    // var_dump($wasSuccessful);
    // return;

    if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] === "un_enrolled_new_enrollee" 
        // && $wasSuccessful[2] == true 
        // && $wasSuccessful[3] == "pending"
        ){

        $_SESSION['username'] = $wasSuccessful[1];
        $_SESSION['status'] = "pending";
        $_SESSION["enrollee_id"] = $wasSuccessful[4];
        $_SESSION["email"] = $wasSuccessful[5];
        $_SESSION["studentLoggedIn"] = $wasSuccessful[1];

        $isFinished = $wasSuccessful[6];
        
        if($isFinished === 1){

            header("Location: student/tentative/profile.php?fill_up_state=finished");
            exit();
        }

        # If New Enrollee hasnt finished the form.
        if($isFinished === 0){

            header("Location: student/tentative/process.php?new_student=true&step=preferred_course");
            exit();
        }

 
    }

    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == "enrolled_enrollee" 
        && $wasSuccessful[2] == true 
        && $wasSuccessful[3] == "enrolled"
        ){

        $_SESSION['username'] = $wasSuccessful[1];
        $_SESSION['status'] = "enrolled";
        $_SESSION['applicaton_status'] = "ongoing";

        $_SESSION["studentLoggedIn"] = $username;
        $_SESSION["studentLoggedInId"] = $wasSuccessful[4];
        $_SESSION["role"] = $wasSuccessful[5];

        header("Location: student/registration/index.php");
        exit();
    }

    if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == "enrollment_users_staff" 
        && $wasSuccessful[1] == true 
        && trim(strtolower($wasSuccessful[2])) === "administrator"
        ){

        # Username.

        $_SESSION["adminLoggedIn"] =  $wasSuccessful[4];
        $_SESSION["adminUserId"] = $wasSuccessful[3];
        $_SESSION["role"] = "admin";

        header("Location: admin/dashboard/index.php");
        exit();

    }

    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == "enrollment_users_staff" 
        && $wasSuccessful[1] == true 
        && trim(strtolower($wasSuccessful[2])) === "registrar"
        ){

    
        $_SESSION["registrarLoggedIn"] = $wasSuccessful[4];
        $_SESSION["registrarUserId"] = $wasSuccessful[3];
        $_SESSION["role"] = "registrar";

        header("Location: registrar/dashboard/index.php");
        exit();
        
    }else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == "enrollment_users_staff" 
        && $wasSuccessful[1] == true 
        && trim(strtolower($wasSuccessful[2])) === "cashier"
        ){

    
        $_SESSION["cashierLoggedIn"] = $wasSuccessful[4];
        $_SESSION["cashierUserId"] = $wasSuccessful[3];
        $_SESSION["role"] = "cashier";

        header("Location: cashier/dashboard/index.php");
        exit();
        
    }
    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == "enrollment_users_staff" 
        && $wasSuccessful[1] == true 
        && trim(strtolower($wasSuccessful[2])) === "super administrator"
        ){
    
        $_SESSION["superAdminLoggedIn"] = $username;
        $_SESSION["superAdminUserId"] = $wasSuccessful[2];
        $_SESSION["role"] = "super_admin";

        header("Location: super_admin/dashboard/index.php");
        exit();
        
    }


    if(false){

        if(sizeof($wasSuccessful) > 0 
            && $wasSuccessful[0] == true 
            && trim(strtolower($wasSuccessful[1])) == "administrator"
            ){

            $_SESSION["adminLoggedIn"] = $username;
            $_SESSION["adminUserId"] = $wasSuccessful[2];
            $_SESSION["role"] = "admin";

            header("Location: admin/dashboard/index.php");
            exit();
            
        } 
        else if(sizeof($wasSuccessful) > 0 
            && $wasSuccessful[0] == true 
            && trim(strtolower($wasSuccessful[1])) == "registrar"){

            // echo "true registrar";
            $_SESSION["registrarLoggedIn"] = $username;
            $_SESSION["registrarUserId"] = $wasSuccessful[2];
            $_SESSION["role"] = "registrar";

            header("Location: registrar/dashboard/index.php");
            exit();

        }
        else if(sizeof($wasSuccessful) > 0 
            && $wasSuccessful[0] == true 
            && trim(strtolower($wasSuccessful[1])) == "cashier"){

            // echo "true cashier";
            $_SESSION["cashierLoggedIn"] = $username;
            $_SESSION["role"] = "cashier";

            header("Location: cashier/dashboard/index.php");

            exit();
        }
        else if(sizeof($wasSuccessful) > 0 
            && $wasSuccessful[0] == true 
            && trim(strtolower($wasSuccessful[1])) == "super administrator"){

            $_SESSION["superAdminLoggedIn"] = $username;
            $_SESSION["superAdminUserId"] = $wasSuccessful[2];
            $_SESSION["role"] = "super_admin";

            header("Location: super_admin/dashboard/index.php");

            exit();
        }
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
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    
    <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</head>
<body style="background-color: rgb(243, 243, 243)">
    <div class="login-element">
      <div class="floating">
        <div class="close-btn">
          <button><a href="online_application.php">&times;</a></button>
        </div>
        <header>
          <div class="title">
            <h2>Enrollment Sign In</h2>
          </div>
        </header>
        <main>
          <form method="POST">
            <?php echo $account->getError(Constants::$loginFailed); ?>
            <div class="form-element">
              <label for="username">Username</label>
              <div>
                <input type="text" name="username" id="username" value="a.12R@dcbt.edu" value="<?php getInputValue('username'); ?>" required />
              </div>
            </div>
            <div class="form-element">
              <label for="password">Password</label>
              <div>
                <input
                  type="password"
                  name="password"
                  id="password"
                  value="123456"
                  required
                />
              </div>
            </div>
            <div class="action">
              <input type="submit" name="enrollment_log_in_btn" value="Submit" />
            </div>
            <div class="form-element">
                <small><a href="pre_enrollment_register.php">Need an account? Sign up here!</a></small>
            </div>
          </form>
        </main>
      </div>
    </div>
  </body>
</html>