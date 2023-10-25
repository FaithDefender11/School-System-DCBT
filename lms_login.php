
<?php 

    require_once("includes/config.php");
    require_once("includes/classes/Account.php");
    require_once("includes/classes/Teacher.php");
    require_once("includes/classes/Constants.php"); 
    require_once("includes/classes/FormSanitizer.php"); 

    $account = new Account($con);
    $teacher = new Teacher($con);
    
    // if (isset($_SESSION['enrollment_form_id'])) {
    //     unset($_SESSION['enrollment_form_id']);
    // }

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


    if (isset($_SESSION['username']) && $_SESSION['role'] == "student") {
        header("Location: student/lms/student_dashboard.php");
        exit();
    }

    if (isset($_SESSION['username']) && $_SESSION['role'] == "teacher") {
        header("Location: teacher/dashboard/index.php");
        exit();
    }

    if (isset($_SESSION['username']) && $_SESSION['role'] == "admin") {
        header("Location: admin_lms/dashboard/index.php");
        exit();
    }

    if(isset($_POST['teacher_lms_btn']) 
        && isset($_POST['username']) &&
        isset($_POST['password'])){

 
        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    
        // echo $username;
        // echo "<br>";
        // echo $password;
        // echo "<br>";

        // return;
        
        $wasSuccess = $teacher->LmsLoginCheck($username, $password);

        if(sizeof($wasSuccess) > 0
            && $wasSuccess[1] == "teacher"
            ){

            $_SESSION['username'] = $wasSuccess[0];
            $_SESSION['role'] = "teacher";

            $_SESSION["teacherLoggedIn"] = $username;
            $_SESSION["teacherLoggedInId"] = $wasSuccess[2];

            header("Location: teacher/dashboard/index.php");
            exit();

        }
        
        else if(sizeof($wasSuccess) > 0
            && $wasSuccess[1] == "student" ){

            $_SESSION['username'] = $wasSuccess[0];
            $_SESSION['status'] = "enrolled";
            $_SESSION['role'] = "student";

            $_SESSION["studentLoggedIn"] = $username;
            $_SESSION["studentLoggedInId"] = $wasSuccess[2];

            // echo "correct login as student";
            header("Location: student/lms/student_dashboard.php");
            exit();

        }
        else if(sizeof($wasSuccess) > 0
            && $wasSuccess[1] == "admin"
            ){

            $_SESSION['username'] = $wasSuccess[0];
            $_SESSION['role'] = "admin";
            $_SESSION["adminLoggedIn"] = $wasSuccess[0];
            $_SESSION["adminUserId"] = $wasSuccess[2];

            // echo "correct login as admin";

            header("Location: admin_lms/dashboard/index.php");
            exit();
        }
        else{
            echo "<script>
                $(document).ready(function() {

                    Swal.fire({
                        icon: 'error',
                        title: 'Wrong credentials',
                        text: 'Username or password is incorrect.',
                        backdrop: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'Ok', // Customize the 'Yes' button text
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = 'lms_login.php';
                        } 
                    });

                });
            </script>";
            exit();

        }


        // var_dump($wasSuccess);

        return;
        // if(sizeof($wasSuccess) > 0
        //     && $wasSuccess[1] == "teacher"
        //     ){

        //     $_SESSION['username'] = $wasSuccess[0];
        //     $_SESSION['role'] = $wasSuccess[1];
        //     $_SESSION["teacherLoggedIn"] = $username;
        //     $_SESSION["teacherLoggedInId"] = $wasSuccess[2];

        //     header("Location: teacher/dashboard/index.php");
        //     exit();

        // }

        // if(sizeof($wasSuccess) > 0
        //     && $wasSuccess[1] == "admin"
        //     ){

        //     $_SESSION['username'] = $wasSuccess[0];
        //     $_SESSION['role'] = "admin";
        //     $_SESSION["adminLoggedIn"] = $username;
        //     $_SESSION["adminUserId"] = $wasSuccess[2];

        //     header("Location: admin_lms/dashboard/index.php");
        //     exit();
        // }
        // else{
        //     echo "Wrong";
        // }


        

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

                    <h3 class="text-center text-muted"><i class="fas fa-lock"></i> LMS Sign in</h3>
                </div>
             
                <div class="loginForm" style="margin-bottom: 15px; padding-bottom: 15px;">
                    <form method="POST">

                        <label for="">Username</label>
                        <input  type="text" name="username" placeholder="Username" autocomplete="off">

                        <label for="">Password</label>
                        <input type="password" name="password" value="123456" placeholder="Password" autocomplete="off">

                        <div style="margin-top:10px; display: flex;flex-direction: center;align-items: center;justify-content: center;" class="register_div">
                            <button style="width: 180px;" type="submit" name="teacher_lms_btn" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

