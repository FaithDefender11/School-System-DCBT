<?php 

    // include_once('../../includes/config.php');
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
 
    echo Helper::RemoveSidebar();
    
    ?>
        <style>
            /* Add CSS for preventing text wrapping */
                    <style>

            tr td {
                text-decoration: underline;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>
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

        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicTemplateId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $course_id = $subjectPeriodCodeTopic->GetCourseId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        // echo $subject_code;

        $assignmentListOnTeachingCode = $subjectCodeAssignment->GetSubjectAssignmentBasedOnTeachingSubject(
            $subject_code,
            $current_school_year_id,
            $teacherLoggedInId);

        $studentGradeBook = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject(
            $subject_code,
            $current_school_year_id);

        // var_dump($assignmentListOnTeachingCode);
        // echo count($assignmentListOnTeachingCode);

        $back_url = "index.php?c_id=$course_id&c=$subject_code";
 
        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

        ?>

            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <h4 style="font-weight: bold;" class="text-muted text-start">Student Grade Book</h4>

                    <div class="floating" id="shs-sy">

                        <header>
                            <div class="title">
                                <h3 class="text-muted text-start">Section Overview</h3>
                            </div>
                        </header>

                        <main>
                            <div class="row col-md-12">

                                <div class="col-md-4">
                                
                                    <table class='table table-hover tb-left'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th colspan='4'>Assignments</th>
                                            </tr>
                                            <!-- <tr style='text-align:right;'>
                                                <th colspan='4'>Category</th>
                                            </tr> -->
                                            <tr style='text-align:right;'>
                                                <th colspan='4'>Due</th>
                                            </tr>
                                            <tr>
                                                <th>Students</th>
                                                <th></th>
                                                <th></th>
                                                <th class='text-center'>Overall</th>
                                            </tr>

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
                                                    $stud->bindParam(":school_year_id", $current_school_year_id);
                                                    $stud->execute();

                                                    if($stud->rowCount() > 0){

                                                        while($row_stud = $stud->fetch(PDO::FETCH_ASSOC)){
                                                            
                                                            $student_id = $row_stud['student_id'];
                                                            $firstname = $row_stud['firstname'];
                                                            $lastname = $row_stud['lastname'];

                                                            // $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                            $fullname = ucwords($lastname) . ", " . ucwords($firstname);

                                                            //  $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                                // Check if the length of the fullname exceeds 10 characters
                                                                if (strlen($fullname) > 18) {
                                                                    // Trim the fullname to 10 characters and add ellipsis
                                                                    $fullname = substr($fullname, 0, 18) . "...";
                                                                }
                                                            echo "
                                                                <tr>
                                                                    <td style='font-size: 15px'>
                                                                    $fullname
                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }
                                                    }

                                                ?>
                                            </tbody>
                                        </thead>

                                    </table>

                                </div>

                                <div class="col-md-8">

                                    <table id="section_topic_grading_table" class="table table-hover" style="margin: 0">
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
                                                                <a style='color: inherit;' href='$url'>
                                                                    $assignment_name
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
                                                                <a style='color: inherit;'>
                                                                    $due_date
                                                                </a>
                                                            </th>
                                                        ";

                                                    }

                                                echo "</tr>";

                                                echo "<tr>";

                                                    foreach ($assignmentListOnTeachingCode as $key => $row_due) {

                                                        $max_score = $row_due['max_score'];
 
                                                        echo "
                                                            <th>
                                                                <a style='color: inherit;'>
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

                                                            $gradeStudent = "gradeStudent($student_id, $subject_code_assignment_id, $max_score, $current_school_year_id)";

                                                            $status = "
                                                                <input style='width: 80px' autocomplete='off' maxLength='3' type='text' name='grade_input_$subject_code_assignment_id' id='grade_input_$subject_code_assignment_id'>
                                                                <button onclick='$gradeStudent'>
                                                                    <i style='color:blue' class='fas fa-pencil'></i>
                                                                </button>
                                                            ";

                                                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

                                                            $checkSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                                $subject_code_assignment_id,
                                                                $student_id, $current_school_year_id);

                                                            $submission_grade = "~";

                                                            if($checkSubmission !== NULL){

                                                                $subject_assignment_submission_id = $checkSubmission['subject_assignment_submission_id'];

                                                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
                                                                    $subject_assignment_submission_id);
                                                                    
                                                                $submission_grade = $subjectAssignmentSubmission->GetSubjectGrade();

                                                                if($submission_grade !== NULL){
                                                                    $status = $submission_grade;
                                                                }

                                                                if($submission_grade === NULL){
                                                                    // $status = $submission_grade;
                                                                    $to_check_assignment_url = "student_submission_view.php?id=$subject_assignment_submission_id";
                                                                    $status = "
                                                                        <a href='$to_check_assignment_url'>
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
                </nav>
            </div>

            
        <?php
    }

?>

<script>

    function gradeStudent(student_id, subject_code_assignment_id, max_score, school_year_id){

        var student_id = parseInt(student_id);
        var subject_code_assignment_id = parseInt(subject_code_assignment_id);

        var grade_input_value = $(`#grade_input_${subject_code_assignment_id}`).val();

        // console.log(grade_input_value)

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