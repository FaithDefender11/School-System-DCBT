<?php 

    $student_subject = new StudentSubject($con);

    // $check = $enrollment->changeYearFormat("2021-2022");
    // var_dump($check);


    $shsHasLRN = $pending->CheckEnrolleeLRNCompleted($pending_enrollees_id);


    // var_dump($shsHasLRN);
    $edit_url =  "pending_enrollee_edit.php?id=$pending_enrollees_id&update_lrn=true";
 
    if(isset($_POST['pending_choose_section_' . $pending_enrollees_id]) && isset($_POST['selected_course_id'])){

        // New (Standard start from the beginning of DCBT). 
        // Make sure it is First Semester

        # Default New student in Student Table
        # This will change accordingly  when the enrollment form was officially enrolled
        $new_enrollee = 1;
        $course_level = 0;

        if($new_enrollee_enrollment_status === "Regular"){
        // if($admission_status == "Standard"){
                
            // echo "qwe";

            $selected_course_id_value = $_POST['selected_course_id'];

            $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";

            // Pending Info to Student
            $generateStudentUniqueId = $student->GenerateUniqueStudentNumber();
            
            // $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.edu.ph';
            $username = $student->GenerateStudentUsername($lastname, $generateStudentUniqueId);

            $selected_course_id_value = $_POST['selected_course_id'];

            $username = NULL;
            $generateStudentUniqueId = NULL;
            $generated_enrollment_id = NULL;

            $successInsert = $student->InsertStudentFromPendingTable(
                $firstname, $lastname, $middle_name, $password,
                $civil_status, $nationality, $contact_number, $birthday, "",
                $sex, $course_id, $generateStudentUniqueId, $course_level, 
                $username, $address, $lrn, $religion,
                $birthplace, $email, $type, $new_enrollee);

            // echo $firstname;

            if($successInsert){

                $generated_student_id = $con->lastInsertId();

                // # CHECK FIRST IF STUDENT HAS A PARENT.
                // # UPDATE IF YES.
                // $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                //     $generated_student_id);
                
                // # Insert student id to the school history
                // $update_student_school_history = $pending->GetSchoolHistoryMatchPendingStudentId($pending_enrollees_id,
                //     $generated_student_id);

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
                $insert_enrollment->bindValue(':student_status', $new_enrollee_enrollment_status);

                if($insert_enrollment->execute()){

                    $generated_enrollment_id = $con->lastInsertId();

                    if($generated_enrollment_id != NULL){

                        # INSERT AUDIT TRAIL.

                        $selectec_section = new Section($con, $selected_course_id_value);
                        $sectionName = $selectec_section->GetSectionName();

                        $enrollmentAudit = new EnrollmentAudit($con);

                        ##
                        $registrarName = "";

                        if($registrarUserId != ""){
                            $user = new User($con, $registrarUserId);
                            $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                        }

                        $now = date("Y-m-d H:i:s");
                        $date_creation = date("M d, Y h:i a", strtotime($now));

                        $description = "Registrar '$registrarName' has been placed student section into '$sectionName' on $date_creation";

                        $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                            $generated_enrollment_id,
                            $description, $current_school_year_id, $registrarUserId
                        );
                        
                        // var_dump($doesAuditInserted);
                        // return;
                    }

                    # Populating Student_Subject base on Section Subjects as DEFAULT Behavior

                    $wasSuccessStudentSubject = $student_subject->AddNonFinalDefaultEnrolledSubject($generated_student_id,
                        $generated_enrollment_id,
                        $selected_course_id_value,
                        $current_school_year_id,
                        $current_school_year_period,
                        $admission_status);

                    if($wasSuccessStudentSubject){

                        // Approved Request
                        $pendingSuccess = $pending->SetPendingApprove($pending_enrollees_id);

                        if($pendingSuccess == true){

                            $url = "process_enrollment.php?subject_evaluation=show&selected_course_id=$selected_course_id_value";
                            $xd = "process_enrollment.php?step3=show&st_id=$generated_student_id&c_id=$selected_course_id_value";

                            $student_table_subject_review = "process_enrollment.php?subject_review=show&st_id=$generated_student_id&selected_course_id=$selected_course_id_value";

                            Alert::successAutoRedirect("New enrollee has been placed to a section.",
                                $student_table_subject_review);

                            exit();
                        }
                    }
                    
                }
            }
            
        }
        
        // New Transferee
        // If student is New and second Semester, then it should be New Transferee
        
        // if($admission_status == "Transferee"){
        if($new_enrollee_enrollment_status === "Irregular"){

            // echo "Selected";
            // return;

            $selected_course_id_value = $_POST['selected_course_id'];

            // echo $selected_course_id_value;

            $section_url = "process_enrollment.php?step3=true&id=$pending_enrollees_id&selected_course_id=$selected_course_id_value";
            // Pending Info to Student
            $generateStudentUniqueId = $student->GenerateUniqueStudentNumber();
            $username = strtolower($lastname) . '.' . $generateStudentUniqueId . 'dcbt.edu.ph';

            $selected_course_id_value = $_POST['selected_course_id'];

            $new_enrollee = 1;
            $age = 0;

            $username = NULL;
            $generateStudentUniqueId = NULL;

            $successCreation = $student->InsertStudentFromPendingTable(
                $firstname, $lastname, $middle_name, $password,
                $civil_status, $nationality, $contact_number, $birthday, $age,
                $sex, $course_id, $generateStudentUniqueId, $course_level, 
                $username, $address, $lrn, $religion,
                $birthplace, $email, $type, $new_enrollee);

            // $successCreation = $student->InsertStudentFromPendingTable($firstname, $lastname, $middle_name, $password, $civil_status, $nationality,
            //     $contact_number, $birthday, $age, $sex, 0,
            //     $generateStudentUniqueId,
            //     0, $username, $address, $lrn, $religion,
            //     $birthplace, $email,
            //     $type, $new_enrollee);
                
            // Course Id of New Transferee should be 0 
            if($successCreation == true){

                $generated_student_id = $con->lastInsertId();

                # CHECK FIRST IF STUDENT HAS A PARENT.
                # UPDATE IF YES.
                // $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                //     $generated_student_id);

                // if($update_parent == false){
                //     Alert::error("Attaching Parent has failed", "");
                //     exit();
                // }

                // if($insert_enrollment->execute()){ fadd
                // it should follow the enrollment course id into student table.

                // $successInsert = $student->InsertStudentFromPendingTable(
                //         $firstname, $lastname, $middle_name, $password,
                //         $civil_status, $nationality, $contact_number, $birthday, $age,
                //         $sex, $course_id, $generateStudentUniqueId, $course_level, 
                //         $username, $address, $lrn, $religion,
                //         $birthplace, $email, $type, $new_enrollee);
                        
                // if($successInsert == true){

                $insert_enrollment_id = null;
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
                $insert_enrollment->bindValue(':student_status', $new_enrollee_enrollment_status);

                if($insert_enrollment->execute()){

                }

                $insert_enrollment_id = $con->lastInsertId();

                // var_dump($insert_enrollment_id);

                if($insert_enrollment_id != NULL){

                    # INSERT AUDIT TRAIL.

                    $selectec_section = new Section($con, $selected_course_id_value);
                    $sectionName = $selectec_section->GetSectionName();

                    $enrollmentAudit = new EnrollmentAudit($con);

                    ##
                    $registrarName = "";

                    if($registrarUserId != ""){
                        $user = new User($con, $registrarUserId);
                        $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                    }

                    $now = date("Y-m-d H:i:s");
                    $date_creation = date("M d, Y h:i a", strtotime($now));

                    $description = "Registrar '$registrarName' has been placed student section into '$sectionName' on $date_creation";

                    $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                        $insert_enrollment_id,
                        $description, $current_school_year_id, $registrarUserId
                    );

                    // var_dump($doesAuditInserted);
                    // return;
                }

                // Approved Request
                $pendingSuccess = $pending->SetPendingApprove($pending_enrollees_id);

                if($pendingSuccess == true){


                    // $selectec_section = new Section($con, $selected_course_id_value);
                    // $sectionName = $selectec_section->GetSectionName();

                    // $enrollmentAudit= new EnrollmentAudit($con);

                    // $user = new User($con, $registrarUserId);

                    // $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());

                    // $description = "'$registrarName' has been inserted the Section into '$sectionName'";

                    // $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                    //     $insert_enrollment_id,
                    //     $description, $current_school_year_id, $registrarUserId
                    // );

                    $student_table_subject_review = "process_enrollment.php?subject_review=show&st_id=$generated_student_id&selected_course_id=$selected_course_id_value";

                    Alert::successAutoRedirect("New enrollee has been placed to a section.",
                        $student_table_subject_review);
                    exit();
                }

                // }
            }
        }


    }
