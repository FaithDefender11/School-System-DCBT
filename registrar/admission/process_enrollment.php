<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/EnrollmentAudit.php');
    include_once('../../includes/classes/User.php');


    ?>
    <style>
        .dropdown-menu.show{
            margin-left: -100px;
        }
    </style>
    <?php

    $department = new Department($con);
    $school_year = new SchoolYear($con, null);
    $student = new Student($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $department_id = $department->GetDepartmentIdByName("Senior High School");

    $shs_department_id = $department->GetDepartmentIdByName("Senior High School");
    $tertiary_department_id = $department->GetDepartmentIdByName("Tertiary");
    
    $now = date("Y-m-d H:i:s");

    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        $pending = new Pending($con, $pending_enrollees_id);

        $pending_level = $pending->GetCourseLevel();
        $pending_type = $pending->GetPendingType();

        // echo $pending_level;

        // unset($_SESSION['pending_enrollees_id']);
        // unset($_SESSION['process_enrollment']);

        $enrollment = new Enrollment($con);
        $section = new Section($con);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId($current_school_year_id);

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId($current_school_year_id);
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
            
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $pending_query = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
                AND student_status = 'EVALUATION'
            ");

        $pending_query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $pending_query->execute();


        $row = null;

        $course_id = 0;

        if($pending_query->rowCount() > 0){

            $row = $pending_query->fetch(PDO::FETCH_ASSOC);
            $pending_enrollees_id = $row['pending_enrollees_id'];

            $get_parent = $con->prepare("SELECT * FROM parent
                WHERE pending_enrollees_id=:pending_enrollees_id");
        
            $get_parent->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $get_parent->execute();

            $parent_firstname = "";
            $parent_lastname = "";
            $parent_middle_name = "";
            $parent_contact_number = "";
            $parent_email = "";
            $parent_occupation = "";
            $parent_suffix = "";

            if($get_parent->rowCount() > 0){

                $rowParnet = $get_parent->fetch(PDO::FETCH_ASSOC);

                $parent_id = $rowParnet['parent_id'];
                $parent_firstname = $rowParnet['firstname'];
                $parent_lastname = $rowParnet['lastname'];
                $parent_middle_name = $rowParnet['middle_name'];
                $parent_contact_number = $rowParnet['contact_number'];
                $parent_occupation = $rowParnet['occupation'];
                $parent_suffix = $rowParnet['suffix'];
                $parent_email = $rowParnet['email'];
            }

            $program_id = $row['program_id'];

            $firstname = $row['firstname'];
            $middle_name = $row['middle_name'];
            $suffix = $row['suffix'];
            $lastname = $row['lastname'];
            $birthday = $row['birthday'];
            $address = $row['address'];
            $sex = $row['sex'];
            $contact_number = $row['contact_number'];
            $date_creation = $row['date_creation'];
            $student_status = $row['student_status'];
            $email = $row['email'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $password = $row['password'];
            $civil_status = $row['civil_status'];
            $nationality = $row['nationality'];
            // $age = $row['age'];
            // $guardian_name = $row['guardian_name'];
            // $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $birthplace = $row['birthplace'];
            $religion = $row['religion'];
            $email = $row['email'];
            $type = $row['type'];
            $admission_status = $row['admission_status'];
            $student_status = $row['student_status'];
            $enrollee_enrollment_status = $row['enrollment_status'];
            $pending_course_level = $row['course_level'];
            $new_enrollee_enrollment_status = $row['enrollment_status'];
            $new_enrollee_program_id = $row['program_id'];

            // var_dump($new_enrollee_program_id);

            $program = new Program($con, $new_enrollee_program_id);

            $enrollee_department_id = $program->GetProgramDepartmentId();

            $department = new Department($con, $enrollee_department_id);

            $enrollee_department_name = $department->GetDepartmentName();

            // var_dump($enrollee_department_name);

            // echo $new_enrollee_enrollment_status;    

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");

            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();


            $student_fullname = $firstname . " " . $lastname;

            $section = new Section($con, null);

            // $program_id = $section->GetProgramIdBySectionId($student_course_id);
            $strand_name = $section->GetAcronymByProgramId($program_id);
            $track_name = $section->GetTrackByProgramId($program_id);
    
            if(isset($_GET['id']) && isset($_GET['enrollee_details'])){
                include("./enrollee_details.php");
            }

            if(isset($_GET['id']) && isset($_GET['enrollee_find_section'])){

                include("./enrollee_find_section.php");
            }

            if(isset($_GET['step3']) && isset($_GET['st_id']) 
                && isset($_GET['selected_course_id'])){

                $selected_course_id = $_GET['selected_course_id'];
                $student_id = $_GET['id'];

                //  $student_id = 540;

                // echo $student_id;

                $section = new Section($con, $selected_course_id);

                $section_name = $section->GetSectionName();
                $section_course_level = $section->GetSectionGradeLevel();

                ?>
                    <!-- STEP 3 -->
                    <div class="content">
                        <nav>
                            <a href="evaluation.php">
                                <i class="bi bi-arrow-return-left fa-1x"></i>
                                <h3>Back</h3>
                            </a>
                        </nav>
                        <div class="content-header">

                            <?php echo Helper::ProcessPendingCards($enrollment_form_id,
                                $date_creation, $admission_status); ?>

                        </div>
                        <main>
                            <div class="progress">
                                <span class="dot active"><p>Check form details</p></span>
                                <span class="line active"></span>
                                <span class="dot active"><p>Find section</p></span>
                                <span class="line active"></span>
                                <span class="dot active"><p>Subject confirmation</p></span>
                            </div>
                            <div class="floating">

                                <header>

                                    <div class="title">
                                        <h3><?php echo $section_name;?> Subjects</h3>
                                    </div>

                                </header>
                                <main>
                                    <table class="a">

                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                                <th rowspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                      

                                            <?php

                                                $sql = $con->prepare("SELECT t2.* 
                                                
                                                    FROM student_subject as t1


                                                    INNER JOIN subject_program as t2 ON t2.subject_program_id=t1.subject_program_id

                                                    WHERE t1.student_id=:student_id
                                                    AND t1.school_year_id=:school_year_id
                                                    AND t1.course_id=:course_id

                                                ");

                                                $sql->bindParam(":student_id", $student_id);
                                                $sql->bindParam(":school_year_id", $current_school_year_id);
                                                $sql->bindParam(":course_id", $selected_course_id);

                                                $sql->execute();
                                            
                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];

                                                        $change_section_subject_url = "";

                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <button 
                                                                        class='btn btn-sm btn-primary'
                                                                        onclick=\"window.location.href = '" . $change_section_subject_url . "'\"
                                                                        >
                                                                        <i class='fas fa-pencil'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                    
                                                }
                                            ?>

                                        </tbody>
                                    </table>
                                </main>
                            </div>
                            <div class="action">
                                <button
                                    class="default large"
                                    onclick="window.location.href = 'process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id; ?>'">
                                    Return
                                </button>

                                <!-- <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo "" ?>", "<?php echo $guardian_name ?>", "<?php echo "" ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address ?>", "<?php echo $lrn ?>", "<?php echo $selected_course_id ?>", "<?php echo $enrollment_form_id ?>", "<?php echo $religion ?>", "<?php echo $birthplace ?>", "<?php echo $email ?>")' class="default clean success large">
                                    Confirm
                                </button> -->

                            </div>
                        </main>
                    </div>

                    <script>

                        // function confirmPendingValidation(type, firstname, lastname, middle_name, password,
                        //     program_id, civil_status, nationality, contact_number, birthday, age,
                        //     guardian_name, guardian_contact_number, sex, student_status,
                        //     pending_enrollees_id, address, lrn,
                        //     selected_course_id, enrollment_form_id,
                        //     religion, birthplace, email){

                        //     selected_course_id = parseInt(selected_course_id);
                        //     program_id = parseInt(program_id);
                        //     age = parseInt(age);
                        //     pending_enrollees_id = parseInt(pending_enrollees_id);

                        //     Swal.fire({
                        //         icon: 'question',
                        //         title: `Confirm Enrollment?`,
                        //         showCancelButton: true,
                        //         confirmButtonText: 'Yes',
                        //         cancelButtonText: 'Cancel'

                        //     }).then((result) => {
                        //         if (result.isConfirmed) {
                        //             $.ajax({
                        //                 url: '../../ajax/admission/pending_enrollment_approval.php',
                        //                 type: 'POST',
                        //                 data: {
                        //                     type,
                        //                     firstname, lastname, middle_name,
                        //                     password, program_id, civil_status, nationality, 
                        //                     contact_number, birthday, age, guardian_name, 
                        //                     guardian_contact_number, sex, student_status, 
                        //                     pending_enrollees_id, address, lrn, selected_course_id,
                        //                     enrollment_form_id, religion, birthplace, email
                        //                 },

                        //                 dataType: "json",

                        //                 success: function(response) {

                        //                     // console.log(response)
                        //                     if(response['status'] == "student_account_exist"){
                        //                         Swal.fire({
                        //                             title: "Student already have an account.",
                        //                             icon: "error",
                        //                             showCancelButton: false,
                        //                             confirmButtonText: "I understand and will verify.",
                        //                         });
                        //                     }

                        //                     if(response.student_id){

                        //                         Swal.fire({
                        //                                 title: "Enrollment Approved",
                        //                                 icon: "success",
                        //                                 showCancelButton: false,
                        //                                 confirmButtonText: "OK",
                        //                         }).then((result) => {
                        //                             if (result.isConfirmed) {

                        //                                 var student_id = parseInt(response.student_id);

                        //                                 var url = `./subject_insertion_summary.php?id=${student_id}&enrolled_subject=show`;

                        //                                 window.location.href = url;

                        //                             } else {
                        //                                 // User clicked Cancel or closed the dialog
                        //                             }
                        //                         });

                        //                     }

                        //                     // Swal.fire({
                        //                     //         title: "Enrollment Approved",
                        //                     //         icon: "success",
                        //                     //         showCancelButton: false,
                        //                     //         confirmButtonText: "OK",
                        //                     // }).then((result) => {
                        //                     //     if (result.isConfirmed) {


                        //                     //         // var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                        //                     //         // window.location.href = url;
                        //                     //     } else {
                        //                     //         // User clicked Cancel or closed the dialog
                        //                     //     }
                        //                     // });
                                          
                        //                 },
                        //                 error: function(xhr, status, error) {
                        //                     // handle any errors here
                        //                 }
                        //             });
                        //         }
                        //     });
                        // }
                    </script>
                <?php
            }

            if(isset($_GET['id']) && isset($_GET['subject_evaluation'])
                && isset($_GET['selected_course_id'])
                && $_GET['subject_evaluation'] == "show"){

                    // echo "subject evaluation";

                    $selected_course_id = $_GET['selected_course_id'];

                    $section = new Section($con, $selected_course_id);

                    $section_name = $section->GetSectionName();
                    $section_course_level = $section->GetSectionGradeLevel();

                ?>
                    <div class="content">
                        
                        <div class="content-header">
                             <?php echo Helper::RevealStudentTypePending($type); ?>

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

                            <?php echo Helper::ProcessPendingCards($enrollment_form_id,
                                $date_creation, $admission_status); ?>

                        </div>
                        <main>
                            <div class="progress">
                                <span class="dot active"><p>Check form details</p></span>
                                <span class="line active"></span>
                                <span class="dot active"><p>Find section</p></span>
                                <span class="line active"></span>
                                <span class="dot active"><p>Subject confirmation</p></span>
                            </div>

                            <div class="floating">

                                <header>
                                    <div class="title">
                                        <h3>Crediting Subjects</h3>
                                    </div>

                                     <div class="action">
                                        <a href="find_credit_subject.php">
                                            <button type="button" class="large default">+ Add new</button>
                                        </a>
                                    </div>
                                </header>

                                <main>
                                    <table class="a">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">Subject ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             
                                        </tbody>
                                    </table>
                                </main>
                            </div>

                            <div class="floating">

                                <header>
                                    <div class="title">
                                        <h3><?php echo $section_name;?> Subjects</h3>
                                    </div>
                                </header>

                                <main>
                                    <table class="a">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">Subject ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                                // $course_level = $type === "SHS" ? 11 : ($type === "Tertiary" ? 1 : 0);

                                                $sql = $con->prepare("SELECT 
                                                
                                                    t2.* FROM course AS t1
                                                    INNER JOIN subject_program as t2 ON t2.program_id = t1.program_id
                                                    AND t1.course_id=:selected_course_id

                                                    WHERE t2.semester=:semester
                                                    AND t2.course_level=:course_level

                                                ");

                                                $sql->bindValue(":selected_course_id", $selected_course_id);
                                                $sql->bindValue(":semester", $current_school_year_period);
                                                $sql->bindValue(":course_level", $pending_course_level);

                                                $sql->execute();
                                            
                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];


                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                            </tr>
                                                        ";
                                                    }
                                                    
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </main>
                            </div>

                            <div class="action">
                                <button
                                    class="default large"
                                    onclick="window.location.href = 'process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id; ?>'">
                                    Return
                                </button>

                                <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo $age ?>", "<?php echo $guardian_name ?>", "<?php echo "" ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address ?>", "<?php echo $lrn ?>", "<?php echo $selected_course_id ?>", "<?php echo $enrollment_form_id ?>", "<?php echo $religion ?>", "<?php echo $birthplace ?>", "<?php echo $email ?>")' class="default clean success large">
                                    Confirm
                                </button>
                            </div>
                        </main>
                    </div>

                    <script>

                        // function confirmPendingValidation(type, firstname, lastname, middle_name, password,
                        //     program_id, civil_status, nationality, contact_number, birthday, age,
                        //     guardian_name, guardian_contact_number, sex, student_status,
                        //     pending_enrollees_id, address, lrn,
                        //     selected_course_id, enrollment_form_id,
                        //     religion, birthplace, email){

                        //     selected_course_id = parseInt(selected_course_id);
                        //     program_id = parseInt(program_id);
                        //     age = parseInt(age);
                        //     pending_enrollees_id = parseInt(pending_enrollees_id);

                        //     Swal.fire({
                        //         icon: 'question',
                        //         title: `Confirm Enrollment?`,
                        //         showCancelButton: true,
                        //         confirmButtonText: 'Yes',
                        //         cancelButtonText: 'Cancel'

                        //     }).then((result) => {
                        //         if (result.isConfirmed) {
                        //             $.ajax({
                        //                 url: '../../ajax/admission/pending_enrollment_approval.php',
                        //                 type: 'POST',
                        //                 data: {
                        //                     type,
                        //                     firstname, lastname, middle_name,
                        //                     password, program_id, civil_status, nationality, 
                        //                     contact_number, birthday, age, guardian_name, 
                        //                     guardian_contact_number, sex, student_status, 
                        //                     pending_enrollees_id, address, lrn, selected_course_id,
                        //                     enrollment_form_id, religion, birthplace, email
                        //                 },

                        //                 dataType: "json",

                        //                 success: function(response) {

                        //                     // console.log(response)
                        //                     if(response['status'] == "student_account_exist"){
                        //                         Swal.fire({
                        //                             title: "Student already have an account.",
                        //                             icon: "error",
                        //                             showCancelButton: false,
                        //                             confirmButtonText: "I understand and will verify.",
                        //                         });
                        //                     }

                        //                     if(response.student_id){

                        //                         Swal.fire({
                        //                                 title: "Enrollment Approved",
                        //                                 icon: "success",
                        //                                 showCancelButton: false,
                        //                                 confirmButtonText: "OK",
                        //                         }).then((result) => {
                        //                             if (result.isConfirmed) {

                        //                                 var student_id = parseInt(response.student_id);

                        //                                 var url = `./subject_insertion_summary.php?id=${student_id}&enrolled_subject=show`;

                        //                                 window.location.href = url;

                        //                             } else {
                        //                                 // User clicked Cancel or closed the dialog
                        //                             }
                        //                         });

                        //                     }

                        //                     // Swal.fire({
                        //                     //         title: "Enrollment Approved",
                        //                     //         icon: "success",
                        //                     //         showCancelButton: false,
                        //                     //         confirmButtonText: "OK",
                        //                     // }).then((result) => {
                        //                     //     if (result.isConfirmed) {


                        //                     //         // var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                        //                     //         // window.location.href = url;
                        //                     //     } else {
                        //                     //         // User clicked Cancel or closed the dialog
                        //                     //     }
                        //                     // });
                                          
                        //                 },
                        //                 error: function(xhr, status, error) {
                        //                     // handle any errors here
                        //                 }
                        //             });
                        //         }
                        //     });
                        // }
                        
                    </script>
                <?php
            }
        }else{
            
            echo "
                <div class='col-md-12'>
                    <br>
                    <h4 class='text-center text-warning'>Enrollee has been approved.</h4>
                </div>
            ";
        }

    }


    //  This will be the enrollment flow of Regular.

    // Return button (Check form Details), student table is now dependent not the pending table.

    // Return button (Find Section), student table is now dependent not the pending table.

    # STID
    if(isset($_GET['st_id'])){

        $student_id = $_GET['st_id'];

        $student = new Student($con, $student_id);
        $student_subject = new StudentSubject($con);
        $enrollment = new Enrollment($con);


        $generated_enrollment_form_id = $enrollment->GenerateEnrollmentFormId($current_school_year_id);

        // if (!isset($_SESSION['enrollment_form_id'])) {
        //     $generated_enrollment_form_id = $enrollment->GenerateEnrollmentFormId($current_school_year_id);
        //     $_SESSION['enrollment_form_id'] = $generated_enrollment_form_id;
            
        // } else {
        //     $generated_enrollment_form_id = $_SESSION['enrollment_form_id'];
        // }


        // echo $generated_enrollment_form_id;

        $student_course_id = $student->GetStudentCurrentCourseId();

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
        $type_status = $student->GetIsTertiary();
        $student_civil_status = $student->GetCivilStatus();
        $student_nationality = $student->GetNationality();
        $student_religion = $student->GetReligion();
        $student_email = $student->GetEmail();
        $student_status_st = $student->GetStudentStatus();
        $student_current_course_id = $student->GetStudentCurrentCourseId();
        $student_contact_number = $student->GetContactNumber();

        $student_admission_status = $student->GetAdmissionStatus();
        $student_active_status= $student->CheckIfActive();

        // echo $student_course_level;

        // echo $student_status_st;

        $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");
        $student_suffix = $student->GetSuffix();
        $student_new_enrollee = $student->GetStudentNewEnrollee();

        $student_unique_id = $student->GetStudentUniqueId();

        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);
        
        
        $enrollment_currently_registrar_id = $enrollment->GetEnrollmentCurrentRegistrarId($student_id,
            $student_enrollment_id);

        

        $user = new User($con, $enrollment_currently_registrar_id);

        $registrarName = "";

        if($enrollment_currently_registrar_id != NULL){
            $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

        }

        // var_dump($registrarName);

        # AS registrar enters to the form. Update immediately his id into the enrollment form.
        // $enrollment_currently_registrar_id = $enrollment->UpdateRegistrarIntoTheEnrollment(
        $update_enrollment_currently_registrar_id = $enrollment->UpdateRegistrarIntoTheEnrollment(
                $student_id,
                $student_enrollment_id,
                $registrarUserId,
                $current_school_year_id);

        // $enrollment_currently_registrar_id = NULL;
        // var_dump($enrollment_currently_registrar_id);

        // if($enrollment_currently_registrar_id == NULL && $registrarUserId != NULL){
        //     # Updates the current registrar.

        //     $enrollment_currently_registrar_id = $enrollment->UpdateRegistrarIntoTheEnrollment(
        //         $student_id,
        //         $student_enrollment_id,
        //         $registrarUserId,
        //         $current_school_year_id);

        //     $_SESSION['enrollment_currently_registrar_id'] = $registrarUserId;
        //     $_SESSION['enrollment_currently_enrollment_id'] = $student_enrollment_id;
        //     $_SESSION['enrollment_currently_student_id'] = $student_id;

        // }

        // var_dump($registrarUserId);
        // echo "<br>";

        // var_dump($enrollment_currently_registrar_id);
        // echo "<br>";
        // return;

        if($enrollment_currently_registrar_id !== NULL 
            && $registrarUserId != $enrollment_currently_registrar_id){
            // echo "You`re inside the form";
            Alert::error("Only one registrar can process with the form, $registrarName is inside.", "evaluation.php");
            exit();
        }




        $student_non_enrolled_enrollment_id = $enrollment->GetEnrollmentIdNonEnrolled($student_id,
            $current_school_year_id);

        // echo $student_enrollment_id;
        // $student_enrollment_id = 1183;
        
       $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $student_enrollment_id, $current_school_year_id);



        

        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $student_enrollment_id, $current_school_year_id);

        // var_dump($student_enrollment_student_status);


        $student_enrollment_is_tertiary = $enrollment->GetEnrollmentFormIsTertiary($student_id,
            $student_enrollment_id);

        // echo $student_enrollment_is_tertiary;
        // echo "<br>";
 
        $student_enrollment_retake_status = $enrollment->GetEnrollmentFormRetakeStatus($student_id,
            $student_enrollment_id, $current_school_year_id);
        
        $student_enrollment_is_transferee = $enrollment->GetEnrollmentFormIsTransferee($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_is_new = $enrollment->GetEnrollmentFormIsNew($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_evaluated_by_registrar = $enrollment->CheckEnrollmentFormRegistrarEvaluated($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_enrollment_id,
            $student_enrollment_course_id, $current_school_year_id);

        $student_enrollment_form_id = $student_enrollment_form_id == 0 
            ? $generated_enrollment_form_id : $student_enrollment_form_id;
        
        // echo $student_enrollment_form_id;
        
        $section = new Section($con, $student_enrollment_course_id);

        // Enrollment form course Based
        $student_program_section = $section->GetSectionName();
        $section_capacity = $section->GetSectionCapacity();
        $section_level = $section->GetSectionGradeLevel();
        $section_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $section_department_id = $section->GetDepartmentIdByProgramId($section_program_id);

        $department = new Department($con, $section_department_id);

        
        $student_department_name = $department->GetDepartmentName();
        # QQ
        // echo $section_department_id;


        $prev_section = new Section($con, $student_current_course_id);
        // Student course Based
        $student_current_program_section = $prev_section->GetSectionName();
        $student_current_program_id = $prev_section->GetSectionProgramId($student_current_course_id);

        
        $student_program_id = $section->GetSectionProgramId(
            $student_enrollment_course_id === 0 
            ? $student_course_id : $student_enrollment_course_id);

        # If student enrollment form course id is 0,
        # It should reflected his latest student course id
        # else should reflected in his enrollment forn course id
        $student_enrollment_course_level = $section->GetSectionGradeLevel(
            $student_enrollment_course_id === 0 
            ? $student_course_id : $student_enrollment_course_id);

            // var_dump($student_course_level);

        $program = new Program($con, $student_program_id);

        $student_program_acronym = $program->GetProgramAcronym();
        $student_current_department_id = $program->GetProgramDepartmentId();

        // echo $student_current_department_id ;
        
        // $enrollment_creation = $enrollment->GetEnrollmentDate($student_id,
        //     $student_course_id, $current_school_year_id);


        // $enrollment_creation = $enrollment->GetEnrollmentDateByEnrollmentId($student_enrollment_id);
        $enrollment_creation = $enrollment->GetEnrollmentDateByEnrollmentId($student_enrollment_id);
        echo "heyy";
        echo "heyy";
        echo "heyy";
        var_dump($enrollment_creation);

        $enrollment_is_new_enrollee = $enrollment->GetEnrollmentIsNewEnrollee($student_id,
            $student_current_course_id, $current_school_year_id);

        $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransferee($student_id,
            $student_current_course_id, $current_school_year_id);

        // echo $student_enrollment_is_new;

        $section_name = $section->GetSectionName();
        // $student_program_id = $section->GetSectionProgramId($student_course_id);

        // Parent based on Student ID.

        $get_parent = $con->prepare("SELECT * FROM parent
            WHERE student_id=:student_id");
    
        $get_parent->bindValue(":student_id", $student_id);
        $get_parent->execute();

        $parent_firstname = "";
        $parent_lastname = "";
        $parent_middle_name = "";
        $parent_contact_number = "";
        $parent_email = "";
        $parent_occupation = "";
        $parent_suffix = "";
        $parent_relationship = "";

        if($get_parent->rowCount() > 0){

            $rowParnet = $get_parent->fetch(PDO::FETCH_ASSOC);

            $parent_id = $rowParnet['parent_id'];
            $parent_firstname = $rowParnet['firstname'];
            $parent_lastname = $rowParnet['lastname'];
            $parent_middle_name = $rowParnet['middle_name'];
            $parent_contact_number = $rowParnet['contact_number'];
            $parent_occupation = $rowParnet['occupation'];
            $parent_suffix = $rowParnet['suffix'];
            $parent_email = $rowParnet['email'];
            $parent_relationship = $rowParnet['relationship'];

        }

        if(isset($_GET['details']) 
            && $_GET['details'] == "show"){
            
            if(isset($_GET['clicked'])
                && $_GET['clicked'] == "true"){
            
                
                $enrollmentAudit = new EnrollmentAudit($con);

                $registrarName = "";

                if($registrarUserId != ""){

                    $user = new User($con, $registrarUserId);
                    $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                
                }
                
                $now = date("Y-m-d H:i:s");
                $date_creation = date("M d, Y h:i a", strtotime($now));

                // echo $period_short;
                // BTB $current_school_year_period;
                // $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

                $description = "Registrar '$registrarName' has entered the enrollment form '#$student_enrollment_form_id' on $date_creation";
                // echo "$description";

                $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                    $student_enrollment_id,
                    $description, $current_school_year_id, $registrarUserId
                );
                
                // echo "nice";

            }

            include("./form_details.php");
        }

        if(isset($_GET['find_section']) 
            && $_GET['find_section'] == "show"
            && isset($_GET['c_id'])
            ){

            include_once('./student_find_section.php');
                
        }

        if(isset($_GET['subject_review']) 
            && $_GET['subject_review'] == "show"
            && isset($_GET['selected_course_id'])){

            include_once('./subject_review.php');
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

    function rejectForm(pending_enrollees_id){

        var pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({
            icon: 'question',
            title: `Reject this pending enrollees form?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/reject_form.php',
                    type: 'POST',
                    data: {
                        pending_enrollees_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                            Swal.fire({
                                icon: 'success',
                                title: `Selected form has been rejected.`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "evaluation.php";
                            }, 1000);


 

                        // if (response === "success_update") {

                        //     Swal.fire({
                        //         icon: 'success',
                        //         title: `Selected form has been rejected.`,
                        //     });
                        // } 

                        // else {
                        //     console.log('Update failed');
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }
    
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

    function enrolleeEnrollmentStatusChanger(
        enrollee_enrollment_status_type,
        pending_enrollees_id){

        var pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({
            icon: 'question',
            title: `Mark Enrollee enrollment form as ${enrollee_enrollment_status_type}?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/enrolleeEnrollmentStatusChanger.php',
                    type: 'POST',
                    data: {
                        enrollee_enrollment_status_type,
                        pending_enrollees_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Modified`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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
                            });}
 
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

    function enrolleeAdmissionStatusChanger(
        enrollee_admission_status_type,
        pending_enrollees_id){

        var pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({
            icon: 'question',
            title: `Mark admission status as ${enrollee_admission_status_type}?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/pending/enrolleeAdmissionStatusChanger.php',
                    type: 'POST',
                    data: {
                        enrollee_admission_status_type,
                        pending_enrollees_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Modified`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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
                            });}
 
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

    // (Has Student Table) For student_find_section & subject_review pphp route

    function enrollmentStudentStatusChanging(student_enrollment_id,
        student_id, current_school_year_id, type){

        var student_enrollment_id = parseInt(student_enrollment_id);
        var student_id = parseInt(student_id);
        var current_school_year_id = parseInt(current_school_year_id);
        
        var title = '';
        var text = '';

        if(type == "Regular"){
            // text = "Note: If retake form is activated, This will de-activate the Retake form.";
            text = "Note: Please finalized this changes.";
        }
        else if(type == "Irregular"){
            text = "Note: Please finalized this changes.";
        }

        Swal.fire({
            icon: 'question',
            title: `Change status as ${type}?`,
            text: `${text}`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {
            if (result.isConfirmed) {
                // console.log(student_enrollment_form_id)
                    $.ajax({
                    url: '../../ajax/admission/changingFormStatus.php',
                    type: 'POST',
                    data: {
                        student_enrollment_id,
                        student_id,
                        current_school_year_id,
                        type
                    },

                    // dataType: "json",

                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "update_success"){

                            Swal.fire({
                                title: "Changes Successfully made",
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonText: "OK",

                            }).then((result) => {
                                if (result.isConfirmed) {

                                    location.reload();

                                } else {
                                    
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // handle any errors here
                    }
                });
            }
        });

    }

</script>