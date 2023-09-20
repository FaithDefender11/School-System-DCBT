<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    
    if(isset($_POST['pending_enrollees_id'])) {

        // echo "im not";
        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        $pending = new Pending($con);

        $successRejected = $pending->MarkAsRejected($pending_enrollees_id);

        if($successRejected == true){
            echo "success_update";
            return;
        }
    }

?>