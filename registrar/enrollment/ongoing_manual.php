
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

            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #enrolled_students_table_filter{
                margin-top: 12px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                }

                #enrolled_students_table_filter input{
                width: 250px;
                }
            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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

        if($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['ongoing_enrollment_btn_' . $enrollment_form_id])
            && isset($_POST['admission_type'])
            // && isset($_POST['course_id'])
            && isset($_POST['student_unique_id_val'])
            && $_POST['student_unique_id_val'] !== ""){

            // $course_id = intval($_POST['course_id']);
            $course_id = 0;
            $admission_type = intval($_POST['admission_type']);

            $student = new Student($con, $_POST['student_unique_id_val']);

            $student_id = intval($student->GetStudentId());

            // echo $course_id;

            // Equals to Ongoing Student
            if($admission_type == 3){


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

                    // $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                    $url = "../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id";
                    Alert::successAutoRedirect("Proceeding to Finding Section", 
                            $url);

                    exit();

                }else if($enrollment_student_status != "Regular"){

                    // $url = "../admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$course_id";
                    $url = "../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id";
                    Alert::successAutoRedirect("Proceeding to Finding Section", 
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
        else if(isset($_POST['student_unique_id_val']) &&
            $_POST['student_unique_id_val'] === ""){
            Alert::error("You forgot to put Student ID on the search box.", "");
            exit();
        }


        $allOngoingActive = $student->GetAllOngoingActive();

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

                    <br>
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
                                    <label for="Old">Ongoing Student</label>
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
                        <br>

                        <div class="floating" id="shs-sy">
                        
                            <header class="mb-2">
                                <div class="title">
                                    <h4 style="font-weight: bold;">Ongoing Enrollment Process</h4>
                                </div>
                            </header>

                            <main>

                                <?php 
                                
                                    if(count($allOngoingActive) > 0){

                                        ?>
                                            <table class="a" style="margin: 0">
                                                <thead>
                                                    <tr>
                                                        <th>Student ID</th>
                                                        <th>Name</th>
                                                        <th>Section</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    

                                                        foreach ($allOngoingActive as $key => $row) {
                                                        
                                                            $student_unique_id = $row['student_unique_id'];
                                                            $student_id = $row['student_id'];
                                                            $program_section = $row['program_section'];
                                                            $admission_status = $row['admission_status'];
                                                            $name = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

                                                            
                                                            $processForm = "processForm(\"$enrollment_form_id\", $student_id, $school_year_id)";

                                                            echo "
                                                                <tr>
                                                                    <td>$student_unique_id</td>
                                                                    <td>$name</td>
                                                                    <td>$program_section</td>
                                                                    <td>$admission_status</td>
                                                                    <td>
                                                                        <button type='button' onclick='$processForm' class='btn btn-primary'>
                                                                            Create form
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            ";

                                                        }

                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php
                                
                                    }
                                ?>
                            </main>

                        </div>





                    </main>

                    <br>
                    <main>

                        <div class="floating" id="shs-sy">
                        
                            <header class="mb-2">
                                <div class="title">
                                    <h4 style="font-weight: bold;">Ongoing Enrollment Process</h4>
                                </div>
                            </header>

                            <main>

                                <?php 
                                
                                    if(count($allOngoingActive) > 0){

                                        ?>
                                            <table id="ongoing_table" class="a" style="margin: 0">
                                                <thead>
                                                    <tr>
                                                        <th>Student ID</th>
                                                        <th>Name</th>
                                                        <th>Section</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            


                                        <?php
                                
                                    }
                                ?>
                            </main>

                        </div>

                    </main>

                </form>
            </div>
        </main>
    </div>
    <?php
?>
 

<script>




    function processForm(enrollment_form_id, student_id, school_year_id) {

        Swal.fire({
            icon: 'question',
            title: `This will create Student ID: ${student_id} an enrollment form: ${enrollment_form_id} ?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {

                // REFX

                $.ajax({
                    url: '../../ajax/enrollment/os_form_creation.php',
                    type: 'POST',
                    data: {
                        enrollment_form_id, student_id, school_year_id
                    },
                    // dataType: 'json',
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "has_already_enrollment_form"){
                            Swal.fire({
                                icon: 'error',
                                title: `Student had enrollment form already`,
                                showCancelButton: true,
                                confirmButtonText: 'Ok',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        }

                        if(response == "os_create_form_success"){

                            // var enrollment_id = parseInt(response.student_enrollment_id);

                            Swal.fire({
                                icon: 'success',
                                title: `Enrollment Form has been created..`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                window.location.href = `../admission/process_enrollment.php?find_section=show&st_id=${student_id}&c_id=0`;
                            }, 1300);
                        }
                        
                    },

                    error: function(xhr, status, error) {
                        // Handle error response here
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('Response Text:', xhr.responseText);
                        console.log('Response Code:', xhr.status);
                    }
                });
            }
        });
    }    
                            
    function handleRadioButtonClick() {

        // var radioValue = $('#admission_type').val(); // If you want to get the value of the radio button
        var radioValue = $('input[name="admission_type"]:checked').val();
        window.location.href = 'manual_create.php?admission_type=' + encodeURIComponent(radioValue);
        return;
    }

    $(document).ready(function() {

        var table = $('#ongoing_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'ongoingManualDataList.php',
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            // 'pageLength': 2,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for enrolled students."
            },
            'columns': [
                { data: 'student_id', orderable: false },  
                { data: 'name', orderable: false },  
                { data: 'section_name', orderable: false },  
                { data: 'status', orderable: false },  
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });

    
 </script>