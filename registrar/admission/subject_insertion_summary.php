<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/PendingParent.php');
 
    ?>
        <style>
            .dropdown-menu.show{
                margin-left: -120px;
            }
            <?php include "../../assets/css/content.css" ?>
        </style>
    <?php

    $department = new Department($con, null);
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $enrollment = new Enrollment($con, null);

    // O.S Irregular, Pending New Standard, New Transferee
    $requirement = new StudentRequirement($con);

    if($_GET['id']){

        $enrollment_form_id_url = $_GET['id'];

        $enrollment_form_student_id = $enrollment->GetStudentIdByEnrollmentId(
            $enrollment_form_id_url, $current_school_year_id);

        $promptIDIfDoesntExists = $enrollment->CheckIdExists($enrollment_form_id_url);

        // $student_id = $_GET['id'];

        $student_id = $enrollment_form_student_id;

        // echo $student_id;
        // echo $student_id;

        // echo $enrollment_form_id_url;

        // return;

        $checkRequirementExists = $requirement->CheckStudentExisted($student_id);
        

        $shs_department_id = $department->GetDepartmentIdByName("Senior High School");
        $tertiary_department_id = $department->GetDepartmentIdByName("Tertiary");

        $student = new Student($con, $student_id);
        $student_subject = new StudentSubject($con);
        $pending = new Pending($con);


        // $promptIDIfDoesntExists = $student->CheckIdExists($student_id);

        $student_course_level = $student->GetStudentLevel($student_id);
        $student_fullname = $student->GetFullName();
        $student_firstname = $student->GetFirstName();
        $student_lastname = $student->GetLastName();
        $student_middle_name = $student->GetMiddleName();
        $date_creation = $student->GetCreation();
        $student_gender = $student->GetStudentSex();
        $student_contact = $student->GetContactNumber();
        $student_address = $student->GetStudentAddress();
        $admission_status = $student->GetAdmissionStatus();
        $student_birthday = $student->GetStudentBirthdays();
        $student_birthplace = $student->GetStudentBirthPlace();
        $student_religion = $student->GetReligion();
        $student_civil_status = $student->GetCivilStatus();
        $student_citizenship = $student->GetNationality();
        $student_email = $student->GetEmail();
        $student_course_id = $student->GetStudentCurrentCourseId();
        $type_status = $student->GetIsTertiary();
        $new_enrollee = $student->GetStudentNewEnrollee();

        $student_admission_status = $student->GetAdmissionStatus();

        // echo $student_admission_status;
        $student_active_status = $student->CheckIfActive();
        
        $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");
       
        $student_suffix = $student->GetSuffix();

        $student_unique_id = $student->GetStudentUniqueId();


        # By Student ID
        // $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        //     $current_school_year_id);

        # By Enrollment ID
        $enrollment_id = $enrollment->GetEnrollmentIdByForm($enrollment_form_id_url,
            $current_school_year_id);
            
        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $enrollment_id, $current_school_year_id);


        $student_enrollment_status = $enrollment->GetEnrollmentFormEnrollmentStatus($student_id,
            $enrollment_id, $current_school_year_id);


        $student_enrollment_made_date = $enrollment->GetEnrollmentMadeDateForm($student_id,
            $enrollment_id, $current_school_year_id);
        
        # By Form ID
        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $student_enrollment_school_year_id = $enrollment->GetEnrollmentSchoolYearByIdForm(
            $student_id,
            $enrollment_id);
        
        $history_year = new SchoolYear($con, $student_enrollment_school_year_id);

        $enrollment_sy_term = $history_year->GetTerm();
        $enrollment_sy_period = $history_year->GetPeriod();
            
        // $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
        //     $student_enrollment_course_id, $current_school_year_id);


        $student_enrollment_form_id = $enrollment->GetEnrollmentFormByFormId($enrollment_form_id_url,
            $student_enrollment_course_id, $current_school_year_id);

        // echo $student_enrollment_course_id;

        $section = new Section($con, $student_enrollment_course_id);

                
        $checkNextActiveSectionIfExistNotFull = $section
            ->CheckNextActiveSectionIfExistNotFull("STEM11-A",
            $current_school_year_term);

        $checkNextActiveSectionIfExist = $section
            ->CheckNextActiveSectionIfExist("STEM11-A",
            $current_school_year_term);

        // $checkNextActiveSectionIfExist = $section
        //     ->CheckNextInActiveSectionIfExistAndUpdateToActive("STEM11-A",
        //     $current_school_year_term);



        // if(($checkNextActiveSectionIfExist && !$checkNextActiveSectionIfExistNotFull) 
        //     || !$checkNextActiveSectionIfExist){}


        $room = new Room($con, null);

        $semesterSectionHasRoomIds = $section->GetSectionIdHasRoomSemester($current_school_year_period,
            $current_school_year_term);

        # CHECK AVAILABLE ROOM
        $hasAvailableRoomWithinSemester = $room->AvailableSectionSYSemesterList($current_school_year_period,
            $current_school_year_term, $semesterSectionHasRoomIds);

        // echo count($hasAvailableRoomWithinSemester);

        $student_program_section = $section->GetSectionName();
        $section_capacity = $section->GetSectionCapacity();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        
        $program = new Program($con, $student_program_id);

        $student_program_acronym = $program->GetProgramAcronym();
        $student_current_department_id = $program->GetProgramDepartmentId();

        // $isSectionFull = $section->CheckSectionIsFull(
        //     $student_enrollment_course_id);



        $updatedTotalStudent =  $section->GetTotalNumberOfStudentInSection(
            $student_enrollment_course_id,
            $current_school_year_id);

        $studentNumberInSection = $section->
            GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                $current_school_year_id);

        // echo $studentNumberInSection;

        $capacity = $section->GetSectionCapacity();


        // if($studentNumberInSection >= $capacity){}

        $isSectionFull = $studentNumberInSection >= $capacity;

        // echo $student_enrollment_course_id;

        $student_new_enrollee = $student->GetStudentNewEnrollee();

        // Referenced by Student ID
        // $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransferee($student_id,
        //     $student_enrollment_course_id, $current_school_year_id);

        // Referenced by Enrollment ID
        $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransfereeByFormId(
            $enrollment_form_id_url,
            $student_enrollment_course_id, $current_school_year_id);
        


        $enrollment_is_new = $enrollment->GetEnrollmentIsNewEnrollee($enrollment_form_id_url,
            $student_enrollment_course_id, $current_school_year_id);

            // echo $enrollment_is_new;
            // echo $student_admission_status;

        $student_admission_status = $student->GetStudentAdmissionStatus();
        $student_status_db = $student->GetStudentStatus();

        $student_status = "";

        // $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
        //     $student_email, $student_firstname, $student_lastname);

        // echo $get_student_new_pending_id;

        // echo "<br>";
        // echo $enrollment_is_new;

        if($enrollment_is_new == 1 && $enrollment_is_transferee == 0){
            $student_status = "New";
        }
        else if($enrollment_is_new == 1 && $enrollment_is_transferee == 1){
            $student_status = "New Transferee";
        }
        else if($student_new_enrollee == 0 && $student_status_db == "Regular"
            && $enrollment_is_new == 0 && $enrollment_is_transferee == 0){
            $student_status = "Ongoing Regular";
        }
        else if($student_new_enrollee == 0 && $student_status_db == "Irregular"
            && $enrollment_is_new == 0 && $enrollment_is_transferee == 0){
            $student_status = "Ongoing Irregular";
        }

        $enrollment_course_section = new Section($con, $student_enrollment_course_id);

        $enrollment_course_section_name = $enrollment_course_section->GetSectionName();
        $enrollment_course_section_level = $enrollment_course_section->GetSectionGradeLevel();
        $student_current_program_section = $enrollment_course_section->GetSectionName();

        // echo $enrollment_course_section_level;

        $back_url = "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";


