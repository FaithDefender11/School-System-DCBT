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
        // echo $token;

        if($checkValidEnrollees !== null){

            // Check if the record exists and if the expiration time has passed
            $pending_enrollees_id = $checkValidEnrollees['pending_enrollees_id'];

            // echo $pending_enrollees_id;
            $firstname = $checkValidEnrollees['firstname'];
            $expiration_time = $checkValidEnrollees['expiration_time'];

            // echo $pending_enrollees_id;

            if (strtotime($expiration_time) < time()) {
                
                # If new enrollee reached expiries time (5mins) from the date of
                # triggering the register. It will removed their enrollee account.
                
                // Remove the record from the pending_enrollees table
                $removeInactiveExpires = $pending->RemoveInActivatedEnrollee($token);


                if($removeInactiveExpires){

                    $url = LOCAL_BASE_URL . "/home.php";
                    // header("Location: /school-system-dcbt/student_enrollment.php");
                    header("Location: $url");
                    exit();
                }
            }
            
            else if(strtotime($expiration_time) >= time()){

                $_SESSION['studentLoggedIn'] = $firstname;
                $_SESSION['username'] = $firstname;
                $_SESSION['enrollee_id'] = $pending_enrollees_id;
                $_SESSION['status'] = "pending";
                $_SESSION['authenticated'] = true;

                $doesActivated = $pending->ActivateEnrolleeAccount($token, $pending_enrollees_id);

                if($doesActivated == true){

                    $url = "/school-system-dcbt/student/tentative/process.php?new_student=true&step=preferred_course";
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
                <h3>Token credentials was wrong. If you have multiple requests of token coming from us, Please Click the latest one.</h3>
            ";
            exit();
        }


    }


?>






