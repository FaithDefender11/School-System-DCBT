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

        
        $checkEnrolled = $enrollment->CheckEnrolledEnrollment($school_year_id,
            $enrollment_id);

        // echo $get_student_new_pending_id;
            
        # If Student Enrollment Form is New
        # Student wanted to withdraw his enrollment form.

        # Remove Student Enrollment Form
        # Remove Student Subjects enrolled
        # Remove Student Table
        # Update student pending status into WITHDRAW

        # If student course section previously has 2/3
        # but because of removal it turns to be 1/3
        # it should automatically removed the room in that section as it reached the below threshold.
        
        if($checkEnrolled == true){

            # Enrollment Removal for Enrolled Subject.
            $new_enrolled_enrollment_remove_success = $enrollment->RemovingEnrollmentFormCashierEvaluated(
                $enrollment_id, $student_id, $school_year_id);

            if($new_enrolled_enrollment_remove_success == true){

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

                // # Remove Student Table.
                $removingNewEnrolledStudent = $student->RemovingNewEnrolledStudent($student_id);
                // $withdrawingEnrolledStudent = $student->WithdrawingNewEnrolledStudent($student_id);

                // if(false){
                if($removingNewEnrolledStudent){

                    echo "success_update";
                    return;

                    # Remove Parent Table.
                    $parent = new PendingParent($con);
                    // $parentRemoved = $parent->RemovingParentOfNewStudent($student_id);

                    // if($get_student_new_pending_id == NULL){
                    //     # Pending Mark as REJECTED.
                    //     echo "success_update";
                    //     return;
                    // }
                    // # Check if Student has Pending Table. By EMAIL, firstname, lastname
                    // if($get_student_new_pending_id !== NULL){
                    //     # Pending Mark as WITHDRAW.
                    //     // $successRejected = $pending->MarkAsWithDraw($get_student_new_pending_id);
                    //     // $successRejected = $pending->MarkAsRejected($get_student_new_pending_id);
                    //     $successRejected = $pending->MarkAsRejected($get_student_new_pending_id);

                    //     if($successRejected == true){
                    //         echo "success_update";
                    //         return;
                    //     } 
                    // }
                }

            }

        }


    }
?>