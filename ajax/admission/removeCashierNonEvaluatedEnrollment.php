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

        
        $checkEnrollmentNew = $enrollment->CheckNewTentativeEnrollment($school_year_id,
            $enrollment_id);

            
        // echo $student_subject_id;

        // New Enrollee -> Enrolled -> Removed

        // - Enrollment Form ( New )
        // - Student Table.
        // - Pending Reject
        // - Enrolled Subject, Grade Remove


        // if($checkEnrollmentNew == false){
        //     $new_enrollment_remove_success = $enrollment->RemovingNewEnrollmentFormCashierNotEvaluated($enrollment_id,
        //         $student_id, $school_year_id);

        //     if($new_enrollment_remove_success == true){
        //         echo "success_update";
        //         return;
        //     }
        // }

        # We have two new enrollment form
        # From Online (Pending Table) vs Manual (Non Pending Table.)

        if($checkEnrollmentNew == true){

            # Enrollment Removal for Cashier Not Evaluated, Only given by subjects in registrar side.
            
            $new_enrollment_remove_success = $enrollment->RemovingEnrollmentFormCashierNotEvaluated($enrollment_id,
                $student_id, $school_year_id);

            if($new_enrollment_remove_success == true){

                # Remove Student Table.

                $removeNewStudentSuccess = $student->RemovingNewStudentFromEnrollmentForm($student_id);
                
                if($removeNewStudentSuccess){

                    # Remove Parent Table.
                    $parent = new PendingParent($con);
                    // $parentRemoved = $parent->RemovingParentOfNewStudent($student_id);

                    # No Pending Table.
                    if($get_student_new_pending_id == NULL){

                        echo "success_update";
                        return;
                    }

                    # Check if Student has Pending Table. By EMAIL, firstname, lastname

                    if($get_student_new_pending_id !== NULL){

                        # Pending Mark as REJECTED.
                        $successRejected = $pending->MarkAsRejected($get_student_new_pending_id);

                        if($successRejected == true){
                            echo "success_update";
                            return;
                        }
                        
                    }
                }
            }

        }


    }
?>