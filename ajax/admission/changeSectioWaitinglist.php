<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/WaitingList.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/SubjectProgram.php");
    
    if (isset($_POST['course_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['student_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['current_school_year_period'])
        
        ){


        $current_school_year_id = $_POST['current_school_year_id'];   
        $course_id = $_POST['course_id'];   
        $enrollment_id = $_POST['enrollment_id'];   
        $current_school_year_period = $_POST['current_school_year_period'];   
        $student_id = $_POST['student_id'];   
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];   
     

        $student = new Student($con);
        $section = new Section($con, $course_id);

        $waiting_list = new WaitingList($con);
        $enrollment = new Enrollment($con);
        $student_subject = new StudentSubject($con);


        $sectionLevel = $section->GetSectionGradeLevel();

        // If Form Enrolled, Mark Student Subject as final 1.

        $doesStudentFormEnrolled = false;

        $checkFormEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
            $student_enrollment_course_id, $current_school_year_id,
            $enrollment_id);

        $doesStudentFormEnrolled = $checkFormEnrolled;
         
        $wasSuccess = $enrollment->WaitingListFormUpdateCourseId($current_school_year_id,
            $student_id, $enrollment_id, $course_id,
            $student_enrollment_course_id, true);

        
        // Update the student subject load.
        if($wasSuccess){

            
            # Remove from the waiting list
            // $removedFromList = $waiting_list->RegistrarWaitingListUpdate($student_id,
            //     $current_school_year_id);
            
            // To know if student form has been enrolled already
            // before the changing section starts.

            if($doesStudentFormEnrolled == true){
                # Update Student Course ID.

                $student_course_update = $student->UpdateStudentCourseFromWaitlistForm($student_id,
                    $course_id, $sectionLevel);

                if($student_course_update){
                    echo "update_success_form_enrolled";
                    return; 
                }
            }
            else if($doesStudentFormEnrolled == false){
                echo "update_success_form_enrolled";
                return; 
            }
        }
        
    }
 
?>