<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/PendingParent.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Room.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    
    if (isset($_POST['enrollment_id']) 
        && isset($_POST['student_id'])
        && isset($_POST['school_year_id'])
        
        ) {

        
        $room = new Room($con);
        $enrollment = new Enrollment($con);
        $pending = new Pending($con);
        $section = new Section($con);

        $student_id = $_POST['student_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $school_year_id = $_POST['school_year_id'];
        $is_new_enrollee = 1;

        $school_year = new SchoolYear($con, $school_year_id);

        $school_year_period = $school_year->GetPeriod();
        $school_year_term = $school_year->GetTerm();

        // echo $student_id;
        // return;

        $student = new Student($con, $student_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId(
            $student_id, $enrollment_id, $school_year_id);

        $student_email = strtolower($student->GetEmail());
        $student_firstname = strtolower($student->GetFirstName());
        $student_lastname = strtolower($student->GetLastName());

        $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
            $student_email, $student_firstname, $student_lastname);

        $checkEnrolledEnrollment = $enrollment->CheckEnrolledEnrollment($school_year_id,
            $enrollment_id);
 
        if($checkEnrolledEnrollment == true){

            # Enrollment Removal for Enrolled Subject.
            $new_enrolled_enrollment_remove_success = $enrollment->RemovingEnrollmentFormCashierEvaluated(
                $enrollment_id, $student_id, $school_year_id);

            if($new_enrolled_enrollment_remove_success){

                $studentHasRoom = $room->CheckStudentSectionHasRoom(
                    $student_enrollment_course_id, $school_year_period, $school_year_term);
                
                if($studentHasRoom == true){
                    #
                    # Check updated room if unreached the minimum threshold
                    # if so, reset.

                    $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                        $student_enrollment_course_id, $school_year_id,
                        $school_year_term, $school_year_period);
 
                    $checkSectionIsBelowMinStudent = $section->CheckSectionIsBelowMinStudent($students_enrolled,
                        $student_enrollment_course_id, $school_year_term);
                
                    if($checkSectionIsBelowMinStudent == true){

                        // echo "checkSectionIsBelowMinStudent";

                        $resetRoomSuccess = $room->DeleteSectionRoomUnreachedMinStudent(
                            $school_year_period,
                            $student_enrollment_course_id);
                    }
                   
                }
                # 
                $setToInActive = $student->UpdateStudentAsInActive($student_id);
                if($setToInActive){
                    echo "success_update";
                    return;
                }
               
            }
        }


    }
?>