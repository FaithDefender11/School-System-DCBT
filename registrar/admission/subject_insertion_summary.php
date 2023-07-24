<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
 

    $department = new Department($con, null);
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $enrollment = new Enrollment($con, null);

    // O.S Irregular, Pending New Standard, New Transferee
    

    if($_GET['id']){

        $student_id = $_GET['id'];

        $shs_department_id = $department->GetDepartmentIdByName("Senior High School");
        $tertiary_department_id = $department->GetDepartmentIdByName("Tertiary");

        $student = new Student($con, $student_id);
        $student_subject = new StudentSubject($con);

        $promptIDIfDoesntExists = $student->CheckIdExists($student_id);

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
        $student_course_id = $student->GetStudentCurrentCourseId();
        $type_status = $student->GetIsTertiary();

        $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");
        $student_suffix = $student->GetSuffix();

        $student_unique_id = $student->GetStudentUniqueId();



            // echo $updatedTotalStudent;

        $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            // $student_course_id,
            $current_school_year_id);





        // $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
        //     $student_course_id, $current_school_year_id);

        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $enrollment_id, $current_school_year_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);


        $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        // echo $student_enrollment_form_id;
        $section = new Section($con, $student_enrollment_course_id);
   

        $student_program_section = $section->GetSectionName();
        $section_capacity = $section->GetSectionCapacity();


        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);

        $isSectionFull = $section->CheckSectionIsFull($student_enrollment_course_id);

        $updatedTotalStudent =  $section->GetTotalNumberOfStudentInSection($student_enrollment_course_id,
            $current_school_year_id);

        $student_new_enrollee = $student->GetStudentNewEnrollee();

        $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransferee($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        $enrollment_is_new = $enrollment->GetEnrollmentIsNewEnrollee($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        $student_admission_status = $student->GetStudentAdmissionStatus();
        $student_status_db = $student->GetStudentStatus();

        $student_status = "";

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

        $back_url = "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

        if(isset($_GET['student_details']) && $_GET['student_details'] == "show"){

            ?>
                <div class="content">
                        <nav>
                            <a href="<?php echo $back_url; ?>"
                            ><i class="bi bi-arrow-return-left fa-1x"></i>
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
                                        <a href="#" class="dropdown-item" style="color: red"
                                        ><i class="bi bi-file-earmark-x"></i>Delete form</a
                                        >
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
                                    <p class="text-center"><?php echo $student_unique_id;?></p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Status</p>
                                    <p class="text-center">Evaluation</p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Submitted on</p>
                                    <p class="text-center">
                                        <?php
                                            $date = new DateTime($date_creation);
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
                                        style='background-color: var(--mainContentBG)'
                                        onclick=\"window.location.href = 'subject_insertion_summary.php?id=$student_id&student_details=show';\">
                                        Student Details
                                    </button>
                                ";

                                echo "
                                    <button class='tab' 
                                        id='shsPayment'
                                        style='background-color: var(--them); color: white'
                                        onclick=\"window.location.href = 'subject_insertion_summary.php?id=$student_id&enrolled_subject=show';\">
                                        Enrolled Subjects
                                    </button>
                                ";
                            ?>
                        </div>

                    <main>
                        <div class="floating" id="shs-information">
                            <header>
                                <div class="title">
                                <h3>Student details</h3>
                                </div>
                            </header>
                            <main>
                                <form action="">
                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input type="text" name="lastName" id="lastName" value="<?php echo $student_lastname;?>" />
                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="firstName"
                                        id="firstName"
                                        value="<?php echo $student_firstname;?>"
                                        />
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="middleName"
                                        id="middleName"
                                        value="<?php echo $student_middle_name;?>"
                                        />
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="suffixName"
                                        id="suffixName"
                                        value="<?php echo $student_suffix;?>"
                                        />
                                        <small>Suffix name</small>
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="birthdate">Birthdate</label>

                                    <div>
                                        <?php 
                                            echo '
                                                <input
                                                    type="date"
                                                    name="birthdate"
                                                    id="birthdate"
                                                    value="' . date('Y-m-d', strtotime($student_birthday)) . '"
                                                />
                                            ';
                                        ?>
                                    </div>
                                    </span>
                                    <span>
                                    <label for="gender">Gender</label>
                                    <div>
                                        <input type="text" name="gender" id="gender" value="<?php echo $student_gender;?>" />
                                    </div>
                                    </span>
                                    <span>
                                    <label for="contact">Contact no.</label>
                                    <div>
                                        <input type="text" name="contact" id="contact" value="<?php echo $student_contact;?>" />
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="address">Address</label>
                                    <div>
                                        <input type="text" name="address" id="address" value="<?php echo $student_address;?>" />
                                    </div>
                                    </span>
                                </div>
                                </form>
                            </main>
                        </div>
                    </main>
                
                </div>
            <?php
        }

        if(isset($_GET['enrolled_subject']) && $_GET['enrolled_subject'] == "show"){

            if(isset($_POST['subject_load_btn']) 
                && isset($_POST['unique_enrollment_form_id']) ){
            
                $array_success = [];

                $unique_enrollment_form_id = $_POST['unique_enrollment_form_id'];
 
                $assignedSubjects = $student_subject->GetStudentAssignSubjects(
                    $enrollment_id, 
                    // $student_course_id,
                    $student_id, $current_school_year_id);

                $isAllFinalized = false;

                $grade = new StudentSubjectGrade($con);

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
                    
                    // if($is_transferee == 1 && $enrollment_id == NULL){

                    //     // Ratified as Passed in the Student_Subject_Grade DB..
                    //     if($grade->MarkAsPassedStudentCreditedSubject(
                    //         $student_id, $student_subject_id) == true){

                    //         $isAllFinalized = true;
                    //     }
                    // }
                }


                // echo $student_enrollment_course_id;

                if($isAllFinalized == true){

                    // enrollment_status =  enrolled
                    // Once given an enrollment form. it should dictated if the student is irregular or regular

                    $markEnrolled = $enrollment->EnrollmentFormMarkAsEnrolled($current_school_year_id,
                        $student_enrollment_course_id, $student_id,
                        $student_enrollment_form_id, $student_enrollment_student_status);

                    if(($markEnrolled) == true){

                        // $change_student_course_id_success = $student->UpdateStudentCourseId($student_id,
                        //     $student_course_id, $student_enrollment_course_id,
                        //     $enrollment_course_section_level, $student_enrollment_student_status);

                        $updateStudentEnrollmentFormBased = $student->UpdateStudentEnrollmentFormBased(
                            $student_id, $enrollment_course_section_level,
                            $student_enrollment_course_id, $student_enrollment_student_status);

                        $studentNumberInSection = $section->
                            GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                                $current_school_year_id);

                        $capacity = $section->GetSectionCapacity();
                        $course_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
                        $course_level = $section->GetSectionGradeLevel();
                        $program_section = $section->GetSectionName();

                        $successCreateNewSection = false;

                        if($studentNumberInSection >= $capacity){

                            # Update Previous Section into Is FULL.
                            $update_isfull = $section->SetSectionIsFull($student_enrollment_course_id);
                            
                            $new_program_section = $section->AutoCreateAnotherSection($program_section);

                            # Create New Section
                            $createNewSection = $section->CreateNewSection($new_program_section, 
                                $course_program_id, $course_level,
                                $current_school_year_term);

                            if($createNewSection == true){

                                $successCreateNewSection = true;

                                if($successCreateNewSection == true
                                    && $updateStudentEnrollmentFormBased){
                                        // echo "Fuckyouy";

                                    // Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled. This section is now full,
                                    //     System has created new section.", "../student/record_details.php?id=$student_id&enrolled_subject=show");

                                    Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled and New Section has been Created.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                                    exit();
                                }
                            }
                        }

                        // if($successCreateNewSection == true){
                        //     echo "success";
                        // }else{
                        //     echo "not";
                        // }

                        if($updateStudentEnrollmentFormBased == true
                            && $successCreateNewSection == false
                            ){

                            Alert::success("Enrollment Form ID: $student_enrollment_form_id is now enrolled.", "../student/record_details.php?id=$student_id&enrolled_subject=show");
                            exit();
                        }
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
                                    <a href="#" class="dropdown-item" style="color: red"
                                    ><i class="bi bi-file-earmark-x"></i>Delete form</a
                                    >
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
                                <p class="text-center">For Approval</p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Submitted on</p>
                                <p class="text-center">
                                    <?php
                                        $date = new DateTime($date_creation);
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
                                    style='background-color: var(--them)'
                                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$student_id&student_details=show';\">
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--mainContentBG); color: white'
                                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$student_id&enrolled_subject=show';\">
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>

                    <main>

                        <div class="floating">
                            <header>
                                <div class="title">
                                    <h3>Enrollment details</h3>
                                </div>
                            </header>

                            <main>
                                
                                <form method="POST">
                                    <div class="row">

                                        <span>
                                            <label for="sy">S.Y.</label>
                                            <div>
                                                <input readonly class="text-center" type="text" name="sy" id="sy" value="<?php echo $current_school_year_term; ?>" />
                                            </div>
                                        </span>

                                        <?php
                                        
                                            if($type == "Tertiary"){
                                                ?>
                                                    <span>
                                                        <label label for="track">Track</label>

                                                        <div>
                                                            <select id="inputTrack" class="form-select">
                                                                <?php 

                                                                    // $SHS_DEPARTMENT = 4;
                                                                
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
                                                            name="strand" id="strand" class="form-select">
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
                                                            <select  style="pointer-events: none;" id="inputTrack" class="form-select">
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
                                                            name="strand" id="strand" class="form-select">
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
                                                <select  style="pointer-events: none;" name="grade" id="grade">
                                                    <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                                    <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option>
                                                    <!-- <option class="text-center" value="">12</option> -->
                                                </select>
                                            </div>
                                        </span>

                                        <span>
                                            <label for="semester">Semester</label>
                                            <div>
                                                <select  style="pointer-events: none;" name="semester" id="semester">
                                                    <option class="text-center" value=""<?php echo ($current_school_year_period == "First") ? " selected" : ""; ?>>1st</option>
                                                    <option class="text-center" value=""<?php echo ($current_school_year_period == "Second") ? " selected" : ""; ?>>2nd</option>
                                                </select>
                                            </div>
                                        </span>
                                    </div>
                                </form>

                            </main>

                        </div>

                        <div class="floating" id="shs-strand-subjects">
                            <header>
                                <div class="title">
                                    <h3><?php echo $enrollment_course_section_name; ?> subjecta</h3>
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
                                    ){

                                        include_once('./changeSectionModal.php');

                                        ?>
                                            <div class="action mb-0">

                                               <button type="button" 
                                                    data-bs-target="#changeSectionModalBtn" 
                                                    data-bs-toggle="modal"
                                                    class="large default"
                                                    >
                                                    Change Section
                                                </button>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </header>

                            <span style="font-size: 13px; font-weight: bold;" class="mt-0 mb-0">
                                Capacity:
                                <?php 
                                    echo $updatedTotalStudent;
                                ?> / <?php echo $section_capacity;?>
                            </span>

                            <form method="POST">

                                <main>
                                    <table id="subjectLoadTablex" class="a" style="margin: 0">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Unit</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                                $assignSubjects = $student_subject->GetStudentAssignSubjects($enrollment_id,
                                                    // $student_course_id, 
                                                    $student_id, $current_school_year_id);
                                                
                                                if(count($assignSubjects) == 0){
                                                    echo "No subject(s) results";
                                                }else{

                                                    foreach ($assignSubjects as $key => $value) {

                                                        $enrollment_id = $value['enrollment_id'];
                                                        $is_transferee = $value['is_transferee'];

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

                                                        echo '<tr>'; 
                                                            echo '<td>'.$subject_title.'</td>';
                                                            echo '<td>'.$ss_subject_code.'</td>';
                                                            echo '<td>'.$subject_type.'</td>';
                                                            echo '<td>'.$unit.'</td>';
                                                            echo '<td>'.$subject_status.'</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                

                                            ?>
                                        </tbody> 
                                    
                                    </table>
                                    
                                </main>
                                <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $student_enrollment_form_id;?>">

                                <?php 

                                    $doesStudentEnrolled = $enrollment->CheckStudentEnrolled($student_id,
                                        $student_enrollment_course_id, $current_school_year_id);

                                    $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                                        $student_enrollment_course_id, $current_school_year_id);
                                        
                                    $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                                        $student_enrollment_course_id, $current_school_year_id);
                                

                                    // echo '$isSectionFull == ' . ($isSectionFull ? 'true' : 'false') . "<br>";
                                    // echo '$checkIfCashierEvaluated == ' . ($checkIfCashierEvaluated ? 'true' : 'false') . "<br>";
                                    // echo '$checkIfRegistrarEvaluated == ' . ($checkIfRegistrarEvaluated ? 'true' : 'false') . "<br>";
                                    // echo '$doesStudentEnrolled == ' . ($doesStudentEnrolled ? 'true' : 'false') . "<br>";

                                    if($isSectionFull == false 
                                        && $checkIfCashierEvaluated == false 
                                        && $checkIfRegistrarEvaluated == true
                                        && $doesStudentEnrolled == false
                                        
                                        ){
                                            ?>
                                                <div style="margin-top: 20px;" class="action">
                                                    <button
                                                        class="default large"
                                                        name="pending_choose_section"
                                                        type="submit">
                                                        Waiting
                                                    </button>
                                                </div>
                                            <?php
                                    }

                                    if($isSectionFull == false 
                                            && $checkIfCashierEvaluated == true
                                            && $checkIfRegistrarEvaluated == true
                                            && $doesStudentEnrolled == false
                                            // TODO. AND NOT ENROLLED.
                                        ){
                                        ?>
                                            <div style="margin-top: 20px;" class="action">
                                                <button type="submit" name="subject_load_btn" 
                                                    class="clean large success"
                                                    onclick="return confirm('Are you sure you want to insert & enroll??')">
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






<?php include_once('../../includes/footer.php') ?>

