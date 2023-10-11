<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    
    if(isset($_POST['enrollment_id'])) {

        // echo "im not";
        $enrollment_id = $_POST['enrollment_id'];

        $enrollment = new Enrollment($con);


        $wasSuccess = $enrollment->ActivateEnrolledStudent($enrollment_id);

        if($wasSuccess){
            echo "success_activate";
            return;
        }
           
    }

?>