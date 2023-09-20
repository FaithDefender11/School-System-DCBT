<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    
    if (isset($_POST['current_school_year_id'])
        && isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id'])
        && isset($_POST['chosen_course_id'])
        && isset($_POST['type_status'])
        && isset($_POST['is_new_enrollee'])
        ){
 
 
        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $current_school_year_id = $_POST['current_school_year_id'];   
        $student_id = $_POST['student_id'];   
        $chosen_course_id = $_POST['chosen_course_id'];   

        $is_tertiary = $_POST['type_status'];   
        $is_new_enrollee = $_POST['is_new_enrollee'];   


        $enrollment = new Enrollment($con);

        $enrollment_form_id =  $enrollment->GenerateEnrollmentFormId();
        $now = date("Y-m-d H:i:s");
    
        $is_transferee = 0;
  
        
        $sql = $con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_form_id, enrollment_approve, enrollment_date,
                is_transferee, is_new_enrollee, is_tertiary)
            VALUES(:student_id, :course_id, :school_year_id, :enrollment_form_id, :enrollment_approve, :enrollment_date,
                :is_transferee, :is_new_enrollee, :is_tertiary)");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindValue(":course_id", $chosen_course_id);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
        $sql->bindParam(":enrollment_date", $now);
        $sql->bindValue(":enrollment_approve", NULL, PDO::PARAM_NULL);
        $sql->bindValue(":is_tertiary", $is_tertiary);
        $sql->bindValue(":is_transferee", $is_transferee);
        $sql->bindValue(":is_new_enrollee", $is_new_enrollee);
        // $sql->bindParam(":student_status", $student_status);

        $sql->execute();
        if($sql->rowCount() > 0){
            echo "success_change_program";
            return;
        }else{
            echo "changing_program_went_wrong";
            return;
        }

    }
?>