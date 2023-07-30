
<?php 

    // include_once('../../includes/config.php');
    include_once('../../includes/registrar_header.php');

    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Alert.php');
    include_once('../../includes/classes/StudentSubject.php');

    echo Helper::RemoveSidebar();

    ?>

    <head>
        <script src="../../assets/js/enrollment/manual_create,js"></script>
    </head>

    <?php

        // echo $registrarLoggedIn;


        $school_year = new SchoolYear($con);
        $department = new Department($con);
        $section = new Section($con, null);
        $student = new Student($con);

        $enrollment = new Enrollment($con);

        $generateFormId = $enrollment->GenerateEnrollmentFormId();
        $enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($generateFormId);
        
        if (!isset($_SESSION['enrollment_form_id'])) {
            
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];

            // $enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($enrollment_form_id);
        }

        $enrollment_manual_session = "new";
        if (!isset($_SESSION['enrollment_manual_session'])) {
            
            $_SESSION['enrollment_manual_session'] = $enrollment_manual_session;
        }

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        // echo $current_school_year_period;

        // $recordsPerPageOptions = [5, 4]; 
        $recordsPerPageOptions = []; 

        $offeredDepartment = $department->GetOfferedDepartment();
        if(count($offeredDepartment) > 0){

            foreach ($offeredDepartment as $key => $value) {
                # code...
                array_push($recordsPerPageOptions, $value['department_id']);
            }
        }
        

        // if( $_SERVER['REQUEST_METHOD'] === 'POST' &&
        //     isset($_POST['ongoing_enrollment_btn'])
        //     // && isset($_POST['program_id'])
        //     // && isset($_POST['selected_department_id'])
        //     // && isset($_POST['admission_type'])
        //     // && isset($_POST['course_id'])


        // ){
        //     $admission_type = intval($_POST['admission_type']);
        //     echo $admission_type;
        // }


        // if(false)

        if($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['ongoing_enrollment_btn'])
            && isset($_POST['admission_type'])
            && isset($_POST['course_id'])
            ){


            $course_id = intval($_POST['course_id']);
            $admission_type = intval($_POST['admission_type']);
            $student_id = intval($_POST['student_id']);
            
            if($admission_type == 3){

                $student = new Student($con, $student_id);


                $is_new_enrollee = 0;
                $is_tertiary = $student->GetIsTertiary();

                # Previous Student Status
                $student_status = $student->GetStudentStatus();

                $enrollment_student_status = $student_status;

                $is_transferee = 0;

                $newEnrollmentSuccess = $enrollment->InsertEnrollmentManualNewStudent($student_id,
                        $course_id, $current_school_year_id, $enrollment_form_id,
                        $enrollment_student_status, $is_tertiary, 
                        $is_transferee, $is_new_enrollee);

                if($enrollment_student_status == "Regular" 
                    && $newEnrollmentSuccess){

                    // Regular -> Subject Populated within the semester.
                    $student_enrollment_id = $con->lastInsertId();

                    $student_subject = new StudentSubject($con);

                    $wasStudentSubjectPopulated = $student_subject
                        ->AddNonFinalDefaultEnrolledSubject($student_id, 
                            $student_enrollment_id, $course_id, $current_school_year_id,
                            $current_school_year_period);

                    if($wasStudentSubjectPopulated){
                        // header("Location: ../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id");

                        $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                        Alert::successAutoRedirect("Proceeding to Subject Review", 
                            "$url");
                        // header("Location: ../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id");
                        exit();
                    }

                }else if($enrollment_student_status != "Regular"){

                    $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";

                    Alert::successAutoRedirect("Proceeding to Subject Review", 
                        "$url");

                    exit();
                }
            }

            // if(true){
            //     echo "LRN: " . $lrn . "<br>";
            //     echo "Last Name: " . $lastname . "<br>";
            //     echo "First Name: " . $firstname . "<br>";
            //     echo "Middle Name: " . $middle_name . "<br>";
            //     echo "Suffix: " . $suffix . "<br>";
            //     echo "Civil Status: " . $civil_status . "<br>";
            //     echo "Nationality: " . $nationality . "<br>";
            //     echo "Sex: " . $sex . "<br>";
            //     echo "Birthday: " . $birthday . "<br>";
            //     echo "Religion: " . $religion . "<br>";
            //     echo "Birthplace: " . $birthplace . "<br>";
            //     echo "Address: " . $address . "<br>";
            //     echo "Contact Number: " . $contact_number . "<br>";
            //     echo "Email: " . $email . "<br>";
            //     echo "Father's Last Name: " . $father_lastname . "<br>";
            //     echo "Father's First Name: " . $father_firstname . "<br>";
            //     echo "Father's Middle Name: " . $father_middle . "<br>";
            //     echo "Father's Suffix: " . $father_suffix . "<br>";
            //     echo "Father's Contact Number: " . $father_contact_number . "<br>";
            //     echo "Father's Email: " . $father_email . "<br>";
            //     echo "Father's Occupation: " . $father_occupation . "<br>";
            //     echo "Mother's Last Name: " . $mother_lastname . "<br>";
            //     echo "Mother's First Name: " . $mother_firstname . "<br>";
            //     echo "Mother's Middle Name: " . $mother_middle . "<br>";
            //     echo "Mother's Suffix: " . $mother_suffix . "<br>";
            //     echo "Mother's Contact Number: " . $mother_contact_number . "<br>";
            //     echo "Mother's Email: " . $mother_email . "<br>";
            //     echo "Mother's Occupation: " . $mother_occupation . "<br>";
            //     echo "Parent's Last Name: " . $parent_lastname . "<br>";
            //     echo "Parent's First Name: " . $parent_firstname . "<br>";
            //     echo "Parent's Middle Name: " . $parent_middle_name . "<br>";
            //     echo "Parent's Suffix: " . $parent_suffix . "<br>";
            //     echo "Parent's Contact Number: " . $parent_contact_number . "<br>";
            //     echo "Parent's Email: " . $parent_email . "<br>";
            //     echo "Parent's Occupation: " . $parent_occupation . "<br>";
            //     echo "Relationship: " . $relationship . "<br>";
            // }
            
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
                        <h2 style="color: var(--titleTheme)">Enrollment Form # <?php echo $enrollment_form_id;?></h2> 
                        <small class="mt-1">SY <?php echo $current_school_year_term ?> &nbsp; <?php echo $current_school_year_period?> Semester</small>
                    </div>
                    <p class="text-primary" id="student_status_attach"></p>
                    <h3 class="text-warning" id="non_fetch"></h3>

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
                                    <label for="newStudent">New Standard</label>
                                    <div>
                                        <a onclick="handleRadioButtonClick(); return false;">
                                            <input
                                                type="radio"
                                                name="admission_type"
                                                id="admission_type"
                                                value="1"
                                               <?php 
                                                    echo $current_school_year_period == "Second" ? "disabled" : ""; 
                                                ?>
                                            />
                                        </a>
                                    </div>
                                </div>

                                <div class="form-element">
                                    <label for="newStudent">New Transferee</label>
                                    <div>
                                        <a onclick="handleRadioButtonClick(); return false;">
                                            <input
                                                type="radio"
                                                name="admission_type"
                                                id="admission_type"
                                                value="2"
                                            />
                                        </a>
                                    </div>

                                </div>
                                
                                 
                                <div class="form-element">
                                    <label for="Old">Ongoing Student2</label>
                                    <div>
                                        <!-- <a href="page.php" onclick="document.getElementById('radioButton').click(); return false;"> -->
                                        <!-- </a> -->
                                        
                                        <!-- <input
                                            type="radio"
                                            name="radioButton"
                                            id="radioButton"
                                            checked
                                        /> -->
                                        <input
                                            type="radio"
                                            name="admission_type"
                                            id="admission_type"
                                            value="3"
                                            checked
                                        />
                                        
                                    </div>
                                </div>
                            </span>
                        </div>

                        <!-- GRADE LEVEL  -->
                        <?php 
                            include_once('./old_student_type.php');
                        ?>
                        <?php 
                            include_once('./old_student_form.php');
                        ?>

                    <div class="modal-footer">
                        <div class="action">
                            <button type="submit"
                                name="ongoing_enrollment_btn"
                                class="default large" >
                                Proceed
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php
?>
 

 <script>
    function handleRadioButtonClick() {

        // var radioValue = $('#admission_type').val(); // If you want to get the value of the radio button
        var radioValue = $('input[name="admission_type"]:checked').val();
        window.location.href = 'manual_create.php?admission_type=' + encodeURIComponent(radioValue);
        return;
    }
 </script>