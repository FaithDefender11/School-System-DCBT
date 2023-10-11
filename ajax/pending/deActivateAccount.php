<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    
    if(isset($_POST['enrollment_id'])
        && isset($_POST['lms_student_id'])) {

        // echo "im not";
        $enrollment_id = $_POST['enrollment_id'];
        $lms_student_id = $_POST['lms_student_id'];

        $enrollment = new Enrollment($con);


        $wasSuccess = $enrollment->DeActivateEnrolledStudent($enrollment_id, $lms_student_id);

        if($wasSuccess){
            echo "success_deactivate";
            return;
        }
           
    }

?>