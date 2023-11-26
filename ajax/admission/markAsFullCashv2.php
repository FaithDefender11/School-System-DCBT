<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/EnrollmentPayment.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/PendingParent.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/StudentRequirement.php");
    require_once("../../includes/classes/EnrollmentAudit.php");
    require_once("../../includes/classes/Alert.php");
    
    if (isset($_POST['enrollment_id']) 
        && isset($_POST['cashier_id'])
   ) {

        $school_year = new SchoolYear($con, null);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        $enrollment_id = $_POST['enrollment_id'];
        $cashier_id = $_POST['cashier_id'];
 
        $enrollment = new Enrollment($con);
        $enrollmentPayment = new EnrollmentPayment($con);
        $requirement = new StudentRequirement($con);

        $student_id = $enrollment->GetStudentIdByEnrollmentId(
            $enrollment_id, $current_school_year_id);

        $student = new Student($con, $student_id);

        $student_email = $student->GetEmail();
        $student_course_level = $student->GetStudentLevel($student_id);
        $student_fullname = $student->GetFullName();
        $student_firstname = $student->GetFirstName();
        $student_lastname = $student->GetLastName();

        $student_admission_status = $student->GetStudentAdmissionStatus();
        $student_status_db = $student->GetStudentStatus();



        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $enrollment_id, $current_school_year_id);

        $enrollment_form_id_real = $enrollment->GetEnrollmentFormByFormIdOnly($enrollment_id);   
        

        $enrollment_is_new = $enrollment->GetEnrollmentIsNewEnrollee($enrollment_id,
            $student_enrollment_course_id, $current_school_year_id);
        
        $enrollment_course_section = new Section($con, $student_enrollment_course_id);

        $enrollment_course_section_name = $enrollment_course_section->GetSectionName();
        $enrollment_course_section_level = $enrollment_course_section->GetSectionGradeLevel();
        $student_current_program_section = $enrollment_course_section->GetSectionName();

        $process_date = date("Y-m-d H:i:s");

        ##
        // $wasSuccessCashPayment = $enrollment->SetEnrollmentApprovedByCashierViaFullCash(
        //     $enrollment_id, $current_school_year_id);
        
        if(true){

            $amount_paid = (float) $enrollment->GetEnrollmentTotalPaymentFormIdOnly($enrollment_id);

            ##
            // $fullCashPaymentSuccess = $enrollmentPayment->InsertPaymentFullCash(
            //     $enrollment_id, $cashier_id, $amount_paid);
            
            $student_subject = new StudentSubject($con);
            
            $assignedSubjects = $student_subject->GetStudentAssignSubjects(
                $enrollment_id, 
                $student_id,
                $current_school_year_id);
            
            $isAllFinalized = false;
            
            
            foreach ($assignedSubjects as $key => $value) {

                $enrollment_id = $value['enrollment_id'];
                $is_transferee = $value['is_transferee'];
                $student_id = $value['student_id'];
                $student_subject_id = $value['student_subject_id'];

                if($is_transferee == 0 && $enrollment_id != NULL){

                    // Mark as Enrolled Subject in the Student_Subject DB.
                    if($student_subject->StudentSubjectMarkAsFinal($enrollment_id,
                        // $student_enrollment_course_id, 
                        $student_id, $current_school_year_id) == true){
                        
                        $isAllFinalized = true;
                    }
                }
            }

            if($isAllFinalized == true){

                $pending = new Pending($con);
                    
                $created_student_unique_id = $student->generateNexStudentUniqueId();
                // $created_student_unique_id = "123123";

                $created_student_username = $student->GenerateStudentUsername(
                    $student_lastname,
                    $created_student_unique_id);

                $generate_password = $student->GenerateRandomPassword();

                $updateStudentEnrollmentFormBasedSuccess = false;

                if($enrollment_is_new == 1 && $student_admission_status === "New"){

                    $updateStudentEnrollmentFormBasedSuccess = $student->UpdateStudentEnrollmentFormBased(
                        $student_id,
                        $enrollment_course_section_level,
                        $student_enrollment_course_id,
                        $student_enrollment_student_status,
                        $created_student_unique_id,
                        $created_student_username,
                        $generate_password);

                    # Create the Student Requirement Table
                    # Enrollment New Form.

                    if($updateStudentEnrollmentFormBasedSuccess == true){

                        $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
                            $student_email, $student_firstname, $student_lastname);

                        if($get_student_new_pending_id !== NULL){

                            // $processEnrolled = true;
                        
                            # Once officially enrolled,
                            # 1. Pending Enrollee Account -> Removed.
                            # 2. Parent Pending Enrollee Id -> NULL, Student_Id (Updated)
                            # 3. Student School History Pending Enrollee Id -> NULL, Student_Id (Updated)

                            $parent = new PendingParent($con);

                            $parentEnrolleeRemovalSuccess = $parent->PendingEnrolleeSetAsNull(
                                $get_student_new_pending_id, $student_id);
 
                            # Pending Mark as Enrolled
                            $successPendingEnrolled = $pending->MarkAsEnrolled(
                                $get_student_new_pending_id);
                            
                            # Initialized Student ID in the Student Requirement Table. 
                            $updateStudentIdOnRequirement = $requirement->UpdateStudentIdOnRequirement(
                                $student_id, $get_student_new_pending_id);
                            
                        }
                         
                    }

                }

                # OLD STUDENT
                if($enrollment_is_new == 0 && $student_admission_status === "Old"){

                    # Updating Student Course Id Scenario is Either on
                    # 1. Ongoing student decided to change program
                    # 2. Moving Up to Higher Program Level (STEM11-A -> STEM12-A)
                    # 3. Has previous regular form but now is Irregular
                        
                    $wasSuccess = $student->UpdateOldStudentEnrollmentForm(
                        $student_id,
                        $enrollment_course_section_level,
                        $student_enrollment_course_id,
                        $student_enrollment_student_status);
                }

                $successCreateNewSection = false;

                if($successCreateNewSection == false){

                    // Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                
                    $enrollmentAudit = new EnrollmentAudit($con);

                    $registrarName = "";

                    if($cashier_id != ""){
                        $user = new User($con, $cashier_id);
                        $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                    }
                    
                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "Cashier '$registrarName' has approved the enrollment form '$enrollment_form_id_real' and placed into section '$enrollment_course_section_name' on $date_creation";

                    $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                        $enrollment_id,
                        $description, $current_school_year_id, $cashier_id
                    );

                    // Alert::successEnrollment("Enrollment Form ID: $student_enrollment_form_id is now enrolled.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                    #approvestate
                    # Check if enrollment form is new, the it should have 
                    # the generate password only for new enrollee
                    // $student_subject->SendingEmailAfterSuccessfulEnrollment(
                    //     $processEnrolled,  $generate_password);

                }

            }


            
            echo "success";
            return;
        }

   }
?>