// if($isSectionFull == false 
//         && $checkIfCashierEvaluated == true
//         && $checkIfRegistrarEvaluated == true
//         && $doesStudentEnrolled == false
//         && $student_enrollment_status != "withdraw"

        $doesStudentEnrolled = $enrollment->CheckStudentEnrolled($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($enrollment_id,
            $student_enrollment_course_id, $current_school_year_id);
      
        $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($enrollment_id,
            $student_enrollment_course_id, $current_school_year_id);

        // var_dump($doesStudentEnrolled);

        $student_enrollment_id = $enrollment_form_id_url;
        $student_current_course_id = $student->GetStudentCurrentCourseId();

        $section = new Section($con, $student_enrollment_course_id);
        $student_program_id = $section->GetSectionProgramId(
            $student_enrollment_course_id === 0 
            ? $student_course_id : $student_enrollment_course_id);

        $prev_section = new Section($con, $student_current_course_id);
        // Student course Based
        $student_current_program_section = $prev_section->GetSectionName();
        $student_current_program_id = $prev_section->GetSectionProgramId($student_current_course_id);

        // echo $student_program_id;

        $student_enrollment_course_level = $section->GetSectionGradeLevel(
            $student_enrollment_course_id === 0 
            ? $student_course_id : $student_enrollment_course_id);

        // echo $student_enrollment_form_id;

        include_once('./changeEnrolledStudentProgramModal.php');
        
        if(isset($_GET['student_details']) && $_GET['student_details'] == "show"){
            include_once('./subject_insertion_details.php');
        }

        if(isset($_GET['enrolled_subject']) && $_GET['enrolled_subject'] == "show"){

            if(isset($_POST['subject_load_btn']) && isset($_POST['unique_enrollment_form_id']) ){
            
                $array_success = [];

                $unique_enrollment_form_id = $_POST['unique_enrollment_form_id'];
 
                $assignedSubjects = $student_subject->GetStudentAssignSubjects(
                    $enrollment_id, 
                    $student_id,
                    $current_school_year_id);

                $isAllFinalized = false;
                $successEnrollmentEnrolled = false;

                $successEnrollmentEnrolled = $enrollment->EnrollmentFormMarkAsEnrolled(
                        $current_school_year_id,
                        $student_enrollment_course_id,
                        $student_id,
                        $student_enrollment_form_id,
                        $student_enrollment_student_status);
                
                $grade = new StudentSubjectGrade($con);

                if($successEnrollmentEnrolled){

                    $section_exec = new Section($con, $student_enrollment_course_id);

                    $latestStudentNumberInSection = $section_exec->
                        GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                            $current_school_year_id);
                     

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

                        # Check if student has enrolled form, if has removed the form
                        # and enrolled the new tentative form
                        
                        $checkPreviousEnrolled = $enrollment->CheckStudentHasEnrolledFormAndRemove(
                            $student_id, $current_school_year_id);

                        // Once given an enrollment form. it should dictated if the student is irregular or regular

                        # Remove the previous form, and Enroll the new Form.
                        
                        // $markEnrolled = $enrollment->EnrollmentFormMarkAsEnrolled(
                        //     $current_school_year_id,
                        //     $student_enrollment_course_id,
                        //     $student_id,
                        //     $student_enrollment_form_id,
                        //     $student_enrollment_student_status);

                        // if(($markEnrolled) == true){

                            // $change_student_course_id_success = $student->UpdateStudentCourseId($student_id,
                            //     $student_course_id, $student_enrollment_course_id,
                            //     $enrollment_course_section_level, $student_enrollment_student_status);
                            
                            # Update latest section to the student & username, student_unique_id creation

                            $created_student_unique_id = $student->GenerateUniqueStudentHexaDecimalNumber();
                            $created_student_username = $student->GenerateStudentUsername(
                                $student_lastname,
                                $created_student_unique_id);


                            $updateStudentEnrollmentFormBasedSuccess = false;

                            if($enrollment_is_new === 1 && $student_admission_status === "New"){

                                $updateStudentEnrollmentFormBasedSuccess = $student->UpdateStudentEnrollmentFormBased(
                                    $student_id,
                                    $enrollment_course_section_level,
                                    $student_enrollment_course_id,
                                    $student_enrollment_student_status,
                                    $created_student_unique_id,
                                    $created_student_username);

                                # Create the Student Requirement Table
                                # Enrollment New Form.
                                if($updateStudentEnrollmentFormBasedSuccess == true){

                                    # If student has Pending Table, Removed as it was created and officially
                                    # enrolled in the Student Table.

                                    $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
                                        $student_email, $student_firstname, $student_lastname);

                                    if($get_student_new_pending_id !== NULL){

                                     
                                        # Once officially enrolled,
                                        # 1. Pending Enrollee Account -> Removed.
                                        # 2. Parent Pending Enrollee Id -> NULL, Student_Id (Updated)
                                        # 3. Student School History Pending Enrollee Id -> NULL, Student_Id (Updated)

                                        $parent = new PendingParent($con);

                                        $parentEnrolleeRemovalSuccess = $parent->PendingEnrolleeSetAsNull(
                                            $get_student_new_pending_id, $student_id);

                                        # Set School History Pending Id to Null (Because Pending enrollee is now enrolled (Student Table generated))
                                        $studentHistoryEnrolleeRemovalSuccess = $pending->SchoolHistoryEnrolleeSetAsNullAndStudentIdUpdated(
                                            $get_student_new_pending_id, $student_id);

                                        # Pending Mark as REJECTED.
                                        // $successRejected = $pending->MarkAsRejected($get_student_new_pending_id);
                                        
                                        $pendingSuccessRemoval = $pending->RemoveNewEnrollee($get_student_new_pending_id);
                                    }
                                
                                    $initRequirement = $requirement->InitializedStudentRequirementTable(
                                        $student_id, $type);
                                }
                            }

                            # RFR
                            if($enrollment_is_new === 0 && $student_admission_status === "Old"){

                                # Updating Student Course Id Scenario is Either on
                                # 1. Ongoing student decided to change program
                                # 2. Moving Up to Higher Program Level (STEM11-A -> STEM12-A)
                                
                                $wasSuccess = $student->UpdateOldStudentEnrollmentForm(
                                    $student_id,
                                    $enrollment_course_section_level,
                                    $student_enrollment_course_id);

                                // if($wasSuccess){
                                //     $updateStudentEnrollmentFormBasedSuccess = true;
                                // }
                            }
                            

                            $capacity = $section->GetSectionCapacity();
                            $course_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
                            $course_level = $section->GetSectionGradeLevel();
                            $program_section = $section->GetSectionName();

                            $successCreateNewSection = false;

                            $checkNextActiveSectionIfExistNotFull = $section->CheckNextActiveSectionIfExistNotFull($program_section,
                                $current_school_year_term);

                            $checkNextActiveSectionIfExist = $section->CheckNextActiveSectionIfExist($program_section,
                                $current_school_year_term);

                            # HUMMS11-A = 2 / 3
                            # 3 / 3, 
                            # if next created same program & level section is not full it should not create HUMMS11-B
                            # if next created same program & level section is full it should create HUMMS11-C
                            # HUMMS11-B (NOT FULL)
                            # HUMMS11-B (FULL)

                            $checkNextInActiveSectionIfExistAndUpdateToActive = $section
                                ->CheckNextInActiveSectionIfExistAndUpdateToActive($program_section,
                                $current_school_year_term);

                            $updateInActivePreviousSectionToActive = false;

                            if($checkNextInActiveSectionIfExistAndUpdateToActive){
                                $updateInActivePreviousSectionToActive = true;
                            }


                            // $section_exec = new Section($con, $student_enrollment_course_id);
                            // $latestStudentNumberInSection = $section_exec->
                            //     GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                            //         $current_school_year_id);

                            if ($latestStudentNumberInSection >= $capacity &&
                                count($hasAvailableRoomWithinSemester) > 0
                                && (($checkNextActiveSectionIfExist && !$checkNextActiveSectionIfExistNotFull) 
                                        || !$checkNextActiveSectionIfExist)){

                                # Update Previous Section into Is FULL.
                                $update_isfull = $section->SetSectionIsFull($student_enrollment_course_id);
                                
                                $new_program_section = $section->AutoCreateAnotherSection($program_section);

                                # Create New Section
                                $createNewSection = $section->CreateNewSection($new_program_section, 
                                    $course_program_id, $course_level,
                                    $current_school_year_term);

                                if($createNewSection == true){

                                    $successCreateNewSection = true;

                                    if(
                                        $successCreateNewSection == true
                                        // && $updateStudentEnrollmentFormBasedSuccess
                                        ){

                                        // Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled. This section is now full,
                                        //     System has created new section.", "../student/record_details.php?id=$student_id&enrolled_subject=show");

                                        Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled and New Section has been Created.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                                        exit();
                                    }
                                }
                            }

                            if(
                                // $updateStudentEnrollmentFormBasedSuccess == true
                                $successCreateNewSection == false
                                ){

                                Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                                exit();
                            }
                        // }
                    }

                }



            }
           
            ?>
                <div class="content">
                    <nav>
                        <a href="<?php echo $back_url; ?>">
                            <i class="bi bi-arrow-return-left fa-1x"></i>
                        <span>Back</span>
                        </a>
                    </nav>

                    <div class="content-header">

                        <header>
                            <div class="title">
                                <h1>Enrollment form</h1>
                            </div>

                            <div class="action">

                                <div class="dropdown">
                                    <button class="icon">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                        <?php 

                                            if($enrollment_is_new == 1 && !$checkIfCashierEvaluated){
                                                
                                                ?>
                                                    <a onclick="<?php echo "rejectedEnrollee($student_id, $enrollment_id, $current_school_year_id)"; ?>"
                                                        href="#" class="dropdown-item" style="color: red">
                                                        <i class="bi bi-file-earmark-x"></i>
                                                        Reject Form
                                                    </a>
                                                    
                                                <?php
                                            }

                                            if($doesStudentEnrolled){
                                                ?>
                                                    
                                                    <a style="cursor: pointer;"
                                                        type='button' 
                                                        data-bs-target='#changeEnrolledStudentProgram' 
                                                        data-bs-toggle='modal'
                                                        href='#' class='dropdown-item text-primary'>
                                                        <i class='bi bi-pencil'></i>&nbsp Create New Form
                                                    </a>
                                                    
                                                <?php
                                            }


                                            if($student_enrollment_status == "enrolled"){

                                                    $unEnrollFormBtn = "";
                                                    
                                                    if($enrollment_is_new == 1){
                                                        $unEnrollFormBtn = "newFormWithdraw($student_id, $enrollment_id, $current_school_year_id)";
                                                    }

                                                    if($enrollment_is_new == 0){
                                                        $unEnrollFormBtn = "oldFormWithdraw($student_id, $enrollment_id, $current_school_year_id)";
                                                    }
                                                ?>

                                                    <a 
                                                        onclick="<?php echo $unEnrollFormBtn; ?>"
                                                        class="dropdown-item" style="cursor:pointer;color: yellow">
                                                            <i class="bi bi-file-earmark-x"></i>
                                                        Withdraw Form
                                                    </a>

                                                    <a data-bs-target="#changeSectionModalBtn" 
                                                        data-bs-toggle="modal"
                                                        class="dropdown-item" style="cursor:pointer;color: blue">
                                                        <i class="bi bi-file-earmark-x"></i>
                                                        Change Section
                                                    </a>
                                                <?php
                                            }

                                        if($isSectionFull){
                                            ?>
                                                <!-- <a data-bs-target="#changeSectionModalBtn" 
                                                    data-bs-toggle="modal"
                                                    class="dropdown-item" style="cursor:pointer;color: blue">
                                                    <i class="bi bi-file-earmark-x"></i>
                                                    Change Section
                                                </a> -->
                                            <?php
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>

                        </header>

                        <div class="cards">
                            <div class="card">
                                <p class="text-center mb-0">Form ID</p>
                                <p class="text-center"><?php echo $student_enrollment_form_id;?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Admission type</p>
                                <p class="text-center"><?php echo $student_status;?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Student no.</p>
                                <p class="text-center">
                                    <a style="all: unset" href="../student/record_details.php?id=<?php echo $student_id;?>&enrolled_subject=show">
                                    <?php echo $student_unique_id;?>

                                    </a>
                                </p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Status</p>
                                <p class="text-center"><?php 
                                    echo $student_enrollment_status === "withdraw" ? "Withdraw" : 
                                    ($student_enrollment_status === "enrolled" ? "Enrolled" : "For Approval");?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Submitted on</p>
                                <p class="text-center">
                                    <?php
                                        $date = new DateTime($student_enrollment_made_date);
                                        $formattedDate = $date->format('m/d/Y H:i');
                                        echo $formattedDate;
                                    ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="tabs">

                        <?php

                            echo "
                                <button class='tab' 
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$enrollment_id&student_details=show';\">
                                    Student Details
                                </button>
                            ";
                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--mainContentBG); color: black'
                                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show';\">
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>

                    <main>

                        <div class="floating">
                            <header>
                                <div class="title">
                                    <h4>Enrollment details</h4>
                                </div>
                            </header>

                            <main>
                                
                                <form method="POST">
                                    <div class="row">

                                        <span>
                                            <label for="sy">S.Y.</label>
                                            <div>
                                                <input style="pointer-events: none;" class="text-center form-control" type="text" name="sy" id="sy" value="<?php echo $enrollment_sy_term; ?>" />
                                            </div>
                                        </span>

                                        <?php
                                        
                                            if($type == "Tertiary"){
                                                ?>
                                                    <span>
                                                        <label label for="track">Track</label>

                                                        <div>
                                                            <select id="inputTrack" class="form-control form-select">
                                                                <?php 
                                                                
                                                                    $track_sql = $con->prepare("SELECT 
                                                                        program_id, track, acronym 
                                                                        
                                                                        FROM program 

                                                                        WHERE department_id !=:department_id
                                                                        GROUP BY track
                                                                    ");

                                                                    $track_sql->bindValue(":department_id", $department_id);
                                                                    $track_sql->execute();
                                                                    
                                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                        $row_program_id = $row['program_id'];

                                                                        $track = $row['track'];

                                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                        echo "<option class='text-center' value='$row_program_id' $selected>$track</option>";
                                                                    }
                                                                ?>
                                                            
                                                            </select>
                                                        </div>
                                                    </span>

                                                    <span>
                                                        <label for="strand">Strand</label>

                                                        <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class=" form-control form-select">
                                                            <?php 

                                                                $SHS_DEPARTMENT = 4;
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                    program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                    WHERE department_id !=:department_id
                                                                    GROUP BY acronym
                                                                ");

                                                                $track_sql->bindValue(":department_id", $department_id);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                    echo "<option class='text-center' value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </span>
                                                <?php
                                            }
                                            else if($type == "SHS"){
                                                
                                                ?>
                                                    <span>

                                                        <label label for="track">Track</label>

                                                        <div>
                                                            <select  style="pointer-events: none;" id="inputTrack"
                                                                class=" form-control form-select">
                                                                <?php 
                                                                    $SHS_DEPARTMENT = 4;

                                                                    // echo $department_id;
                                                                
                                                                    $track_sql = $con->prepare("SELECT 
                                                                        program_id, track, acronym 
                                                                        
                                                                        FROM program 

                                                                        WHERE department_id =:department_id
                                                                        GROUP BY track
                                                                    ");

                                                                    $track_sql->bindValue(":department_id", $shs_department_id);
                                                                    $track_sql->execute();

                                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                        $row_program_id = $row['program_id'];

                                                                        $track = $row['track'];

                                                                        $selected = ($row_program_id == $student_program_id) ? "selected" : "";

                                                                        echo "<option value='$row_program_id' $selected>$track</option>";
                                                                    }
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                    </span>

                                                    <span>
                                                        <label for="strand">Strand</label>
                                                        <select style="pointer-events: none;" onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                            name="strand" id="strand" class=" form-control form-select">
                                                            <?php 
                                                            
                                                                $track_sql = $con->prepare("SELECT 
                                                                    program_id, track, acronym 
                                                                    
                                                                    FROM program 
                                                                    WHERE department_id =:department_id
                                                                    GROUP BY acronym
                                                                ");

                                                                $track_sql->bindParam(":department_id", $shs_department_id);
                                                                $track_sql->execute();

                                                                while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                    $row_program_id = $row['program_id'];

                                                                    $acronym = $row['acronym'];

                                                                    $selected = ($row_program_id == $student_program_id) ? "selected" : "";

                                                                    echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                }
                                                            ?>

                                                        </select>
                                                    </span>
                                                <?php
                                            }
                                        ?>

                                    </div>

                                    <div class="row">

                                        <span>
                                            <label for="grade">Level</label>
                                            <div>
                                                <select class=" form-control" style="pointer-events: none;" name="grade" id="grade">
                                                    <!-- <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                                    <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option> -->
                                                    <?php if($student_course_level): ?>
                                                        <option class="text-center" value="<?php echo $student_course_level ?>"><?php echo $student_course_level ?></option>
                                                    <?php endif;?>
                                                </select>
                                            </div>
                                        </span>

                                        <span>
                                            <label for="semester">Semester</label>
                                            <div>
                                                <select class=" form-control" style="pointer-events: none;" name="semester" id="semester">
                                                    <option class="text-center" value=""<?php echo ($enrollment_sy_period == "First") ? " selected" : ""; ?>>1st</option>
                                                    <option class="text-center" value=""<?php echo ($enrollment_sy_period == "Second") ? " selected" : ""; ?>>2nd</option>
                                                </select>
                                            </div>
                                        </span>
                                    </div>
                                </form>
