<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SchoolYear.php');


    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];

        $GRADE_TWELVE = 12;
        $GRADE_ELEVEN = 11;

        $FIRST_YEAR= 1;
        $SECOND_YEAR= 2;
        $THIRD_YEAR= 3;
        $FOURTH_YEAR= 4;

        $FIRST_SEMESTER = "First";
        $SECOND_SEMESTER = "Second";

        $subject_program = new SubjectProgram($con);
        $enrollment = new Enrollment($con);
        $student = new Student($con, $student_id);
        $parent = new StudentParent($con, $student_id);
        $student_subject = new StudentSubject($con);

        $prompIfIDNotExists = $student->CheckIdExists($student_id);
        $raw_type = $student->CheckIfTertiary($student_id);

        $student_level = $student->GetStudentLevel($student_id);
        $student_course_id = $student->GetStudentCurrentCourseId($student_id);

        $section = new Section($con, $student_course_id);

        $section_program_id = $section->GetSectionProgramId($student_course_id);
        $section_acronym = $section->GetAcronymByProgramId($section_program_id);

        $type = $raw_type == 1 ? "Tertiary" : "Senior High School";

        $unique_id = $student->GetStudentUniqueId($student_id);

        $firstname = $student->GetFirstName();
        $middle_name = $student->GetMiddleName();
        $suffix = $student->GetSuffix();
        $lastname = $student->GetLastName();
        $birthday = $student->GetStudentBirthdays();
        $address = $student->GetStudentAddress();
        $sex = $student->GetStudentSex();
        $contact_number = $student->GetContactNumber();
        $student_unique_id = $student->GetStudentUniqueId();

        $email = $student->GetEmail();
        $birthplace = $student->GetStudentBirthPlace();
        $religion = $student->GetReligion();
        $civil_status = $student->GetCivilStatus();
        $nationality = $student->GetNationality();

        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();

        
        $student_program_id = $section->GetSectionProgramId($student_course_id);
        
        $student_enrollment_id = $enrollment->GetEnrollmentId($student_id,
            $student_course_id, $current_school_year_id);

        // echo $student_enrollment_id;

        $enrollment_date = $enrollment->GetStudentEnrollmentDateWithinSemester($student_id, $student_course_id, $current_school_year_id);

        // echo $student_course_id;

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $current_school_year_id, $student_enrollment_id);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $current_school_year_id);

        $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                $student_course_id, $current_school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true 
            && $cashierEvaluated == true 
            && $registrarEvaluated == true){

            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == true 
            && $registrarEvaluated == true){

            $payment_status = "Approved";
            
        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == false 
            && $registrarEvaluated == true){

            $payment_status = "Waiting Payment";
        }
        else if($checkEnrollmentEnrolled == false 
            && $registrarEvaluated == false
            && $cashierEvaluated == false){

            $payment_status = "Evaluation";
        }



        // $enrollmentGrade11FirstSemester = $enrollment->GetStudentSectionGradeLevelSemester(
        //     $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

        // $enrollment_course_id = $enrollmentGrade11FirstSemester['course_id'];
        // $enrollment_date_approved = $enrollmentGrade11FirstSemester['enrollment_approve'];

        // $date = new DateTime($enrollment_date_approved);
        // $enrollment_date_approved = $date->format('m/d/Y');

        // $enrollment_student_status = $enrollmentGrade11FirstSemester['student_status'];

        
        $enrollment_section = new Section($con);

        // $enrollment_section_program_id = $enrollment_section->GetSectionProgramId($enrollment_course_id);
        // $enrollment_section_acronym = $enrollment_section->GetAcronymByProgramId($enrollment_section_program_id);
        // $enrollment_section_level = $enrollment_section->GetSectionGradeLevel($enrollment_course_id);


        $enrollmentRecordDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_ELEVEN, $FIRST_SEMESTER);

        if($enrollmentRecordDetails1 != null){

            $enrollment_date_approved11_1st = $enrollmentRecordDetails1['enrollment_date_approved'];
            $enrollment_section_acronym11_1st = $enrollmentRecordDetails1['enrollment_section_acronym'];
            $enrollment_section_level11_1st = $enrollmentRecordDetails1['enrollment_section_level'];
            $enrollment_period11_1st = $enrollmentRecordDetails1['enrollment_period'];
            $enrollment_student_status11_1st = $enrollmentRecordDetails1['enrollment_student_status'];
        }


        $enrollmentRecordDetails2 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_ELEVEN, $SECOND_SEMESTER);
        
        
        if($enrollmentRecordDetails2 != null){
            $enrollment_date_approved11_2nd = $enrollmentRecordDetails2['enrollment_date_approved'];
            $enrollment_section_acronym11_2nd = $enrollmentRecordDetails2['enrollment_section_acronym'];
            $enrollment_section_level11_2nd = $enrollmentRecordDetails2['enrollment_section_level'];
            $enrollment_period11_2nd = $enrollmentRecordDetails2['enrollment_period'];
            $enrollment_student_status11_2nd = $enrollmentRecordDetails2['enrollment_student_status'];
        }


        $enrollmentRecordDetails3 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_TWELVE, $FIRST_SEMESTER);
        
        
        if($enrollmentRecordDetails3 != null){
            $enrollment_date_approved12_1st = $enrollmentRecordDetails3['enrollment_date_approved'];
            $enrollment_section_acronym12_1st = $enrollmentRecordDetails3['enrollment_section_acronym'];
            $enrollment_section_level12_1st = $enrollmentRecordDetails3['enrollment_section_level'];
            $enrollment_period12_1st = $enrollmentRecordDetails3['enrollment_period'];
            $enrollment_student_status12_1st = $enrollmentRecordDetails3['enrollment_student_status'];
        }


        $enrollmentRecordDetails4 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_TWELVE, $SECOND_SEMESTER);
        
        
        if($enrollmentRecordDetails4 != null){
            $enrollment_date_approved12_2nd = $enrollmentRecordDetails4['enrollment_date_approved'];
            $enrollment_section_acronym12_2nd = $enrollmentRecordDetails4['enrollment_section_acronym'];
            $enrollment_section_level12_2nd = $enrollmentRecordDetails4['enrollment_section_level'];
            $enrollment_period12_2nd = $enrollmentRecordDetails4['enrollment_period'];
            $enrollment_student_status12_2nd = $enrollmentRecordDetails4['enrollment_student_status'];
        }

        
        if(isset($_GET['details']) && $_GET['details'] == "show"){

            ?>
                <div class="content">
                    <nav>
                        <a href="SHS-find-form-evaluation.html"
                        ><i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                        </a>
                    </nav>
                    <div class="content-header">
                        <?php echo Helper::RevealStudentTypePending($type); ?>

                        <header>
                            <div class="title">
                                <h2><?php echo $lastname;?>, <?php echo $firstname;?>, <?php echo $middle_name;?>, <?php echo $suffix;?></h2>
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

                        <!-- <div class="cards">
                            <div class="card">
                                <p class="text-center mb-0">Student No.</p>
                                <p class="text-center"><?php echo $student_unique_id;?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Level</p>
                                <p class="text-center"><?php echo $student_level; ?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0"><?php echo $type == "Tertiary" ? "Course" : ($type == "Senior High School" ? "Strand" : "");?></p>
                                <p class="text-center"><?php echo $section_acronym; ?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Status</p>
                                <p class="text-center"><?php echo $payment_status;?></p>
                            </div>
                            <div class="card">
                                <p class="text-center mb-0">Added on</p>
                                <p class="text-center">
                                    <?php
                                        $date = new DateTime($enrollment_date);
                                        $formattedDate = $date->format('m/d/Y');
                                        echo $formattedDate;
                                    ?>
                                </p>
                            </div>
                        </div> -->

                        <?php echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                            $type, $section_acronym, $payment_status,
                            $enrollment_date);?>
                    </div>

                    <div class="tabs">

                        <?php
                            echo "
                                <button class='tab' 
                                    style='background-color: var(--mainContentBG)'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                                    <i class='bi bi-clipboard-check'></i>
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                                    <i class='bi bi-book'></i>
                                    Grade Records
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                                    <i class='bi bi-collection icon'></i>
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>

                    <main>
                        <div class="floating">
                            <header>
                                <div class="title">
                                <h3>Student form details</h3>
                                <small
                                    >Assure every student information in this section. This will be
                                    the student data.</small
                                >
                                </div>
                            </header>
                
                            <header>
                                <div class="title">
                                <h3>Student Information</h3>
                                </div>
                            </header>

                            <main>
                                <form action="">
                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input type="text" name="lastName" 
                                            id="lastName" value="<?php echo $firstname;?>" />
                                        <small></small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="firstName"
                                        id="firstName"
                                        value="<?php echo $firstname;?>"
                                        />
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="middleName"
                                        id="middleName"
                                        value="<?php echo $middle_name;?>"
                                        />
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="suffixName"
                                        id="suffixName"
                                        value="<?php echo $suffix;?>"
                                        />
                                        <small>Suffix name</small>
                                    </div>
                                    </span>
                                </div>

                                <div class="row">
                                    <span>
                                    <label for="status">Status</label>
                                        <div>
                                            <select name="status" id="status">
                                                <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                            </select>
                                        </div>
                                    </span>

                                    <span>
                                        <label for="citizenship">Citizenship</label>
                                        <div>
                                            <input
                                            type="text"
                                            name="citizenship"
                                            id="citizenship"
                                            value="<?php echo $nationality;?>"
                                            />
                                        </div>
                                    </span>

                                    <span>
                                    <label for="gender">Gender</label>
                                    <div>
                                        <select name="gender" id="gender">
                                        <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                                            <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                        </select>
                                    </div>
                                    </span>
                                </div>

                                <div class="row">
                                    <span>
                                        <label for="birthdate">Birthdate</label>
                                        <div>
                                            <input
                                            type="date"
                                            name="birthdate"
                                            id="birthdate"
                                            value="<?php echo $birthday;?>"
                                            />
                                        </div>
                                    </span>
                                        <span>
                                            <label for="birthplace">Birthplace</label>
                                            <div>
                                                <input
                                                type="text"
                                                name="birthplace"
                                                id="birthplace"
                                                value="<?php echo $birthplace;?>"
                                                />
                                            </div>
                                        </span>
                                    <span>
                                        <label for="religion">Religion</label>
                                        <div>
                                            <input type="text" name="religion" id="religion" value="<?php echo $religion;?>" />
                                        </div>
                                    </span>

                                </div>
                                <div class="row">
                                    <span>

                                    <label for="address">Address</label>
                                    <div>
                                        <input type="text" name="address" id="address" value="<?php echo $address;?>" />
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="phoneNo">Phone no.</label>
                                    <div>
                                        <input type="text" name="phone" id="phone" value="<?php echo $contact_number;?>" />
                                    </div>
                                    </span>
                                    <span>
                                    <label for="email">Email</label>
                                    <div>
                                        <input type="email" name="email" id="email" value="<?php echo $email;?>" />
                                    </div>
                                    </span>
                                </div>
                                </form>
                            </main>

                            <header>
                                <div class="title">
                                <h4>Guardian's Information</h4>
                                </div>
                            </header>


                            <main>
                                <form action="">
                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianLN"
                                        id="guardianLN"
                                        value="<?php echo $parent_lastname;?>"
                                        />
                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianFN"
                                        id="guardianFN"
                                        value="<?php echo $parent_firstname;?>"
                                        />
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianMN"
                                        id="guardianMN"
                                        value="<?php echo $parent_middle_name;?>"
                                        />
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianSN"
                                        id="guardianSN"
                                        value="<?php echo $parent_suffix;?>"
                                        />
                                        <small>Suffix name</small>
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="phoneNo">Phone no.</label>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianPhone"
                                        id="guardianPhone"
                                        value="<?php echo $parent_contact_number;?>"
                                        />
                                    </div>
                                    </span>
                                    <span>
                                    <label for="email">Email</label>
                                    <div>
                                        <input
                                        type="email"
                                        name="guardianEmail"
                                        id="guardianEmal"
                                        value="<?php echo $parent_email;?>"
                                        />
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="relationship">Relationship</label>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianRelation"
                                        id="guardianRelation"
                                        value=""
                                        />
                                    </div>
                                    </span>
                                    <span>
                                    <label for="occupation">Occupation</label>
                                    <div>
                                        <input
                                        type="text"
                                        name="guardianOccupation"
                                        id="guardianOccupation"
                                        value="<?php echo $parent_occupation;?>"
                                        />
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

        if(isset($_GET['grade_records']) && $_GET['grade_records'] == "show"){

            ?>

                <div class="content">

                    <nav>
                        <a href="SHS-find-form-evaluation.html"
                        ><i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                        </a>
                    </nav>

                    <div class="content-header">
                        <?php echo Helper::RevealStudentTypePending($type); ?>

                        <header>
                            <div class="title">
                                <h2><?php echo $student->GetLastName();?>, <?php echo $student->GetFirstName();?>, <?php echo $student->GetMiddleName();?>, <?php echo $student->GetSuffix();?></h2>
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

                        <?php echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                            $type, $section_acronym, $payment_status,
                            $enrollment_date);?>
                        
                    </div>

                    <div class="tabs">

                        <?php
                            echo "
                                <button class='tab' 
                                    style='background-color: var(--them)'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                                    
                                    <i class='bi bi-clipboard-check'></i>
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--mainContentBG); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                                    <i class='bi bi-book'></i>
                                    Grade Records
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                                    <i class='bi bi-collection icon'></i>
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>

                    <main>
                        <!-- Grade Records Requirement -->
                        <!-- 1. Passed -->
                        <!-- 2. Failed -->
                        <!-- Some of subjects were passed, but some were fao;  -->


                        <!-- GRADE 11 1st Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

                                if($enrollment_school_year !== null){

                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    // Grade 11 $enrollment_section_name $period Semester (SY $term)
                                    
                                    echo "
                                        <header>
                                            <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 11 1st Semester
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }
                            ?>
                            
                            <main>
                              
                                <div style="
                                    display: flex;
                                    justify-content: space-around;
                                    gap: 10px;
                                    flex-wrap: wrap;
                                    text-align: center" class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_period11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_section_level11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_section_acronym11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_student_status11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_date_approved11_1st ?? "-";?></p>
                                    </div>

                                </div>
                                
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th rowspan="2">Unit</th>
                                            <th>Section</th>  
                                            <th>Prelim</th>  
                                            <th>Midterm</th>  
                                            <th>Pre-Final</th>  
                                            <th>Final</th>  
                                            <th>Average</th>  
                                            <th >Remarks</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            // $listOfSubjects = $subject_program
                                            //     ->GetStudentCurriculumBasedOnSemesterSubject($student_program_id,
                                            //         $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

                                            // if($listOfSubjects !== null){
 
                                            //     foreach ($listOfSubjects as $key => $value) {
                                                    
                                            //         $subject_id = $value['subject_id'];
                                            //         $course_id = $value['course_id'];
                                            //         $course_level = $value['course_level'];

                                            //         $schedule_day = $value['schedule_day'];
                                            //         $schedule_time = $value['schedule_time'];
                                            //         $time_from = $value['time_from'];
                                            //         $time_to = $value['time_to'];

                                            //         $remarks_url = "Pending";

                                            //         $subject_title = $value['subject_title'];


                                            //         $check = $student_subject->CheckAlreadyCreditedSubject($student_id,
                                            //             $subject_title);

                                            //         $credited = "";
                                                    
                                            //         if($check){
                                            //             $credited = "Credited";
                                            //         }

                                            //         if($remarks_url == ""){
                                            //             $remarks_url = $credited;
                                            //         }


                                            //         $query_student_subject = $con->prepare("SELECT 

                                            //             t1.subject_id, t1.student_subject_id AS t1_student_subject_id,
                                                        
                                                        
                                            //             t2.student_subject_id AS t2_student_subject_id,
                                            //             t2.remarks,

                                            //             t3.subject_code AS taken_subject_code,
                                            //             t4.program_section AS taken_subject_section

                                            //             FROM student_subject AS t1

                                            //             LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id
                                            //             LEFT JOIN subject as t3 ON t3.subject_id = t1.subject_id
                                            //             LEFT JOIN course as t4 ON t4.course_id = t3.course_id

                                            //             WHERE t1.subject_id=:subject_id
                                            //             AND t1.student_id=:student_id
                                            //             LIMIT 1");

                                            //         $query_student_subject->bindValue(":subject_id", $subject_id);
                                            //         $query_student_subject->bindValue(":student_id", $student_id);
                                            //         $query_student_subject->execute();

                                            //         $t1_student_subject_id = null;

                                            //         $taken_subject_section = "";
                                            //         $taken_subject_code = "";

                                            //             // echo $subject_id . " 1 ";
                                            //         if($query_student_subject->rowCount() > 0){

                                            //             $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                            //             $student_subject_subject_id = $row['subject_id'];
                                            //             $t1_student_subject_id = $row['t1_student_subject_id'];
                                            //             $t2_student_subject_id = $row['t2_student_subject_id'];
                                            //             $remarks = $row['remarks'];

                                            //             $taken_subject_section = $row['taken_subject_section'];
                                            //             $taken_subject_code = $row['taken_subject_code'];

                                            //             if($t1_student_subject_id == $t2_student_subject_id){

                                            //                 $remarks_url = $remarks;
                                            //                 // echo "we";
                                            //             }

                                            //             else if($student_subject_subject_id == $subject_id
                                            //                 && $t1_student_subject_id != $t2_student_subject_id 
                                            //                 && $payment_status == "Enrolled"
                                            //                 ){

                                            //                 $markAsPassed = "MarkAsPassed($subject_id,
                                            //                     $student_id, \"Passed\",
                                            //                     $t1_student_subject_id, $course_id, \"$subject_title\")";

                                            //                 $remarks_url = "
                                            //                     <i style='color:blue; cursor:pointer;' onclick='$markAsPassed' class='fas fa-marker'></i>
                                            //                 ";
                                            //             }
                                            //         }

                                            //         echo '<tr class="text-center">'; 
                                            //                 echo '<td>'.$taken_subject_code.'</td>';
                                            //                 echo '<td>'.$value['subject_title'].'</td>';
                                            //                 echo '<td>'.$value['unit'].'</td>';
                                            //                 echo '<td>'.$taken_subject_section.'</td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td>'.$remarks_url.'</td>';
                                            //         echo '</tr>';
                                            //     }     
                                            // }


                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_ELEVEN, $FIRST_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                
                                                $remarks_url = "";

                                                if ($student_subject_code != null) {
                                                    $subject_code = $student_subject_code;

                                                    if ($student_subject_id != $graded_student_subject_id 
                                                            && $checkEnrollmentEnrolled == true) {

                                                        $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";
                                                        
                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' 
                                                            onclick='$remarkAsPassed' class='fas fa-marker'></i>
                                                        ";
                                                    }
                                                    if ($student_subject_id == $graded_student_subject_id) {

                                                        $remarks_url = "
                                                            Passed
                                                        ";
                                                    }
                                                }


                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </main>

                        </div>


                        <!-- GRADE 11 2nd Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    echo "
                                        <header>
                                           <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 11 2nd Semester
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }
                                ?>
                            
                            <main>

                                <div style="
                                        display: flex;
                                        justify-content: space-around;
                                        gap: 10px;
                                        flex-wrap: wrap;
                                        text-align: center"
                                    class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_period11_2nd ?? "-"?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_section_level11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_section_acronym11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_student_status11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_date_approved11_2nd ?? "-";?></p>
                                    </div>

                                </div>
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Unit</th>
                                            <th>Section</th>  
                                            <th>Prelim</th>  
                                            <th>Midterm</th>  
                                            <th>Pre-Final</th>  
                                            <th>Final</th>  
                                            <th>Average</th>  
                                            <th >Remarks</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            // $listOfSubjects = $subject_program
                                            //     ->GetStudentCurriculumBasedOnSemesterSubject($student_program_id,
                                            //         $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

                                            

                                            // if($listOfSubjects !== null){
 
                                            //     foreach ($listOfSubjects as $key => $value) {
                                                    
                                            //         $subject_id = $value['subject_id'];
                                            //         $course_id = $value['course_id'];
                                            //         $course_level = $value['course_level'];
                                            //         $subject_code = $value['subject_code'];

                                            //         $schedule_day = $value['schedule_day'];
                                            //         $schedule_time = $value['schedule_time'];
                                            //         $time_from = $value['time_from'];
                                            //         $time_to = $value['time_to'];

                                            //         $remarks_url = "Pending";

                                            //         $subject_title = $value['subject_title'];


                                            //         $check = $student_subject->CheckAlreadyCreditedSubject($student_id,
                                            //             $subject_title);

                                            //         $credited = "";
                                                    
                                            //         if($check){
                                            //             $credited = "Credited";
                                            //         }

                                            //         if($remarks_url == ""){
                                            //             $remarks_url = $credited;
                                            //         }


                                            //         $query_student_subject = $con->prepare("SELECT 

                                            //             t1.subject_id, t1.student_subject_id AS t1_student_subject_id,
                                                        
                                                        
                                            //             t2.student_subject_id AS t2_student_subject_id,
                                            //             t2.remarks,

                                            //             t3.subject_code AS taken_subject_code,
                                            //             t4.program_section AS taken_subject_section

                                            //             FROM student_subject AS t1

                                            //             LEFT JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id
                                            //             LEFT JOIN subject as t3 ON t3.subject_id = t1.subject_id
                                            //             LEFT JOIN course as t4 ON t4.course_id = t3.course_id

                                            //             WHERE t1.subject_id=:subject_id
                                            //             AND t1.student_id=:student_id
                                            //             LIMIT 1");

                                            //         $query_student_subject->bindValue(":subject_id", $subject_id);
                                            //         $query_student_subject->bindValue(":student_id", $student_id);
                                            //         $query_student_subject->execute();

                                            //         $t1_student_subject_id = null;

                                            //         $taken_subject_section = "";
                                            //         $taken_subject_code = "";

                                                    // if($query_student_subject->rowCount() > 0){

                                                    //     $row = $query_student_subject->fetch(PDO::FETCH_ASSOC);

                                                    //     $student_subject_subject_id = $row['subject_id'];
                                                    //     $t1_student_subject_id = $row['t1_student_subject_id'];
                                                    //     $t2_student_subject_id = $row['t2_student_subject_id'];
                                                    //     $remarks = $row['remarks'];

                                                    //     $taken_subject_section = $row['taken_subject_section'];
                                                    //     $taken_subject_code = $row['taken_subject_code'];

                                                    //     if($t1_student_subject_id == $t2_student_subject_id){

                                                    //         $remarks_url = $remarks;
                                                    //         // echo "we";
                                                    //     }

                                                    //     else if($student_subject_subject_id == $subject_id
                                                    //         && $t1_student_subject_id != $t2_student_subject_id 
                                                    //         && $payment_status == "Enrolled"
                                                    //         ){

                                                    //         $markAsPassed = "MarkAsPassed($subject_id,
                                                    //             $student_id, \"Passed\",
                                                    //             $t1_student_subject_id, $course_id, \"$subject_title\")";

                                                    //         $remarks_url = "
                                                    //             <i style='color:blue; cursor:pointer;' 
                                                    //             onclick='$markAsPassed' class='fas fa-marker'></i>
                                                    //         ";
                                                    //     }
                                                    // }

                                            //         if($taken_subject_code == ""){
                                            //             //  IF not actual subject code coming from section subjects
                                            //             // then, the curriculum subject code would be displayed.
                                            //             $taken_subject_code = $subject_code;
                                            //         }

                                            //         echo '<tr class="text-center">'; 
                                            //                 echo '<td>'.$taken_subject_code.'</td>';
                                            //                 echo '<td>'.$value['subject_title'].'</td>';
                                            //                 echo '<td>'.$value['unit'].'</td>';
                                            //                 echo '<td>'.$taken_subject_section.'</td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td></td>';
                                            //                 echo '<td>'.$remarks_url.'</td>';
                                            //         echo '</tr>';

                                            //     }     
                                            // }


                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_ELEVEN, $SECOND_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                
                                                $remarks_url = "";


                                                $db_enrollment_id = $value['enrollment_id'];
                                                $db_is_transferee = $value['is_transferee'];

                                                if($db_enrollment_id == NULL && $db_is_transferee == 1){
                                                    $remarks_url = "Credited";
                                                    $program_section=  "-";
                                                }

                                                # DFF

                                                if ($student_subject_code != null) {
                                                    $subject_code = $student_subject_code;

                                
                                                    if ($student_subject_id != $graded_student_subject_id 
                                                            && $checkEnrollmentEnrolled == true) {

                                                        $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";
                                                        
                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' 
                                                            onclick='$remarkAsPassed' class='fas fa-marker'></i>
                                                        ";
                                                    }
                                                    if ($student_subject_id == $graded_student_subject_id) {

                                                        $remarks_url = "
                                                            Passed
                                                        ";
                                                    }
                                                }




                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </main>


                        </div>

                        <!-- GRADE 12 1st Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_TWELVE, $FIRST_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    echo "
                                        <header>
                                           <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 12 First Semester
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }
                                ?>
                            
                            <main>

                                <div style="
                                        display: flex;
                                        justify-content: space-around;
                                        gap: 10px;
                                        flex-wrap: wrap;
                                        text-align: center"
                                    class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_period12_1st ?? "-"?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_period12_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_section_acronym12_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_student_status12_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_period12_1st ?? "-";?></p>
                                    </div>

                                </div>
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Unit</th>
                                            <th>Section</th>  
                                            <th>Prelim</th>  
                                            <th>Midterm</th>  
                                            <th>Pre-Final</th>  
                                            <th>Final</th>  
                                            <th>Average</th>  
                                            <th >Remarks</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_TWELVE, $FIRST_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                
                                                $remarks_url = "";

                                                if ($student_subject_code != null) {
                                                    $subject_code = $student_subject_code;

                                                    if ($student_subject_id != $graded_student_subject_id 
                                                            && $checkEnrollmentEnrolled == true) {

                                                        $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";
                                                        
                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' 
                                                            onclick='$remarkAsPassed' class='fas fa-marker'></i>
                                                        ";
                                                    }
                                                    if ($student_subject_id == $graded_student_subject_id) {

                                                        $remarks_url = "
                                                            Passed
                                                        ";
                                                    }
                                                }


                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </main>


                        </div>


                         <!-- GRADE 12 2nd Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_TWELVE, $SECOND_SEMESTER);

                                if($enrollment_school_year !== null){
                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    echo "
                                        <header>
                                           <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 12 Second Semester
                                                </h4>
                                            </div>
                                        </header>
                                     
                                    ";
                                }
                                ?>
                            
                            <main>

                                <div style="
                                        display: flex;
                                        justify-content: space-around;
                                        gap: 10px;
                                        flex-wrap: wrap;
                                        text-align: center"
                                    class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_period12_2nd ?? "-"?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_period12_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_section_acronym12_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_student_status12_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_period12_2nd ?? "-";?></p>
                                    </div>

                                </div>
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Unit</th>
                                            <th>Section</th>  
                                            <th>Prelim</th>  
                                            <th>Midterm</th>  
                                            <th>Pre-Final</th>  
                                            <th>Final</th>  
                                            <th>Average</th>  
                                            <th >Remarks</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_TWELVE, $SECOND_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                
                                                $remarks_url = "";

                                                if ($student_subject_code != null) {
                                                    $subject_code = $student_subject_code;

                                                    if ($student_subject_id != $graded_student_subject_id 
                                                            && $checkEnrollmentEnrolled == true) {

                                                        $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";
                                                        
                                                        $remarks_url = "
                                                            <i style='color:blue; cursor:pointer;' 
                                                            onclick='$remarkAsPassed' class='fas fa-marker'></i>
                                                        ";
                                                    }
                                                    if ($student_subject_id == $graded_student_subject_id) {

                                                        $remarks_url = "
                                                            Passed
                                                        ";
                                                    }
                                                }


                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td></td>';
                                                echo '<td>'.$remarks_url.'</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </main>


                        </div>
 

                    </main>
                </div>
            <?php
        }

        if(isset($_GET['enrolled_subject']) && $_GET['enrolled_subject'] == "show"){



            $enrollmentEnrolledSubDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
                $GRADE_ELEVEN, $FIRST_SEMESTER);

                
                // EE
            if($enrollmentEnrolledSubDetails1 != null){

                $enrollment_es_date_approved11_1st = $enrollmentEnrolledSubDetails1['enrollment_date_approved'];
                $enrollment_es_section_acronym11_1st = $enrollmentEnrolledSubDetails1['enrollment_section_acronym'];
                $enrollment_es_section_level11_1st = $enrollmentEnrolledSubDetails1['enrollment_section_level'];
                $enrollment_es_period11_1st = $enrollmentEnrolledSubDetails1['enrollment_period'];
                $enrollment_es_student_status11_1st = $enrollmentEnrolledSubDetails1['enrollment_student_status'];
            }


            $enrollmentEnrolledSubDetails2 = $enrollment->getEnrollmentSectionDetails($student_id,
                $GRADE_ELEVEN, $SECOND_SEMESTER);
            
            
            if($enrollmentEnrolledSubDetails2 != null){
                $enrollment_es_date_approved11_2nd = $enrollmentEnrolledSubDetails2['enrollment_date_approved'];
                $enrollment_es_section_acronym11_2nd = $enrollmentEnrolledSubDetails2['enrollment_section_acronym'];
                $enrollment_es_section_level11_2nd = $enrollmentEnrolledSubDetails2['enrollment_section_level'];
                $enrollment_es_period11_2nd = $enrollmentEnrolledSubDetails2['enrollment_period'];
                $enrollment_es_student_status11_2nd = $enrollmentEnrolledSubDetails2['enrollment_student_status'];
            }


            $enrollmentEnrolledSubDetails3 = $enrollment->getEnrollmentSectionDetails($student_id,
                $GRADE_TWELVE, $FIRST_SEMESTER);
            
            
            if($enrollmentEnrolledSubDetails3 != null){
                $enrollment_es_date_approved12_1st = $enrollmentEnrolledSubDetails3['enrollment_date_approved'];
                $enrollment_es_section_acronym12_1st = $enrollmentEnrolledSubDetails3['enrollment_section_acronym'];
                $enrollment_es_section_level12_1st = $enrollmentEnrolledSubDetails3['enrollment_section_level'];
                $enrollment_es_period12_1st = $enrollmentEnrolledSubDetails3['enrollment_period'];
                $enrollment_es_student_status12_1st = $enrollmentEnrolledSubDetails3['enrollment_student_status'];
            }


            $enrollmentEnrolledSubDetails4 = $enrollment->getEnrollmentSectionDetails($student_id,
                $GRADE_TWELVE, $SECOND_SEMESTER);
            
            
            if($enrollmentEnrolledSubDetails4 != null){
                $enrollment_es_date_approved12_2nd = $enrollmentEnrolledSubDetails4['enrollment_date_approved'];
                $enrollment_es_section_acronym12_2nd = $enrollmentEnrolledSubDetails4['enrollment_section_acronym'];
                $enrollment_es_section_level12_2nd = $enrollmentEnrolledSubDetails4['enrollment_section_level'];
                $enrollment_es_period12_2nd = $enrollmentEnrolledSubDetails4['enrollment_period'];
                $enrollment_es_student_status12_2nd = $enrollmentEnrolledSubDetails4['enrollment_student_status'];
            }

            ?>

                <div class="content">
                    <nav>
                        <a href="SHS-find-form-evaluation.html"
                        ><i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                        </a>
                    </nav>
                    <div class="content-header">
                        <?php echo Helper::RevealStudentTypePending($type); ?>

                        <header>
                            <div class="title">
                                <h2><?php echo $student->GetLastName();?>, <?php echo $student->GetFirstName();?>, <?php echo $student->GetMiddleName();?>, <?php echo $student->GetSuffix();?></h2>
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
                        
                        <?php echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                            $type, $section_acronym, $payment_status,
                            $enrollment_date);?>
                    </div>

                    <div class="tabs">

                        <?php
                            echo "
                                <button class='tab' 
                                    style='background-color: var(--them)'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                                    <i class='bi bi-clipboard-check'></i>
                                    Student Details
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--them); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                                    <i class='bi bi-book'></i>
                                    Grade Records
                                </button>
                            ";

                            echo "
                                <button class='tab' 
                                    id='shsPayment'
                                    style='background-color: var(--mainContentBG); color: white'
                                    onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                                    <i class='bi bi-collection icon'></i>
                                    Enrolled Subjects
                                </button>
                            ";
                        ?>
                    </div>
                            
                    <main>

                        <!-- GRADE 11 1st Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

                                if($enrollment_school_year !== null){

                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    // Grade 11 $enrollment_section_name $period Semester (SY $term)
                                    
                                    echo "
                                        <header>
                                            <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                    
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 11 First Semester
                                                </h4>
                                            </div>
                                        </header>
                                    
                                    ";
                                }
                            ?>
                            
                            <main>
                            
                                <div style="
                                    display: flex;
                                    justify-content: space-around;
                                    gap: 10px;
                                    flex-wrap: wrap;
                                    text-align: center" class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_es_period11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_es_section_level11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_es_section_acronym11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_es_student_status11_1st ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_es_date_approved11_1st ?? "-";?></p>
                                    </div>

                                </div>
                                
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Unit</th>
                                            <th>Section</th>  
                                            <th>Days</th>  
                                            <th>Time</th>  
                                            <th>Room</th>  
                                            <th>Instructor</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_ELEVEN, $FIRST_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                $time_from = $value['time_from'];
                                                $time_to = $value['time_to'];
                                                $schedule_day = $value['schedule_day'];
                                                $schedule_time = $value['schedule_time'];
                                                $room = $value['room'];

                                                $room = $value['room'];

                                                $teacher_firstname = $value['firstname'];
                                                $teacher_lastname = $value['lastname'];

                                                $instructor_name = "N/A";

                                                if($teacher_firstname != null){
                                                    $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                                }


                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td>'.$schedule_day.'</td>';
                                                echo '<td>'.$schedule_time.'</td>';
                                                echo '<td>'.$room.'</td>';
                                                echo '<td>'.$instructor_name.'</td>';
                                                echo '</tr>';
                                            }

                                        ?>
                                    </tbody>
                                </table>

                            </main>

                        </div>

                        <!-- GRADE 11 2nd Semester -->
                        <div class="floating">

                            <?php 

                                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                                    $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

                                if($enrollment_school_year !== null){

                                    $term = $enrollment_school_year['term'];
                                    $period = $enrollment_school_year['period'];
                                    $school_year_id = $enrollment_school_year['school_year_id'];
                                    $enrollment_course_id = $enrollment_school_year['course_id'];

                                    $section = new Section($con, $enrollment_course_id);
                                    $enrollment_course_level = $section->GetSectionGradeLevel();

                                    $enrollment_section_name = $section->GetSectionName();
                                    
                                    // Grade 11 $enrollment_section_name $period Semester (SY $term)
                                    
                                    echo "
                                        <header>
                                            <div class='title'>
                                                <h4 class='text-info'>
                                                    SY $term
                                                </h4>
                                            </div>
                                        </header>
                                    
                                    ";
                                }else{
                                    echo "
                                        <header>
                                            <div class='col-md-12' class='title'>
                                                <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'>To be taken</p>
                                                <h4 class='text-muted'>
                                                    Grade 11 Second Semester
                                                </h4>
                                            </div>
                                        </header>
                                    
                                    ";
                                }
                            ?>
                            
                            <main>
                            
                                <div style="
                                    display: flex;
                                    justify-content: space-around;
                                    gap: 10px;
                                    flex-wrap: wrap;
                                    text-align: center" class="cards">
                                    <div class="card">
                                        <p>Semester</p>
                                        <p><?php echo $enrollment_es_period11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Grade level</p>
                                        <p><?php echo $enrollment_es_section_level11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Strand</p>
                                        <p><?php echo $enrollment_es_section_acronym11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Scholastic Status</p>
                                        <p><?php echo $enrollment_es_student_status11_2nd ?? "-";?></p>
                                    </div>
                                    <div class="card">
                                        <p>Added</p>
                                        <p><?php echo $enrollment_es_date_approved11_2nd ?? "-";?></p>
                                    </div>

                                </div>
                                
                                <table class="a">
                                    <thead>
                                        <tr> 
                                            <th>Subject</th>  
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Unit</th>
                                            <th>Section</th>  
                                            <th>Days</th>  
                                            <th>Time</th>  
                                            <th>Room</th>  
                                            <th>Instructor</th>  
                                        </tr>
                                    </thead> 	
                                    <tbody>
                                        <?php 

                                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubject($student_program_id, $student_id,
                                                $GRADE_ELEVEN, $SECOND_SEMESTER);

                                            foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                                $course_id = $value['course_id'];
                                                $course_level = $value['course_level'];
                                                $subject_code = $value['subject_code'];
                                                $subject_title = $value['subject_title'];
                                                $unit = $value['unit'];
                                                $program_section = $value['program_section'];
                                                $subject_type = $value['subject_type'];

                                                $db_enrollment_id = $value['enrollment_id'];
                                                $db_is_transferee = $value['is_transferee'];

                                                $student_subject_code = $value['student_subject_code'];

                                                $student_subject_id = $value['student_subject_id'];

                                                $graded_student_subject_id = $value['graded_student_subject_id'];


                                                $time_from = $value['time_from'];
                                                $time_to = $value['time_to'];
                                                $schedule_day = $value['schedule_day'];
                                                $schedule_time = $value['schedule_time'];
                                                $room = $value['room'];

                                                $room = $value['room'];

                                                $teacher_firstname = $value['firstname'];
                                                $teacher_lastname = $value['lastname'];

                                                $instructor_name = "N/A";

                                                if($teacher_firstname != null){
                                                    $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                                }

                                                if($db_enrollment_id == NULL && $db_is_transferee == 1){
                                                    $program_section = "-";
                                                    $schedule_day = "-";
                                                    $schedule_time = "-";
                                                    $room = "-";
                                                    $instructor_name = "-";
                                                }


                                                echo '<tr class="text-center">';
                                                echo '<td>'.$subject_title.'</td>';
                                                echo '<td>'.$subject_code.'</td>';
                                                echo '<td>'.$subject_type.'</td>';
                                                echo '<td>'.$unit.'</td>';
                                                echo '<td>'.$program_section.'</td>';
                                                echo '<td>'.$schedule_day.'</td>';
                                                echo '<td>'.$schedule_time.'</td>';
                                                echo '<td>'.$room.'</td>';
                                                echo '<td>'.$instructor_name.'</td>';
                                                echo '</tr>';
                                            }

                                        ?>
                                    </tbody>
                                </table>

                            </main>

                        </div>


                    </main>
                </div>
            <?php
        }
    }


