<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/PendingParent.php");
    
    if (isset($_POST['enrollment_id']) 
        && isset($_POST['student_id'])
        && isset($_POST['school_year_id'])
        
        ) {

        $enrollment = new Enrollment($con);
        $pending = new Pending($con);

        $student_id = $_POST['student_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $school_year_id = $_POST['school_year_id'];
        $is_new_enrollee = 1;

        $student = new Student($con, $student_id);

        $student_email = strtolower($student->GetEmail());
        $student_firstname = strtolower($student->GetFirstName());
        $student_lastname = strtolower($student->GetLastName());

        $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
            $student_email, $student_firstname, $student_lastname);

        
        $checkTentativeEnrollment = $enrollment->CheckTentativeEnrollment(
            $school_year_id,
            $enrollment_id);

        if($checkTentativeEnrollment == true){
            $new_enrollment_remove_success = $enrollment->RemovingEnrollmentFormCashierNotEvaluated($enrollment_id,
                $student_id, $school_year_id);

            # Should also removed new student table
            # No removing of enrollment form only.
            if($new_enrollment_remove_success == true){
                echo "success_update";
                return;
            }

        }
    }
?>