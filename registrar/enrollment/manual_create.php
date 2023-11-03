
<?php 

    // include_once('../../includes/config.php');
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Alert.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Helper.php');
    include_once('../../includes/classes/Constants.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentRequirement.php');

    echo Helper::RemoveSidebar();

    ?>

    <head>

        <script src="../../assets/js/enrollment/manual_create.js"></script>
         
    </head>


    <?php

        
        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        $department = new Department($con);
        $section = new Section($con, null);
        $student = new Student($con);

        $enrollment = new Enrollment($con);

        $generateFormId = $enrollment->GenerateEnrollmentFormId($current_school_year_id);
        $enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($generateFormId);
        
        if (!isset($_SESSION['enrollment_form_id'])) {
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
            // $enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($enrollment_form_id);
        }

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_id = $school_year_obj['school_year_id'];


        // $recordsPerPageOptions = [5, 4]; 
        $recordsPerPageOptions = []; 

        $offeredDepartment = $department->GetOfferedDepartment();
        if(count($offeredDepartment) > 0){

            foreach ($offeredDepartment as $key => $value) {
                # code...
                array_push($recordsPerPageOptions, $value['department_id']);
            }
        }


        $admission_type_url = NULL;

        if(isset($_GET['admission_type'])){
            $admission_type_url = $_GET['admission_type'];
        }

        $stored_admission_type_id = null;
        $stored_department_id = null;

        $stored_program_name = "";
        $stored_program_id = null;

        $stored_course_id = null;
        $stored_course_name = "";

        $program = new Program($con);
        
        $parent = new PendingParent($con);


        // $lastname = Helper::ValidateLastname($_POST['lastname']);
        // $firstname = Helper::ValidateFirstname($_POST['firstname']);
        // $middle_name = Helper::ValidateMiddlename($_POST['middle_name']);
        // $suffix = isset($_POST['suffix']) ? Helper::ValidateSuffix($_POST['suffix']) : '';
        // $civil_status = Helper::ValidateCivilStatus($_POST['civil_status']);
        // $nationality = Helper::ValidateNationality($_POST['nationality']);
        // $sex = Helper::ValidateGender($_POST['sex']);
        // $birthday = Helper::sanitizeFormString($_POST['birthday']);
        // $religion = isset($_POST['religion']) ? Helper::ValidateReligion($_POST['religion']) : '';
        // $birthplace = Helper::ValidateBirthPlace($_POST['birthplace']);
        // $address = Helper::ValidateAddress($_POST['address']);
        // $contact_number = Helper::ValidateContactNumber($_POST['contact_number']);
        // $email = Helper::ValidateEmail($_POST['email'], false, $con);
        // $lrn = Helper::ValidateLRN($_POST['lrn'], false, $con);

        $lrn = "";
        $firstname = "";
        $lastname = "";
        $middle_name = "";
        $suffix = "";
        $civil_status = "";
        $nationality = "";
        $sex = "";
        $birthday = "";
        $religion = "";
        $birthplace = "";
        $address = "";
        $contact_number = "";
        $email = "";

        $father_firstname = "";
        $father_lastname = "";
        $father_middle = "";
        $father_suffix = "";
        $father_contact_number = "";
        $father_email = "";
        $father_occupation = "";

        // Father
        $mother_firstname = "";
        $mother_lastname = "";
        $mother_middle = "";
        $mother_suffix = "";
        $mother_contact_number = "";
        $mother_email = "";
        $mother_occupation = "";

        $parent_firstname = "";
        $parent_lastname = "";
        $parent_middle_name = "";
        $parent_suffix = "";
        $parent_contact_number = "";
        // $parent_email = $parent->GetEmail();
        $parent_occupation = "";
        $parent_relationship = "";

        $year_ended = "";
        $year_started = "";
        $school_name = "";
        $school_address = "";

        if($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['enrollment_btn_' . $enrollment_form_id])
            && isset($_POST['admission_type'])
            && isset($_POST['program_id'])
            && isset($_POST['selected_department_id'])
            && isset($_POST['course_id'])
            ){

            // Student
            $selected_department_id = $_POST['selected_department_id'] ?? "";
            $stored_department_id = $selected_department_id;

            // $_SESSION['selected_department_id'] = $_POST['selected_department_id'];
            // $selected_department_id = $_SESSION['selected_department_id'];

            $program_id = $_POST['program_id'] ?? "";
            $stored_program_id = $program_id;

            $stored_program_name = $program->GetProgramNamev2($program_id);

            $admission_type = intval($_POST['admission_type'] ?? NULL);
            $stored_admission_type_id = $admission_type;
            
            // echo $admission_type;
            // return;

            $course_id = intval($_POST['course_id'] ?? 0);
            $stored_course_id = $course_id;

            $section_exec = new Section($con, $course_id);
            $stored_course_name = $section_exec->GetSectionName();

            $lastname = Helper::ValidateLastname($_POST['lastname']);
            $firstname = Helper::ValidateFirstname($_POST['firstname']);
            $middle_name = Helper::ValidateMiddlename($_POST['middle_name']);
            $suffix = isset($_POST['suffix']) ? Helper::ValidateSuffix($_POST['suffix']) : '';
            $civil_status = Helper::ValidateCivilStatus($_POST['civil_status']);
            $nationality = Helper::ValidateNationality($_POST['nationality']);
            $sex = Helper::ValidateGender($_POST['sex']);
            $birthday = Helper::sanitizeFormString($_POST['birthday']);

            // $religion = isset($_POST['religion']) ? Helper::ValidateReligion($_POST['religion']) : '';
            $religion = isset($_POST['religion']) ?$_POST['religion'] : '';
 
            $birthplace = Helper::ValidateBirthPlace($_POST['birthplace']);
            $address = Helper::ValidateAddress($_POST['address']);
            $contact_number = Helper::ValidateContactNumber($_POST['contact_number']);
            $email = Helper::ValidateEmail($_POST['email'], false, $con);
            // $lrn = Helper::ValidateLRN($_POST['lrn'], false, $con);
            $lrn = isset($_POST['lrn']) ? $_POST['lrn'] : "";


            $school_name = isset($_POST['school_name']) ? $_POST['school_name'] : "";
            $school_address = isset($_POST['school_address']) ? $_POST['school_address'] : "";

            $year_started = isset($_POST['year_started']) ? $_POST['year_started'] : "";
            $year_ended = isset($_POST['year_ended']) ? $_POST['year_ended'] : "";

            // echo "school_name: $school_name <br>";
            // echo "school_address: $school_address <br>";
            // echo "year_started: $year_started <br>";
            // echo "year_ended: $year_ended <br>";
            // return;

            // echo "Last Name: $lastname <br>";
            // echo "First Name: $firstname <br>";
            // echo "Middle Name: $middle_name <br>";
            // echo "Suffix: $suffix <br>";
            // echo "Civil Status: $civil_status <br>";
            // echo "Nationality: $nationality <br>";
            // echo "Sex: $sex <br>";
            // echo "Birthday: $birthday <br>";
            // echo "Religion: $religion <br>";
            // echo "Birthplace: $birthplace <br>";
            // echo "Address: $address <br>";
            // echo "Contact Number: $contact_number <br>";
            // echo "Email: $email <br>";
            // echo "LRN: $lrn <br>";

            // $age = $pending->CalculateAge($birthday);

            // Password -> July 25, 2023 -> 20230725 OR 123456
            $password = "123456";
            

            $section = new Section($con, $course_id);

            // Section selected level
            $course_level =  $section->GetSectionGradeLevel();

            $student_unique_id = $student->GenerateUniqueStudentNumber();
            $username = $student->GenerateStudentUsername($lastname, $student_unique_id);

            $department_override = new Department($con, $selected_department_id);

            $department_name = $department_override->GetDepartmentName();

            $is_tertiary = $department_name == "Senior High School" ? 0 : ($department_name == "Tertiary" ? 1 : 0);

            $is_new_enrollee = $admission_type == 1 || $admission_type == 2 ? 1 : 0;

            $age = $student->CalculateAge($birthday);

            // We based on enrollment Form Based
            $default_course_id = 0;
            $default_course_level = 0;

            // If New Standard or New Transferee.

            $father_lastname = $parent->ValidateFatherLastName($_POST['father_lastname']);
        
            $father_firstname_bool = $father_lastname !== "" 
                ? true : false;

            $father_firstname = $parent->ValidateFatherFirstName(
                $_POST['father_firstname'], $father_firstname_bool);

            if($father_firstname !== "" && $father_lastname === ""){
                $father_lastname = $parent->ValidateFatherLastName(
                    $_POST['father_lastname'], true);
            }

            // FATHER MIDDLENAME
            $father_middle = $parent->ValidateFatherMiddlename($_POST['father_middle']);

            if($father_middle !== ""){

                if($father_contact_number === NULL){

                    $father_contact_number = $parent->ValidateFatherContactNumber(
                        $_POST['father_contact_number'], true);
                }

                if($father_firstname === ""){

                    $father_firstname = $parent->ValidateFatherFirstname(
                        $_POST['father_firstname'], true);
                }

                if($father_lastname === ""){

                    $father_lastname = $parent->ValidateFatherLastname(
                        $_POST['father_lastname'], true);
                }
            }

            // FATHER SUFFIX
            $father_suffix = $parent->ValidateFatherSuffix($_POST['father_suffix']);

            if($father_suffix !== ""){

                if($father_contact_number === NULL){

                    $father_contact_number = $parent->ValidateFatherContactNumber(
                        $_POST['father_contact_number'], true);
                }

                if($father_firstname === ""){

                    $father_firstname = $parent->ValidateFatherFirstname(
                        $_POST['father_firstname'], true);
                }

                if($father_lastname === ""){

                    $father_lastname = $parent->ValidateFatherLastname(
                        $_POST['father_lastname'], true);
                }

            }

            $father_contact_bool = $father_lastname !== ""
                || $father_firstname !== "" ? true : false;

            $father_contact_number = $parent->ValidateFatherContactNumber(
                $_POST['father_contact_number'], $father_contact_bool);


            if($father_contact_number !== NULL){

                if($father_lastname === ""){
                    $father_lastname = $parent->ValidateFatherLastName(
                        $_POST['father_lastname'], true);
                }

                if($father_firstname === ""){
                    $father_firstname = $parent->ValidateFatherFirstName(
                        $_POST['father_firstname'], true);
                }
            }

            // FATHER MIDDLE NAME 
            if($father_middle !== ""){

                if($father_contact_number === NULL){
                    $father_contact_number = $parent->ValidateFatherContactNumber(
                        $_POST['father_contact_number'], true);
                }

                if($father_firstname === ""){
                    $father_firstname = $parent->ValidateFatherFirstname(
                        $_POST['father_firstname'], true);
                }

                if($father_lastname === ""){
                    $father_lastname = $parent->ValidateFatherLastname(
                        $_POST['father_lastname'], true);
                }

            }

            # FATHER OCCUPATION.
            $father_occupation = $parent->ValidateFatherOccupation($_POST['father_occupation']);

            if($father_occupation !== ""){

                if($father_contact_number === NULL){

                    $father_contact_number = $parent->ValidateFatherContactNumber(
                        $_POST['father_contact_number'], false);
                }

                if($father_firstname === ""){

                    $father_firstname = $parent->ValidateFatherFirstname(
                        $_POST['father_firstname'], false);
                }

                if($father_lastname === ""){

                    $father_lastname = $parent->ValidateFatherLastname(
                        $_POST['father_lastname'], false);
                }

            }

            // echo "Father's Last Name: $father_lastname <br>";
            // echo "Father's First Name: $father_firstname <br>";
            // echo "Father's Middle Name: $father_middle <br>";
            // echo "Father's Suffix: $father_suffix <br>";
            // echo "Father's Contact Number: $father_contact_number <br>";
            // echo "Father's Occupation: $father_occupation <br>";
            // echo "<br>";


            // Mother
            $mother_lastname = $parent->ValidateMotherLastName(
                $_POST['mother_lastname']);

            $mother_firstname_bool = $mother_lastname !== "" ? true : false;

            $mother_firstname = $parent->ValidateMotherFirstname(
                $_POST['mother_firstname'], $mother_firstname_bool);

            if($mother_firstname !== "" && $mother_lastname === ""){
                $mother_lastname = $parent->ValidateMotherLastName(
                    $_POST['mother_lastname'], true);
            }
    
            $mother_contact_bool = $mother_lastname !== ""
                || $mother_firstname !== "" ? true : false;


            $mother_contact_number = $parent->ValidateMotherContactNumber(
                $_POST['mother_contact_number'], $mother_contact_bool);
            

            if($mother_contact_number !== NULL){
                if($mother_lastname === ""){

                    $mother_lastname = $parent->ValidateMotherLastName(
                        $_POST['mother_lastname'], true);
                }
                if($mother_firstname === ""){

                    $mother_firstname = $parent->ValidateMotherFirstName(
                        $_POST['mother_firstname'], true);
                }
            }
            
            $mother_middle = $parent->ValidateMotherMiddlename($_POST['mother_middle']);

            # If mother middle has user input, all mother required field should be provided.
            if($mother_middle !== ""){

                if($mother_contact_number === NULL){

                    $mother_contact_number = $parent->ValidateMotherContactNumber(
                        $_POST['mother_contact_number'], true);
                }

                if($mother_firstname === ""){

                    $mother_firstname = $parent->ValidateMotherFirstname(
                        $_POST['mother_firstname'], true);
                }

                if($mother_lastname === ""){

                    $mother_lastname = $parent->ValidateMotherLastname(
                        $_POST['mother_lastname'], true);
                }
            }

            $mother_occupation = $parent->ValidateMotherOccupation($_POST['mother_occupation']);

            # If mother occupation has user input, all mother required field should be provided.
            
            if($mother_occupation !== ""){

                if($mother_contact_number === NULL){
                        
                    $mother_contact_number = $parent->ValidateMotherContactNumber(
                        $_POST['mother_contact_number'], true);
                }
                if($mother_firstname === ""){

                    $mother_firstname = $parent->ValidateMotherFirstname(
                        $_POST['mother_firstname'], true);
                }
                if($mother_lastname === ""){

                    $mother_lastname = $parent->ValidateMotherLastname(
                        $_POST['mother_lastname'], true);
                }
            }

            // echo "Mother's Last Name: $mother_lastname <br>";
            // echo "Mother's First Name: $mother_firstname <br>";
            // echo "Mother's Middle Name: $mother_middle <br>";
            // echo "Mother's Suffix: $mother_suffix <br>";
            // echo "Mother's Contact Number: $mother_contact_number <br>";
            // echo "Mother's Occupation: $mother_occupation <br>";
            // echo "<br>";

            # GUARDIAN SIDE.
            $parent_lastname = $parent->ValidateGuardianLastName($_POST['parent_lastname'],
                false);

            $guardian_lastname_bool = $parent_lastname !== "" ? true : false;

            $parent_firstname = $parent->ValidateGuardianFirstname($_POST['parent_firstname'],
                $guardian_lastname_bool);

            $parent_middle_name = $parent->ValidateGuardianMiddlename($_POST['parent_middle_name']);

            if($parent_middle_name !== ""){

                if($parent_contact_number === NULL){

                    $parent_contact_number = $parent->ValidateGuardianContactNumber(
                        $_POST['parent_contact_number'], true);
                }
                
                if($parent_firstname === ""){

                    $parent_firstname = $parent->ValidateGuardianFirstname(
                        $_POST['parent_firstname'], true);
                }

                if($parent_lastname === ""){

                    $parent_lastname = $parent->ValidateGuardianLastname(
                        $_POST['parent_lastname'], true);
                }
            }

            $guardian_contact_bool = $parent_lastname !== ""
                || $parent_firstname !== "" ? true : false;

            $parent_contact_number = $parent->ValidateGuardianContactNumber(
                $_POST['parent_contact_number'], $guardian_contact_bool);

            # GUARDIAN SUFFIX.
            $parent_suffix = isset($_POST['parent_suffix']) ? $parent->ValidateGuardianSuffix($_POST['parent_suffix']
                ,false) : '';

            if($parent_suffix !== ""){

                if($parent_contact_number === NULL){

                    $parent_contact_number = $parent->ValidateGuardianContactNumber(
                        $_POST['parent_contact_number'], true);
                }
                
                if($parent_firstname === ""){

                    $parent_firstname = $parent->ValidateGuardianFirstname(
                        $_POST['parent_firstname'], true);
                }

                if($parent_lastname === ""){

                    $parent_lastname = $parent->ValidateGuardianLastname(
                        $_POST['parent_lastname'], true);
                }
            }

            $parent_email = isset($_POST['parent_email']) 
                ? $parent->ValidateGuardianEmail($_POST['parent_email'],
                false) : '';

            $parent_occupation = isset($_POST['parent_occupation']) 
                ? $parent->ValidateGuardianOccupation($_POST['parent_occupation'],
                false) : '';

            if($parent_occupation !== ""){

                if($parent_contact_number === NULL){

                    $parent_contact_number = $parent->ValidateGuardianContactNumber(
                        $_POST['parent_contact_number'], true);
                }
                
                if($parent_firstname === ""){

                    $parent_firstname = $parent->ValidateGuardianFirstname(
                        $_POST['parent_firstname'], true);
                }

                if($parent_lastname === ""){

                    $parent_lastname = $parent->ValidateGuardianLastname(
                        $_POST['parent_lastname'], true);
                }
            }

            $relationship_bool = $parent_lastname !== ""
                || $parent_firstname !== ""
                || $parent_occupation !== ""
                ? true : false;
    

            $parent_relationship = isset($_POST['parent_relationship']) ?
                $parent->ValidateGuardianRelationship($_POST['parent_relationship']
                ,$relationship_bool) : '';

            // echo "parent's Last Name: $parent_lastname <br>";
            // echo "parent's First Name: $parent_firstname <br>";
            // echo "parent's Middle Name: $parent_middle_name <br>";
            // echo "parent's Suffix: $parent_suffix <br>";
            // echo "parent's Contact Number: $parent_contact_number <br>";
            // echo "parent's Occupation: $parent_occupation <br>";
            // echo "parent's relationship: $parent_relationship <br>";
            // echo "<br>";
            
            #   
            #

            $guardianError = false;

            if(empty(Helper::$errorArray)){
                
                // echo "empty";
                if($father_lastname === "" && $father_firstname === ""

                    && $mother_lastname === "" && $mother_firstname === ""

                    && $parent_firstname === "" && $parent_lastname === ""
                    && $parent_relationship === ""){

                    # Student Should fill-up the guardian.

                    $guardianError = true;
                    Alert::errorNoRedirect("If student doesnt have father or mother. Please kindly fill-up guardian required input fields.",
                        "");
                }
            }

            if( ($is_new_enrollee == 1 || $is_new_enrollee == 2)){

                if(empty(Helper::$errorArray) 
                    && $guardianError == false){
                    
                    $student_unique_id = NULL;
                    $username = NULL;
                    
                    $addStudent = $student->InsertStudentFromEnrollmentForm(
                        $firstname, $lastname, $middle_name,
                        $password, $civil_status, $nationality, $contact_number, $birthday,
                        $age, $sex, $default_course_id, $student_unique_id, $default_course_level, 
                        $username, $address, $lrn, $religion, $birthplace,
                        $email, $is_tertiary, $is_new_enrollee, $suffix);

                    if($addStudent == false){
                        Alert::error("Something went wrong in adding student", "");
                        exit();
                    }

                    // if(false){

                    $student_id = 0;
                    if($addStudent){

                        $student_id = $con->lastInsertId();

                        # Add Pending.
                        $pending = new Pending($con);

                        $admission_type_pending = $admission_type == 1 ? "Standard" : "Transferee";

                        $pendingSuccess = $pending->InitializePendingDataFromManualEnrollment(
                            $firstname, $lastname, $middle_name, $password,
                            $civil_status, $nationality, $contact_number,
                            $birthday, $birthplace, $sex, $suffix, $program_id, $religion,
                            $course_level, $is_tertiary, $admission_type_pending,
                            $current_school_year_id, $lrn, $email, $address);

                        // var_dump($pendingSuccess);
                        // echo "<br>";
                        // return;

                        $pending_enrollees_id = $con->lastInsertId();

                        if($pending_enrollees_id == 0){
                            Alert::error("Something went wrong in adding New Enrollee", "");
                            exit();
                        }

                        $father_email = "";
                        $mother_email = "";
                        $parent_email = "";
                        
                        $wasParentInserted = $parent->InsertParentInformation(
                            $pending_enrollees_id, $parent_firstname, $parent_lastname, $parent_middle_name,
                            $parent_suffix, $parent_contact_number, $parent_email,
                            $parent_occupation, $parent_relationship,

                            $father_firstname, $father_lastname, $father_middle, $father_suffix,
                            $father_contact_number, $father_email, $father_occupation,

                            $mother_firstname, $mother_lastname, $mother_middle, $mother_suffix,
                            $mother_contact_number, $mother_email, $mother_occupation, null,
                            
                            $school_name, $school_address, $year_started, $year_ended
                        );


                        // if(false){
                        if(true){

                            // Subject Review should finalized is Regular or Not.

                            $enrollment_student_status = "";

                            // Standard Regular -> Regular Enrollment Status
                            // No New Standard in 2nd Semester.
                            $current_school_year_period = "First";
                            if($admission_type == 1 && $current_school_year_period == "First"){
                                $enrollment_student_status = "Regular";
                            }

                            // New Transferee Either First or Second -> Irregular
                            else if($admission_type == 2){
                                $enrollment_student_status = "Irregular";
                            }

                            // 2 is equivalent to Transferee
                            $is_transferee = $admission_type == 2 ? 1 : 0;
                            // $is_tertiary = "";



                            
                            $studentRequirement = new StudentRequirement($con);

                            
                            # Pending Id should inserted because we cant guaranteed the student enrollment go through.
                            $wasStudentRequirementInserted = $studentRequirement
                                ->InitializedPendingEnrolleeRequirementFromManual(
                                $pending_enrollees_id,
                                $current_school_year_id, $admission_type_pending, $is_tertiary);
                            
                            $newEnrollmentSuccess = $enrollment->InsertEnrollmentManualNewStudent(
                                $student_id, $course_id, $current_school_year_id,
                                $enrollment_form_id,
                                $enrollment_student_status, $is_tertiary, 
                                $is_transferee, $is_new_enrollee);

                            $student_enrollment_id = 0;

                            if($newEnrollmentSuccess){

                                $student_enrollment_id = $con->lastInsertId();

                                // $url = "../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id";

                                // Alert::successAutoRedirect("Enrollment Successfully Created.", 
                                //     "$url");
                                // exit();

                                $student_subject = new StudentSubject($con); 

                                // If New Standard Grade 11 and 1st Year Only
                                //  -> Subject Populated 

                                # It used for giving section subject loads for student with blcoked section.
                                
                                if($admission_type == 1){

                                    if($student_enrollment_id != 0 && $student_id != 0 && $course_id != 0 ){

                                        $wasStudentSubjectPopulated = $student_subject
                                            ->AddNonFinalDefaultEnrolledSubject(
                                            $student_id, 
                                            $student_enrollment_id, $course_id,
                                            $current_school_year_id, $current_school_year_period);

                                        if($wasStudentSubjectPopulated){

                                            $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";

                                            Alert::success("Proceeding to Subject Review", 
                                                "$url");

                                            exit();
                                        }

                                    }


                                    // $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";

                                    // Alert::successAutoRedirect("Proceeding to Subject Review", 
                                    //     "$url");

                                    // exit();
                                }
                                
                                if($admission_type == 2){
                                    
                                    $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                                    
                                    Alert::successAutoRedirect("Proceeding to Subject Review", 
                                        "$url");

                                    exit();

                                }

                            }
                        }
                    }
                }
            }
        }


    ?>

    <div class="content">

        <nav>
            <a href="./index.php"
            ><i class="bi bi-arrow-return-left fa-1x"></i>
                <h3>Back</h3>
            </a>
        </nav>

        <main>
            <div class="floating noBorder">
                <header>
                    <div class="title">
                        <h2 class="text-muted" style="color: var(--titleTheme)">Enrollment Form <?php echo $enrollment_form_id;?></h2>
                        <small class="mt-1">SY <?php echo $current_school_year_term ?> &nbsp; <?php echo $current_school_year_period?> Semester</small>
                    </div>
                </header>

                <form method="POST">
                    <main>
                        <header>
                            <div class="title">
                            <h3>Admission Type</h3>
                            </div>
                        </header>
                        
                        <div class="row">
                                
                            <span>
                                <div class="form-element">
                                    <label for="new_standard">New Standard</label>
                                    <div>
                                        <input
                                            type="radio"
                                            name="admission_type"
                                            value="1"
                                            id="new_standard"
                                            <?php 
                                                echo ($admission_type_url == 1 || $stored_admission_type_id == 1) ? "checked" : "";
                                                echo $current_school_year_period == "Second" ? "disabled" : ""; 
                                            ?>
                                        />
                                    </div>
                                </div>

                                <div class="form-element">
                                    <label for="new_transferee">New Transferee</label>
                                    <div>
                                    <input
                                        type="radio"
                                        name="admission_type"
                                        value="2"
                                        id="new_transferee"

                                        <?php echo ($admission_type_url == 2 || $stored_admission_type_id == 2) ? "checked" : ""; ?>
                                    />
                                    </div>
                                </div>
 
                                <div class="form-element">
                                    <label for="radioButton">Ongoing Student</label>
                                    <div>
                                        <a  onclick="document.getElementById('radioButton').click();
                                            return false;">
                                        
                                        <input
                                            type="radio"
                                            name="radioButton"
                                            id="radioButton"
                                        />
                                        </a>
                                        
                                    </div>
                                </div>

                            </span>
                        </div>

                        <!-- GRADE LEVEL  -->
                        <?php 
                            # All adjustment in form section should be in one location student_find_section
                            include_once('./new_student_type.php'); 
                        ?>
                        

                        <hr>
                        <?php include_once('./new_student_form.php'); ?>
                         

 
                        <div class="action">
                            <button type="submit"
                                name="enrollment_btn_<?php echo $enrollment_form_id; ?>"
                                class="default large" >
                                Proceed
                            </button>
                        </div>
                    </main>
                </form>
            </div>
        </main>
    </div>

    <?php
