<?php 

    include_once('../../includes/cashier_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Schedule.php');

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

        // $student_id = $_GET['id'];

        $enrollment_form_id_url = $_GET['id'];

        $enrollment_form_student_id = $enrollment->GetStudentIdByEnrollmentId(
            $enrollment_form_id_url, $current_school_year_id);

        $student_id = $enrollment_form_student_id;
        

        // echo $student_id;
        
        $checkRequirementExists = $requirement->CheckStudentExisted($student_id);
        if($checkRequirementExists){
            // echo "true";
        }else{
            // echo "false";
        }

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
        $student_birthplace = $student->GetStudentBirthPlace();
        $student_religion = $student->GetReligion();
        $student_civil_status = $student->GetCivilStatus();
        $student_citizenship = $student->GetNationality();
        $student_email = $student->GetEmail();
        $student_course_id = $student->GetStudentCurrentCourseId();
        $type_status = $student->GetIsTertiary();




        $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");
        $student_suffix = $student->GetSuffix();

        $student_unique_id = $student->GetStudentUniqueId();


        $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            // $student_course_id,
            $current_school_year_id);

        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $enrollment_form_id_url, $current_school_year_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_form_id_url, $current_school_year_id);

        $student_enrollment_waiting_list = $enrollment->GetEnrollmentFormWaitingList($student_id,
            $enrollment_form_id_url, $current_school_year_id);

        $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($enrollment_form_id_url,
            $student_enrollment_course_id, $current_school_year_id);

        $enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
            $student_enrollment_course_id, $current_school_year_id);

        $section = new Section($con, $student_enrollment_course_id);
   
        $student_program_section = $section->GetSectionName();
        $section_capacity = $section->GetSectionCapacity();

        $student_enrollment_school_year_id = $enrollment->GetEnrollmentSchoolYearByIdForm(
            $student_id,
            $enrollment_id);

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);

        $isSectionFull = $section->CheckSectionIsFull($student_enrollment_course_id);

        $updatedTotalStudent =  $section->GetTotalNumberOfStudentInSection($student_enrollment_course_id,
            $current_school_year_id);

        $student_new_enrollee = $student->GetStudentNewEnrollee();

        $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransfereeByFormId($enrollment_form_id_url,
            $student_enrollment_course_id, $current_school_year_id);

        $enrollment_is_new = $enrollment->GetEnrollmentIsNewEnrollee($enrollment_form_id_url,
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

        if(isset($_GET['student_details']) 
            && $_GET['student_details'] == "show"){

            ?>
            <style>
                <?php include "../../assets/css/content.css" ?>
            </style>
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
                                        <a  href="#" class="dropdown-item" style="color: red">
                                            <i class="bi bi-file-earmark-x"></i>
                                            Delete form
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </header>

                        <div class="cards">
                            <div class="card">
                                <sup>Form ID</sup>
                                <sub><?php echo $student_enrollment_form_id;?></sub>
                            </div>
                            <div class="card">
                                <sup>Admission type</sup>
                                <sub><?php echo $student_status;?></sub>
                            </div>
                            <div class="card">
                                <sup>Student no.</sup>
                                <sub><?php echo $student_unique_id;?></sub>
                            </div>
                            <div class="card">
                                <sup>Status</sup>
                                <sub>Evaluation</sub>
                            </div>
                            <div class="card">
                                <sup>Submitted on</sup>
                                <sub>
                                    <?php
                                        $date = new DateTime($date_creation);
                                        $formattedDate = $date->format('m/d/Y H:i');
                                        echo $formattedDate;
                                    ?>
                                </sub>
                            </div>
                        </div>
                    </div>

                    <div class="tabs">

                        <?php
                            echo "
                                <button class='tab' 
                                    style='background-color: var(--mainContentBG)'
                                    onclick=\"window.location.href = 'payment_summary.php?id=$enrollment_form_id_url&student_details=show';\">
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'payment_summary.php?id=$enrollment_form_id_url&enrolled_subject=show';\">
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>


                    <main>
                        <div class="floating">
                            <header class="mt-4">
                                <div class="title">
                                <h4>Student Information</h4>
                                </div>
                            </header>

                            <form method="POST">

                                <main>
                                    <div class="row">
                                        <span>
                                            <label for="name">Name</label>
                                            <div>
                                            <input type="text" name="lastname" id="lastname" value="<?php echo $student_lastname;?>" class="form-control" />
                                            <small>Last name</small>
                                            </div>
                                            <div>
                                            <input type="text" name="firstname" id="firstname" value="<?php echo $student_firstname;?>" class="form-control" />
                                            <small>First name</small>
                                            </div>
                                            <div>
                                            <input type="text" name="middle_name" id="middle_name" value="<?php echo $student_middle_name;?>" class="form-control" />
                                            <small>Middle name</small>
                                            </div>
                                            <div>
                                            <input type="text" name="suffix" id="suffix" value="<?php echo $student_suffix;?>" class="form-control" />
                                            <small>Suffix name</small>
                                            </div>
                                        </span>
                                    </div>

                                    <div class="row">
                                    <span>
                                        <label for="status">Status</label>
                                        <div>
                                        <select name="civil_status" id="civil_status" class="form-control">
                                            <option value="Single"<?php echo ($student_civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                            <option value="Married"<?php echo ($student_civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                            <option value="Divorced"<?php echo ($student_civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                            <option value="Widowed"<?php echo ($student_civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                        </select>
                                        </div>
                                    </span>

                                    <span>
                                        <label for="citizenship">Citizenship</label>
                                        <div>
                                        <input type="text" name="nationality" id="nationality" value="<?php echo $student_citizenship;?>" class="form-control" />
                                        </div>
                                    </span>

                                    <span>
                                        <label for="sex">Gender</label>
                                        <div>
                                        <select name="sex" id="sex" class="form-control">
                                            <option value="Male"<?php echo ($student_gender == "Male") ? " selected" : ""; ?>>Male</option>
                                            <option value="Female"<?php echo ($student_gender == "Female") ? " selected" : ""; ?>>Female</option>
                                        </select>
                                        </div>
                                    </span>
                                    </div>

                                    <div class="row">
                                    <span>
                                        <label for="birthdate">Birthdate</label>
                                        <div>
                                        <input type="date" name="birthday" id="birthday" value="<?php echo $student_birthday;?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="birthplace">Birthplace</label>
                                        <div>
                                        <input type="text" name="birthplace" id="birthplace" value="<?php echo $student_birthplace;?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="religion">Religion</label>
                                        <div>
                                        <input type="text" name="religion" id="religion" value="<?php echo $student_religion;?>" class="form-control" />
                                        </div>
                                    </span>
                                    </div>

                                    <div class="row">
                                    <span>
                                        <label for="address">Address</label>
                                        <div>
                                        <input type="text" name="address" id="address" value="<?php echo $student_address;?>" class="form-control" />
                                        </div>
                                    </span>
                                    </div>

                                    <div class="row">
                                    <span>
                                        <label for="phoneNo">Phone no.</label>
                                        <div>
                                        <input type="text" name="contact_number" id="contact_number" value="<?php echo $student_contact;?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="email">Email</label>
                                        <div>
                                        <input type="email" name="email" id="email" value="<?php echo $student_email;?>" class="form-control" />
                                        </div>
                                    </span>
                                    </div>
                                </main>
                        
                            </form>
                        </div>
                    </main>

                     
                </div>
            <?php

        }

        if(isset($_GET['enrolled_subject']) 
            && $_GET['enrolled_subject'] == "show"){

            if(isset($_POST['subject_load_btn']) 
                && isset($_POST['unique_enrollment_form_id'])
                && isset($_POST['enrollment_payment'])
                ){
            
                $array_success = [];

                $unique_enrollment_form_id = $_POST['unique_enrollment_form_id'];
                $enrollment_payment = $_POST['enrollment_payment'];
 

                // echo $enrollment_payment;
                // return;


                $assignedSubjects = $student_subject->GetStudentAssignSubjects(
                    $enrollment_form_id_url, 
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
                }

                // echo $student_enrollment_course_id;

                // if(true){
                if($isAllFinalized == true){
                     
                    $markAsPaid = $enrollment->EnrollmentFormMarkAsPaid(
                        $current_school_year_id,
                        $student_id,
                        $student_enrollment_form_id,
                        $student_enrollment_waiting_list,
                        $enrollment_payment);

                    if(($markAsPaid) == true){
                        Alert::success("You have successfully mark as Paid Enrollment Form ID: $student_enrollment_form_id.", "index.php");
                        exit();
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
                                    onclick=\"window.location.href = 'payment_summary.php?id=$enrollment_form_id_url&student_details=show';\">
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--mainContentBG); color: white'
                                    onclick=\"window.location.href = 'payment_summary.php?id=$enrollment_form_id_url&enrolled_subject=show';\">
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
                                                <input  class="text-center form-control" type="text" name="sy" id="sy" value="<?php echo $current_school_year_term; ?>" />
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
                                                    <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                                    <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option>
                                                    <!-- <option class="text-center" value="">12</option> -->
                                                </select>
                                            </div>
                                        </span>

                                        <span>
                                            <label for="semester">Semester</label>
                                            <div>
                                                <select class=" form-control" style="pointer-events: none;" name="semester" id="semester">
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
                                    <span style="font-size: 13px; font-weight: bold;" class="mt-0 mb-0 text-right">
                                        <?php 
                                            $student_enrollment_program_id = $section->
                                                GetSectionProgramId($student_enrollment_course_id);

                                            $studentNumberInSection = $section->
                                                GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                                                    $current_school_year_id);

                                            if($student_enrollment_school_year_id == $current_school_year_id
                                                // && $student_enrollment_status !== "enrolled"
                                                ){
                                                echo "Capacity: $studentNumberInSection / $section_capacity";
                                            }
                                        ?>
                                    </span>
                                    <h4><?php echo $enrollment_course_section_name; ?></h4>
                                </div>

                                <?php

                                    // $student_enrollment_program_id = $section->
                                    //     GetSectionProgramId($student_enrollment_course_id);

                                    // $studentNumberInSection = $section->
                                    //     GetTotalNumberOfStudentInSection($student_enrollment_course_id,
                                    //         $current_school_year_id);

                                    // $capacity = $section->GetSectionCapacity();

                                ?>
                            </header>

                            <!-- <span style="font-size: 13px; font-weight: bold;" class="mt-0 mb-0">
                                Capacity:
                                <?php 
                                    echo $updatedTotalStudent;
                                ?> / <?php echo $section_capacity;?>
                            </span> -->

                            <form method="POST">

                                <main>
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
                                    
                                </main>

                                <?php 

                                    $doesStudentEnrolled = $enrollment->CheckStudentEnrolled($enrollment_form_id_url,
                                        $student_enrollment_course_id, $current_school_year_id);

                                    $checkIfCashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($enrollment_form_id_url,
                                        $student_enrollment_course_id, $current_school_year_id);
                                        
                                    $checkIfRegistrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($enrollment_form_id_url,
                                        $student_enrollment_course_id, $current_school_year_id);
                              
                                    // var_dump($checkIfRegistrarEvaluated);

                                    if($checkIfCashierEvaluated == false
                                            && $checkIfRegistrarEvaluated == false
                                            && $doesStudentEnrolled == false){
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

                                    if($checkIfCashierEvaluated == false
                                            && $checkIfRegistrarEvaluated == true
                                            && $doesStudentEnrolled == false){
                                        ?>
                                            <div style="margin-top: 20px;" class="action">

                                                <input type="hidden" name="unique_enrollment_form_id" value="<?php echo $student_enrollment_form_id;?>">

                                                <input style="margin-right: 5px; width: 200px;" 
                                                    class="form-control" type="text"
                                                    maxlength="5" name="enrollment_payment" id="enrollment_payment">
                                                
                                                <button type="submit" name="subject_load_btn" 
                                                    class="default large clean"
                                                    onclick="return confirm('Are you sure to mark this form as paid?')"
                                                    >
                                                    Mark as Paid
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

    function studentRemoveForm(student_id, enrollment_id, school_year_id){

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
                    url: '../../ajax/admission/removeEnrollmentForm.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: `Enrollment Form has been removed..`,
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


</script>

<?php include_once('../../includes/footer.php') ?>

