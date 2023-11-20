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
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Schedule.php');

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

            #evaluation_table_filter{
            margin-top: 15px;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: start;
            margin-bottom: 7px;
            }

            #evaluation_table_filter input{
            width: 250px;
            }

        </style>

        <script src="choosing_subject_code.js"></script>

        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </head>

    <?php
  
    $school_year = new SchoolYear($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $pre_requisite_subject_code = "";

    if(isset($_GET['e_id']) && isset($_GET['st_id'])){

        $enrollment_id = $_GET['e_id'];
        $student_id = $_GET['st_id'];

        $student = new Student($con, $student_id);
        $enrollment = new Enrollment($con);

        // $student_enrollment_course_id = $student->GetStudentCurrentCourseId();

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $section = new Section($con, $student_enrollment_course_id);

        $studentSubject = new StudentSubject($con);
        $section_name = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $student_course_level = $student->GetStudentLevel($student_id);

        $program = new Program($con, $student_program_id);

        $program_name = $program->GetProgramAcronym();

        $program_department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $program_department_id);

        $subjectProgram = new SubjectProgram($con, $program_department_id);

        $program_department_name =  $department->GetDepartmentName();
        $department_id =  $department->GetDepartmentIdByName($program_department_name);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
        $enrollment_section_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        
        $back_url = "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

        $department_type = $program_department_name == "Senior High School" ? "SHS" : ( $program_department_name == "Tertiary" ? "Tertiary" : "");
 
        
        $selected_subject_program_id = NULL;
        $search_word = NULL;

        // echo $enrollment_section_level;

        $get = $subjectProgram->GetProgramSemesterAvailableSubjectCodes(
            $enrollment_section_program_id, $current_school_year_period, $enrollment_section_level);

        // var_dump($get);

        // if(in_array("Taekwondo 2", $get)){
              
        //     echo "Has";
        // }else{
        //     echo "not has";
        // }
        
        if(isset($_POST['search_student'])
            && isset($_POST['selected_subject_program_id'])
            && isset($_POST['search_word'])){

            $search_word = $_POST['search_word'];

            $selected_subject_program_id = $_POST['selected_subject_program_id'];
            // echo "Search results: $search_word";
        }


        $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

        $student_grade_record = "../student/record_details.php?id=$student_id&grade_records=show";

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>"><i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <main>
               
                    <div class="floating">
                        <header class="mb-2">

                            <div class="title">
                                <h3 style="font-weight: bold;" class="text-center text-primary">A.Y <?= "$current_school_year_term $period_short"?> Available subjects for <?php echo " <a title='View students grade records' href='$student_grade_record' target='_blank'>$program_name</a>" ?> </h3>
                            </div>

                        </header>

                        <div style="display: none;" class="filters">
                            <table>
                                <tr>
                                    <th rowspan="2" style="border-right: 2px solid black">
                                    Search by
                                    </th>
                                    <th><button>Subject Code</button></th>
                                </tr>
                            </table>
                        </div>

                        <input id="student_enrollment_id" value="<?php echo $enrollment_id ?>" type="hidden">
                        <input id="student_id" value="<?php echo $student_id ?>" type="hidden">

                        <form style="display: none;" id="choosingSubjectCodeForm" method="POST" class="p-3">
                            <div class="input-group">
                                <?php 
                                    $searchText = $search_word !== NULL ? $search_word : "";
                                ?>
                                
                                <input type="text" name="search_subject_code" 
                                    id="search_subject_code" 
                                    value="<?php echo htmlspecialchars($searchText); ?>"
                                    class="form-control form-control-lg rounded-0 border-info"
                                    placeholder="Search section subject code..." autocomplete="off">
                                

                                <div class="input-group-append">

                                    <input type="hidden" id="selected_subject_program_id" name="selected_subject_program_id">
                                    <input type="hidden" id="search_word" name="search_word">

                                    <button type="submit" name="search_student" class="btn btn-info btn-lg rounded-0">
                                        <i class="fas fa-undo"></i>
                                    </button>
              
                                </div>

                            </div>

                        </form>


                        <div class="col-md-8 show_search">
                            <div class="list-group" id="show_subject_code_list">
                                <!-- <a href="#" class="list-group-item list-group-item-action border-1">First</a>
                                <a href="#" class="list-group-item list-group-item-action border-1">First</a> -->
                            </div>
                        </div>

                        
                    </div>

                    <div class="floating">
                        <main>
                            <table id="choosing_subject_tablex" class="a" style="margin: 0">
                                <thead>
                                    <tr class="text-center"> 
                                        <th>Code</th>
                                        <th style="min-width: 190px;">Description</th>
                                        <th style="min-width: 80px;">Section</th>
                                        <th>Status</th>
                                        <th>Requisite</th>
                                        <th>Unit</th>
                                        <th>Level</th>
                                        <th>Semester</th>
                                        <th style="min-width: 190px;">Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                            </table>
                        </main>
                        
                        <script>
                            $(document).ready(function() {

                                var selected_student_id = `
                                    <?php echo $student_id; ?>
                                `;

                                var selected_enrollment_id = `
                                    <?php echo $enrollment_id; ?>
                                `;

                                selected_student_id = selected_student_id.trim();
                                selected_enrollment_id = selected_enrollment_id.trim();

                                var table = $('#choosing_subject_tablex').DataTable({
                                    'processing': true,
                                    'serverSide': true,
                                    'serverMethod': 'POST',
                                    'ajax': {
                                        'url': `choosingSubjectDataList.php?st_id=${selected_student_id}&e_id=${selected_enrollment_id}`,
                                        'error': function(xhr, status, error) {
                                            // Handle error response here
                                            console.error('Error:', error);
                                            console.log('Status:', status);
                                            console.log('Response Text:', xhr.responseText);
                                            console.log('Response Code:', xhr.status);
                                        }
                                    },

                                    'pageLength': 15,
                                    'language': {
                                        'infoFiltered': '',
                                        'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                                        'emptyTable': "No available data for enrolled students."
                                    },
                                    'columns': [
                                        { data: 'code', orderable: false },  
                                        { data: 'description', orderable: false },  
                                        { data: 'section', orderable: false },
                                        { data: 'status', orderable: false },
                                        { data: 'requisite', orderable: false },  
                                        { data: 'unit', orderable: false },  
                                        { data: 'level', orderable: false },  
                                        { data: 'semester', orderable: false },
                                        { data: 'time', orderable: false },
                                        { data: 'button_url', orderable: false }
                                    ],
                                    'ordering': true
                                });
                            });

                        </script>
                    </div>
                </main>
            </div>

        <?php

    }

