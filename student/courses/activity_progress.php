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
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');

    if(isset($_GET['id'])
        ){
         
        $student_subject_id = $_GET['id'];
           
        // echo $student_subject_id;

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $studentSubject = new StudentSubject($con, $student_subject_id);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $student_subject_id);

        $subject_code = $studentSubject->GetStudentSubjectCode();
        $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();
        $school_year_id = $studentSubject->GetSchoolYearId();

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);

        $subject_title = $subjectProgram->GetTitle();

        // var_dump($subject_code);

        $allGivenAssignments = $subjectCodeAssignment->GetSubjectCodeAssignments(
            $subject_code, $school_year_id);

        // var_dump($allGivenAssignments);
        
        // $back_url = "index.php?id=$student_subject_id";
        $back_url = "subject_module.php?id=$student_subject_id";

        // var_dump($allGivenAssignments);


        # Get all subject_perido_code_topic_id base on your enrolled subject and school_year

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

        $allSubjectPeriodCodeTopicIds = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicIdsBySubjectCode($subject_code, $school_year_id);


        $assignmentList = $subjectCodeAssignment->GetSubjectTopicAssignmentListBasedOnTopicIdss(
            $allSubjectPeriodCodeTopicIds);

        $handoutList = $subjectCodeAssignment->GetSubjectTopicHandoutListBasedOnTopicIds(
            $allSubjectPeriodCodeTopicIds);

            // $handoutList = [];
            // $assignmentList = [];

        
        $mergedList = array_merge($handoutList, $assignmentList);

        
        // var_dump($mergedList);
?>

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
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
                            <h3><?= $subject_title; ?></h3>
                        </div>
                    </header>
                    <main>
                        <?php if(count($allGivenAssignments) > 0): ?>
                            <table class="a" id="progress_table">
                                <thead>
                                    <tr>
                                        <th>Section</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $now = date("Y-m-d H:i:s");
                                        $totalScore = 0;

                                        $totalOverProgress = 0;
                                        $totalProgressOkayStatus = 0;

                                        foreach ($mergedList as $key => $row_ass) {
                                            # code...

                                            $assignment_name = isset($row_ass['assignment_name']) ? $row_ass['assignment_name'] : "";
                                            $subject_code_assignment_id = isset($row_ass['subject_code_assignment_id']) ? $row_ass['subject_code_assignment_id'] : "";
                                            
                                           
                                            $subject_code_handout_id = isset($row_ass['subject_code_handout_id']) ? $row_ass['subject_code_handout_id'] : "";

                                            if($subject_code_assignment_id != ""){
                                                $totalOverProgress++;
                                            }
                                            if($subject_code_handout_id != ""){
                                                $totalOverProgress++;

                                            }
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

                                            // var_dump($handout_name);

                                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);
                                            $subjectCodeHandoutStudent = new SubjectCodeHandoutStudent($con);
                                            
                                            $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                $subject_code_assignment_id,
                                                $studentLoggedInId, $school_year_id);

                                            $singleHandoutViewed = $subjectCodeHandoutStudent->CheckSingleHandoutViewed(
                                                $subject_code_handout_id,
                                                $studentLoggedInId, $school_year_id);

                                            $task_view_url = "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$student_subject_id";
                                            
                                            if($assignment_name !== ""){

                                                $section_output = "
                                                    <a href='$task_view_url'>
                                                        $assignment_name
                                                    </a>
                                                ";

                                            }
                                            else{
                                                $section_output = "
                                                    <a href='topic_module_view.php?id=$subject_code_handout_id&ss_id=$student_subject_id'>
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
                                                $submitted_status = $submitted_graded_status;
                                                $totalProgressOkayStatus++;
                                            }
                                            if($statusSubmission !== NULL){

                                                $totalProgressOkayStatus++;

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

                                                    // <td>$submitted_graded_status</td>

                                            echo "
                                                <tr>
                                                    <td>$section_output</td>
                                                    <td>$submitted_status</td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                                <?php if(true): ?>
                                    <tr>
                                        <td colspan="6">Overall: 
                                            <?php
                                                if($totalOverProgress > 0){

                                                    // $pecentage_equivalent_total = ($totalScore / $totalOverProgress) * 100;
    
                                                    $pecentage_equivalent_total = ($totalProgressOkayStatus / $totalOverProgress) * 100;
    
                                                    // $totalProgressOkayStatus++;
    
                                                    $equivalent_total = round($pecentage_equivalent_total, 0, PHP_ROUND_HALF_UP);
                                                    $equivalent_total = $equivalent_total . "%";
    
                                                    echo "$totalProgressOkayStatus / $totalOverProgress = $equivalent_total";
                                                }else{
                                                    echo "??";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                                <?php else: ?>
                                    <h4 style="text-align: center"></h4>No Data</h4>
                                <?php endif; ?>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>