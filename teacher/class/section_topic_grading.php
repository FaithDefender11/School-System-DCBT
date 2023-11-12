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
        $subject_code_assignment_id = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicTemplateId($subject_period_code_topic_id);
        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicTemplateId();
        // $subject_code_assignment_id = $subjectPeriodCodeTopic->GetSubjectCodeAssignmentIdByTopicId($subject_period_code_topic_id);
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        $subject_period_code_topic_template_id = $subjectCodeAssignment->GetSubject_code_assignment_template_id();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $period_name = $subjectPeriodCodeTopic->GetSubjectPeriodName();

        $course_id = $subjectPeriodCodeTopic->GetCourseId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";
?>

            <?php
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "second",
                    "second"
                );
            ?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Gradebook in <?php echo "$topic_name ($period_name)"; ?></h3>
                            <small>Section Overview</small>
                        </div>
                    </header>
                    <main>
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Assignments</th>
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
                                                            <td style='font-size: 14px'>
                                                            $fullname
                                                            </td>
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
                                            $query = $con->prepare("SELECT * 
                                                
                                            FROM subject_code_assignment as t1
                                        
                                            INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                            AND t2.teacher_id=:teacher_id

                                            WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id");

                                            $query->bindValue(":teacher_id", $teacherLoggedInId);
                                            $query->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);

                                            $query->execute();

                                            if($query->rowCount() > 0){

                                                // $asd = $query->fetchAll(PDO::FETCH_ASSOC);
                                                // print_r($asd);

                                                echo "<tr>";
                                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                        $assignment_name = $row['assignment_name'];

                                                        $subject_code_assignment_id = $row['subject_code_assignment_id'];

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

                                                // POSTING ON LINK

                                            }

                                            $query_due = $con->prepare("SELECT * FROM subject_code_assignment as t1
                                            
                                                INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                AND t2.teacher_id=:teacher_id

                                                WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id");

                                            $query_due->bindValue(":teacher_id", $teacherLoggedInId);
                                            $query_due->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);

                                            $query_due->execute();

                                            if($query_due->rowCount() > 0){
                                                echo "<tr>";
                                                    while($row_due = $query_due->fetch(PDO::FETCH_ASSOC)){

                                                        $due_date = $row_due['due_date'];
                                                        $enrollment_approve = date("M d",
                                                            strtotime($due_date));


                                                        echo "
                                                            <th>$enrollment_approve</th>
                                                        ";
                                                    }
                                                echo "</tr>";
                                            }

                                            $query_max_score = $con->prepare("SELECT * FROM subject_code_assignment as t1
                                            
                                                INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                AND t2.teacher_id=:teacher_id

                                                WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id");

                                            $query_max_score->bindValue(":teacher_id", $teacherLoggedInId);
                                            $query_max_score->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);

                                            $query_max_score->execute();

                                            if($query_max_score->rowCount() > 0){

                                                echo "<tr>";
                                                    while($row_due = $query_max_score->fetch(PDO::FETCH_ASSOC)){

                                                        $max_score = $row_due['max_score'];
                                                        $enrollment_approve = date("M d",
                                                            strtotime($due_date));


                                                        echo "
                                                            <th>$max_score</th>
                                                        ";
                                                    }
                                                echo "</tr>";
                                            }


                                            // Student Grade Book Data
                                            $stud = $con->prepare("SELECT 
                                                t3.firstname
                                                ,t3.lastname
                                                ,t3.student_unique_id
                                                ,t3.student_id

                                                FROM student_subject as t1
                                                INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
                                                AND t2.enrollment_status ='enrolled'

                                                INNER JOIN student as t3 ON t3.student_id = t2.student_id


                                                WHERE t1.subject_code=:subject_code
                                                AND t1.school_year_id=:school_year_id
                                                
                                                -- GROUP BY t3.student_id
                                            ");

                                            $stud->bindParam(":subject_code", $subject_code);
                                            $stud->bindParam(":school_year_id", $current_school_year_id);
                                            $stud->execute();

                                            if($stud->rowCount() > 0){

                                                while($row_stud = $stud->fetch(PDO::FETCH_ASSOC)){

                                                    $student_id = $row_stud['student_id'];
                                                    $firstname = $row_stud['firstname'];
                                                    $lastname = $row_stud['lastname'];
                                                    $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                    $query2 = $con->prepare("SELECT * FROM subject_code_assignment as t1
                                        
                                                        INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                        AND t2.teacher_id=:teacher_id

                                                        WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id");

                                                    $query2->bindValue(":teacher_id", $teacherLoggedInId);
                                                    $query2->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);

                                                    $query2->execute();

                                                    if($query2->rowCount() > 0){

                                                        echo "<tr style='margin-top:20px'>";
                                                            while($row_query2 = $query2->fetch(PDO::FETCH_ASSOC)){

                                                                $subject_code_assignment_id =  $row_query2['subject_code_assignment_id'];

                                                                $max_score =  $row_query2['max_score'];

                                                                // echo $subject_code_assignment_id;

                                                                // $status = "
                                                                //     <div id='status' onclick='replaceStatus()'>
                                                                //         <i style='color:orange; cursor:pointer;' class='fas fa-pen'></i>
                                                                //     </div>

                                                                // ";

                                                                $gradeStudent = "gradeStudent($student_id, $subject_code_assignment_id, $max_score, $current_school_year_id)";

                                                                $status = "
                                                                    <input style='width: 80px' autocomplete='off' maxLength='3' type='text' name='grade_input' id='grade_input'>
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
                                                                        
                                                                        // $status = $submission_grade;

                                                                        $status = "
                                                                            <a href='student_submission_view.php?id=$subject_assignment_submission_id'>
                                                                                $submission_grade
                                                                            </a>
                                                                        ";
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
                                                }
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
        let originalContent = `
            <div id='status' onclick='replaceStatus()'>
                <i style='color:orange; cursor:pointer;' class='fas fa-pen'></i>
            </div>
        `;
        function handleInputClick(event) {
            event.preventDefault();
        }

        function replaceStatus() {

            var statusElement = document.getElementById("status");

            if (statusElement) {

                var student_id = <?php echo $student_id; ?>;
                var subject_code_assignment_id = <?php echo $subject_code_assignment_id; ?>;
                var max_score = <?php echo $max_score; ?>;
                var current_school_year_id = <?php echo $current_school_year_id; ?>;

                // Replace with the desired HTML
                statusElement.innerHTML = `
                    <input style='width: 80px' autocomplete='off' 
                        maxLength='3' type='text' name='grade_input'
                        id='grade_input'
                        onclick="handleInputClick(event)"
                        >
                    <button onclick="gradeStudent(${student_id}, ${subject_code_assignment_id}, ${max_score}, ${current_school_year_id})">
                        <i style='color: blue' class='fas fa-pencil'></i>
                    </button>
                    <button onclick="defaultStatus()">
                        <i style='color: orange' class='fas fa-times'></i>
                    </button>
                `;

                var inputElement = document.getElementById("grade_input");
                if (inputElement) {
                    inputElement.removeAttribute("disabled");
                    // Set focus on the input field
                    inputElement.focus();
                }
            }

        }

        function defaultStatus() {
            var statusElement = document.getElementById("status");
            if (statusElement) {
                statusElement.innerHTML = originalContent; // Restore the original content
            }
        }


        function gradeStudent(student_id, subject_code_assignment_id, max_score, school_year_id){

            var student_id = parseInt(student_id);
            var subject_code_assignment_id = parseInt(subject_code_assignment_id);

            var grade_input_value = parseInt($("#grade_input").val());

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