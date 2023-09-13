<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/FormSanitizer.php"); 

$account = new Account($con);

if(isset($_POST["enrollment_log_in_btn"])) {
    
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);


    $wasSuccessful = $account->enrollmentLogIn($username, $password);
    
    // print_r($wasSuccessful);
    # Check user role.

    if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && strtolower($wasSuccessful[1]) == "administrator"){

        // echo "true admin";
        $_SESSION["adminLoggedIn"] = $username;
        header("Location: admin/dashboard/index.php");
        exit();
        
    } 
    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && strtolower($wasSuccessful[1]) == "registrar"){

        // echo "true registrar";
        $_SESSION["registrarLoggedIn"] = $username;
        header("Location: registrar_dashboard.php");
        exit();

    }
    else if(sizeof($wasSuccessful) > 0 
        && $wasSuccessful[0] == true 
        && strtolower($wasSuccessful[1]) == "cashier"){

        // echo "true cashier";
        $_SESSION["cashierLoggedIn"] = $username;
        header("Location: cashier_dashboard.php");
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

    <title>Daehan College of Business & Technology</title>
    <link rel="stylesheet" type="text/css" href="assets/css/DCBT-landing-page.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/forms.css" />
    <!--Link fonts-->
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</head>
<body style="background-color: rgb(243, 243, 243)">
    <div class="login-element">
      <div class="floating">
        <div class="close-btn">
        <button><a href="index.php">&times;</a></button>
        </div>
        <header>
          <div class="title">
            <h2>Log-in</h2>
            <small>Log-in with your school email</small>
          </div>
        </header>
        <main>
          <form method="POST">
            <?php echo $account->getError(Constants::$loginFailed); ?>
            <div class="form-element">
              <label for="email">Email</label>
              <div>
                <input type="text" name="username" id="email" value="<?php getInputValue('username'); ?>" required />
              </div>
              <small><a href="#">Forgot email?</a></small>
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
              <small><a href="#">Forgot password?</a></small>
            </div>
            <div class="action">
              <input type="submit" name="enrollment_log_in_btn" value="Submit" />
            </div>
          </form>
        </main>
      </div>
    </div>
  </body>
</html>