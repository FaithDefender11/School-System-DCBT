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
     
    

        $enrollment = new Enrollment($con);
        $student_subject = new StudentSubject($con);

        // If Form Enrolled, Mark Student Subject as final 1.

        $doesStudentFormEnrolled = false;


        $checkFormEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
            $student_enrollment_course_id, $current_school_year_id,
            $enrollment_id);

        $doesStudentFormEnrolled = $checkFormEnrolled;
         
        $wasSuccess = $enrollment->FormUpdateCourseId($current_school_year_id,
            $student_id, $enrollment_id, $course_id, $student_enrollment_course_id);
        
        // Update the student subject load.
        if($wasSuccess){

            echo "update_success";
        }
        
    }
 
?>