<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    

    # Good Moral
    if (isset($_POST['pending_enrollees_id'])){

        
        $pending_enrollees_id = intval($_POST['pending_enrollees_id']);

        $pending = new Pending($con);

        $wasSuccess = $pending->ProcessRejectedEnrollee($pending_enrollees_id);
        if($wasSuccess){
            echo "success_process";
            return;
        }

    }
?>