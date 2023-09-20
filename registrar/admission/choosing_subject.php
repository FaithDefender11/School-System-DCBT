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

    ?>
        <header>
            <script src="choosing_subject_code.js"></script>
        </header>
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
        $section_name = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $student_course_level = $student->GetStudentLevel($student_id);

        $program = new Program($con, $student_program_id);

        $program_name = $program->GetProgramAcronym();

        $program_department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $program_department_id);

        $program_department_name =  $department->GetDepartmentName();
        $department_id =  $department->GetDepartmentIdByName($program_department_name);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
        
        $back_url = "http://localhost/school-system-dcbt/registrar/admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

        $department_type = $program_department_name == "Senior High School" ? "SHS" : ( $program_department_name == "Tertiary" ? "Tertiary" : "");
 
        
        $selected_subject_program_id = NULL;
        $search_word = NULL;

        if(isset($_POST['search_student'])
            && isset($_POST['selected_subject_program_id'])
            && isset($_POST['search_word'])){

            $search_word = $_POST['search_word'];

            $selected_subject_program_id = $_POST['selected_subject_program_id'];
            // echo "Search results: $search_word";
        }

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
                                <h5 class="text-center text-info">Available <?php echo $current_school_year_period;?> Semester Section Subjects for <?php echo $program_name ?> </h5>
                            </div>

                        </header>

                        <div class="filters">
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

                        <form id="choosingSubjectCodeForm" method="POST" class="p-3">
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
                                        <!-- <th rowspan="2">Course ID</th> -->
                                        <!-- <th rowspan="2">ID</th> -->
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Description</th>
                                        <th rowspan="2">Requisite</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Level</th>
                                        <th rowspan="2">Semester</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Schedule</th>
                                        <th rowspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 

                                        $subject_program = new SubjectProgram($con);

                                        $sec_exec = new Section($con, $student_enrollment_course_id);

                                        // echo $student_enrollment_course_id;

                                        $student_enrollment_program_id_course_id = $sec_exec->GetSectionProgramId($student_enrollment_course_id);


                                        $availableSubjectCode = $subject_program->GetAvailableSubjectCodeWithinSemester(
                                            $department_type, $current_school_year_period, 
                                            $current_school_year_term, $student_id,
                                            $student_enrollment_program_id_course_id, 
                                            // $student_program_id, 
                                            $selected_subject_program_id
                                        );

                                        if(count($availableSubjectCode) > 0){

                                            $semester_subjects = [];
                                            $inserted_semester_subjects = [];
                                            $schedule_array = [];

                                            foreach ($availableSubjectCode as $key => $row) {
                                                # code...

                                                $subject_program_id = $row['subject_program_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_req_subject_title = $row['pre_req_subject_title'];
                                                $sp_program_id = $row['program_id'];
                                                $unit = $row['unit'];
                                                $subject_type = $row['subject_type'];
                                                $semester = $row['semester'];
                                                $course_level = $row['course_level'];
                                                $program_section = $row['program_section'];
                                                $course_id = $row['course_id'];
                                                $ss_course_id = $row['ss_course_id'];
                                                $ss_student_id = $row['ss_student_id'];
                                                $student_subject_id = $row['student_subject_id'];
                                                $ss_enrollment_id = $row['ss_enrollment_id'];
                                                $ss_subject_program_id = $row['ss_subject_program_id'];
                                                $ss_is_transferee = $row['ss_is_transferee'];
                                                $ss_is_final = $row['ss_is_final'];
                                                $ss_school_year_id = $row['ss_school_year_id'];
                                                $ssg_student_subject_id = $row['ssg_student_subject_id'];

                                                $section = new Section($con, $course_id);
                                                $sectionName = $section->GetSectionName();


                                                $sectionSubjectCode = $section->CreateSectionSubjectCode(
                                                    $sectionName, $subject_code
                                                );

                                                $schedule = new Schedule($con);

                                                $hasSubjectCode = $schedule->GetSameSubjectCode($course_id,
                                                    $sectionSubjectCode, $current_school_year_id);

                                                    // var_dump($hasSubjectCode);
                                                $allTime  = "";
                                                $allDays  = "";
                                                $scheduleOutput = "TBA";
                                                // if($hasSubjectCode !== NULL){
                                                if($hasSubjectCode !== []){

                                                    foreach ($hasSubjectCode as $key => $value) {

                                                        // $schedule_subject_code = $value['subject_code'];
                                                        
                                                        $schedule_day = $value['schedule_day'];
                                                        $schedule_time = $value['schedule_time'];
                                                        
                                                        // array_push($schedule_array, $schedule_time);

                                                        $allDays .= $schedule_day;
                                                        $allTime .= $schedule_time;

                                                        $scheduleOutput .= "$schedule_day - $schedule_time <br>";
                                                        // echo "<br>";
                                                    }

                                                   
                                                    
                                                }

                                                // var_dump($schedule_array);
                                                // echo "<br>";

                                                // echo $sectionSubjectCode;
                                                // echo "<br>";

                                                $addAvailable = "addAvailable($subject_program_id, $current_school_year_id, $student_id, $student_enrollment_course_id, $enrollment_id, $course_id, \"$subject_code\")";

                                                $icon = "
                                                    <button onclick='$addAvailable' class='btn btn-primary btn-sm'>
                                                        <i class='fas fa-plus-circle'></i>
                                                    </button>
                                                ";

                                                if ($ss_subject_program_id == $subject_program_id && $ss_is_final == 1 && $ss_is_transferee == 1) {
                                                    $currentCredited = "currentCredited($enrollment_id, $student_id)";

                                                    // Enable crediting
                                                    if ($ss_school_year_id == $current_school_year_id) {
                                                        $icon = "
                                                            <button onclick='$currentCredited' class='btn btn-success btn-sm'>
                                                                Current Credited
                                                            </button>
                                                        ";
                                                        array_push($inserted_semester_subjects, $subject_program_id);
                                                    }
                                                    // Disable crediting
                                                    else if ($ss_school_year_id != $current_school_year_id) {
                                                        $icon = "
                                                            <button disabled class='btn btn-success btn-sm'>
                                                                Credited
                                                            </button>
                                                        ";
                                                    }
                                                } else if ($ss_subject_program_id == $subject_program_id 
                                                    && $ss_enrollment_id != NULL && $ss_is_final == 0 
                                                    && $ss_is_transferee == 0) {

                                                    array_push($inserted_semester_subjects, $subject_program_id);

                                                    // Comes from within Program and section enrollment course id BASED.
                                                    if ($ss_course_id == $course_id 
                                                        && $ss_enrollment_id == $enrollment_id 
                                                        && $ss_student_id == $student_id) {
                                                        $icon = "
                                                            <button class='btn btn-info btn-sm'>
                                                                Taken
                                                            </button>
                                                        ";
                                                    }
                                                }
 
                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$pre_req_subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$course_level</td>
                                                        <td>$semester</td>
                                                        <td>$sectionName</td>
                                                        <td>$scheduleOutput</td>
                                                        ";
                                                        
                                                // Start your loop to populate values from $schedule_array
                                                // foreach ($schedule_array as $schedule_time) {
                                                //     echo "$schedule_time<br>"; // Assuming you want to separate the values with line breaks
                                                // }

                                                echo "</td>
                                                        <td>$icon</td>
                                                    </tr>
                                                ";


                                            }

                                        }
                                        else{
                                            echo "
                                                <div style='height:150px;' class='bg-info col-md-12'>
                                                    <h2 class='text-center'>No Subject Code Available.</h2>
                                                </div>
                                            ";
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
        subject_code){

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
                            enrollment_id, course_id
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);

                            if(response == "add_success"){
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

                                $("#search_subject_code").val('');
                                $('#choosing_subject_tablex').load(
                                    location.href + ' #choosing_subject_tablex'
                                );

                                // window.location.href = `choosing_subject.php?e_id=${enrollment_id}&st_id=${student_id}`;
                            })}

                            // if(response == "taken_different_strand"){

                            //     Swal.fire({
                            //         icon: 'error',
                            //         title: 'Oh no!',
                            //         text: `Subject ID: ${subject_program_id} has been already taken!`,
                            //     });
                            // }
                            if (response == "subject_already_taken") {
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

                                                // response = response.trim();
                                                // console.log(response);
                                                
                                                // if(response == "add_success"){
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

                                                    $("#search_subject_code").val('');
                                                    $('#choosing_subject_tablex').load(
                                                        location.href + ' #choosing_subject_tablex'
                                                    );
                                                })}
                                            // }
                                        });
                                    } 
                                    // else if (result.isDismissed) {}
                                });
                            }

                            if(response == "already_credited"){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `Subject ID: ${subject_program_id} has been already credited!`,
                                });
                            }
                            if(response == "failed_pre_requisite_of_selected_code"){

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `You had not taken or failed the subject pre-requisite of Subject Code: ${subject_code}.`,
                                });

                            }
                            if(response == "subject_prerequisite_not_taken"){

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `You had not taken yet the subject pre-requisite of Subject Code: ${subject_code}.`,
                                });

                            }
                            if(response == "already_passed"){

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Validation!',
                                    text: `Student had already passed the selected subject code: ${subject_code}. Please check student grade record for more detailed advicing.`,
                                });
                            }

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Response Code:', xhr.status);
                        }
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






