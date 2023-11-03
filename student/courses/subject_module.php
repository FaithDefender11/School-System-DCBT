 <?php 


    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');


    echo Helper::RemoveSidebar();
    
    ?>
        <head>
                <!--Link JavaScript-->
            <script src="../../assets/js/elms-sidebar.js" defer></script>
            <script src="../../assets/js/elms-dropdown.js" defer></script>
            <script src="../../assets/js/table-dropdown.js" defer></script>

            <link
                rel="stylesheet"
                href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous"
                />
                <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
                />
                <!--Link Fonts-->
                <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Lato"
                />
                <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Arimo"
                />
        </head>
    <?php

    if(isset($_GET['id'])){

        $student_subject_id = $_GET['id'];

        // echo $student_subject_id;

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $studentSubject = new StudentSubject($con, $student_subject_id);

        $subject_code = $studentSubject->GetStudentSubjectCode();
        $school_year_id = $studentSubject->GetSchoolYearId();
 
        $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);

        $subject_title = $subjectProgram->GetTitle();

        $subject_code_assignment = new SubjectCodeAssignment($con);

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
        $announcement = new Announcement($con);
        
        $teachingSubject_teacher_id = $subjectPeriodCodeTopic->GetTeachingCodeTeacherId($subject_code, $school_year_id);

        // echo $teachingSubject_teacher_id;

        $studentAnnouncementList = $announcement->GetAnnouncementsAsStudentBasedOnSubject(
            $subject_code, $teachingSubject_teacher_id, $studentLoggedInId, $school_year_id);

            // echo $teachingSubject_teacher_id;
            // var_dump($studentAnnouncementList);

        $assignmentTodoIds = $subject_code_assignment->GetAllTodosWithinSubjectCode(
            $studentLoggedInId,
            $school_year_id,
            $subject_code);
        

        $get_only_one_assignmentTodoId = NULL;

        if(count($assignmentTodoIds) > 0){
            $get_only_one_assignmentTodoId = $assignmentTodoIds[0];
        }
 
        $back_url = "../lms/student_dashboard.php";


        $subjectCodeAssignment = new SubjectCodeAssignment($con, $student_subject_id);

        // var_dump($subject_code);

        $allGivenAssignments = $subjectCodeAssignment->GetSubjectCodeAssignments(
            $subject_code,
            $school_year_id);
        
        $totalScore = 0;
        $totalOver = 0;
        $now = date("Y-m-d H:i:s");



        foreach ($allGivenAssignments as $key => $row) {

            $subject_code_assignment_id = $row['subject_code_assignment_id'];
            $assignment_name = $row['assignment_name'];

            $max_score = $row['max_score'];

            $date_creation = $row['date_creation'];
            $start_date = date("M d, h:i a", strtotime($date_creation));

            $due_date_db = $row['due_date'];

            $due_date = date("M d, h:i a", strtotime($due_date_db));


            $submitted_status = "";


            $submitted_grade_status = "";


            $score = 0;

            $total = "";

            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

            $statusSubmission = $subjectAssignmentSubmission
                ->CheckStatusSubmission(
                $subject_code_assignment_id,
                $studentLoggedInId, $school_year_id);

            $equivalent = "";


            if($statusSubmission !== NULL){

                $submitted_grade =  $statusSubmission['subject_grade'];
                // $graded_over_score =  $statusSubmission['max_score'];
                $date_graded =  $statusSubmission['date_graded'];

                $score = $submitted_grade;

                if($submitted_grade != NULL){
                    
                    $submitted_grade_status = "
                        <i style='color: green;' class='fas fa-check'></i>
                    ";

                    $totalScore += $submitted_grade;
                    $totalOver += $max_score;
                
                }

            }
            else if($statusSubmission == NULL){

                $nowTimestamp = strtotime($now);

                if(strtotime($due_date_db) <=  $nowTimestamp){
                  
                    $totalOver += $max_score;
                } 
            }

        }

        // var_dump($equivalent);

        $enrollment = new Enrollment($con);

        // $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($studentLoggedInId,
        //     $current_school_year_id);

        $enrollment_id = $studentSubject->GetEnrollmentId();

        // echo $school_year_id;

        $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
            ($studentLoggedInId, $school_year_id, $enrollment_id);

        // var_dump($allEnrolledSubjectCode);
        
        $enrolledSubjectList = [];

        foreach ($allEnrolledSubjectCode as $key => $value) {
            # code...
            $subject_codeGet = $value['student_subject_code'];
            array_push($enrolledSubjectList, $subject_codeGet);
        }
        // var_dump($enrolledSubjectList);

        ?>

            <div style="min-width: 100%;" class="content">

                <?php
                
                    echo Helper::lmsStudentNotificationHeader(
                        $con, $studentLoggedInId,
                        $current_school_year_id, $enrolledSubjectList,
                        $enrollment_id,
                        "second",
                        "first",
                        "second");
                
                ?>

                <div class="content-header">
                    <header>
                    <div class="title">
                        <h1><span style="font-size: 27px;"><?= $subject_title?></span>  <em style="font-size: 27px;">SY2324-1T</em></h1>
                    </div>
                    </header>
                </div>

                <nav>
                    <a href="<?php echo $back_url; ?>">
                        <i class="bi bi-arrow-return-left"></i>Back
                    </a>
                </nav>

                <main>

                    <div class="bars">

                        <div class="floating">
                            <a style="color: white;" href="subject_progress.php?id=<?php echo $student_subject_id; ?>">
                                <header>
                                        
                                    <div class="title">
                                        <h4>Progress</h4>
                                    </div>
                                </header>
                                <main>
                                    <div class="progress" style="position: relative; height: 20px">
                                        <?php 

                                            $equivalent_total = "";
                                            if($totalOver > 0){
                                                $pecentage_equivalent_total = ($totalScore / $totalOver) * 100;

                                                // $pecentage_equivalent_total = 89.19;
                                                $equivalent_total = round($pecentage_equivalent_total, 0, PHP_ROUND_HALF_UP);
                                                // $equivalent_total += 50;
                                                $equivalent_total = $equivalent_total . "%";
                                            }
                                           

                                        ?>
                                        <div class="progress-bar" style="position: absolute; top:0; height: 30px;  width: <?= $equivalent_total;?>"><?php 
                                        
                                            // echo "$totalScore / $totalOver = $equivalent_total";
                                            echo "$equivalent_total";
                                            
                                        ?></div>
                                    </div>
                                </main>
                            </a>
                        </div>

                        <div class="floating">
                            <header>
                            <div class="title">
                                <h4>Grade</h4>
                            </div>
                            </header>
                            <main>
                            <div class="progress" style="position: relative; height: 20px">
                                <div class="progress-bar" style="position: absolute; top:0; height: 30px;  width: 50%">0.5</div>
                            </div>
                            </main>
                        </div>

                        <div class="floating">
                            <header>
                            <div class="title">
                                <h4>Scores</h4>
                            </div>
                            </header>
                            <main>
                                <div class="progress" style="position: relative; height: 20px">
                                    <div class="progress-bar" style="position: absolute; top:0; height: 30px; width: 50%">50%</div>
                                </div>
                            </main>
                        </div>

                    </div>

                    <?php 
                    
                        $sql = $con->prepare("SELECT 
                            t1.*
                        
                            FROM subject_period_code_topic as t1 

                            WHERE t1.subject_code=:subject_code
                            AND t1.school_year_id=:school_year_id

                            ORDER BY
                            CASE subject_period_name
                                WHEN 'Prelim' THEN 1
                                WHEN 'Midterm' THEN 2
                                WHEN 'Pre-final' THEN 3
                                WHEN 'Final' THEN 4
                                ELSE 5  
                            END

                            -- ORDER BY t1.period_order ASC
                        ");

                        $sql->bindValue(":subject_code", $subject_code);
                        $sql->bindValue(":school_year_id", $school_year_id);
                        $sql->execute();

                        if($sql->rowCount() > 0){


                            $i = 0;

                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
                                $subject_period_name = $row['subject_period_name'];

                                $topic = $row['topic'];
                                $description = $row['description'];

                                $i++;

                                $subjectCodeHandout = new SubjectCodeHandout($con);
                                
                                $subjectCodeAssignment = new SubjectCodeAssignment($con);


                                $givenAssignmentsInsideTopicSection = $subjectCodeAssignment
                                    ->GetTotalGivenAssignmentByTopicSection($subject_period_code_topic_id);

                                $givenHandoutInsideTopicSection = $subjectCodeHandout
                                    ->GetTotalGivenHandoutByTopicSection($subject_period_code_topic_id);

                                $getAssignmentSubmissionCount = $subjectCodeAssignment
                                    ->GetTotalSubmissionCountOnAssignmentOnTopicSection($subject_period_code_topic_id,
                                        $studentLoggedInId ,$school_year_id);

                                $getHandoutViewedCount = $subjectCodeHandout
                                    ->GetTotalViewedHandoutCountOnTopicSection(
                                        $subject_period_code_topic_id,
                                        $studentLoggedInId,$school_year_id);
                                
                                $totalProgress =  $getAssignmentSubmissionCount + $getHandoutViewedCount;
                                
                                $sectionModuleMerge = array_merge($givenAssignmentsInsideTopicSection,
                                    $givenHandoutInsideTopicSection);

                                $sectionModuleItemsCount = count($sectionModuleMerge);

                                if ($sectionModuleItemsCount > 0) {

                                    $totalProgressPercentage = ($totalProgress / $sectionModuleItemsCount) * 100;
                                    $equivalent = round($totalProgressPercentage, 0, PHP_ROUND_HALF_UP);
                                    $equivalent = $equivalent . "%";

                                } else {
                                    // Handle the case when $sectionModuleItemsCount is zero
                                    $equivalent = "N/A"; // or any appropriate value or message
                                }

                                ?>

                                    <div class="floating">

                                        <header>

                                            <div class="title">
                                                <h3><?= $topic; ?> <em><?= $subject_period_name;?></em></h3>
                                                <small><?= $description; ?></small>
                                                    <span>( <?= $totalProgress;?> / <?= $sectionModuleItemsCount;?> = <?= $equivalent;?>)</span>
                                            
                                            </div>

                                            <div class="action">
                                                <?= $sectionModuleItemsCount; ?> 
                                                <?= $sectionModuleItemsCount > 1 ? "Sections" : ($sectionModuleItemsCount == 1 ? "Section" : ""); ?>

                                                <div class="dropdown">
                                                    <button class="table-drop">
                                                        <i class="bi bi-chevron-up"></i>
                                                        <i class="bi bi-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </header>

                                        <main>
                                            <table class="b">
                                                <thead>
                                                    <tr>
                                                        <th>Section</th>
                                                        <th>Submitted</th>
                                                        <th>Score</th>
                                                        <th>Due</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php

                                                        $subjectCodeAssignment = new SubjectCodeAssignment($con);

                                                        $subjectTopicAssignmentList = $subjectCodeAssignment->
                                                            GetSubjectTopicAssignmentList($subject_period_code_topic_id);
                                                            

                                                        $handoutList = $subjectCodeAssignment->
                                                                GetSubjectTopicHandoutList($subject_period_code_topic_id);

                                                            // var_dump($handoutList);

                                                        $mergedList = array_merge($handoutList, $subjectTopicAssignmentList);
                                                        

                                                        if(count($mergedList) > 0){

                                                            $now = date("Y-m-d H:i:s");

                                                            foreach ($mergedList as $key => $row_ass) {
                                                                
                                                                $assignment_name = isset($row_ass['assignment_name']) ? $row_ass['assignment_name'] : "";
                                                                $subject_code_assignment_id = isset($row_ass['subject_code_assignment_id']) ? $row_ass['subject_code_assignment_id'] : "";
                                                                
                                                                $subject_code_handout_id = isset($row_ass['subject_code_handout_id']) ? $row_ass['subject_code_handout_id'] : "";

                                                                // echo $subject_code_handout_id;
                                                                // echo "<br>";

                                                                
                                                                $submitted_status = "-";

                                                                $due_date = isset($row_ass['due_date']) ? $row_ass['due_date'] : "";
                                                                $due_date_output = "";


                                                                if($due_date != ""){
                                                                    $due_date_output = date("M d", strtotime($due_date));
                                                                }

                                                                // $due_date = isset($row_ass['due_date']) ? $row_ass['due_date'] : "";

                                                                $max_score = isset($row_ass['max_score']) ? $row_ass['max_score'] : "";

                                                                $section_output = "";

                                                                $handout_name = isset($row_ass['handout_name']) ? $row_ass['handout_name'] : "";
                                                                $subject_code_handout_id = isset($row_ass['subject_code_handout_id']) ? $row_ass['subject_code_handout_id'] : NULL;

                                                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);
                                                                $subjectCodeHandoutStudent = new SubjectCodeHandoutStudent($con);
                                                                
                                                                $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                                    $subject_code_assignment_id,
                                                                    $studentLoggedInId, $school_year_id);

                                                                $singleHandoutViewed = $subjectCodeHandoutStudent->CheckSingleHandoutViewed(
                                                                    $subject_code_handout_id,
                                                                    $studentLoggedInId, $school_year_id);

                                                                

                                                                // $doesAssignmentEnded = $subjectAssignmentSubmission->DoesAssignmentHasEnded(
                                                                //     $subject_code_assignment_id,
                                                                //     $current_school_year_id);   

                                                                //     echo $subject_code_assignment_id;
                                                                    
                                                                // var_dump($doesAssignmentEnded);

                                                                $task_view_url = "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$student_subject_id";
                                                                
                                                                if($assignment_name !== ""){

                                                                    $section_output = "
                                                                        <a style='color: blue;' href='$task_view_url'>
                                                                            $assignment_name
                                                                        </a>
                                                                    ";

                                                                }
                                                                else{
                                                                    $section_output = "
                                                                        <a style='color: inherit;' href='topic_module_view.php?id=$subject_code_handout_id&ss_id=$student_subject_id'>
                                                                            <i class='fas fa-file'></i>&nbsp $handout_name
                                                                        </a>
                                                                    ";
                                                                }

                                                                

                                                                // $submitted_status = "
                                                                //     <i style='color: orange;' class='fas fa-times'></i>
                                                                // ";


                                                                $submission_grade = NULL;
                                                                $submitted_grade = NULL;
                                                                $score_output = "";

                                                                $submitted_graded_status = "
                                                                    <span style='font-weight: bold;'>></span>
                                                                ";

                                                                if($singleHandoutViewed == true){

                                                                    $submitted_graded_status =  "
                                                                        <i style='color: green;' class='fas fa-check'></i>
                                                                    ";

                                                                }
                                                                if($statusSubmission !== NULL){

                                                                    $submitted_status = "
                                                                        <i style='color: green;' class='fas fa-check'></i>
                                                                    ";
                                                                        $submitted_graded_status =  $submitted_status;

                                                                    // var_dump($statusSubmission);

                                                                    $submitted_grade =  $statusSubmission['subject_grade'];
                                                                    $date_graded =  $statusSubmission['date_graded'];

                                                                    if($submitted_grade != NULL && $date_graded != NULL){

                                                                        $submission_grade =  $submitted_grade;
                                                                    }

                                                                    if($submitted_grade == NULL && $date_graded == NULL){
                                                                        $score_output = "? / $max_score";
                                                                    }
                                                                    

                                                                }

                                                                // var_dump($statusSubmission);
                                                                # TODO If not submitted and already deadline
                                                                # Should be allowed to pass.
                                                                # Student No Choose file click prompt error (it should have files in order to prepare answer)
                                                                
                                                                # The deadline flag should appear only for assignment
                                                                # Not for handouts given.
                                                                if($now >= $due_date 
                                                                    && $statusSubmission == NULL
                                                                    && $subject_code_handout_id == ""
                                                                ){
                                                                    $submitted_status = "
                                                                        <i style='color: orange;' class='fas fa-flag'></i>
                                                                    ";
                                                                    // echo "due";
                                                                } 


                                                                
                                                                if($submission_grade !== NULL){
                                                                    $score_output = "$submission_grade  / $max_score";
                                                                } 


                                                                echo "
                                                                    <tr class='text-center'>
                                                                        <td>$section_output</td>

                                                                        <td>$submitted_status</td>
                                                                        <td>$score_output</td>
                                                                        <td>$due_date_output</td>
                                                                        <td>$submitted_graded_status</td>
                                                                    </tr>
                                                                ";
                                                            }
                                                        }

                                                        
                                                        
                                                    ?>
        
                                                </tbody>

                                            </table>
                                        </main>

                                        
                                    </div>

                                <?php
                            }
                        }else{
                            // echo "not";
                        }
                    
                    ?>
                    
                </main>

            </div>
        <?php
    }

?>
 