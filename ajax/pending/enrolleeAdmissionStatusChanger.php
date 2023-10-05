<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    

    if (isset($_POST['pending_enrollees_id'])
        && isset($_POST['enrollee_admission_status_type'])
        ){
        
        $enrollee_admission_status_type = $_POST['enrollee_admission_status_type'];
        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        $pending = new Pending($con);

        $wasChangeSuccess = $pending->ToggleAdmissionEnrollmentForm(
            $pending_enrollees_id, $enrollee_admission_status_type);

        if($wasChangeSuccess){
            echo "success";
            return;
        }

        // echo $enrollee_enrollment_status_type;
       
    }

?>