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
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
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
        $subjectCodeHandout = new SubjectCodeHandout($con);

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



        // echo $subject_code;

        $assignmentListOnTeachingCode = $subjectCodeAssignment->GetSubjectAssignmentBasedOnTeachingSubject(
            $subject_code,
            $school_year_id,
            $teacherLoggedInId);

        $handoutGivenListOnTeachingCode = $subjectCodeAssignment->GetSubjectHandoutBasedOnTeachingSubject(
            $subject_code,
            $school_year_id,
            $teacherLoggedInId);

        $handoutIdsArray = [];

        foreach ($handoutGivenListOnTeachingCode as $key => $value) {
            # code...
            array_push($handoutIdsArray, $value['subject_code_handout_id']);
            
        }
        
        // var_dump($handoutIdsArray);

        // var_dump($handoutGivenListOnTeachingCode);

        // echo count($handoutGivenListOnTeachingCode);

        $studentGradeBook = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject(
            $subject_code,
            $school_year_id);

            
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

        // var_dump($assignmentListOnTeachingCode);
        // echo count($assignmentListOnTeachingCode);

        // $back_url = "index.php?c_id=$course_id&c=$subject_code";
 
        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";
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
                        $logout_url, "second");
                    
                ?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <div class="content-header">
                <header>
                    <div class="title">
                        <h1><?= $subject_title; ?> <em><?= "SY$formatTerm-$period_short"; ?></em></h1>
                    </div>
                </header>
            </div>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Student Handout Summary</h3>
                            <small>Section Overview</small>
                        </div>
                    </header>
                    <main>
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th colspan="4">
                                                <div class="table-header">
                                                    <div class="header-text">Handout</div>
                                                    <div class="sub-header-text"></div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Given</th>
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

                                            while($row_stud = $stud->fetch(PDO::FETCH_ASSOC)){
                                                
                                                $student_id = $row_stud['student_id'];
                                                $firstname = $row_stud['firstname'];
                                                $lastname = $row_stud['lastname'];

                                                // $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                $fullname = ucwords($lastname) . ", " . ucwords($firstname);

                                                
                                                // Check if the length of the fullname exceeds 10 characters
                                                if (strlen($fullname) > 18) {
                                                    // Trim the fullname to 10 characters and add ellipsis
                                                    $fullname = substr($fullname, 0, 18) . "...";
                                                }

                                                $testCount = $subjectCodeHandout
                                                    ->GetTotalViewedHandoutOnSubject($handoutIdsArray,
                                                        $student_id);
                                                
                                                // $totalHandoutViewedCount = $subjectCodeHandout
                                                //     ->GetTotalViewedHandoutCountOnTopicSection(
                                                //     $subject_period_code_topic_id,
                                                //     $student_id, $current_school_year_id
                                                // );


                                                $totalCount = count($handoutGivenListOnTeachingCode);
                                                
                                                $rounded_equivalent = "";

                                                if($totalCount > 0){

                                                    $equivalent = ($testCount / $totalCount) * 100;
                                                    // $rounded_equivalent = floor($equivalent / 10) * 10;
                                                    
                                                    $rounded_equivalent = round($equivalent, 0, PHP_ROUND_HALF_UP);
                                                }
                                                    
                                                echo "
                                                    <tr>
                                                        <td colspan='2' style='font-size: 14px'>$fullname</td>
                                                        <td colspan='2' style='font-size: 14px'>$testCount / $totalCount = $rounded_equivalent%</td>
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

                                            foreach ($handoutGivenListOnTeachingCode as $key => $row) {

                                                $handout_name = $row['handout_name'];

                                                $subject_period_code_topic_id = $row['subject_period_code_topic_id'];

                                                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

                                                $topicName = $subjectPeriodCodeTopic->GetTopic();

                                                // $subject_code_assignment_id = $row['subject_code_assignment_id'];

                                                // $url = "edit.php?id=$subject_code_assignment_id";
                                                
                                                echo "
                                                    <th>
                                                        <a href=''>
                                                            <span class='topic-name'>($topicName)</span>
                                                            <span class='handout-name'>$handout_name</span>
                                                        </a>
                                                    </th>
                                                ";

                                            }

                                        echo "</tr>";

                                        echo "<tr>";

                                            foreach ($handoutGivenListOnTeachingCode as $key => $row_due) {

                                                $date_creation = $row_due['date_creation'];

                                                $date_creation = date("M d",
                                                    strtotime($date_creation));
                                                
                                                echo "
                                                    <th>
                                                        <a style='color: inherit;' href=''>
                                                            $date_creation
                                                        </a>
                                                    </th>
                                                ";

                                            }

                                        echo "</tr>";

                                        echo "<tr>";

                                            foreach ($handoutGivenListOnTeachingCode as $key => $row_due) {

                                                $date_creation = $row_due['date_creation'];

                                                $date_creation = date("M d", strtotime($date_creation));
                                                
                                                echo "
                                                    <th>
                                                        -
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

                                                foreach ($handoutGivenListOnTeachingCode as $key => $row_query2) {
                                                    
                                                    $gb_subject_code_handout_id = $row_query2['subject_code_handout_id'];
                                                    
                                                    // $query = $con->prepare
                                                    $subjectCodeHandoutStudent = new SubjectCodeHandoutStudent($con);

                                                    $studentHandout = $subjectCodeHandoutStudent->GetStudentWhoViewedHandout(
                                                        $gb_subject_code_handout_id, $student_id);
                                                    $studentViewedHandoutId = NULL;
                                                    

                                                    $status = "
                                                        <i style='color: orange' class='fas fa-times'></i>
                                                    ";

                                                    if($studentHandout != NULL){

                                                        $studentViewedHandoutId = $studentHandout['student_id'];

                                                        if($studentViewedHandoutId == $student_id){
                                                            $status = "
                                                                <i style='color: yellow' class='fas fa-check'></i>
                                                            ";
                                                        }
                                                    }

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
    </body>
</html>