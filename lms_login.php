

 

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ELMS Sign in</title>
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

        require_once("includes/config.php");
        require_once("includes/classes/Account.php");
        require_once("includes/classes/Teacher.php");
        require_once("includes/classes/Constants.php"); 
        require_once("includes/classes/FormSanitizer.php"); 
        require_once("includes/classes/Teacher.php"); 
        require_once("includes/classes/UserLog.php"); 
        require_once("includes/classes/Student.php"); 
        require_once("includes/classes/SchoolYear.php"); 

        $account = new Account($con);
        $teacher = new Teacher($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        
        function getInputValue($name) {
            if(isset($_POST[$name])) {
                echo $_POST[$name];
            }
        }

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

            
            $wasSuccess = $teacher->LmsLoginCheck($username, $password, $current_school_year_id);

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
                echo "
                    <script>
                        $(document).ready(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Username or password is incorrect!',
                                backdrop: false,
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        });
                    </script>";
            }


            // var_dump($wasSuccess);

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

    <body>
        <div class="content">
            <div class="login-element">
                <div class="floating">
                    <div class="close-btn">
                        <button><a href="index.php">&times;</a></button>
                    </div>
                    <header>
                        <div class="title">
                            <h2>ELMS Sign in</h2>
                        </div>
                    </header>
                    <main>
                        <form method="POST">
                            <div class="form-element">
                                <label for="username">Username</label>
                                <div>
                                    <input type="text" 
                                    autocomplete="off"
                                    name="username"
                                    value="<?php getInputValue('username'); ?>"id="username" required />
                                </div>
                            </div>
                            <div class="form-element">
                                <label for="password">Password</label>
                                <div>
                                    <input
                                        autocomplete="off"
                                        type="password"
                                        name="password"
                                        id="password"
                                        required
                                    />
                                </div>
                                <!-- <small><a href="#">Forgot password?</a></small> -->
                            </div>
                            <div class="action">
                                <input type="submit" name="teacher_lms_btn" value="Submit" />
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>

    </body>
</html>

