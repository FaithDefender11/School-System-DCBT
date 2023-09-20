<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/WaitingList.php");
    
    if (
        isset($_POST['pending_enrollees_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['current_term'])
        && isset($_POST['current_period'])
        
    ) {

        $pending_enrollees_id = $_POST['pending_enrollees_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $current_term = $_POST['current_term'];
        $current_period = $_POST['current_period'];

        $pending = new Pending($con, $pending_enrollees_id);

        $markAsValidated = $pending->MarkAsValidated($pending_enrollees_id,
            $current_school_year_id, $current_term, $current_period);

        if($markAsValidated == true){
            echo "success";
        }else{
            echo "not_success";
        }

    }

?>