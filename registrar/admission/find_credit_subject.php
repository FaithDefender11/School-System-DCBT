<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Program.php');
    
    echo Helper::RemoveSidebar();

    ?>
        <header>
            <script src="find_credit_subject_code.js"></script>
        </header>
    <?php
  
    $school_year = new SchoolYear($con);


    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['e_id']) && isset($_GET['st_id'])){

        $enrollment_id = $_GET['e_id'];
        $student_id = $_GET['st_id'];

        $student = new Student($con, $student_id);
        $enrollment = new Enrollment($con);


        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);
 
        $section = new Section($con, $student_enrollment_course_id);
        $section_name = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $student_course_level = $student->GetStudentLevel($student_id);
        $student_username = $student->GetUsername();

        $student_firstname = $student->GetFirstName();
        $student_lastname = $student->GetLastName();
        $student_middle_name = $student->GetMiddleName();
        $student_email = $student->GetEmail();

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id, $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);


        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);
        
        $back_url = "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";


        $search_word = NULL;


        $subject_program = new SubjectProgram($con);


        $program = new Program($con, $student_program_id);

        $program_name = $program->GetProgramAcronym();


        $selected_subject_program_id = NULL;
        $search_word = NULL;

        if(isset($_POST['search_student'])
            && isset($_POST['selected_subject_program_id'])
            && isset($_POST['search_word'])
        ){

            $search_word = $_POST['search_word'];

            $selected_subject_program_id = $_POST['selected_subject_program_id'];

            // echo "Search results: $selected_subject_program_id";
        }

        $offeredCourseStrandCurriculum = $subject_program->GetCourseStrandCurriculum(
        $student_program_id,
        $student_id, $selected_subject_program_id);


        $student_grade_record = "../student/record_details.php?id=$student_id&grade_records=show";


        # If student is not enroleld yet, use their pending enrollees id.
        # If enrolled, use the student id.

        // var_dump($student_username);

        $enrollee_id = NULL;

        if($student_username == NULL){
            $enrollee_id = $student->GetNonEnrolledStudentPendingId($student_firstname,
                $student_lastname, $student_middle_name, $student_email);
        }

        // var_dump($enrollee_id);


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
                                <!-- <h4 class="text-center">School Subject Curriculum</h4> -->
                                <h3 style="font-weight: bold;" class="text-center text-primary">Curriculum subjects for <?php echo " <a title='View students grade records' href='$student_grade_record' target='_blank'>$program_name</a>" ?> </h3>

                            </div>

                            <div class="action">
                                <div class="dropdown">

                                    <button class="icon">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">

                                        <?php 
                                            if($student_username == NULL){

                                                ?>
                                                    <a onclick="window.open('../requirements/view_new_enrollee.php?id=<?= $enrollee_id;?>&clicked=true', '_blank');"
                                                        href="#" class="dropdown-item">
                                                            <i class="bi bi-file-earmark-x"></i>
                                                            View Credentials
                                                    </a>
                                                <?php

                                            }else{

                                                ?>
                                                    <a onclick="window.open('../requirements/view_student.php?id=<?= $student_id;?>', '_blank');"
                                                    href="#" class="dropdown-item">
                                                        <i class="bi bi-file-earmark-x"></i>
                                                        View Credentials
                                                    </a>
                                                <?php
                                            }
                                        ?>
                                       


                                    </div>

                                </div>
                            </div>

                        </header>

                        <!-- <div class="filters">
                            <table>
                                <tr>
                                    <th rowspan="2" style="border-right: 2px solid black">
                                    Search by
                                    </th>
                                    <th><button>Subject Code</button></th>
                                </tr>
                            </table>
                        </div> -->


                        <!-- <input type="hidden" id="student_program_id" value="<?php echo $student_program_id ?>"> -->
                        <!-- <input type="hidden" id="student_id" value="<?php echo $student_id ?>"> -->
                        <!-- <input type="hidden" id="subject_program_id" value="<?php echo $subject_program_id ?>"> -->


                        <!-- <form id="findCurriculumForm" method="POST" class="p-3">
                            <div class="input-group">
                                <?php 
                                    $searchText = $search_word !== NULL ? $search_word : "";
                                ?>
                                
                                <input type="text" name="search_curriculum_subject_code" 
                                    id="search_curriculum_subject_code" 
                                    value="<?php echo htmlspecialchars($searchText); ?>"

                                    class="form-control form-control-lg rounded-0 border-info"
                                    placeholder="Find subject code..." autocomplete="off">
                                

                                <div class="input-group-append">

                                    <input type="hidden" id="selected_subject_program_id"
                                        name="selected_subject_program_id">

                                    <input type="hidden" id="search_word" name="search_word">

                                    <button type="submit" name="search_student" class="btn btn-info btn-lg rounded-0">
                                        <i class="fas fa-undo"></i>
                                    </button>

                                </div>

                            </div>

                        </form> -->


                        <div class="col-md-8 show_search">
                            <div class="list-group" id="show_curriculum_subject_list">
                                <!-- <a href="#" class="list-group-item list-group-item-action border-1">First</a>
                                <a href="#" class="list-group-item list-group-item-action border-1">First</a> -->
                            </div>
                        </div>

                        
                    </div>


                    <div class="floating">

                        <main>

                            <?php 
                            
                                // if(false){
                                if(count($offeredCourseStrandCurriculum) > 0){
                                    ?>
                                        <table id="credited_subject_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr class="text-center"> 
                                                    <!-- <th rowspan="2">SS_ID</th> -->
                                                    <!-- <th rowspan="2">SP_ID</th> -->
                                                    <th rowspan="2">Code</th>
                                                    <th rowspan="2">Description</th>
                                                    <th rowspan="2">Unit</th>
                                                    <th rowspan="2">Type</th>
                                                    <th rowspan="2">Level</th>
                                                    <th rowspan="2">Semester</th>
                                                    <th rowspan="2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                    foreach ($offeredCourseStrandCurriculum as $key => $row) {

                                                        // $student_subject_id = $row['student_subject_id'];

                                                        $subject_program_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];
                                                        $semester = $row['semester'];
                                                        $course_level = $row['course_level'];
                                                        $ss_subject_program_id = $row['ss_subject_program_id'];
                                                        $is_transferee = $row['is_transferee'];
                                                        $student_subject_id = $row['student_subject_id'];
                                                        $query_enrollment_id = $row['enrollment_id'];
                                                        $is_final = $row['is_final'];
                                                        $ss_student_id = $row['student_id'];
                                                        $ssg_student_subject_id = $row['ssg_student_subject_id'];
                                                        $ss_school_year_id = $row['school_year_id'];

                                                        $student_subject_code = $section->CreateSectionSubjectCode($section_name,
                                                            $subject_code);

                                                        $action = "";

                                                        
                                                        $credit_btn = "creditNonAssignSubject($subject_program_id, $current_school_year_id, $student_id, \"$subject_title\", \"$subject_code\", $enrollment_id)";

                                                        // ASSIGN Subjects
                                                        // Subjects that are present in the current selected subject load list

                                                        // echo " ss_subject_program_id: $ss_subject_program_id";
                                                        // echo "<br>";

                                                        // echo " subject_program_id: $subject_program_id";
                                                        // echo "<br>";



                                                        if($ss_subject_program_id == $subject_program_id 
                                                            && $is_transferee == 0 && $is_final == 0){
                                                            
                                                            // if($student_course_level == $course_level
                                                            //     && $current_school_year_period == $semester){

                                                                $credit_assign_subject = "creditAssignSubject($subject_program_id, $current_school_year_id,
                                                                    $student_id, \"$subject_title\", $student_subject_id, \"$subject_code\")";

                                                                $action = "
                                                                    <button onclick='$credit_assign_subject' class='btn btn-success btn-sm'>
                                                                        <i class='fas fa-plus-circle'></i>
                                                                    </button>
                                                                ";
                                                            // }
                                                        }

                                                        // Default assigned subject credited.
                                                        else if($ss_subject_program_id === $subject_program_id 
                                                            && $is_transferee == 1 
                                                            && $query_enrollment_id == NULL
                                                            && $ss_student_id == $student_id
                                                            && $is_final == 1 ){

                                                                // echo "enrollment_section_level: $enrollment_section_level";
                                                                // echo "<br>";

                                                                // echo "course_level: $course_level";
                                                                // echo "<br>";

                                                            // Enrollment section that registrar placed you into it
                                                            // is equal to the course_level of curriculum subject.
                                                            if($enrollment_section_level == $course_level && 
                                                                $current_school_year_period == $semester){

                                                                $un_credit_assign_subject = "unCreditAssignSubject($subject_program_id, $current_school_year_id,
                                                                    $student_id, \"$subject_title\",$student_subject_id, $enrollment_id, $student_enrollment_course_id, \"$student_subject_code\")";

                                                                $disabled = $ss_school_year_id == $current_school_year_id ? "" : "disabled";

                                                                $undo_credit_btn = "undoCreditNonAssignSubject($subject_program_id, $enrollment_id, $current_school_year_id, $student_id, \"$subject_title\")";

                                                                $action = "
                                                                    <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                        <i class='fas fa-undo'></i>
                                                                    </button>
                                                                ";
                                                            }

                                                            // 
                                                            $disabled = $ss_school_year_id == $current_school_year_id ? "" : "disabled";

                                                            $undo_credit_btn = "undoCreditNonAssignSubject($subject_program_id, $enrollment_id, $current_school_year_id, $student_id, \"$subject_title\")";

                                                            if($enrollment_section_level == $course_level && 
                                                                $ss_school_year_id == $current_school_year_id){

                                                                $action = "
                                                                    <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                        <i class='fas fa-undo'></i>
                                                                    </button>
                                                                ";
                                                            }
                                                            // 

                                                            if($enrollment_section_level != $course_level 
                                                                && $ss_school_year_id == $current_school_year_id){

                                                                $undo_credit_btn = "undoCreditNonAssignSubject($subject_program_id, $enrollment_id, $current_school_year_id, $student_id, \"$subject_title\")";

                                                                $action = "
                                                                    <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                        <i class='fas fa-undo'></i> e
                                                                    </button>
                                                                ";
                                                            }

                                                            if($enrollment_section_level != $course_level
                                                                && $ss_school_year_id != $current_school_year_id){

                                                                $undo_credit_btn = "";

                                                                $action = "
                                                                    <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                        Credited
                                                                    </button>
                                                                ";
                                                            }
                                                            
                                                        }

                                                        if($ss_subject_program_id !== $subject_program_id){
                                                            $action = "
                                                                <button onclick='$credit_btn' class='btn btn-sm btn-primary'>
                                                                    <i class='bi bi-map'></i>
                                                                </button>
                                                            ";
                                                        }
                                                        else if($ss_subject_program_id !== $subject_program_id){
                                                            $action = "
                                                                <button onclick='$credit_btn' class='btn btn-sm btn-primary'>
                                                                    <i class='bi bi-map'></i>
                                                                </button>
                                                            ";
                                                        } 


                                                        if($ssg_student_subject_id == $student_subject_id
                                                            && $is_final == 1){
                                                            $action = "
                                                                <button class='btn btn-sm btn-success'>
                                                                    Passed
                                                                </button>
                                                            ";
                                                        }
                                                        //   <td>$ss_subject_program_id</td>
                                                        //         <td>$subject_program_id</td>

                                                        echo "
                                                            <tr class='text-center'>
                                                              
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                                <td>$course_level</td>
                                                                <td>$semester</td>
                                                                <td>
                                                                    $action
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }  
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php
                                }else{
                                    echo "
                                        <div style='height:150px; ' class='bg-dark col-md-12'>
                                            <h2 class='text-center'>No Curriculum Subjects are available.</h2>
                                        </div>
                                    ";
                                }
                            ?>
                        </main>
                    </div>
                </main>
            </div>

        <?php

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
    
    // Enrollment ID WILL BE NULL
    // FINALIZED
    function creditAssignSubject(subject_program_id,
        current_school_year_id, student_id,
        subject_title, student_subject_id, subject_code){

        Swal.fire({
                icon: 'question',
                title: `I agreed to credit semester assigned subject: ${subject_title}`,
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
                            type: "creditEnrolledSubject",
                            student_subject_id,
                            subject_code
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);
                            if(response == "credited_success"){
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
                                
                                $('#search_curriculum_subject_code').val('');

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
     
    // Revert the state of currently assigned subject column attributes.

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

                               $('#search_curriculum_subject_code').val('');

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



    function creditNonAssignSubject(subject_program_id,
        current_school_year_id, student_id, subject_title,
        subject_code, enrollment_id){

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
                            subject_code,
                            type: "Credit",
                            enrollment_id

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

                                $('#search_curriculum_subject_code').val('');

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

    //   Simply deleted the generated credited subject of creditNonAssignSubject
    
    function undoCreditNonAssignSubject(subject_program_id, enrollment_id,
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
                            enrollment_id,
                            current_school_year_id, 
                            student_id,
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

                                $('#search_curriculum_subject_code').val('');
                                
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