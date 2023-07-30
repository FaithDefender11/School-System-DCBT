<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Pending.php");
    
    if (isset($_POST['pending_enrollees_id'])
    ) {

        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        $pending = new Pending($con, $pending_enrollees_id);


        $markAsValidated = $pending->MarkAsValidated($pending_enrollees_id);
        if($markAsValidated == true){
            echo "success";
        }else{
            echo "not_success";
        }

    }

?>