<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/EnrollmentAudit.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/Section.php");

    
    if (isset($_POST['student_enrollment_form_id'])
    
        && isset($_POST['student_enrollment_id']) 
        && isset($_POST['student_course_id']) 
        && isset($_POST['student_course_id']) 
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id'])
        && isset($_POST['enrollment_payment'])
        && isset($_POST['registrarUserId'])
        
        ){


        $registrarUserId = intval($_POST['registrarUserId']);  

        $enrollment_payment = intval($_POST['enrollment_payment']);  

        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $student_enrollment_form_id = $_POST['student_enrollment_form_id'];   
        $student_course_id = $_POST['student_course_id'];   
        $current_school_year_id = $_POST['current_school_year_id']; 
        $student_id = $_POST['student_id']; 
        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->MarkAsRegistrarEvaluated($current_school_year_id,
            $student_course_id, $student_id, $student_enrollment_form_id, $enrollment_payment, $registrarUserId);

        // $enrollment_course_id = $enrollment->GetCourseIdByEnrollmentForm(
        //     $student_id, $student_enrollment_form_id, $current_school_year_id);

        if($wasSuccess){

            # 
            $enrollmentAudit = new EnrollmentAudit($con);
            $section = new Section($con, $student_course_id);

            $sectioName = $section->GetSectionName();

            $registrarName = "";
            if($registrarUserId != ""){
                $user = new User($con, $registrarUserId);
                $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
            }
            
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $description = "Registar '$registrarName' has confirmed the enrollment form and placed on section'$sectioName' on $date_creation";

            $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                $student_enrollment_id,
                $description, $current_school_year_id, $registrarUserId
            );

            echo "update_success";
        }
        
    }
?>