?>

<script>

    function currentCredited(enrollment_id, student_id){
        Swal.fire({
                icon: 'question',
                title: `This subject is currently credited`,
                text: 'Note: Please Un-credit first before actually selecting as subject load.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = `find_credit_subject.php?e_id=${enrollment_id}&st_id=${student_id}`;
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
    
    function addAvailable(subject_program_id, current_school_year_id,
        student_id, student_enrollment_course_id, enrollment_id, course_id,
        subject_code, subject_schedule_arr, subject_title, doesFull){

        // console.log(subject_schedule_arr)

        Swal.fire({
                icon: 'question',
                title: `Adding Subject Code: ${subject_code}`,
                text: '',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {
                    // console.log('first')
                    $.ajax({
                        url: "../../ajax/admission/added_subject_load.php",
                        type: 'POST',
                        data: {
                            subject_program_id, current_school_year_id,
                            student_id, student_enrollment_course_id,
                            enrollment_id, course_id,
                            subject_schedule_arr: JSON.stringify(subject_schedule_arr),
                            doesFull
                        },

                        dataType: 'json',

                        success: function(response) {
                            // response = response.trim();

                            console.log(response);

                            if(response[0].output == "subject_is_full"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Not available!',
                                    text: `Subject '${subject_title}' is currently full`,
                                });
                            }

                            if (response[0].output == 'conflicted_schedule') {

                                // INSIDE THE CART SCHEDULE

                                let cart_schedule_time_from = response[0].conflicted_schedule_time_from;
                                let cart_schedule_time_to = response[0].conflicted_schedule_time_to;
                                let cart_schedule_day = response[0].conflicted_schedule_day;

                                let conflicted_schedule_course_name = response[0].conflicted_schedule_course_name;
                                let conflicted_schedule_subject = response[0].conflicted_schedule_subject;

 
                                // DESIRED SCHEDULE

                                let conflicted_desired_schedule_day = response[0].conflicted_desired_schedule_day;
                                let conflicted_desired_schedule_time_from = response[0].conflicted_desired_schedule_time_from;
                                let conflicted_desired_schedule_time_to = response[0].conflicted_desired_schedule_time_to;
                                
                                let conflicted_desired_schedule_course_name = response[0].conflicted_desired_schedule_course_name;
                                let conflicted_desired_schedule_subject = response[0].conflicted_desired_schedule_subject;

                                // Swal.fire({
                                //     icon: 'error',
                                //     title: 'Oh no!',
                                //     text: `Conflicted schedule occured.`,
                                // });

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no! Schedule Conflict',
                                    html: `<div><p>Cart Schedule: ${cart_schedule_time_from} - ${cart_schedule_time_to} <br> ( ${cart_schedule_day} )<br> Section: ${conflicted_schedule_course_name} <br> Subject: ${conflicted_schedule_subject}</p><p>Desired Schedule: ${conflicted_desired_schedule_time_from} - ${conflicted_desired_schedule_time_to} <br> ( ${conflicted_desired_schedule_day} )<br> Section: ${conflicted_desired_schedule_course_name} <br> Subject: ${conflicted_desired_schedule_subject}. <br> <br> Anyway, Do you want to still insert the subject?</p></div>`,
                                    showCancelButton: true,
                                    backdrop: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK',
                                    cancelButtonText: 'Cancel',
                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        
                                        $.ajax({
                                            url: "../../ajax/admission/continueAddSubject.php",
                                            type: 'POST',
                                            data: {
                                                subject_program_id, current_school_year_id,
                                                student_id, student_enrollment_course_id,
                                                enrollment_id, course_id
                                            },
                                            success: function(response) {

                                            Swal.fire({
                                                icon: 'success',
                                                title: `Successfully Added`,
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

                                                // $("#search_subject_code").val('');
                                                // $('#choosing_subject_tablex').load(
                                                //     location.href + ' #choosing_subject_tablex'
                                                // );

                                                location.reload();

                                            })}
                                        });
                                    }    
                                });
                                
                            }

                            if(response[0].output == "add_success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Added`,
                                showConfirmButton: false,
                                timer: 1300, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                // $("#search_subject_code").val('');
                                // $('#choosing_subject_tablex').load(
                                //     location.href + ' #choosing_subject_tablex'
                                // );

                                location.reload();

                                // window.location.href = `choosing_subject.php?e_id=${enrollment_id}&st_id=${student_id}`;
                            })}

                            if (response[0].output == "subject_already_taken") {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `Subject ID: ${subject_program_id} has been already taken!. Anyway, Do you want to insert the subject?`,
                                    showCancelButton: true,  // Show the "Cancel" button
                                    confirmButtonText: 'Proceed',  // Text for the "Proceed" button
                                    cancelButtonText: 'Cancel',    // Text for the "Cancel" button
                                    footer: '<p>Please use Student grade records as reference.</p>', // Additional text
                                }).then((result) => {

                                    if (result.isConfirmed) {

                                        $.ajax({
                                            url: "../../ajax/admission/continueAddSubject.php",
                                            type: 'POST',
                                            data: {
                                                subject_program_id, current_school_year_id,
                                                student_id, student_enrollment_course_id,
                                                enrollment_id, course_id
                                            },
                                            success: function(response) {

                                                // response[0].output = response.trim();
                                                // console.log(response);
                                                
                                                // if(response[0].output == "add_success"){
                                                    Swal.fire({
                                                    icon: 'success',
                                                    title: `Successfully Added`,
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

                                                    // $("#search_subject_code").val('');
                                                    // $('#choosing_subject_tablex').load(
                                                    //     location.href + ' #choosing_subject_tablex'
                                                    // );
                                                    location.reload();

                                                })}
                                            // }
                                        });
                                    } 

                                    // else if (result.isDismissed) {}
                                });
                            }

                            if(response[0].output == "already_credited"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `Subject ID: ${subject_program_id} has been already credited!`,
                                });
                            }

                            if(response[0].output == "failed_pre_requisite_of_selected_code"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `You had not taken or failed the subject pre-requisite of Subject Code: ${subject_code}.`,
                                });
                            }

                            if(response[0].output == "subject_prerequisite_not_taken"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `You had not taken yet the subject pre-requisite of Subject Code: ${subject_code}. Please check student grade records for better evaluation`,
                                });
                            }

                            if(response[0].output == "already_passed"){
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Validation!',
                                    text: `Student had already passed the selected subject code: ${subject_code}. Please check student grade record for more detailed evaluation.`,
                                });
                            }
                            
                        },

                        // error: function(xhr, status, error) {
                        //     // handle any errors here
                        //     console.error('Error:', error);
                        //     console.log('Status:', status);
                        //     console.log('Response Text:', xhr.responseText);
                        //     console.log('Response Code:', xhr.status);
                        // }

                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function unCreditAssignSubject(subject_program_id,
        current_school_year_id, student_id, subject_title,
        student_subject_id, enrollment_id,
        student_enrollment_course_id, student_subject_code){

        Swal.fire({
                icon: 'question',
                title: `I agreed to un-credit semester assigned subject: ${subject_title}`,
                text: '',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        url: "../../ajax/admission/crediting_subject.php",
                        type: 'POST',
                        data: {
                            subject_program_id,
                            current_school_year_id, student_id,
                            type: "unCreditEnrolledSubject",
                            student_subject_id, enrollment_id,
                            student_enrollment_course_id,
                            student_subject_code
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);
                            if(response == "un_credited_success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Uncredited`,
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

                                $('#credited_subject_table').load(
                                    location.href + ' #credited_subject_table'
                                );
                            });

                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function undoCreditSubjectAction(subject_program_id,
        current_school_year_id, student_id, subject_title){

        Swal.fire({
                icon: 'question',
                title: `I agreed to un-credit subject: ${subject_title}`,
                text: '',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/admission/crediting_subject.php",
                        type: 'POST',
                        data: {
                            subject_program_id,
                            current_school_year_id, student_id,
                            type: "Uncredit"
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);
                            if(response == "uncredited_success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Uncredited`,
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

                                $('#credited_subject_table').load(
                                    location.href + ' #credited_subject_table'
                                );
                            });

                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function creditSubjectAction(subject_program_id,
        current_school_year_id, student_id, subject_title){

        Swal.fire({
                icon: 'question',
                title: `Do you want to credit subject: ${subject_title}`,
                text: '',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/admission/crediting_subject.php",
                        type: 'POST',
                        data: {
                            subject_program_id,
                            current_school_year_id, student_id,
                            type: "Credit"
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);
                            if(response == "credited_success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Credited`,
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

                                $('#credited_subject_table').load(
                                    location.href + ' #credited_subject_table'
                                );
                            });

                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
    

    
    

</script>