?>
 
<script>

    // $(document).ready(function() {

    //     $('input[name="selected_department_id"]').on('change', function() {

    //         var selected_department_id = parseInt($(this).val());
    //         // console.log(selected_department_id);
            
    //         $.ajax({
    //             url: '../../ajax/enrollment/get_course_strand.php',
    //             type: 'POST',
    //             data: {
    //                 selected_department_id
    //             },
    //             dataType: 'json',

    //             success: function(response) {
    //                 // response = response.trim();
    //                 $.each(response, function(index, value) {

    //                     var program_id = value.program_id;
    //                     var program_name = value.program_name;

    //                 });

    //                 var options = '<option selected value="">Select Program</option>';

    //                 $.each(response, function(index, value) {
    //                     options += '<option value="' + value.program_id + '">' + value.program_name + '</option>';
    //                 });

    //                 $('#program_id').html(options);
    //             }
    //         });

    //     });

        // $('#program_id').on('change', function() {

        //     var program_id = parseInt($(this).val());
        //     // console.log(program_id);

        //     $.ajax({
        //         url: '../../ajax/enrollment/populate_section.php',
        //         type: 'POST',
        //         data: {
        //             program_id
        //         },
        //         dataType: 'json',

        //         success: function(response) {

        //             // response = response.trim();
        //             // console.log(response)
        //             var options = "";
        //             if (response.length === 0) {

        //                 // console.log('empty')
        //                 options += '<option value="NoSection">No Section</option>';
        //                 $('#course_id').html(options);

        //                 return;
        //             }else{
        //                 $.each(response, function(index, value) {
        //                     var course_id = value.course_id;
        //                     var program_section = value.program_section;
        //                 });
                    

        //                 var options = '<option selected value="">Available Sections</option>';

        //                 $.each(response, function(index, value) {
        //                     options += '<option value="' + value.course_id + '">' + value.program_section + '</option>';
        //                 });

        //                 $('#course_id').html(options);
        //             }
        //         }
        //     });
        // });

    // });

</script>