<!-- 
                                <div style="margin-top: 20px;" class="action">
                                                    <button
                                                        class="default large"
                                                        name="pending_choose_section"
                                                        type="button">
                                                        Waiting
                                                    </button>
                                                </div> -->
                            </main>

                        </div>

                        <div class="floating">

                            <header>
                                <div class="title">
                                    <span 
                                        style="font-size: 13px; font-weight: bold;" class="mt-0 mb-0 text-right">
                                        <?php 
                                            if($student_enrollment_school_year_id == $current_school_year_id
                                                && $student_enrollment_status !== "enrolled"){

                                                echo "Capacity: $studentNumberInSection / $section_capacity";
                                            }
                                        ?>
                                    </span>
                                    <h4>
                                       <a style="all: unset;" href='../section/show.php?id=<?php echo $student_enrollment_course_id; ?>'>
                                            <?php echo $enrollment_course_section_name; ?>
                                        </a> 
                                    </h4>
                                    
                                </div>

                                <?php

                                    $student_enrollment_program_id = $section->
                                        GetSectionProgramId($student_enrollment_course_id);

                                        // echo $student_enrollment_program_id;
                                            
                                    $studentNumberInSection = $section->
                                        GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                                            $current_school_year_id);

                                    $capacity = $section->GetSectionCapacity();


                                    // echo $enrollment_course_section_level;
 
                                    if($studentNumberInSection >= $capacity 
                                        || $studentNumberInSection <= $capacity
                                            && $student_enrollment_status != "withdraw"){

                                        include_once('./changeSectionModal.php');

                                        ?>
                                            <div class="action mb-0">

                                               <!-- <button type="button" 
                                                    data-bs-target="#changeSectionModalBtn" 
                                                    data-bs-toggle="modal"
                                                    class="large default"
                                                    >
                                                    Change Section
                                                </button> -->
                                            </div>
                                        <?php
                                    }
                                ?>
                            </header>


                            <?php 
                            
                                $assignSubjects = $student_subject->GetStudentAssignSubjects(
                                    $enrollment_id,
                                    $student_id);

                                    // echo $enrollment_id;

                                if(count($assignSubjects) === 0){
                                    echo "
                                        <h4 class='text-center text-info'>No Subject given in this Form</h4>
                                    ";
                                }else{
                                    ?>
                                        <table id="subjectLoadTablex" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Course Description</th>
                                                    <!-- <th>Code</th> -->
                                                    <th>Unit</th>
                                                    <th>Section</th>
                                                    <th>Type</th>
                                                    <th>Time</th>
                                                    <th>Room</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php

                                                    $assignSubjects = $student_subject->GetStudentAssignSubjects($enrollment_id,
                                                        $student_id, $current_school_year_id);
                                                    
                                                    if(count($assignSubjects) == 0 && $student_enrollment_status != "withdraw"){
                                                        echo "No subject(s) results";
                                                    }
                                                    else{

                                                        foreach ($assignSubjects as $key => $value) {

                                                            $enrollment_id = $value['enrollment_id'];
                                                            $is_transferee = $value['is_transferee'];
                                                            
                                                            $enrolled_course_id = $value['enrolled_course_id'];

                                                            $subject_id = $value['subject_program_id'];
                                                            $pre_requisite = $value['pre_req_subject_title'];
                                                            $subject_type = $value['subject_type'];
                                                            $subject_code = $value['subject_code'];
                                                            $ss_subject_code = $value['ss_subject_code'];
                                                            $program_section = $value['program_section'];
                                                            $subject_title = $value['subject_title'];
                                                            $course_id = $value['course_id'];
                                                            $unit = $value['unit'];

                                                            $section = new Section($con, $course_id);
                                                            $sectionName = $section->GetSectionName();


                                                            // $subject_code = $program_section . "-" . $value['subject_code'];

                                                            $student_subject_code = "";

                                                            $subject_status = "";

                                                            if($course_id != null && $enrollment_id != NULL){

                                                                $student_subject_code = $section->CreateSectionSubjectCode($subject_code, 
                                                                    $sectionName);
                                                                
                                                                $subject_status = "
                                                                    <i style='color: green;' class='fas fa-check-circle'></i>
                                                                ";
                                                            }
                                                            
                                                            else if($course_id === null && $enrollment_id === NULL){
                                                                $student_subject_code = "-";
                                                                $ss_subject_code = "Credited";
                                                                $subject_status = "
                                                                    <i style='color: orange;' class='fas fa-credit-card'></i>
                                                                ";
                                                            }

                                                            $section_exec = new Section($con, $enrolled_course_id);
                                                            $enrolled_section_name = $section_exec->GetSectionName();

                                                            $allTime  = "";
                                                            $allDays  = "";

                                                            $schedule = new Schedule($con);

                                                            // echo $section_subject_code;
                                                            // echo "<br>";

                                                            $hasSubjectCode = $schedule->GetSameSubjectCode(
                                                                $enrolled_course_id,
                                                                $ss_subject_code, $current_school_year_id);
                                                            
                                                            $scheduleOutput = "";
                                                            $roomOutput = "";

                                                            if($hasSubjectCode !== []){

                                                                foreach ($hasSubjectCode as $key => $value) {

                                                                    // $schedule_subject_code = $value['subject_code'];
                                                                    
                                                                    $schedule_day = $value['schedule_day'];
                                                                    $schedule_time = $value['schedule_time'];
                
                                                                    $allDays .= $schedule_day;
                                                                    $allTime .= $schedule_time;

                                                                    $scheduleOutput .= "$schedule_day - $schedule_time <br>";
                                                                    // echo "<br>";

                                                                    $room = $value['room_number'];

                                                                    if($value['room_number'] != NULL){
                                                                        $roomOutput .= "$room <br>";
                                                                    }else{
                                                                        $roomOutput .= "TBA<br>";
                                                                    }
                                                                }
                                                            }else{
                                                                $scheduleOutput = "TBA";
                                                                $roomOutput = "TBA";
                                                            }

                                                            echo '<tr>'; 
                                                                echo '<td>'.$subject_title.'</td>';
                                                                // echo '<td>'.$subject_code.'</td>';
                                                                echo '<td>'.$unit.'</td>';
                                                                echo '<td>'.$enrolled_section_name.'</td>';
                                                                echo '<td>'.$subject_type.'</td>';
                                                                echo '<td>'.$scheduleOutput.'</td>';
                                                                echo '<td>'.$roomOutput.'</td>';
                                                                echo '<td>'.$subject_status.'</td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                ?>
                                            </tbody> 
                                        </table>
                                    <?php
                                }

                            ?>

                            <form method="POST">

                                <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $student_enrollment_form_id;?>">

                                <?php 

                                    $getPreviousEnrolledFormId = $enrollment->GetStudentPreviousEnrolledForm(
                                            $student_id, $current_school_year_id
                                        );

                                    # If new tentative form course id has the same course id of enrolled form
                                    # if thhat so, it can be enroll if registrar desired to.
                                    $checkPreviousEnrolledFormHasSameSectionToCurrrent = $enrollment->CheckPreviousEnrolledFormHasSameSectionToCurrrent(
                                        $student_id, $current_school_year_id);
                                            
                                    // if($checkIfRegistrarEvalueated){
                                    //     echo "checkIfRegistrarEvaluated true";
                                    // }

                                    // echo '$isSectionFull == ' . ($isSectionFull ? 'true' : 'false') . "<br>";
                                    // echo '$checkIfCashierEvaluated == ' . ($checkIfCashierEvaluated ? 'true' : 'false') . "<br>";
                                    // echo '$checkIfRegistrarEvaluated == ' . ($checkIfRegistrarEvaluated ? 'true' : 'false') . "<br>";
                                    // echo '$doesStudentEnrolled == ' . ($doesStudentEnrolled ? 'true' : 'false') . "<br>";

                                    if($isSectionFull == false 
                                        && $checkIfCashierEvaluated == false 
                                        && $checkIfRegistrarEvaluated == true
                                        && $doesStudentEnrolled == false
                                        && $student_enrollment_status != "withdraw"
                                        
                                        ){
                                            ?>
                                                <div style="margin-top: 20px;" class="action">
                                                    <button
                                                        class="default large"
                                                        name="pending_choose_section"
                                                        type="button"
                                                        onclick="window.location.href='process_enrollment.php?subject_review=show&st_id=<?php echo $student_id;?>&selected_course_id=<?php echo $student_enrollment_course_id;?>'"
                                                        
                                                        >
                                                        Waiting
                                                    </button>
                                                </div>
                                            <?php
                                    }


                                    if($isSectionFull == false 
                                        && $checkIfCashierEvaluated == false 
                                        && $checkIfRegistrarEvaluated == false
                                        && $doesStudentEnrolled == false
                                        ){
                                            ?>
                                                <div style="margin-top: 20px;" class="action">
                                                    
                                                    <button
                                                        class="default large information"
                                                        name="pending_choose_section"
                                                        type="button"
                                                        onclick="window.location.href='process_enrollment.php?subject_review=show&st_id=<?php echo $student_id;?>&selected_course_id=<?php echo $student_enrollment_course_id;?>'"
                                                        >

                                                        Registrar not evaluated
                                                    </button>

                                                </div>
                                            <?php
                                    }

                                    if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == true
                                            && $checkIfRegistrarEvaluated == true
                                            && $doesStudentEnrolled == false
                                        ){

                                            $getPreviousEnrolledFormId = $enrollment->GetStudentPreviousEnrolledForm(
                                                $student_id, $current_school_year_id
                                            );

                                            $text = "";
                                            if($getPreviousEnrolledFormId != NULL){
                                                $text = "System sensed you have enrolled previous form: $getPreviousEnrolledFormId. Note if you enrolled this tentative form, the previous enrolled form will be remove.";
                                            }else{
                                                $text = "I agreed to enroll this enrollment form.";
                                            }

                                            // echo $getPreviousEnrolledFormId;
                                        ?>
                                            <div style="margin-top: 20px;" class="action">
                                                <button type="submit" name="subject_load_btn" 
                                                    class="clean large success"
                                                    onclick="return confirm('<?php echo $text;?>')">
                                                    Approve Enrollment
                                                </button>
                                            </div>

                                        <?php
                                    }
                                    
                                    // echo $getPreviousEnrolledFormId;

                                    if($isSectionFull 
                                        && $getPreviousEnrolledFormId == NULL
                                        && $student_enrollment_status !== "enrolled"){
                                            ?>
                                                <div style="margin-top: 20px;" class="action">
                                                    <button
                                                        class="default large information"
                                                        name="pending_choose_section"
                                                        type="button">
                                                        Section is full
                                                    </button>
                                                </div>
                                            <?php
                                    }

                                    # This happens when Registrar wanted to create new form
                                    # With the same Section but different subject load.

                                    # If previous enrolled form selected is STEM11-A section
                                    # and created new form has been selected the same section STEM11-A

                                    if($isSectionFull == true 
                                            && $checkIfCashierEvaluated == true
                                            && $checkIfRegistrarEvaluated == true
                                            && $doesStudentEnrolled == false
                                            && $student_enrollment_status != "withdraw"
                                            && $checkPreviousEnrolledFormHasSameSectionToCurrrent == true
                                        ){
                                            $text = "";
                                            if($getPreviousEnrolledFormId != NULL){
                                                $text = "System sensed you have enrolled previous form: $getPreviousEnrolledFormId. Note if you enrolled this tentative form, the previous enrolled form will be remove.";
                                            }else{
                                                $text = "I agreed to enroll this enrollment form.";
                                            }

                                            // echo $getPreviousEnrolledFormId;
                                        ?>
                                            <div style="margin-top: 20px;" class="action">
                                                <button type="submit" name="subject_load_btn" 
                                                    class="clean large success"
                                                    onclick="return confirm('<?php echo $text;?>')">
                                                    Approve Enrollment
                                                </button>
                                            </div>

                                        <?php
                                    }


                                ?>

                            </form>

                        </div>
                    </main>
                </div>
            <?php
        }
    }
