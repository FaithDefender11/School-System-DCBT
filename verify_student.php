<?php

    include('includes/config.php');
    include('includes/classes/Pending.php');

    if(isset($_GET['token'])){

        $pending = new Pending($con);

        $token = $_GET['token'];

        // echo $token;

        // $check = $pending->PromptToken($token);
        
        $checkValidEnrollees = $pending->CheckValidTokenEnrolleeNonActivated($token);

        
        // if($checkValidEnrollees){
        //     echo "valid";
        // }else{
        //     echo "not valid";
        // }
        // var_dump($checkValidEnrollees);


        if($checkValidEnrollees !== null){

            // Check if the record exists and if the expiration time has passed
            $pending_enrollees_id = $checkValidEnrollees['pending_enrollees_id'];

            // echo $pending_enrollees_id;
            $firstname = $checkValidEnrollees['firstname'];
            $expiration_time = $checkValidEnrollees['expiration_time'];

            // $asd = strtotime($expiration_time);

            // var_dump($checkValidEnrollees);
            // return;
            // if (strtotime($expiration_time) < time()) {
                
            //     # If new enrollee reached expiries time (5mins) from the date of
            //     # triggering the register. It will removed their enrollee account.
                
            //     // Remove the record from the pending_enrollees table
            //     $removeInactiveExpires = $pending->RemoveInActivatedEnrollee($token);

            //     if($removeInactiveExpires){

            //         $url = LOCAL_BASE_URL . "/index.php";
            //         // header("Location: /school-system-dcbt/student_enrollment.php");
            //         header("Location: $url");
            //         exit();
            //     }
            // }
            
            if(strtotime($expiration_time) >= time()){

                // var_dump($checkValidEnrollees);
                // return;

                $_SESSION['studentLoggedIn'] = $firstname;
                $_SESSION['username'] = $firstname;
                $_SESSION['enrollee_id'] = $pending_enrollees_id;
                $_SESSION['status'] = "pending";
                $_SESSION['authenticated'] = true;

                $doesActivated = $pending->ActivateEnrolleeAccount($token, $pending_enrollees_id);

                if($doesActivated == true){

                    $url = "";

                    if ($_SERVER['SERVER_NAME'] === 'localhost') {
                        $url = "/school-system-dcbt/student/tentative/process.php?new_student=true&step=preferred_course";
                    }else{
                        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/tentative/process.php?new_student=true&step=preferred_course';
                    }

                    // $url = "/school-system-dcbt/student/tentative/process.php?new_student=true&step=preferred_course";
                    header("Location: $url");
                    exit();

                }else{

                    // echo "Updating token went wrong";
                    echo "Something went wrong.";
                }
            }

        }
        else{
            echo "
                <div class='col-md-12'>
                
                <h3 class='text-primary'>Invalid token credentials or your account has already been activated.</h3>
                </div>
            ";
            exit();
        }


    }


?>






