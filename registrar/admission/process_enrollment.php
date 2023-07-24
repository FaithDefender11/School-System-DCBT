<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Program.php');


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

        // unset($_SESSION['pending_enrollees_id']);
        // unset($_SESSION['process_enrollment']);

        $enrollment = new Enrollment($con);
        $section = new Section($con);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
            
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $pending_query = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
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
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $birthplace = $row['birthplace'];
            $religion = $row['religion'];
            $email = $row['email'];
            $type = $row['type'];
            $admission_status = $row['admission_status'];
            $pending_course_level = $row['course_level'];

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
    
            if(isset($_GET['id']) && isset($_GET['step1'])){

                include("./process_step_1.php");
            }

            if(isset($_GET['id']) && isset($_GET['step2'])){

                $student_subject = new StudentSubject($con);

                if(isset($_POST['pending_choose_section'])
                    && isset($_POST['selected_course_id'])
                    ){

                    // New (Standard start from the beginning of DCBT). 
                    // Make sure it is First Semester

                    if($admission_status == "Standard" 
                        && $current_school_year_period == "First"){
                            
                        $selected_course_id_value = $_POST['selected_course_id'];

                        $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";


                        // Pending Info to Student
                        $generateStudentUniqueId = $student->GenerateUniqueStudentNumber();
                        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                        $selected_course_id_value = $_POST['selected_course_id'];

                        $stmt_insert = $con->prepare("INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, age, sex,
                            course_id, student_unique_id, course_level, username,
                            address, lrn, religion, birthplace, email, student_statusv2, is_tertiary, new_enrollee) 

                            VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :age, :sex,
                                :course_id, :student_unique_id, :course_level, :username,
                                :address, :lrn, :religion, :birthplace, :email, :student_statusv2, :is_tertiary , :new_enrollee)");

                        $stmt_insert->bindParam(':firstname', $firstname);
                        $stmt_insert->bindParam(':lastname', $lastname);
                        $stmt_insert->bindParam(':middle_name', $middle_name);
                        $stmt_insert->bindParam(':password', $password);
                        $stmt_insert->bindParam(':civil_status', $civil_status);
                        $stmt_insert->bindParam(':nationality', $nationality);
                        $stmt_insert->bindParam(':contact_number', $contact_number);
                        $stmt_insert->bindParam(':birthday', $birthday);
                        $stmt_insert->bindParam(':age', $age);
                        $stmt_insert->bindParam(':sex', $sex);
                        // 
                        // SHOULD BE NULL FIRST If Irregular.
                        $stmt_insert->bindParam(':course_id', 0);
                        $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
                        $stmt_insert->bindParam(':course_level', 0);
                        $stmt_insert->bindParam(':username', $username);
                        $stmt_insert->bindParam(':address', $address);
                        $stmt_insert->bindParam(':lrn', $lrn);
                        $stmt_insert->bindParam(':religion', $religion);
                        $stmt_insert->bindParam(':birthplace', $birthplace);
                        $stmt_insert->bindParam(':email', $email);
                        $stmt_insert->bindValue(':student_statusv2', "Regular");
                        // replaced by new_enrollee
                        // $stmt_insert->bindValue(':admission_status', "");
                        $stmt_insert->bindValue(':is_tertiary', $type == "Tertiary" ? 1 : 0);
                        $stmt_insert->bindValue(':new_enrollee', 1);

                        if($stmt_insert->execute()){

                            $generated_student_id = $con->lastInsertId();

                            # CHECK FIRST IF STUDENT HAS A PARENT.
                            # UPDATE IF YES.
                            $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                                $generated_student_id);

                            if($update_parent == false){
                                Alert::error("Attaching Parent has failed", "");
                                exit();
                            }
 
                            // Enrollment - r.e=no
                            $insert_enrollment = $con->prepare("INSERT INTO enrollment
                                (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee,
                                    registrar_evaluated, is_transferee, enrollment_form_id,
                                    is_tertiary, enrollment_date, student_status)
                                VALUES (:student_id, :course_id, :school_year_id, :enrollment_status,
                                    :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id, :is_tertiary,
                                    :enrollment_date, :student_status)");

                            $insert_enrollment->bindValue(':student_id', $generated_student_id);
                            $insert_enrollment->bindValue(':course_id', $selected_course_id_value);
                            $insert_enrollment->bindValue(':enrollment_date', $now);
                            $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
                            $insert_enrollment->bindValue(':enrollment_status', "tentative");
                            $insert_enrollment->bindValue(':is_new_enrollee', 1);

                            # Modified
                            $insert_enrollment->bindValue(':registrar_evaluated', "no");
                            $insert_enrollment->bindValue(':is_transferee', $admission_status == "Transferee" ? 1 : 0);
                            $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
                            $insert_enrollment->bindValue(':is_tertiary', $type == "Tertiary" ? 1 : 0);
                            // New Student From Online
                            $insert_enrollment->bindValue(':student_status', "Regular");

                            if($insert_enrollment->execute()){

                                $generated_enrollment_id = $con->lastInsertId();


                                # Adding Student_Subject base on Section
                                $wasSuccessStudentSubject = $student_subject->AddNonFinalDefaultEnrolledSubject($generated_student_id,
                                    $generated_enrollment_id, $selected_course_id_value, $current_school_year_id,
                                    $current_school_year_period, $admission_status);

                                if($wasSuccessStudentSubject){

                                    // Approved Request
                                    $pendingSuccess = $pending->SetPendingApprove($pending_enrollees_id);

                                    if($pendingSuccess == true){

                                        // $url = "process_enrollment.php?subject_evaluation=show&selected_course_id=$selected_course_id_value";
                                        // $xd = "process_enrollment.php?step3=show&st_id=$generated_student_id&c_id=$selected_course_id_value";

                                        $student_table_subject_review = "process_enrollment.php?subject_review=show&st_id=$generated_student_id&selected_course_id=$selected_course_id_value";

                                        Alert::success("Success Up to Pending (5steps)",
                                            $student_table_subject_review);

                                        exit();
                                    }
                                }
                               
                            }
                        }
                    }

                    // New Transferee
                    // If student is New and second Semester, then it should be New Transferee

                    if($admission_status == "Transferee" 
                        // && $current_school_year_period == "Second"
                        ){

                        $selected_course_id_value = $_POST['selected_course_id'];

                        // echo $selected_course_id_value;

                        $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";
                        // Pending Info to Student
                        $generateStudentUniqueId = $student->GenerateUniqueStudentNumber();
                        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';

                        $selected_course_id_value = $_POST['selected_course_id'];
 
                        $new_enrollee = 1;

                        // Course Id of New Transferee should be 0 
                        if($student->InsertStudentFromPendingTable($firstname, $lastname, $middle_name, $password, $civil_status, $nationality,
                            $contact_number, $birthday, $age, $sex, 0, $generateStudentUniqueId,
                            0, $username, $address, $lrn, $religion, $birthplace, $email,
                            $type, $new_enrollee)){

                            $generated_student_id = $con->lastInsertId();

                            # CHECK FIRST IF STUDENT HAS A PARENT.
                            # UPDATE IF YES.
                            $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                                $generated_student_id);

                            if($update_parent == false){
                                Alert::error("Attaching Parent has failed", "");
                                exit();
                            }

                            // if($insert_enrollment->execute()){ fadd
                            // it should follow the enrollment course id into student table.
                            if($enrollment->InsertPendingRequestToEnrollment($generated_student_id,
                                $selected_course_id_value, $now, $current_school_year_id,
                                $admission_status, $enrollment_form_id, $type)){

                                $generated_enrollment_id = $con->lastInsertId();


                                # Adding Student_Subject base on Section
                                $wasSuccessStudentSubject = $student_subject->AddNonFinalDefaultEnrolledSubject($generated_student_id,
                                    $generated_enrollment_id, $selected_course_id_value, $current_school_year_id,
                                    $current_school_year_period, $admission_status);

                                if($wasSuccessStudentSubject){

                                    // Approved Request
                                    $pendingSuccess = $pending->SetPendingApprove($pending_enrollees_id);

                                    if($pendingSuccess == true){

                                        $student_table_subject_review = "process_enrollment.php?subject_review=show&st_id=$generated_student_id&selected_course_id=$selected_course_id_value";

                                        Alert::success("Success Up to Pending (5steps)",
                                            $student_table_subject_review);
                                        exit();
                                    }
                                }
                               
                            }
                        }
                    }

                }

                ?>
                    <!-- STEP 2 -->
                    <div class="content">
                        <nav>
                            <a href="#"
                            ><i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                            </a>
                        </nav>
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
                                            <a href="#" class="dropdown-item" style="color: red">
                                                <i class="bi bi-file-earmark-x"></i>Delete form
                                            </a>
                                            <a href="form_alignment.php?id=<?php echo $pending_enrollees_id;?>" class="text-primary dropdown-item">
                                                <i class="bi bi-pencil"></i>Edit form
                                            </a>
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
                                <span class="line inactive"></span>
                                <span class="dot inactive"><p>Subject confirmation</p></span>
                                
                            </div>

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
                                                                <select class="text-center" style="pointer-events: none;" id="inputTrack" class="form-select">
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

                                                            <select style="width: 170px" class="text-center" onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
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
                                                                <select style="width: 170px" class="text-center" style="pointer-events: none;" id="inputTrack" class="form-select">
                                                                    <?php 
                                                                        $SHS_DEPARTMENT = 4;

                                                                        echo $department_id;
                                                                    
                                                                        $track_sql = $con->prepare("SELECT 
                                                                            program_id, track, acronym 
                                                                            
                                                                            FROM program 

                                                                            WHERE department_id =:department_id
                                                                            GROUP BY track
                                                                        ");

                                                                        $track_sql->bindValue(":department_id", $department_id);
                                                                        $track_sql->execute();

                                                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                            $row_program_id = $row['program_id'];

                                                                            $track = $row['track'];

                                                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                            echo "<option value='$row_program_id' $selected>$track</option>";
                                                                        }
                                                                    ?>
                                                                    
                                                                </select>
                                                            </div>
                                                        </span>

                                                        <span>
                                                            <label for="strand">Strand</label>
                                                            <select class="text-center" style="width: 170px" onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                                name="strand" id="strand" class="form-select">
                                                                <?php 
                                                                
                                                                    $track_sql = $con->prepare("SELECT 
                                                                        program_id, track, acronym 
                                                                        
                                                                        FROM program 
                                                                        WHERE department_id =:department_id
                                                                        GROUP BY acronym
                                                                    ");

                                                                    $track_sql->bindValue(":department_id", $department_id);
                                                                    $track_sql->execute();

                                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                        $row_program_id = $row['program_id'];

                                                                        $acronym = $row['acronym'];

                                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

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
                                                    <select name="grade" id="grade">
                                                        <!-- <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                                        <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option> -->
                                                        <option class="text-center" value="<?php echo $pending_course_level;?>"><?php echo $pending_course_level;?></option>
                                                    </select>
                                                </div>
                                            </span>

                                            <span>
                                                <label for="semester">Semester</label>
                                                <div>
                                                    <select style="pointer-events: none;" name="semester" id="semester">
                                                        <option class="text-center" value=""<?php echo ($current_school_year_period == "First") ? " selected" : ""; ?>>1st</option>
                                                        <option class="text-center" value=""<?php echo ($current_school_year_period == "Second") ? " selected" : ""; ?>>2nd</option>
                                                    </select>
                                                </div>
                                            </span>
                                        </div>
                                    </form>

                                </main>

                            </div>

                            <script>
                                function chooseStrand(entity, pending_enrollees_id){

                                    var strand = document.getElementById("strand").value;

                                    // console.log("Selected value: " + strand);

                                    Swal.fire({
                                        icon: 'question',
                                        title: `Update Strand?`,
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes',
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {

                                        if (result.isConfirmed) {
                                            // REFX
                                            $.ajax({
                                                url: '../ajax/pending/update_student_strand.php',
                                                type: 'POST',
                                                data: {
                                                    strand, pending_enrollees_id
                                                },
                                                success: function(response) {

                                                    console.log(response);

                                                    // enrollment-details
                                                    if(response == "success"){
                                                        $('#enrollment-details').load(
                                                            location.href + ' #enrollment-details'
                                                        );
                                                        $('#regular_available_section').load(
                                                            location.href + ' #regular_available_section'
                                                        );
                                                    }


                                                }
                                            });
                                        }

                                    });
                                }
                            </script>

                            <div class="floating">

                                <header>
                                    <div class="title">
                                    <h3>Available sections</h3>
                                    </div>
                                </header>

                                <form method="post">

                                    <main>
                                        <table class="a">
                                            <thead>
                                                <tr class="text-center"> 
                                                    <th rowspan="2">Section Id</th>
                                                    <th rowspan="2">Section Name</th>
                                                    <th rowspan="2">Student</th>
                                                    <th rowspan="2">Capacity</th>
                                                    <th rowspan="2">Term</th>
                                                    <th rowspan="2"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                    
                                                    $active = "yes";

                                                    # Only Available now.
                                                    $sql = $con->prepare("SELECT * FROM course

                                                        WHERE program_id=:program_id
                                                        AND active=:active
                                                        AND school_year_term =:school_year_term
                                                        AND course_level =:course_level
                                                        AND is_full ='no'
                                                        AND is_remove = 0
                                                        ");

                                                    $sql->bindParam(":program_id", $program_id);
                                                    $sql->bindParam(":active", $active);
                                                    $sql->bindParam(":school_year_term", $current_school_year_term);
                                                    $sql->bindParam(":course_level", $pending_course_level);

                                                    $sql->execute();
                                                
                                                    if($sql->rowCount() > 0){

                                                        while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                            $course_id = $get_course['course_id'];

                                                            $program_section = $get_course['program_section'];
                                                            $capacity = $get_course['capacity'];
                                                            $school_year_term = $get_course['school_year_term'];

                                                            $section = new Section($con, $course_id);

                                                            $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);

                                                            $capacity = $section->GetSectionCapacity();

                                                            $program_id = $section->GetSectionProgramId($course_id);
                                                            $course_level = $section->GetSectionGradeLevel();
            
                                                            if($totalStudent == $capacity){

                                                            }
                                                            echo "
                                                                <tr class='text-center'>
                                                                    <td>$course_id</td>
                                                                    <td>$program_section</td>
                                                                    <td>$totalStudent</td>
                                                                    <td>$capacity</td>
                                                                    <td>$school_year_term</td>
                                                                    <td>
                                                                        <input name='selected_course_id' class='radio' value='$course_id' type='radio' " . (($totalStudent == $capacity) ? "disabled" : "") . ">
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                        
                                                    }else{
                                                        echo "
                                                            <div class='col-md-12'>
                                                                <h4 class='text-center text-muted'>No currently available section for $program_acronym</h4>
                                                            </div>
                                                        ";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </main>


                                    <div style="margin-top: 20px;" class="action">
                                        <button
                                        type="button"
                                            class="default large "
                                            onclick="window.location.href = 'process_enrollment.php?step1=true&id=<?php echo $pending_enrollees_id; ?>'">
                                            Return
                                        </button>
                                        <button class="default large success"
                                            name="pending_choose_section" type="submit">
                                            Proceed
                                        </button>
                                    </div>
                                </form>

                            </div>

                            
                        </main>
                    </div>
                <?php
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
                            <a href="SHS-find-form-evaluation.html"
                            ><i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                            </a>
                        </nav>
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

                                <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo $age ?>", "<?php echo $guardian_name ?>", "<?php echo $guardian_contact_number ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address ?>", "<?php echo $lrn ?>", "<?php echo $selected_course_id ?>", "<?php echo $enrollment_form_id ?>", "<?php echo $religion ?>", "<?php echo $birthplace ?>", "<?php echo $email ?>")' class="default clean success large">
                                    Confirm
                                </button>
                            </div>
                        </main>
                    </div>

                    <script>
                        function confirmPendingValidation(type, firstname, lastname, middle_name, password,
                            program_id, civil_status, nationality, contact_number, birthday, age,
                            guardian_name, guardian_contact_number, sex, student_status,
                            pending_enrollees_id, address, lrn,
                            selected_course_id, enrollment_form_id,
                            religion, birthplace, email){

                            selected_course_id = parseInt(selected_course_id);
                            program_id = parseInt(program_id);
                            age = parseInt(age);
                            pending_enrollees_id = parseInt(pending_enrollees_id);

                            Swal.fire({
                                icon: 'question',
                                title: `Confirm Enrollment?`,
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'Cancel'

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '../../ajax/admission/pending_enrollment_approval.php',
                                        type: 'POST',
                                        data: {
                                            type,
                                            firstname, lastname, middle_name,
                                            password, program_id, civil_status, nationality, 
                                            contact_number, birthday, age, guardian_name, 
                                            guardian_contact_number, sex, student_status, 
                                            pending_enrollees_id, address, lrn, selected_course_id,
                                            enrollment_form_id, religion, birthplace, email
                                        },

                                        dataType: "json",

                                        success: function(response) {

                                            // console.log(response)
                                            if(response['status'] == "student_account_exist"){
                                                Swal.fire({
                                                    title: "Student already have an account.",
                                                    icon: "error",
                                                    showCancelButton: false,
                                                    confirmButtonText: "I understand and will verify.",
                                                });
                                            }

                                            if(response.student_id){

                                                Swal.fire({
                                                        title: "Enrollment Approved",
                                                        icon: "success",
                                                        showCancelButton: false,
                                                        confirmButtonText: "OK",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {

                                                        var student_id = parseInt(response.student_id);

                                                        var url = `./subject_insertion_summary.php?id=${student_id}&enrolled_subject=show`;

                                                        window.location.href = url;

                                                    } else {
                                                        // User clicked Cancel or closed the dialog
                                                    }
                                                });

                                            }

                                            // Swal.fire({
                                            //         title: "Enrollment Approved",
                                            //         icon: "success",
                                            //         showCancelButton: false,
                                            //         confirmButtonText: "OK",
                                            // }).then((result) => {
                                            //     if (result.isConfirmed) {


                                            //         // var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                                            //         // window.location.href = url;
                                            //     } else {
                                            //         // User clicked Cancel or closed the dialog
                                            //     }
                                            // });
                                          
                                        },
                                        error: function(xhr, status, error) {
                                            // handle any errors here
                                        }
                                    });
                                }
                            });
                        }
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

                                <button onclick='confirmPendingValidation("<?php echo $type ?>", "<?php echo $firstname ?>", "<?php echo $lastname ?>", "<?php echo $middle_name ?>", "<?php echo $password ?>", "<?php echo $program_id ?>", "<?php echo $civil_status ?>", "<?php echo $nationality ?>", "<?php echo $contact_number ?>", "<?php echo $birthday ?>", "<?php echo $age ?>", "<?php echo $guardian_name ?>", "<?php echo $guardian_contact_number ?>", "<?php echo $sex ?>", "<?php echo $student_status ?>", "<?php echo $pending_enrollees_id ?>", "<?php echo $address ?>", "<?php echo $lrn ?>", "<?php echo $selected_course_id ?>", "<?php echo $enrollment_form_id ?>", "<?php echo $religion ?>", "<?php echo $birthplace ?>", "<?php echo $email ?>")' class="default clean success large">
                                    Confirm
                                </button>
                            </div>
                        </main>
                    </div>

                    <script>
                        function confirmPendingValidation(type, firstname, lastname, middle_name, password,
                            program_id, civil_status, nationality, contact_number, birthday, age,
                            guardian_name, guardian_contact_number, sex, student_status,
                            pending_enrollees_id, address, lrn,
                            selected_course_id, enrollment_form_id,
                            religion, birthplace, email){

                            selected_course_id = parseInt(selected_course_id);
                            program_id = parseInt(program_id);
                            age = parseInt(age);
                            pending_enrollees_id = parseInt(pending_enrollees_id);

                            Swal.fire({
                                icon: 'question',
                                title: `Confirm Enrollment?`,
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'Cancel'

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '../../ajax/admission/pending_enrollment_approval.php',
                                        type: 'POST',
                                        data: {
                                            type,
                                            firstname, lastname, middle_name,
                                            password, program_id, civil_status, nationality, 
                                            contact_number, birthday, age, guardian_name, 
                                            guardian_contact_number, sex, student_status, 
                                            pending_enrollees_id, address, lrn, selected_course_id,
                                            enrollment_form_id, religion, birthplace, email
                                        },

                                        dataType: "json",

                                        success: function(response) {

                                            // console.log(response)
                                            if(response['status'] == "student_account_exist"){
                                                Swal.fire({
                                                    title: "Student already have an account.",
                                                    icon: "error",
                                                    showCancelButton: false,
                                                    confirmButtonText: "I understand and will verify.",
                                                });
                                            }

                                            if(response.student_id){

                                                Swal.fire({
                                                        title: "Enrollment Approved",
                                                        icon: "success",
                                                        showCancelButton: false,
                                                        confirmButtonText: "OK",
                                                }).then((result) => {
                                                    if (result.isConfirmed) {

                                                        var student_id = parseInt(response.student_id);

                                                        var url = `./subject_insertion_summary.php?id=${student_id}&enrolled_subject=show`;

                                                        window.location.href = url;

                                                    } else {
                                                        // User clicked Cancel or closed the dialog
                                                    }
                                                });

                                            }

                                            // Swal.fire({
                                            //         title: "Enrollment Approved",
                                            //         icon: "success",
                                            //         showCancelButton: false,
                                            //         confirmButtonText: "OK",
                                            // }).then((result) => {
                                            //     if (result.isConfirmed) {


                                            //         // var url = `../enrollees/subject_insertion.php?enrolled_subjects=true&id=${student_id}`;
                                            //         // window.location.href = url;
                                            //     } else {
                                            //         // User clicked Cancel or closed the dialog
                                            //     }
                                            // });
                                          
                                        },
                                        error: function(xhr, status, error) {
                                            // handle any errors here
                                        }
                                    });
                                }
                            });
                        }
                    </script>
                <?php
            }
        }
    }

    //  This will be the enrollment flow of Regular.

    // Return button (Check form Details), student table is now dependent not the pending table.

    // Return button (Find Section), student table is now dependent not the pending table.

    if(isset($_GET['st_id'])){

        $student_id = $_GET['st_id'];
        
        $student = new Student($con, $student_id);
        $student_subject = new StudentSubject($con);
        $enrollment = new Enrollment($con);

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

        // echo $student_status_st;

        $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");
        $student_suffix = $student->GetSuffix();
        $student_new_enrollee = $student->GetStudentNewEnrollee();

        $student_unique_id = $student->GetStudentUniqueId();

        // $student_program_section = $section->GetSectionName();

        // $student_enrollment_id = $enrollment->GetEnrollmentId($student_id,
        //     $student_course_id, $current_school_year_id);

        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);

        
       $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_retake_status = $enrollment->GetEnrollmentFormRetakeStatus($student_id,
            $student_enrollment_id, $current_school_year_id);
        
        $student_enrollment_is_transferee = $enrollment->GetEnrollmentFormIsTransferee($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_is_new = $enrollment->GetEnrollmentFormIsTransferee($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_evaluated_by_registrar = $enrollment->CheckEnrollmentFormRegistrarEvaluated($student_id,
            $student_enrollment_id, $current_school_year_id);

        // echo $student_enrollment_is_new;


        $student_enrollment_form_id = $enrollment->GetEnrollmentFormId($student_id,
            $student_enrollment_course_id, $current_school_year_id);
        
        $section = new Section($con, $student_enrollment_course_id);
        $student_program_section = $section->GetSectionName();
        $section_capacity = $section->GetSectionCapacity();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id === 0 ? $student_course_id : $student_enrollment_course_id);

        $student_enrollment_course_level = $section->GetSectionGradeLevel($student_enrollment_course_id === 0 ? $student_course_id : $student_enrollment_course_id);

        // echo $student_program_id;

        $program = new Program($con, $student_program_id);

        $student_program_acronym = $program->GetProgramAcronym();

        $enrollment_creation = $enrollment->GetEnrollmentDate($student_id,
            $student_course_id, $current_school_year_id);

        $enrollment_is_new_enrollee = $enrollment->GetEnrollmentIsNewEnrollee($student_id,
            $student_course_id, $current_school_year_id);

        $enrollment_is_transferee = $enrollment->GetEnrollmentIsTransferee($student_id,
            $student_course_id, $current_school_year_id);

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
</script>