?>


<script>

    var dropBtns = document.querySelectorAll(".icon");

    dropBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const dropMenu = e.currentTarget.nextElementSibling;
            if (dropMenu.classList.contains("show")) {
                dropMenu.classList.toggle("show");
            } else {
                document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                dropMenu.classList.add("show");
            }
        });
    });

    function rejectedEnrollee(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
        var enrollment_id = parseInt(enrollment_id);
        var school_year_id = parseInt(school_year_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure to reject the new enrollment form?`,
            text: 'Note: This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/admission/removeCashierNonEvaluatedEnrollment.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: `Enrollment form has been rejected.`,

                        });

                        setTimeout(() => {
                            Swal.close();
                            // location.reload();
                            window.location.href = "evaluation.php";
                        }, 1000);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }


    function removeForm(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
        var enrollment_id = parseInt(enrollment_id);
        var school_year_id = parseInt(school_year_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure to remove this enrollment form?`,
            text: 'Note: This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/admission/removeForm.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: `Enrollment form has been rejected.`,
                        });

                        setTimeout(() => {
                            Swal.close();
                            // location.reload();
                            window.location.href = "evaluation.php";
                        }, 1000);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }


    function newFormWithdraw(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
        var enrollment_id = parseInt(enrollment_id);
        var school_year_id = parseInt(school_year_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure to un-enroll this new enrollment form?`,
            // text: 'Important! This action will remove Student and Enrollment record and cannot be undone.',
            // If NEW Student -> Will removed:  Student, Enrollment, Parent, School History Table.
            text: 'Important! This action will remove all student related data.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/admission/unEnrollEnrolledForm.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "success_update"){

                            Swal.fire({
                                icon: 'success',
                                title: `Enrollment form has been withdraw..`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "../enrollment/index.php";
                            }, 1000);

                        }

                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

    function oldFormWithdraw(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
        var enrollment_id = parseInt(enrollment_id);
        var school_year_id = parseInt(school_year_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure to un-enroll this enrollment form?`,
            text: 'Note: This action will cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/admission/oldFormWithdraw.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "success_update"){

                            Swal.fire({
                                icon: 'success',
                                title: `Enrollment form has been withdraw..`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "../enrollment/index.php";
                            }, 1000);
                        }
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }


</script>

<?php include_once('../../includes/footer.php') ?>

