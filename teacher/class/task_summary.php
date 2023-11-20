<?php
    // include_once('../../includes/config.php');
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
 

    ?>
        <head>
                <!--Link JavaScript-->
            <script src="../../assets/js/elms-sidebar.js" defer></script>
            <script src="../../assets/js/elms-dropdown.js" defer></script>
        </head>
    <?php

    ?>
        <style>
            th a {
                text-decoration: underline;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>

    <?php

    if(isset($_GET['ct_id'])){
 
        $subject_period_code_topic_id = $_GET['ct_id'];

        // $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
        //     $con, $subject_period_code_topic_template_id);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

 
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicTemplateId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $course_id = $subjectPeriodCodeTopic->GetCourseId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();
        $subject_program_id = $subjectPeriodCodeTopic->GetSubjectProgramId();

        $enrollment =  new Enrollment($con);

        $subjectProgram =  new SubjectProgram($con, $subject_program_id);
        $subject_title = $subjectProgram->GetTitle();

        $fomatTerm = $enrollment->changeYearFormat($current_school_year_term);
        $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");


        $assignmentListOnTeachingCode = $subjectCodeAssignment->GetSubjectAssignmentBasedOnTeachingSubject(
            $subject_code,
            $school_year_id,
            $teacherLoggedInId,
            true
        );

        $total_subject_score = NULL;

        $subjectCodeAssignmentIds = [];

        $now = date("Y-m-d H:i:s");

        foreach ($assignmentListOnTeachingCode as $key => $value) {
            # code...
            $scores = $value['max_score'];

            $subject_code_assignment_id = $value['subject_code_assignment_id'];

            $due_date =  $value['due_date'];

            $total_subject_score += $scores;

            array_push($subjectCodeAssignmentIds, $subject_code_assignment_id);

        }
     
        $studentGradeBook = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject(
            $subject_code,
            $school_year_id);
 
        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

        $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects(
            $teacherLoggedInId,
            $school_year_id);

        $teachingSubjects = [];


        foreach ($teachingSubjectCode as $key => $value) {

            $teachingCode = $value['subject_code'];
            array_push($teachingSubjects, $teachingCode);
        }

        $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {

            $base_url = 'http://localhost/school-system-dcbt/teacher/';
        } else {

            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/teacher/';
        }

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/teacher/", "", $base_url);
            $logout_url = "$new_url/lms_logout.php";
        }

    ?>
        <div style="min-width: 100%;" class="content">

            <?php 
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "first",
                    "second",
                    $logout_url,"second");
                
            ?>

            <div class="content-header">
                <header>

                    <div class="title">
                        <h1><span style="font-size: 27px;"><?= $subject_title?></span>  <em style="font-size: 27px;"><?= "SY$fomatTerm-$period_short";?></em></h1>
                    </div>

                </header>
            </div>

            <?php 

                if(isset($_GET['calendar_clicked']) == false){
                    ?>
                     <nav>
                        <a href="<?= $back_url; ?>">
                            <i class="bi bi-arrow-return-left"></i>
                            Back
                        </a>
                    </nav>
                    <?php
                }

            ?>
           
            

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Student Grade Book</h3>
                            <small>Section Overview</small>
                        </div>
                    </header>
                    <main>
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Tasks</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Category</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Due</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Students</th>
                                            <th colspan="2">Overall</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $stud = $con->prepare("SELECT 
                                            t3.firstname
                                            ,t3.lastname
                                            ,t3.student_unique_id
                                            ,t3.student_id

                                            FROM student_subject as t1
                                            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
                                            AND t2.enrollment_status = 'enrolled'

                                            INNER JOIN student as t3 ON t3.student_id = t2.student_id


                                            WHERE t1.subject_code=:subject_code
                                            AND t1.school_year_id=:school_year_id
                                            
                                            GROUP BY t3.student_id
                                            ");

                                            $stud->bindParam(":subject_code", $subject_code);
                                            $stud->bindParam(":school_year_id", $school_year_id);
                                            $stud->execute();

                                            if($stud->rowCount() > 0){

                                                $output_max_score = 0;

                                                while($row_stud = $stud->fetch(PDO::FETCH_ASSOC)){
                                                    
                                                    $student_id = $row_stud['student_id'];
                                                    $firstname = ucwords(trim($row_stud['firstname']));
                                                    $lastname = ucwords(trim($row_stud['lastname']));

                                                    $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                    // Check if the length of the fullname exceeds 10 characters
                                                    if (strlen($fullname) > 18) {
                                                        // Trim the fullname to 10 characters and add ellipsis
                                                        $fullname = substr($fullname, 0, 18) . "...";
                                                    }

                                                    $student_points = 0;
                                                    
                                                    $getAllSubmissionsPointsToSubjectCode = $subjectAssignmentSubmission
                                                        ->GetSubjectSubmissionTotalPoints($subjectCodeAssignmentIds,
                                                            $student_id, $school_year_id);

                                                        // var_dump($subjectCodeAssignmentIds);
                                                    
                                                    $qualifiedMaxScore = $subjectAssignmentSubmission
                                                        ->GetOverscoreFromAssignmentAnswered($subjectCodeAssignmentIds,
                                                            $student_id, $school_year_id);

                                                    foreach ($getAllSubmissionsPointsToSubjectCode as $key => $value) {
                                                        $student_points += $value;
                                                    }

                                                    $rounded_equivalent = "";

                                                    // $rounded_equivalent = floor($equivalent / 10) * 10;
                                                    // var_dump($qualifiedMaxScore);

                                                    if($qualifiedMaxScore > 0){
                                                        $equivalent = ($student_points / $qualifiedMaxScore) * 100;
                                                        $rounded_equivalent = round($equivalent, 0, PHP_ROUND_HALF_UP);

                                                    }
                                                            // <td>$student_points / $qualifiedMaxScore = $rounded_equivalent% </td>

                                                    echo "
                                                        <tr>
                                                             <td style='font-size: 15px'>
                                                                    
                                                                       <a style='text-decoration: none; color: inherit;'
                                                                            href='student_module_audit.php?id=$student_id&code=$subject_code&sy_id=$school_year_id'>
                                                                            $lastname
                                                                       </a> 

                                                                    </td>
                                                            <td>$firstname</td>
                                                            <td></td>
                                                            <td>$rounded_equivalent% </td>
                                                        </tr>
                                                    ";
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-8">
                                <table class="table table-hover" id="section_topic_grading_table">
                                    <thead>
                                        <?php
                                            echo "<tr>";

                                            foreach ($assignmentListOnTeachingCode as $key => $row) {

                                                $assignment_name = $row['assignment_name'];

                                                $subject_code_assignment_id = $row['subject_code_assignment_id'];

                                                // echo $subject_code_assignment_id;
                                                // echo "<br>";
                                                $url = "edit.php?id=$subject_code_assignment_id";
                                                
                                                echo "
                                                    <th>
                                                        <a href='$url'>
                                                            $assignment_name
                                                        </a>
                                                    </th>
                                                ";

                                            }

                                            echo "</tr>";

                                            # CATEGORY

                                            echo "<tr>";

                                                foreach ($assignmentListOnTeachingCode as $key => $row_due) {

                                                    $task_type = $row_due['task_type'];

                                                
                                                    
                                                    echo "
                                                        <th>
                                                            <a>
                                                                $task_type
                                                            </a>
                                                        </th>
                                                    ";

                                                }

                                            echo "</tr>";

                                            echo "<tr>";

                                                foreach ($assignmentListOnTeachingCode as $key => $row_due) {

                                                    $due_date = $row_due['due_date'];

                                                    $due_date = date("M d",
                                                        strtotime($due_date));
                                                    
                                                    echo "
                                                        <th>
                                                            <a>
                                                                $due_date
                                                            </a>
                                                        </th>
                                                    ";

                                                }

                                            echo "</tr>";

                                            echo "<tr>";

                                                foreach ($assignmentListOnTeachingCode as $key => $row) {

                                                    $max_score = $row['max_score'];

                                                    echo "
                                                        <th>
                                                            <a>
                                                                $max_score
                                                            </a>
                                                        </th>
                                                    ";

                                                }

                                            echo "</tr>";

                                            foreach ($studentGradeBook as $key => $row_stud) {

                                                $student_id = $row_stud['student_id'];
                                                $firstname = $row_stud['firstname'];
                                                $lastname = $row_stud['lastname'];
                                                $fullname = ucwords($firstname) . " " . ucwords($lastname);
                                            

                                                echo "<tr style='margin-top:20px'>";

                                                    foreach ($assignmentListOnTeachingCode as $key => $row_query2) {
                                                        
                                                        $subject_code_assignment_id =  $row_query2['subject_code_assignment_id'];

                                                        // echo $subject_code_assignment_id;
                                                        // echo "<br>";

                                                        $max_score =  $row_query2['max_score'];

                                                        $due_date =  $row_query2['due_date'];
                                                        $now = date("Y-m-d H:i:s");
                                                        $doesPastDue = false;

                                                        if(strtotime($due_date) <= strtotime($now)){
                                                            // echo "expire";
                                                            $doesPastDue = true;

                                                        }



                                                        $gradeStudent = "gradeStudent($student_id, $subject_code_assignment_id, $max_score, $current_school_year_id)";

                                                        $status = "";


                                                        if($doesPastDue == false){

                                                            # DEFAULT STAGE.
                                                            $status = "
                                                                <div id='statusContainer'>
                                                                    <button class='btn btn-sm btn-primary' id='statusButton'>
                                                                        <i class='fas fa-marker'></i>
                                                                    </button>
                                                                </div>
                                                            ";

                                                            # WHEN IT WAS CLICKED.

                                                            $status = "
                                                                <div style='justify-content: center;' class='row'>
                                                                    <input style='width: 60px' autocomplete='off' maxLength='3' type='text' name='grade_input_$student_id-_$subject_code_assignment_id' id='grade_input_$student_id-_$subject_code_assignment_id'>
                                                                    
                                                                    <button title='Add grade' style='margin-left: 4px' onclick='$gradeStudent'>
                                                                        <i style='color:blue' class='fas fa-pencil'></i>
                                                                    </button>
                                                                </div>
                                                            ";

                                                        }
                                                        else{
                                                            $status = "
                                                                <i title='Past Due' style='color: orange;' class='fas fa-flag'></i>
                                                            ";
                                                        }
                                                        

                                                        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

                                                        $checkSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                            $subject_code_assignment_id,
                                                            $student_id, $school_year_id);

                                                        $submission_grade = "~";

                                                        if($checkSubmission !== NULL){

                                                            $subject_assignment_submission_id = $checkSubmission['subject_assignment_submission_id'];

                                                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
                                                                $subject_assignment_submission_id);
                                                                
                                                            $submission_grade = $subjectAssignmentSubmission->GetSubjectGrade();

                                                            $to_check_assignment_url = "student_submission_view.php?id=$subject_assignment_submission_id";
                                                            
                                                            if($submission_grade !== NULL){

                                                                $status = "
                                                                    <a style='color: inherit'; href='$to_check_assignment_url'>
                                                                        $submission_grade
                                                                    </a>
                                                                ";
                                                            }

                                                            if($submission_grade === NULL){
                                                                // $status = $submission_grade;
                                                                $to_check_assignment_url = "student_submission_view.php?id=$subject_assignment_submission_id";
                                                                
                                                                $status = "
                                                                    <a title='View Submission' href='$to_check_assignment_url'>
                                                                        <i style='cursor: pointer;color:blue' class='fas fa-eye'></i>
                                                                    </a>
                                                                ";
                                                            }
                                                        } 

                                                        $ass = $row_query2['assignment_name'];
                                                        $subject_code_assignment_id = $row_query2['subject_code_assignment_id'];

                                                        echo "
                                                            <th>$status</th>
                                                        ";
                                                    }

                                                echo "</tr>";
                                            }
                                        ?>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    <script>
        
        function gradeStudent(student_id, subject_code_assignment_id, max_score, school_year_id){

            var student_id = parseInt(student_id);
            var subject_code_assignment_id = parseInt(subject_code_assignment_id);

            var grade_input_value = $(`#grade_input_${student_id}-_${subject_code_assignment_id}`).val();

            // console.log(grade_input_value);


            Swal.fire({
                    icon: 'question',
                    title: `Are you sure you want to grade student?`,
                    text: 'Important! This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "../../ajax/class/sectionTopicAddGrade.php",
                            type: 'POST',
                            data: {
                                subject_code_assignment_id,
                                student_id,
                                grade_input_value,
                                max_score,
                                school_year_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);
                                if(response == "invalid_graded_value"){
                                Swal.fire({
                                        icon: 'error',
                                        title: `Oh no`,
                                        text: 'Input value is Invalid',
                                        showCancelButton: false,
                                        confirmButtonText: 'Try again'

                                });
                                }
                                if(response == "success_graded"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Successfully Graded`,
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

                                    $('#section_topic_grading_table').load(
                                        location.href + ' #section_topic_grading_table'
                                    );
                                    // location.reload();
                                });}

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
        </script>
    </body>
</html>