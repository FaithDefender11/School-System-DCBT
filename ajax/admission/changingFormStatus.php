<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");

    if (isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id']) 
        && isset($_POST['type'])
        && ($_POST['type'] == 'Retake' || $_POST['type'] == 'Unretake')
        ){


        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $student_id = $_POST['student_id']; 
        $current_school_year_id = $_POST['current_school_year_id']; 
        $type = $_POST['type']; 

        // echo $type;

        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->FormUpdateAsRetake($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

        if($wasSuccess){
            echo "update_success";
        }
        
    }
    
    else if (isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id']) 
        && isset($_POST['type'])
        && $_POST['type'] !== 'Retake'
        && $_POST['type'] !== 'Unretake'
        ){


        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $student_id = $_POST['student_id']; 
        $current_school_year_id = $_POST['current_school_year_id']; 
        $type = $_POST['type']; 

        // echo $type;
        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->FormUpdateStudentStatus($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

        if($wasSuccess){
            echo "update_success";
        }
        
    }
?>