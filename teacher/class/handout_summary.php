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
 
    echo Helper::RemoveSidebar();
    
    ?>
        <style>
            /* Add CSS for preventing text wrapping */
            /* th a {
                text-decoration: underline;
                color: inherit; 
                white-space: nowrap; 
            } */

            th a {
                text-decoration: underline;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
                display: flex;
                flex-direction: column; /* Stack the spans vertically */
                align-items: center; /* Center content horizontally within the th */
            }

            .topic-name {
                font-weight: bold; /* Make the topic name bold */
                margin-bottom: -1px; /* Add space between the two spans */
            }

            .handout-name {
                margin-top: 1px; /* Add space between the two spans */
            }

            .table-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-text {
                font-weight: bold; /* Make "Handouts" bold if desired */
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

        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicTemplateId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $course_id = $subjectPeriodCodeTopic->GetCourseId();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        // echo $subject_code;

        $assignmentListOnTeachingCode = $subjectCodeAssignment->GetSubjectAssignmentBasedOnTeachingSubject(
            $subject_code,
            $current_school_year_id,
            $teacherLoggedInId);

        $handoutGivenListOnTeachingCode = $subjectCodeAssignment->GetSubjectHandoutBasedOnTeachingSubject(
            $subject_code,
            $current_school_year_id,
            $teacherLoggedInId);



        // var_dump($handoutGivenListOnTeachingCode);

        // echo count($handoutGivenListOnTeachingCode);

        $studentGradeBook = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject(
            $subject_code,
            $current_school_year_id);

        // var_dump($assignmentListOnTeachingCode);
        // echo count($assignmentListOnTeachingCode);

        // $back_url = "index.php?c_id=$course_id&c=$subject_code";
 
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
                                            <!-- <tr class='text-center'>
                                                <th colspan='4'>Handouts</th>
                                            </tr> -->

                                            <tr class='text-center'>
                                                <th colspan='4'>
                                                    <div class="table-header">
                                                        <div class="header-text">Handouts</div>
                                                        <div class="sub-header-text">Given</div>
                                                    </div>
                                                </th>
                                            </tr>
                                            <!-- <tr style='text-align:right;'>
                                                <th colspan='4'>Category</th>
                                            </tr> -->
                                            <tr style='text-align:right;'>
                                                <th colspan='4'>Given</th>
                                            </tr>
                                            <tr>
                                                <th>Students</th>
                                                <th></th>
                                                <th></th>
                                                <th class='text-center'></th>
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

                                                            // ($student_id)
                                                            
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
                </nav>
            </div>
        <?php
    }
?>
 