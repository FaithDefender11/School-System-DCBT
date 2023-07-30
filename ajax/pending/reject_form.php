<?php 

    require_once("../../includes/config.php");
    
    if(isset($_POST['pending_enrollees_id'])) {

        // echo "im not";
        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        // echo $pending_enrollees_id;

        $query = $con->prepare("UPDATE pending_enrollees
            SET student_status=:student_status
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":student_status", "REJECTED");
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            echo "success_update";
            return;
        }
    }

?>