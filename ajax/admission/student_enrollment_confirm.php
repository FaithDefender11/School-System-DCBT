<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");

    
    if (isset($_POST['student_enrollment_form_id'])
        && isset($_POST['student_course_id']) 
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id'])){


        $student_enrollment_form_id = $_POST['student_enrollment_form_id'];   
        $student_course_id = $_POST['student_course_id'];   
        $current_school_year_id = $_POST['current_school_year_id']; 
        $student_id = $_POST['student_id']; 
        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->MarkAsRegistrarEvaluated($current_school_year_id,
            $student_course_id, $student_id, $student_enrollment_form_id);

        if($wasSuccess){
            echo "update_success";
        }
        
    }
?>