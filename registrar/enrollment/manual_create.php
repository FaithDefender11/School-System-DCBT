
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
        

        // $recordsPerPageOptions = ["Senior High", "Tertiary"]; 

        // print_r($recordsPerPageOptions);

        $changing_department_id = isset($_GET['selected_department_id']) 
            ? $_GET['selected_department_id']
            : $recordsPerPageOptions[0];

        $recordsPerPageRadios = '';

        foreach ($offeredDepartment as $option) {

            // $text = $option == 5 ? "Tertiary" : "Senior High";

            $name = $option['department_name'];

            $text = $option['department_name'] == "Tertiary" ? "Tertiary" : ($option['department_name'] == "Senior High School" ? "Senior High" : "");

            $recordsPerPageRadios .= "<div class='form-element'>";

            $label = " <label>$text</label>";
            $recordsPerPageRadios .= $label;
            
            $recordsPerPageRadios .= "<div>";

            $recordsPerPageRadios .= '<input type="radio" id="selected_department_id" name="selected_department_id" value="' . $option['department_id'] . '"';

            // if ($option['department_id'] == $changing_department_id) {
            //     $recordsPerPageRadios .= ' checked';
            // }

            $recordsPerPageRadios .= ' >';
            $recordsPerPageRadios .= "</div>";
            $recordsPerPageRadios .= "</div>";
        }

        // if(false)
        // {

        $admission_type_url = NULL;

        if(isset($_GET['admission_type'])){

            $admission_type_url = $_GET['admission_type'];

            // echo $admission_type_url;
        }

        // if($_SERVER['REQUEST_METHOD'] === 'POST' &&
        //     isset($_POST['enrollment_btn'])
        //     // && isset($_POST['program_id'])
        //     // && isset($_POST['selected_department_id'])
        //     && isset($_POST['admission_type'])
        //     // && isset($_POST['course_id'])

        //     ){

        //     $admission_type = intval($_POST['admission_type']);
        //     echo $admission_type;
        // }


        // if(false){

        if($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['enrollment_btn'])
            && isset($_POST['program_id'])
            && isset($_POST['selected_department_id'])
            && isset($_POST['admission_type'])
            && isset($_POST['course_id'])
            ){

            // Student
            $selected_department_id = $_POST['selected_department_id'];
            $program_id = $_POST['program_id'];

            $admission_type = intval($_POST['admission_type']);
            // echo $admission_type;
            // return;

            $course_id = intval($_POST['course_id']);
            
            $lrn = $_POST['lrn'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $firstname = $_POST['firstname'] ?? '';
            $middle_name = $_POST['middle_name'] ?? '';
            $suffix = $_POST['suffix'] ?? '';
            $civil_status = $_POST['civil_status'] ?? '';
            $nationality = $_POST['nationality'] ?? '';
            $sex = $_POST['sex'] ?? '';
            $birthday = $_POST['birthday'] ?? '';
            $religion = $_POST['religion'] ?? '';
            $birthplace = $_POST['birthplace'] ?? '';
            $address = $_POST['address'] ?? '';
            $contact_number = $_POST['contact_number'] ?? '';
            $email = $_POST['email'] ?? '';

            // Password -> July 25, 2023 -> 20230725 OR 123456
            $password = "123456";
            
            $student_unique_id = $student->GenerateUniqueStudentNumber();

            $section = new Section($con, $course_id);

            // Section selected level
            $course_level =  $section->GetSectionGradeLevel();

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
            // if(false){
            if($is_new_enrollee == 1 || $is_new_enrollee == 2){

                $addStudent = $student->InsertStudentFromEnrollmentForm($firstname, $lastname, $middle_name,
                    $password, $civil_status, $nationality, $contact_number, $birthday,
                    $age, $sex, $default_course_id, $student_unique_id, $default_course_level, 
                    $username, $address, $lrn, $religion, $birthplace,
                    $email, $is_tertiary, $is_new_enrollee);

                if($addStudent){

                    $student_id = $con->lastInsertId();
                    // Subject Review should finalized is Regular or Not.

                    $enrollment_student_status = "";

                    // Standard Regular -> Regular Enrollment Status
                    // No New Standard in 2nd Semester.
                    $current_school_year_period = "First";
                    if($admission_type == 1 && $current_school_year_period == "First"){
                        $enrollment_student_status = "Regular";

                    }
                    // New Transferee Either First or Second -> Irregular
                    else if($admission_type == 2 
                        // && $current_school_year_period == "Second"
                        ){
                        $enrollment_student_status = "Irregular";
                    }

                    // 2 is equal to Transferee
                    $is_transferee = $admission_type == 2 ? 1 : 0;

                    // $is_tertiary = "";

                    $newEnrollmentSuccess = $enrollment->InsertEnrollmentManualNewStudent($student_id,
                        $course_id, $current_school_year_id, $enrollment_form_id,
                        $enrollment_student_status, $is_tertiary, 
                        $is_transferee, $is_new_enrollee);

                    if($newEnrollmentSuccess){

                        $student_enrollment_id = $con->lastInsertId();

                        // Redirect to the Subject Review Page.
                        // Alert::success("Successfully manually created a Student Details",
                        //     "../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id");

                        $student_subject = new StudentSubject($con); 

                        // If New Standard Grade 11 and 1st Year Only
                        //  -> Subject Populated 
                        if($admission_type == 1){

                            $wasStudentSubjectPopulated = $student_subject
                            ->AddNonFinalDefaultEnrolledSubject($student_id, 
                                $student_enrollment_id, $course_id, $current_school_year_id,
                                $current_school_year_period);

                            if($wasStudentSubjectPopulated){

                                $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";

                                Alert::successAutoRedirect("Proceeding to Subject Review", 
                                    "$url");
                                // header("Location: ../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id");
                                exit();

                                // header("Location: ../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id");
                                // exit();
                            }
                        }

                        if($admission_type == 2){
                            // header("Location: ../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id");
                            
                            
                            $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                            Alert::successAutoRedirect("Proceeding to Subject Review", 
                                "$url");

                            exit();
                        }

                        
                    }
                }
            }


            $father_lastname = $_POST['father_lastname'] ?? '';
            $father_firstname = $_POST['father_firstname'] ?? '';
            $father_middle = $_POST['father_middle'] ?? '';
            $father_suffix = $_POST['father_suffix'] ?? '';
            $father_contact_number = $_POST['father_contact_number'] ?? '';
            $father_email = $_POST['father_email'] ?? '';
            $father_occupation = $_POST['father_occupation'] ?? '';

            // Guardian
            $mother_lastname = $_POST['mother_lastname'] ?? '';
            $mother_firstname = $_POST['mother_firstname'] ?? '';
            $mother_middle = $_POST['mother_middle'] ?? '';
            $mother_suffix = $_POST['mother_suffix'] ?? '';
            $mother_contact_number = $_POST['mother_contact_number'] ?? '';
            $mother_email = $_POST['mother_email'] ?? '';
            $mother_occupation = $_POST['mother_occupation'] ?? '';

            // Guardian
            $parent_lastname = $_POST['parent_lastname'] ?? '';
            $parent_firstname = $_POST['parent_firstname'] ?? '';
            $parent_middle_name = $_POST['parent_middle_name'] ?? '';
            $parent_suffix = $_POST['parent_suffix'] ?? '';
            $parent_contact_number = $_POST['parent_contact_number'] ?? '';
            $parent_email = $_POST['parent_email'] ?? '';
            $parent_occupation = $_POST['parent_occupation'] ?? '';
            $relationship = $_POST['relationship'] ?? '';


            // Output the values using echo with <br> tag

            // echo "LRN: " . $lrn . "<br>";
            // echo "Last Name: " . $lastname . "<br>";
            // echo "First Name: " . $firstname . "<br>";
            // echo "Middle Name: " . $middle_name . "<br>";
            // echo "Suffix: " . $suffix . "<br>";
            // echo "Civil Status: " . $civil_status . "<br>";
            // echo "Nationality: " . $nationality . "<br>";
            // echo "Sex: " . $sex . "<br>";
            // echo "Birthday: " . $birthday . "<br>";
            // echo "Religion: " . $religion . "<br>";
            // echo "Birthplace: " . $birthplace . "<br>";
            // echo "Address: " . $address . "<br>";
            // echo "Contact Number: " . $contact_number . "<br>";
            // echo "Email: " . $email . "<br>";
            // echo "Father's Last Name: " . $father_lastname . "<br>";
            // echo "Father's First Name: " . $father_firstname . "<br>";
            // echo "Father's Middle Name: " . $father_middle . "<br>";
            // echo "Father's Suffix: " . $father_suffix . "<br>";
            // echo "Father's Contact Number: " . $father_contact_number . "<br>";
            // echo "Father's Email: " . $father_email . "<br>";
            // echo "Father's Occupation: " . $father_occupation . "<br>";
            // echo "Mother's Last Name: " . $mother_lastname . "<br>";
            // echo "Mother's First Name: " . $mother_firstname . "<br>";
            // echo "Mother's Middle Name: " . $mother_middle . "<br>";
            // echo "Mother's Suffix: " . $mother_suffix . "<br>";
            // echo "Mother's Contact Number: " . $mother_contact_number . "<br>";
            // echo "Mother's Email: " . $mother_email . "<br>";
            // echo "Mother's Occupation: " . $mother_occupation . "<br>";
            // echo "Parent's Last Name: " . $parent_lastname . "<br>";
            // echo "Parent's First Name: " . $parent_firstname . "<br>";
            // echo "Parent's Middle Name: " . $parent_middle_name . "<br>";
            // echo "Parent's Suffix: " . $parent_suffix . "<br>";
            // echo "Parent's Contact Number: " . $parent_contact_number . "<br>";
            // echo "Parent's Email: " . $parent_email . "<br>";
            // echo "Parent's Occupation: " . $parent_occupation . "<br>";
            // echo "Relationship: " . $relationship . "<br>";
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
                                        <input
                                            
                                            type="radio"
                                            name="admission_type"
                                            value="1"
                                            <?php  echo $admission_type_url == 1 ? "checked" : ""; ?>
                                            <?php 
                                                echo $current_school_year_period == "Second" ? "disabled" : ""; 
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
                                        <?php  echo $admission_type_url == 2 ? "checked" : "";?>
                                    />
                                    </div>
                                </div>

                                <!-- <div class="form-element">
                                    <label for="Old">Ongoing Student</label>
                                    <div>
                                    <input
                                        type="radio"
                                        name="os_route"
                                        id="os_route"
                                    />
                                    </div>
                                </div> -->

                                <div class="form-element">
                                    <label for="Old">Ongoing Student2</label>
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
                            include_once('./new_student_type.php');
                        ?>
                        <hr>
                        <?php 
                            include_once('./new_student_form.php');
                        ?>

                        <?php 
                            // include_once('./old_student_type.php');
                        ?>
                        <?php 
                            // include_once('./old_student_form.php');
                        ?>




                <div class="action">
                    <button type="submit"
                        name="enrollment_btn"
                        class="default large" >
                        Proceed
                    </button>
                </div>

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

    //     $('#program_id').on('change', function() {

    //         var program_id = parseInt($(this).val());
    //         // console.log(program_id);

    //         $.ajax({
    //             url: '../../ajax/enrollment/populate_section.php',
    //             type: 'POST',
    //             data: {
    //                 program_id
    //             },
    //             dataType: 'json',

    //             success: function(response) {

    //                 // response = response.trim();
    //                 // console.log(response)
    //                 var options = "";
    //                 if (response.length === 0) {

    //                     // console.log('empty')
    //                     options += '<option value="NoSection">No Section</option>';
    //                     $('#course_id').html(options);

    //                     return;
    //                 }else{
    //                     $.each(response, function(index, value) {
    //                         var course_id = value.course_id;
    //                         var program_section = value.program_section;
    //                     });
                    

    //                     var options = '<option selected value="">Available Sections</option>';

    //                     $.each(response, function(index, value) {
    //                         options += '<option value="' + value.course_id + '">' + value.program_section + '</option>';
    //                     });

    //                     $('#course_id').html(options);
    //                 }
    //             }
    //         });
    //     });

    // });

</script>