<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    
    if (isset($_POST['course_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['student_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['current_school_year_period'])
        
        ){


        $course_id = $_POST['course_id'];   
        $enrollment_id = $_POST['enrollment_id'];   
        $current_school_year_id = $_POST['current_school_year_id'];   
        $current_school_year_period = $_POST['current_school_year_period'];   
        $student_id = $_POST['student_id'];   
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];   
     
        // echo $course_id;
        // echo "    ";
        // echo $enrollment_id;
        // echo "    ";
        // echo $current_school_year_id;
        // echo "    ";
        // echo $student_id;
        // echo "    ";
        // echo $current_school_year_period;
        // echo "    ";

        $enrollment = new Enrollment($con);
        $student_subject = new StudentSubject($con);

        // If Form Enrolled, Mark Student Subject as final 1.

        // $checkFormEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
        //     $student_enrollment_course_id, $current_school_year_id,
        //     $enrollment_id);

        $checkFormEnrolled = $enrollment->CheckEnrollmentEnrolledStatus($student_id,
            $current_school_year_id, $enrollment_id);

         
        $wasSuccess = $enrollment->FormUpdateCourseId($current_school_year_id,
            $student_id, $enrollment_id, $course_id);
        
        // Update the student subject load.
        if($wasSuccess){

            // $update_subject = $student_subject->UpdateStudentSubjectCourseIdApprove(
            //     $student_id, $student_enrollment_course_id, $course_id,
            //     $enrollment_id, $current_school_year_id,
            //     $current_school_year_period, $checkFormEnrolled
            // );

            // if($update_subject){
            //     echo "update_success";
            // }

            echo "update_success";
        }
        
        // if($wasSuccess){
        //     echo "update_success";
        // }
        
    }
 
?>