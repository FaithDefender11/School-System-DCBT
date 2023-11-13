<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    

     if (isset($_POST['update_activity'])) {

        // Update the last_activity timestamp in the database
    
        $registrarUserId = isset($_SESSION["registrarUserId"]) 
            ? $_SESSION["registrarUserId"] : "";

        $currentTime = time();

        echo $currentTime;

        $updateQuery = "UPDATE users 
            SET last_activity = :current_time 
            WHERE user_id=:user_id
        ";
        $stmt = $con->prepare($updateQuery);

        $stmt->bindParam(':current_time', $currentTime, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', 3);
        $stmt->execute();

        // if($stmt->rowCount() > 0){

        //     echo "success";
        //     return;

        // }
        // Additional logic: Reset registrar information if needed
        // $resetCurrentRegistrarIdBaseOnLoggedInRegistrar = $enrollment
        //     ->GetAllEnrollmentFormWithRegistrarIdAndResetGlobal(
        //         $userId, $current_school_year_id);

        // Destroy the session
        // session_destroy();

    }

    if (isset($_POST['status'])
        ) {


            

        // $get = $con->prepare("UPDATE users 
        //     SET remember_me_token=:remember_me_token
        //     WHERE user_id=:user_id
        // ");

        // $get->bindValue(":remember_me_token", "updated");
        // $get->bindValue(":user_id", 3);
        // $get->execute();

        // if($get->rowCount() > 0){
        //     echo "executed";
        //     return;
        // } 
    }

?>