?>

<div class="content">
    
    <div class="content-header">
        
        <?php 

            echo Helper::RevealStudentTypePending($type,
                $enrollee_enrollment_status, $admission_status); 
            
            ?>

        <?php echo Helper::PendingEnrollmentDetailsTop(null,
            $pending_enrollees_id,
            $enrollee_enrollment_status, $admission_status); ?>

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

        <?php 
            include_once('./pending_enrollment_details.php');
        ?>
        
        <div id="pending_available_section" class="floating">

            <header>
                <div class="title">
                    <!-- <h3>Available sections</h3> -->
                    <h4 style="font-weight: 350;">Available section <a style="font-size: 18px; color: inherit" href="../section/createe_section.php?id=<?= $program_id;?>&p_id=<?= $pending_enrollees_id;?>">+</a></h4>

                </div>
            </header>
            
            <?php 
            
                $availableSection = $section->GetAvailableFindSection(
                    $program_id,
                    $current_school_year_term,
                    $pending_course_level);


                if(count($availableSection) > 0){
                    ?>
                        <form method="post">
                            <main>
                                <table class="a">
                                    <thead>
                                        <tr class="text-center"> 
                                            <!-- <th rowspan="2">Section Id</th> -->
                                            <th rowspan="2">Section Name</th>
                                            <th rowspan="2">Enrolled</th>
                                            <th rowspan="2">Capacity</th>
                                            <th rowspan="2">Term</th>
                                            <th rowspan="2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
            

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
                                            $sql->bindValue(":active", "yes");
                                            $sql->bindParam(":school_year_term", $current_school_year_term);
                                            $sql->bindParam(":course_level", $pending_course_level);

                                            $sql->execute();
                                        

                                            foreach ($availableSection as $key => $get_course) {
                                                
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
                                                // <td>$course_id</td>

                                                echo "
                                                    <tr class='text-center'>
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
                                                

                                           
                                        ?>
                                    </tbody>
                                </table>
                            </main>

                            <div style="margin-top: 20px;" class="action">
                                <button
                                type="button"
                                    class="default large mr-2"
                                    onclick="window.location.href = 'process_enrollment.php?enrollee_details=true&id=<?php echo $pending_enrollees_id; ?>'">
                                    Return
                                </button>

                                <?php 
                                    if($enrollee_department_name == "Senior High School" 
                                        && $shsHasLRN){

                                        ?>
                                            <button class="default large success"
                                                name="pending_choose_section_<?php echo $pending_enrollees_id ?>" type="submit">
                                                Proceed
                                            </button>
                                        <?php
                                    }
                                    if($enrollee_department_name == "Senior High School" 
                                        && $shsHasLRN == false){
                                        ?>
                                            <button onclick="window.location.href = '<?=$edit_url?>'"
                                                    class="default large warning" type="button">

                                                Update Enrollee LRN
                                            </button>
                                        <?php
                                    }
                                    if($enrollee_department_name != "Senior High School"){

                                        ?>
                                            <button class="default large success"
                                                name="pending_choose_section_<?php echo $pending_enrollees_id ?>" type="submit">
                                                Proceed
                                            </button>
                                        <?php
                                    }
                                ?>
                                
                            </div>

                        </form>
                    <?php
                }
                else if(count($availableSection)  == 0){
                    echo "
                        <div class='col-md-12'>
                            <h4 class='text-center text-muted'>No currently available section for $program_acronym-$pending_course_level</h4>

                            <div style='margin-top: 20px; margin-bottom: -15px' class='action'>
                                <button type='button'
                                    class='default large'
                                    
                                    onclick=\"window.location.href = 'process_enrollment.php?enrollee_details=true&id=" . $pending_enrollees_id . "'\">
                                    Back
                                </button>
                            </div>
                        </div>
                    ";
                }
            ?>
        </div>
    </main>
</div>