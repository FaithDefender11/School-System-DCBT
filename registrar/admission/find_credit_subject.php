<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');


  
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

        // $student_enrollment_course_id = $student->GetStudentCurrentCourseId();
        // $student_enrollment_course_id= 805;

        $section = new Section($con, $student_enrollment_course_id);
        $section_name = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $student_course_level = $student->GetStudentLevel($student_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id, $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);


        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);
        
        $back_url = "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

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
                                <h4 class="text-center">Subject Curriculum</h4>
                            </div>

                        </header>
                        <main>

                            <table id="credited_subject_table" class="a" style="margin: 0">
                                <thead>
                                    <tr class="text-center"> 
                                        <th rowspan="2">SS_ID</th>
                                        <th rowspan="2">SP_ID</th>
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

                                        $sql = $con->prepare("SELECT 
                                        
                                            t1.*,

                                            t2.subject_program_id AS ss_subject_program_id,

                                            t2.is_transferee, t2.enrollment_id,
                                            t2.student_subject_id, t2.is_final,
                                            t2.student_id,
                                            t2.school_year_id,

                                            t3.student_subject_id AS ssg_student_subject_id
                                        
                                            FROM subject_program AS t1

                                            LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
                                           
                                            AND t2.student_id =:student_id


                                            LEFT JOIN student_subject_grade AS t3 ON t3.student_subject_id = t2.student_subject_id
                                            AND t3.remarks = 'Passed'
                                            AND t3.student_id = t2.student_id

                                            
                                            WHERE t1.program_id=:program_id
                                            -- AND t1.school_year_id=:school_year_id
                                            -- AND t1.is_final=1

                                            ORDER BY t1.course_level,t1.semester
                                        ");

                                        $sql->bindParam(":program_id", $student_program_id);
                                        $sql->bindParam(":student_id", $student_id);

                                        $sql->execute();
                                    
                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                
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

                                                $credit_btn = "creditNonAssignSubject($subject_program_id, $current_school_year_id, $student_id, \"$subject_title\", \"$subject_code\")";

                                                // ASSIGN Subjects
                                                // Subjects that are present in the current selected subject load list
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
                                                    && $is_final == 1){

                                                    if(
                                                        $enrollment_section_level == $course_level && 
                                                        $current_school_year_period == $semester){
                                                            // echo "Qwe";

                                                        $un_credit_assign_subject = "unCreditAssignSubject($subject_program_id, $current_school_year_id,
                                                            $student_id, \"$subject_title\",$student_subject_id, $enrollment_id, $student_enrollment_course_id, \"$student_subject_code\")";

                                                        $disabled = $ss_school_year_id == $current_school_year_id ? "" : "disabled";

                                                        $action = "
                                                            <button $disabled onclick='$un_credit_assign_subject' class='btn btn-success btn-sm'>
                                                                <i class='fas fa-undo'></i>
                                                            </button>
                                                        ";

                                                    }
                                                   
                                                }

                                                // Credited Subject but not course level 
                                                // && semester not assigned subject (default)

                                                if($ss_subject_program_id === $subject_program_id 
                                                    && $is_transferee == 1 
                                                    && $query_enrollment_id == NULL
                                                    && $ss_student_id == $student_id
                                                    && $is_final == 1){


                                                    $disabled = $ss_school_year_id == $current_school_year_id ? "" : "disabled";
 
                                                    $undo_credit_btn = "undoCreditNonAssignSubject($subject_program_id, $current_school_year_id, $student_id, \"$subject_title\")";

                                                    if(
                                                        $enrollment_section_level == $course_level && 
                                                        $current_school_year_period != $semester && 
                                                        $ss_school_year_id == $current_school_year_id

                                                        ){
                                                        $action = "
                                                            <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-undo'></i>
                                                            </button>
                                                        ";
                                                    }

                                                    if(
                                                        $enrollment_section_level != $course_level  &&
                                                        $ss_school_year_id != $current_school_year_id
                                                        // $current_school_year_period != $semester 
                                                        ){
                                                            $undo_credit_btn = "";
                                                        $action = "
                                                            <button $disabled onclick='$undo_credit_btn' class='btn btn-sm btn-danger'>
                                                                Credited
                                                            </button>
                                                        ";
                                                    }

                                                }
                                                else if($ss_subject_program_id !== $subject_program_id){
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

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$ss_subject_program_id</td>
                                                        <td>$subject_program_id</td>
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
        subject_code){

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

        //  Simply deleted the generated credited subject of creditNonAssignSubject
    function undoCreditNonAssignSubject(subject_program_id,
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
</script>