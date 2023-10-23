
<?php 

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

    echo Helper::RemoveSidebar();

    ?>

    <head>

        <script src="../../assets/js/enrollment/change_form.js"></script>
         
    </head>

    <?php

        if(isset($_GET['id'])){

            $enrollment_id = $_GET['id'];

            $school_year = new SchoolYear($con);

            $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

            $school_year_id = $school_year_obj['school_year_id'];
            $current_school_year_period = $school_year_obj['period'];
            $current_school_year_term = $school_year_obj['term'];
            $current_school_year_id = $school_year_obj['school_year_id'];



            $department = new Department($con);

            $enrollment = new Enrollment($con);

            $checkEnrollmentIdExists = $enrollment->CheckIdExists($enrollment_id);

            $student_id = $enrollment->GetStudentIdByEnrollmentId($enrollment_id,
                $school_year_id);



            $enrollment_new = $enrollment->GetEnrollmentFormIsNew($student_id,
                $enrollment_id, $current_school_year_id);
            
            $enrollment_transferee = $enrollment->GetEnrollmentFormIsTransferee($student_id,
                $enrollment_id, $current_school_year_id);

                
            $enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
                $enrollment_id, $current_school_year_id);

            $enrollment_is_tertiary = $enrollment->GetEnrollmentFormIsTertiary($student_id,
                $enrollment_id, $current_school_year_id);
            

            $student = new Student($con, $student_id);

            $student_course_id = $student->GetStudentCurrentCourseId();



            $previous_enrollment_form_id = $enrollment->GetEnrollmentFormId(
                $student_id, $student_course_id, $school_year_id);


            $section = new Section($con, $student_course_id);

            $program_id = $section->GetSectionProgramId($student_course_id);
            $department_id = $section->GetDepartmentIdByProgramId($program_id);


            $department = new Department($con, $department_id);
            $department_name = $department->GetDepartmentName();
            
            $student_type = "";
            $student_admission_status = $student->GetAdmissionStatus();

            $generateFormId = $enrollment->GenerateEnrollmentFormId($current_school_year_id);
            $enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($generateFormId);

            if (!isset($_SESSION['enrollment_form_id'])) {
                $_SESSION['enrollment_form_id'] = $enrollment_form_id;
            } else {
                $enrollment_form_id = $_SESSION['enrollment_form_id'];
            }


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

            $stored_course_name = "";
            

            if($_SERVER['REQUEST_METHOD'] === "POST" 
                && isset($_POST['change_form_btn_'.$enrollment_id])
                && isset($_POST['course_id'])
                
                ){

                
                $course_id = $_POST['course_id'];

                # Remove Previous Enrollment.
                # If the desired section is secured

                // echo $course_id;

                $newEnrollmentSuccess = $enrollment->ChangeFormInsertEnrollment(
                    $student_id,
                    $course_id, $current_school_year_id, $enrollment_form_id,
                    $enrollment_student_status, $enrollment_is_tertiary, 
                    $enrollment_transferee, $enrollment_new);

                if($newEnrollmentSuccess){
                    $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                    Alert::successAutoRedirect("Proceeding to Subject Review", 
                        "$url");
                    exit();
                }

                
            }

            $offeredDepartment = $department->GetOfferedDepartment();

            if(count($offeredDepartment) > 0){

                foreach ($offeredDepartment as $key => $value) {
                    # code...
                    array_push($recordsPerPageOptions, $value['department_id']);
                }
            }

            $recordsPerPageRadios = "";

            foreach ($offeredDepartment as $option) {

                $checked = ($option['department_id'] == $department_id) ? 'checked' : '';
                // $checked = '';

                $text = $option['department_name'] == "Tertiary" ? "Tertiary" : ($option['department_name'] == "Senior High School" ? "Senior High" : "");
                
                $recordsPerPageRadios .= "<div class='form-element'>";
                $recordsPerPageRadios .= "<label>$text</label>";
                $recordsPerPageRadios .= "<div>";

                $recordsPerPageRadios .= '<input type="radio" 
                    id=""
                    name="student_type"
                    value="' . $option['department_id'] . '" '.$checked.'>';

                $recordsPerPageRadios .= "</div>";
                $recordsPerPageRadios .= "</div>";

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
                                    <h4 style="color: var(--titleTheme)">Enrollment Form # <?php echo $enrollment_form_id;?></h4>
                                    <small class="mt-1">SY <?php echo $current_school_year_term ?> &nbsp; <?php echo $current_school_year_period?> Semester</small>
                                </div>

                                <span class="text-info">Previous Enrollment: #<?php echo $previous_enrollment_form_id; ?></span>
                            </header>


                            <form method="POST">
                                <main>
                                    <header>
                                        <div class="title">
                                            <h4>Admission type</h4>
                                        </div>
                                    </header>

                                    <div>
                                    
                                        <div class="row">
                                                
                                            <span>
                                                <div class="form-element">
                                                    <label for="newStudent">New Standard</label>
                                                    <div>
                                                        <input
                                                            type="radio"
                                                            name="admission_status"
                                                            value="New"
                                                            <?php 
                                                                echo $enrollment_new === 1 ? "checked" : "";
                                                            ?>
                                                        />
                                                    </div>
                                                </div>

                                                <div class="form-element">
                                                    <label for="newStudent">New Transferee</label>
                                                    <div>
                                                    <input
                                                        type="radio"
                                                        name="admission_type"
                                                        value="2"
                                                        <?php echo $enrollment_transferee === 1  ? "checked" : ""; ?>
                                                    />
                                                    </div>
                                                </div>
                
                                                <div class="form-element">
                                                    <label for="Old">Ongoing Student</label>
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

                                        <header>
                                            <div class="title">
                                                <h4>Student type</h4>
                                            </div>
                                        </header>

                                    </div>
                                        
                                    <div>
                                        <div class="row">
                                            <span>
                                                <?php 
                                                    echo $recordsPerPageRadios;
                                                ?>
                                            </span>
                                        </div>
                                    </div>



                                    <div>
                                        <header>
                                            <div class="title">
                                            <h4>Program & Section</h4>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                                <div class="form-element courseStrand">
                                                    <label>Choose Program</label>
                                                    <div>
                                                    
                                                        <select  style="width: 450px;" class='form-control'
                                                            name="program_id" id="program_id"
                                                        >
                                                            <?php 
                                                                // $program_id_val = Helper::DisplayText("program_id", "");

                                                                if($stored_program_name != ""){
                                                                    echo "
                                                                        <option value='$stored_program_id' selected >$stored_program_name</option>
                                                                    ";
                                                                }
                                                            
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-element courseStrand">
                                                    <label>Choose Section</label>
                                                    <div>
                                                        <select style="width: 350px;" 
                                                            class='form-control' name="course_id" id="course_id">
                                                        
                                                            <?php 
                                                                // $program_id_val = Helper::DisplayText("program_id", "");
                                                                if($stored_course_name != ""){
                                                                    echo "
                                                                    <option value='$stored_course_id' selected >$stored_course_name</option>
                                                                    ";
                                                                }
                                                            
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>

                                    </div>

                                </main>

                                <div style="margin-top:15px ;" class="action">
                                    <button type="submit"
                                        name="change_form_btn_<?php echo $enrollment_id?>"
                                        class="default large" >
                                        Proceed
                                    </button>
                                </div>
                            </form>
                        </div>

                    </main>
                </div>
            <?php
        }
        

    ?>