?>

<script>

    // 
    function MarkAsPassed(subject_id, student_id, remarks,
        student_subject_id, course_id, subject_title){

        // console.log('click');

        $.post('../../ajax/subject/grading_temporary.php', {
            student_id,
            subject_id,
            remarks,
            student_subject_id, 
            course_id,
            subject_title

        }).done(function (data) {
            // console.log(data)
            Swal.fire({
                icon: 'success',
                title: `Subject: ${subject_title} remarked as Passed.`,
                showConfirmButton: false,
                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                toast: true,
                position: 'top-end',
                showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
                },
                hideClass: {
                popup: '',
                backdrop: ''
                }
            }).then((result) => {
                location.reload();
            });
            
        });
    }

    function RemarkAsPassed(student_subject_id, student_id, remarks, subject_title){

        console.log('click');

        $.post('../../ajax/subject/grading_temporary.php', {
           student_subject_id, student_id, remarks

        }).done(function (data) {
            // console.log(data)
            Swal.fire({
                icon: 'success',
                title: `Subject: ${subject_title} remarked as Passed.`,
                showConfirmButton: false,
                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                toast: true,
                position: 'top-end',
                showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
                },
                hideClass: {
                popup: '',
                backdrop: ''
                }
            }).then((result) => {
                location.reload();
            });
            
        });
    }
</script>