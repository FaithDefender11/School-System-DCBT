<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

            // header("Location: procedure.php?information=show");
            // exit();

    $school_year_exec = new SchoolYear($con, $school_year_id);

    $enrollment_status = $school_year_exec->GetEnrollmentStatus();
    $startEnrollment = $school_year_exec->GetStartEnrollment();
    $endEnrollment = $school_year_exec->GetEndEnrollment();

    $now = date("Y-m-d H:i:s");

    // echo $enrollment_status;
    // echo $endEnrollment;

    if($enrollment_status == 0 || ($endEnrollment != null 
        && $endEnrollment < $now)){

        # STart of Enrollment is not yet set now.
        echo "
            <div class='container'>
                <div class='alert alert-warning mt-4'>
                    <strong>Daehan College of Business and Technology Online Enrollment is currently closed</strong> <br>Please check back later for enrollment availability.
                </div>
            </div>
            ";
        exit();
    }

    if(isset($_SESSION['username'])
        && isset($_SESSION['status']) 
        && $_SESSION['status'] == 'enrolled'){

            $enrollment = new Enrollment($con);
            
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

            if (!isset($_SESSION['enrollment_form_id'])) {
                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
                $_SESSION['enrollment_form_id'] = $enrollment_form_id;
                
            } else {
                $enrollment_form_id = $_SESSION['enrollment_form_id'];
            }

            $student = new Student($con, $_SESSION['username']);

            $student_id = $student->GetStudentId();

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
            $student_civil_status = $student->GetCivilStatus();
            $student_nationality = $student->GetNationality();
            $student_birthday = $student->GetStudentBirthdays();
            $student_birthplace = $student->GetStudentBirthPlace();
            $student_religion = $student->GetReligion();
            $student_email = $student->GetEmail();
            $student_course_id = $student->GetStudentCurrentCourseId();
            $student_admission_status = $student->GetAdmissionStatus();

            $student_level = $student->GetStudentLevel($student_id);

            // $student_level = 12;
            // $current_semester = "First";

            $student_level = $student_admission_status == "Old" 
                && $current_semester == "First" 
                ? $student_level + 1 : $student_level;
            
            // echo $student_level;
            $student_lrn = $student->GetStudentLRN();
            $student_status = $student->GetStudentStatus();

            $type_status = $student->GetIsTertiary();

            $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");

            $student_suffix = $student->GetSuffix();

            $student_unique_id = $student->GetStudentUniqueId();

            $section = new Section($con, $student_course_id);

            $student_program_section = $section->GetSectionName();
            $student_section_level = $section->GetSectionGradeLevel();
            $student__section_program_id = $section->GetSectionProgramId($student_course_id);

            $student_program_id = $section->GetSectionProgramId($student_course_id);
            $student_program_acronym = $section->GetAcronymByProgramId($student_program_id);

            $program = new Program($con, $student__section_program_id);

            $programName = $program->GetProgramAcronym();

            // echo $programName;

            if(isset($_GET['information']) && $_GET['information'] == "show"){
                include_once("./os_information.php");
            }

            if(isset($_GET['enrollment_details']) && $_GET['enrollment_details'] == "show"){
                include_once("./os_enrollment_details.php");
            }

            if(isset($_GET['validate_details']) && $_GET['validate_details'] == "show"){
                include_once("./os_validate_details.php");
            }

            if(isset($_GET['subject_summary']) && $_GET['subject_summary'] == "show"){

                if(isset($_POST['apply_next_semester_os_' . $student_id])){

                    // echo $student_status;
                    // Note. registrar in evaluation can modify the selected course id.
                    # System had choose only the previous section of student in their 1st semester (As DEFAULT).
                    

                    // $asd = $student_status == "Regular" ? $student_course_id : 0;
                    // student_course_id should be zero because the course section they was belong
                    // is now deactivated and had moved-up to new section.
                    
                    if($current_semester == "First"){
                        $enrollment_request_success = $enrollment->ApplyEnrollmentOS($student_id, 0, $school_year_id,
                            $enrollment_form_id, $student_status, $type);
                        
                        if($enrollment_request_success == true){

                            Alert::success("Success applied for S.Y $current_term $current_semester Semester",
                                "application_summary.php?e_id=$enrollment_form_id&id=$student_id");
                            exit();
                            
                        }
                    }
                    else if($current_semester == "Second"){
                        $enrollment_request_success = $enrollment->ApplyEnrollmentOS($student_id, $student_course_id,
                            $school_year_id, $enrollment_form_id, $student_status, $type);
                        
                        if($enrollment_request_success == true){

                            Alert::success("Success applied for S.Y $current_term $current_semester Semester",
                                "application_summary.php?e_id=$enrollment_form_id&id=$student_id");
                            exit();
                            
                        }
                    }
                    

                }
                ?>

                    <div class="content">

                        <main>
                            <div class="floating noBorder">

                                <header>
                                    <div class="title row">
                                        <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                        <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>

                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Enrollment Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Validate Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Finished</p></span>
                                </div>
                                <hr>

                                <form method="POST">
                                    <main>
                                        <div class="floating">

                                            <!-- REGULAR -> Populate Subjects -->
                                            <!-- IRREGULAR -> Should be evaluated by registrar. -->
                                            <?php 

                                                if($student_status == "Regular"){
                                                    
                                                    ?>
                                                        <header>
                                                            <div class="title">
                                                                <h3>Subjects for upcoming <?php echo "$programName-$student_level"; ?> <?php echo "&nbsp A.Y $current_term - $current_semester Semester";?></h3>
                                                            </div>
                                                        </header>

                                                        <form method="post">

                                                            <main>
                                                                <table class="a">
                                                                    <thead>
                                                                        <tr class="text-center"> 
                                                                            <th rowspan="2">Code</th>
                                                                            <th rowspan="2">Subject Title</th>
                                                                            <th rowspan="2">Unit</th>
                                                                            <th rowspan="2">Type</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php

                                                                            $active = "yes";

                                                                            # Only Available now.
                                                                            $sql = $con->prepare("SELECT 
                                                                            
                                                                                DISTINCT t2.subject_title, t2.subject_type, t2.unit, t2.subject_code

                                                                                FROM course AS t1
                                                                                INNER JOIN subject_program AS t2 ON t2.program_id = t1.program_id
                                                                                WHERE t2.program_id = :program_id
                                                                                AND t2.course_level = :course_level
                                                                                AND t2.semester = :semester
                                                                            ");

                                                                            $sql->bindParam(":program_id", $student_program_id);
                                                                            $sql->bindParam(":course_level", $student_level);
                                                                            $sql->bindParam(":semester", $current_semester);
                                                                            $sql->execute();
                                                                        
                                                                            if($sql->rowCount() > 0){

                                                                                while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                                    $subject_title = $get_course['subject_title'];
                                                                                    $subject_type = $get_course['subject_type'];
                                                                                    $unit = $get_course['unit'];
                                                                                    $subject_code = $get_course['subject_code'];
                                                                                    
                                                                                    echo "
                                                                                        <tr class='text-center'>
                                                                                            <td>$subject_code</td>
                                                                                            <td>$subject_title</td>
                                                                                            <td>$unit</td>
                                                                                            <td>$subject_type</td>
                                                                                        </tr>
                                                                                    ";
                                                                                    }
                                                                            }else{
                                                                                echo "
                                                                                    <div class='col-md-12'>
                                                                                        <h4 class='text-center text-muted'>No currently available section for $student_program_acronym-$student_level</h4>
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
                                                                    class="default large"
                                                                    onclick="window.location.href = 'procedure.php?validate_details=show'">
                                                                    Return
                                                                </button>
                                                                <button
                                                                    class="default large success"
                                                                    name="apply_next_semester_os_<?php echo $student_id;?>"
                                                                    type="submit">
                                                                    Apply for Next Semester
                                                                </button>
                                                            </div>
                                                        </form>

                                                    <?php
                                                }
                                                
                                                else if($student_status == "Irregular"){
                                                    ?>
                                                        <p>Note. Enrollee personel should evaluate you to know your required subjects for this <?php echo $current_semester;?> Semester</p>

                                                        <form method="post">

                                                            <div style="margin-top: 20px;" class="action">
                                                                <button
                                                                type="button"
                                                                    class="default large"
                                                                    onclick="window.location.href = 'procedure.php?validate_details=show'">
                                                                    Return
                                                                </button>
                                                                <button
                                                                    class="default large success"
                                                                    name="apply_next_semester_os_<?php echo $student_id;?>"
                                                                    type="submit">
                                                                    Apply for Next Semester
                                                                </button>
                                                            </div>
                                                        </form>
                                                    <?php
                                                }
                                            
                                            ?>
                                            


                                        </div>
                                    </main>

                                </form>


                            </div>
                        </main>

                    </div>
                <?php
            }
    }

?>