<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentSubject.php");
    
    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "Credit"
        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];

        $student_subject = new StudentSubject($con);

        $wasSuccess = $student_subject->MarkStudentSubjectAsCredited($student_id,
            $current_school_year_id, $subject_program_id);

        if($wasSuccess){
            echo "credited_success";
        }
 
    }
    // else{
    //     echo "Something went wrong on the subject_program_id";
    // }

    
    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "Uncredit"
        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];

        $student_subject = new StudentSubject($con);

 

        $query = $con->prepare("DELETE FROM student_subject 
            WHERE school_year_id = :school_year_id
            AND subject_program_id = :subject_program_id
            AND student_id = :student_id
            AND is_transferee = :is_transferee
            AND is_final = :is_final

            ");
        $query->bindValue(":school_year_id", $current_school_year_id);
        $query->bindValue(":subject_program_id", $subject_program_id);
        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":is_transferee", 1);
        $query->bindValue(":is_final", 1);

        if($query->execute()){
            echo "uncredited_success";
        }

        // $wasSuccess = $student_subject->MarkStudentSubjectAsCredited($student_id,
        //     $current_school_year_id, $subject_program_id);

        // if($wasSuccess){
        //     echo "credited_success";
        // }
 
    }

    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "creditEnrolledSubject"
        && isset($_POST['student_subject_id'])

        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_subject_id = $_POST['student_subject_id'];

        $student_subject = new StudentSubject($con);


        $wasSuccess = $student_subject->CreditAssignedStudentSubjectNonFinal(
            $student_subject_id, $subject_program_id,
            $student_id, $current_school_year_id);

        if($wasSuccess == true){
            echo "credited_success";
        }
 
    }


    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "unCreditEnrolledSubject"
        && isset($_POST['student_subject_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['student_subject_code'])

        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_subject_id = $_POST['student_subject_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];
        $student_subject_code = $_POST['student_subject_code'];

        $student_subject = new StudentSubject($con);

        // echo $student_subject_code;



        $wasSuccess = $student_subject->UnCreditAssignedStudentSubjectNonFinal(
            $student_subject_id, $subject_program_id,
            $student_id, $current_school_year_id, $enrollment_id,
            $student_enrollment_course_id, $student_subject_code);

        if($wasSuccess == true){
            echo "un_credited_success";
        }
 
    }

?>