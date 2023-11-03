<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");

    
    if (isset($_POST['student_enrollment_form_id'])
        && isset($_POST['student_course_id']) 
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id'])
        && isset($_POST['enrollment_payment'])
        && isset($_POST['registrarUserId'])
        
        ){


        $registrarUserId = intval($_POST['registrarUserId']);  

        $enrollment_payment = intval($_POST['enrollment_payment']);  

        $student_enrollment_form_id = $_POST['student_enrollment_form_id'];   
        $student_course_id = $_POST['student_course_id'];   
        $current_school_year_id = $_POST['current_school_year_id']; 
        $student_id = $_POST['student_id']; 
        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->MarkAsRegistrarEvaluated($current_school_year_id,
            $student_course_id, $student_id, $student_enrollment_form_id, $enrollment_payment, $registrarUserId);

        if($wasSuccess){
            echo "update_success";
        }
        
    }
?>