<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['subject_schedule_id'])) {

        $subject_schedule_id = $_POST['subject_schedule_id'];

        $query = $con->prepare("DELETE FROM subject_schedule 
            WHERE subject_schedule_id = :subject_schedule_id");

        $query->bindParam(":subject_schedule_id", $subject_schedule_id);

        if($query->execute()){
            echo "success_delete";
        }


    }
    else{
        echo "not";
    }
?>