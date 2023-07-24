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

        $program_department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $program_department_id);

        $program_department_name =  $department->GetDepartmentName();
        $department_id =  $department->GetDepartmentIdByName($program_department_name);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
        
        $back_url = "http://localhost/school-system-dcbt/registrar/admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

        $department_type = $program_department_name == "Senior High School" ? "SHS" : ( $program_department_name == "Tertiary" ? "Tertiary" : "");
 
        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>"><i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <main>
                    <!-- <?php include_once('./samp.php') ?> -->

                    <div class="floating">
                        <header class="mb-2">

                            <div class="title">
                                <h5 class="text-center text-primary">Available <?php echo $current_school_year_period;?> Semester Section Subjects for <?php echo $program_department_name ?></h5>
                            </div>

                        </header>
                        <main>

                            <table id="choosing_subject_tablex" class="a" style="margin: 0">
                                <thead>
                                    <tr class="text-center"> 
                                        <th rowspan="2">Course ID</th>
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Description</th>
                                        <th rowspan="2">Pre-Code</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Level</th>
                                        <th rowspan="2">Semester</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        $sql = $con->prepare("SELECT 
                                        
                                            t1.*
                                            ,t2.program_section
                                            ,t2.course_id

                                            ,t3.student_subject_id,
                                            t3.is_final AS ss_is_final,
                                            t3.enrollment_id AS ss_enrollment_id,
                                            t3.subject_program_id AS ss_subject_program_id,

                                            t3.is_transferee AS ss_is_transferee,
                                            t3.school_year_id AS ss_school_year_id,
                                            t3.course_id AS ss_course_id,
                                            t3.student_id AS ss_student_id,

                                            t4.student_subject_id AS ssg_student_subject_id
                                            
                                            FROM subject_program AS t1

                                            INNER JOIN course as t2 ON t2.program_id = t1.program_id
                                            AND t2.course_level = t1.course_level
 
                                            LEFT JOIN student_subject as t3 ON t1.subject_program_id = t3.subject_program_id
                                            AND t3.student_id=:student_id

                                            LEFT JOIN student_subject_grade AS t4 ON t4.student_subject_id = t3.student_subject_id
                                            AND t4.remarks = 'Passed'
                                            
                                            WHERE t1.department_type = :department_type
                                            AND t1.semester=:semester
                                            AND t2.active= 'yes'
                                            AND t2.school_year_term=:school_year_term
                                            -- AND t1.program_id= 4
                                            -- AND t1.course_level=12

                                            ORDER BY t1.course_level,t1.semester
                                        ");

                                        $sql->bindParam(":department_type", $department_type);
                                        $sql->bindParam(":semester", $current_school_year_period);
                                        $sql->bindParam(":school_year_term", $current_school_year_term);
                                        $sql->bindParam(":student_id", $student_id);

                                        $sql->execute();


                                        $semester_subjects = [];
                                        $inserted_semester_subjects = [];
                                    
                                        if($sql->rowCount() > 0){

                                            $semester_subjects = [];
                                            $inserted_semester_subjects = [];


                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                
                                                // $student_subject_id = $row['student_subject_id'];
                                                $subject_program_id = $row['subject_program_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $pre_req_subject_title = $row['pre_req_subject_title'];
                                                $sp_program_id = $row['program_id'];
                                                $unit = $row['unit'];
                                                $subject_type = $row['subject_type'];
                                                $semester = $row['semester'];
                                                $course_level = $row['course_level'];
                                                // $program_section = "";
                                                $program_section = $row['program_section'];
                                                // $course_id = 0;
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
                                                 
                                                $addAvailable = "addAvailable($subject_program_id, $current_school_year_id, $student_id, $student_enrollment_course_id, $enrollment_id, $course_id, \"$subject_code\")";

                                                // $subject_program = new SubjectProgram($con, $subject_program_id);



                                                $icon = "
                                                    <button onclick='$addAvailable' class='btn btn-primary btn-sm'>
                                                        <i class='fas fa-plus-circle'></i>
                                                    </button>
                                                ";

                                                // echo $ss_course_id;


                                                if($ss_subject_program_id == $subject_program_id
                                                    // && $ss_enrollment_id != NULL
                                                    && $ss_is_final  == 1
                                                    && $ss_is_transferee == 1
                                                    ){
                                                        $currentCredited = "currentCredited($enrollment_id, $student_id)";

                                                        // Enable crediting
                                                        if($ss_school_year_id == $current_school_year_id){
                                                            $icon = "
                                                                <button onclick='$currentCredited' class='btn btn-success btn-sm'>
                                                                   Current Credited
                                                                </button>
                                                            ";
                                                            array_push($inserted_semester_subjects, $subject_program_id);

                                                        }
                                                        // Disable crediting
                                                        else if($ss_school_year_id != $current_school_year_id){
                                                            $icon = "
                                                                <button disabled class='btn btn-success btn-sm'>
                                                                    Credited
                                                                </button>
                                                            ";
                                                        }
                                                }else if(
                                                    $ss_subject_program_id == $subject_program_id
                                                    && $ss_enrollment_id != NULL
                                                    && $ss_is_final  == 0
                                                    && $ss_is_transferee == 0
                                                    // && $ss_subject_code == $subject_code
                                                    // && $course_id == $ss_course_id
                                                       ){

                                                        // echo $subject_program_id;
                                                        array_push($inserted_semester_subjects, $subject_program_id);

                                                        // Comes from within Program and section enrollment course id BASED.
                                                        if($ss_course_id == $course_id
                                                            && $ss_enrollment_id == $enrollment_id
                                                            && $ss_student_id == $student_id
                                                            ){
                                                            $icon = "
                                                                <button class='btn btn-info btn-sm'>
                                                                    Taken
                                                                </button>
                                                            ";


                                                        // }else if($course_id != $ss_course_id){
                                                            
                                                        // Comes from Other Program. enrollment course id not BASED. Ex you`re stem but registrar
                                                        // selects HUMMS FOR PE101 subject code.
                                                        }
                                                        // else if(
                                                        //     // $student_enrollment_course_id != $ss_course_id
                                                        //     $student_enrollment_course_id != $ss_course_id
                                                        //     // && $ss_course_id == $course_id
                                                        //     && $ss_enrollment_id == $enrollment_id
                                                        //     && $ss_student_id == $student_id
                                                        // ){
                                                        //     $icon = "
                                                        //         <button class='btn btn-primary btn-sm'>
                                                        //             Taken Other
                                                        //         </button>
                                                        //     ";
                                                        // }
                                                        
                                                }
                                               

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$course_id</td>
                                                        <td>$subject_program_id</td>
                                                        <td>$subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$pre_req_subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$course_level</td>
                                                        <td>$semester</td>
                                                        <td>$sectionName</td>
                                                        <td>$icon</td>
                                                    </tr>
                                                ";
                                            }

                                            // print_r($inserted_semester_subjects);
                                            // print_r($semester_subjects);

                                          

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
                title: `Adding Subject Load ID: ${subject_program_id}`,
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

                                $('#choosing_subject_tablex').load(
                                    location.href + ' #choosing_subject_tablex'
                                );
                            })}

                            if(response == "taken_different_strand"){

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: `Subject ID: ${subject_program_id} has been already taken!`,
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