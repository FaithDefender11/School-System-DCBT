
<?php 

    require_once("includes/config.php");
    require_once("includes/classes/Account.php");
    require_once("includes/classes/Student.php");
    require_once("includes/classes/Constants.php"); 
    require_once("includes/classes/FormSanitizer.php"); 

    $account = new Account($con);
    $student = new Student($con);

    // if (isset($_SESSION['enrollment_form_id'])) {
    //     unset($_SESSION['enrollment_form_id']);
    // }

    if(isset($_POST['os_submit_btn']) && isset($_POST['os_username']) &&
        isset($_POST['os_password'])){

        $username = FormSanitizer::sanitizeFormUsername($_POST["os_username"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["os_password"]);
    
        $wasSuccess = $student->verifyStudentLoginCredentials($username, $password);

        if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true 
            && $wasSuccess[2] == "enrolled" ){

            $_SESSION['username'] = $wasSuccess[0];
            $_SESSION['status'] = "enrolled";
            $_SESSION['applicaton_status'] = "ongoing";

            $_SESSION["studentLoggedIn"] = $username;
            $_SESSION["studentLoggedInId"] = $wasSuccess[3];

            header("Location: student/ongoing_enrollment/procedure.php?information=show");
            exit();
        }else{
            echo "Wrong";
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
                    <h3 class="text-center text-muted">Student Enrollment</h3>
                    <span style="font-size: 15px;">NOTE: If you cant apply through Online application for any reason. Please kindly reach the registrar for further assistance.</span>
                </div>
             
                <div class="loginForm" style="margin-bottom: 15px; padding-bottom: 15px;">
                    <form method="POST">

                        <label for="">Username</label>
                        <input  type="text" name="os_username" placeholder="Username" autocomplete="off">

                        <label for="">Password</label>
                        <input type="password" name="os_password" value="123456" placeholder="Password" autocomplete="off">

                        <div style="margin-top:10px; display: flex;flex-direction: center;align-items: center;justify-content: center;" class="register_div">
                            <button style="width: 180px;" type="submit" name="os_submit_btn" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

