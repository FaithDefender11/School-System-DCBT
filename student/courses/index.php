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
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');

    if(
        isset($_GET['id'])
        ){

        // $subject_code = $_GET['c'];

        $student_subject_id = $_GET['id'];

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $studentSubject = new StudentSubject($con, $student_subject_id);

        $subject_code = $studentSubject->GetStudentSubjectCode();
        $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);

        $subject_title = $subjectProgram->GetTitle();

        $subject_code_assignment = new SubjectCodeAssignment($con);



        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
        $announcement = new Announcement($con);
        
        $teachingSubject_teacher_id = $subjectPeriodCodeTopic->GetTeachingCodeTeacherId($subject_code, $current_school_year_id);

        // echo $teachingSubject_teacher_id;

        $studentAnnouncementList = $announcement->GetAnnouncementsAsStudentBasedOnSubject(
            $subject_code, $teachingSubject_teacher_id, $studentLoggedInId, $current_school_year_id);

            // echo $teachingSubject_teacher_id;
            // var_dump($studentAnnouncementList);

        $assignmentTodoIds = $subject_code_assignment->GetAllTodosWithinSubjectCode(
            $studentLoggedInId,
            $current_school_year_id,
            $subject_code);
        

        $get_only_one_assignmentTodoId = NULL;

        if(count($assignmentTodoIds) == 1){
            $get_only_one_assignmentTodoId = $assignmentTodoIds[0];
        }

        if(count($assignmentTodoIds) > 1){

        }

        // var_dump($assignmentTodoIds);
        // echo $get_only_one_assignmentTodoId;

        $back_url = "../lms/student_dashboard2.php";


        ?>

            <div class="content">
                    <nav style="min-width: 100%; margin-bottom: 7px;
                        display: flex;flex-direction: row;">
                        <a href="<?php echo $back_url;?>">
                            <i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                        </a>

                        <div style="flex: 1;"  class="text-right">

                            <span>
                                <h3 style="font-weight: bold; color:white;"><?= $subject_title?></h3>
                            </span>
                        </div>

                    </nav>

                <div class="row content col-md-12">
                    

                    <div class="col-md-9">

                        <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="text-center text-muted"><?php echo $subject_code;?> Modules</h4> -->
                                <h4 class="text-start text-primary mb-2">Modules</h4>
                                
                                <button onclick="window.location.href = 'subject_progress.php?id=<?php echo $student_subject_id; ?>'" class="text-start mb-2 btn btn-sm btn-info">
                                    Progress Tab
                                </button>
                                
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
                                $sql->bindValue(":school_year_id", $current_school_year_id);
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
                                                $studentLoggedInId ,$current_school_year_id);

                                        $getHandoutViewedCount = $subjectCodeHandout
                                            ->GetTotalViewedHandoutCountOnTopicSection($subject_period_code_topic_id,
                                                $studentLoggedInId ,$current_school_year_id);
                                        
                                        $totalProgress =  $getAssignmentSubmissionCount + $getHandoutViewedCount;
                                        // var_dump($getHandoutViewedCount);

                                        $sectionModuleMerge = array_merge($givenAssignmentsInsideTopicSection,
                                            $givenHandoutInsideTopicSection);

                                        $sectionModuleItemsCount = count($sectionModuleMerge);

                                        // var_dump($subject_period_code_topic_id);

                                        // echo "count: " . count($givenHandoutInsideTopicSection);
                                    
                                        if ($sectionModuleItemsCount > 0) {

                                            $totalProgressPercentage = ($totalProgress / $sectionModuleItemsCount) * 100;
                                            $equivalent = round($totalProgressPercentage, 0, PHP_ROUND_HALF_UP);
                                            $equivalent = $equivalent . "%";

                                        } else {
                                            // Handle the case when $sectionModuleItemsCount is zero
                                            $equivalent = "N/A"; // or any appropriate value or message
                                        }
                                        ?>

                                            <div class='col-md-12 mb-3'>
                                                
                                                <div style='border: 2px solid green;' class='card'>
                                                    <div class='card-body'>
                                                        <div class='card-block'>
                                                            <span>( <?= $totalProgress;?> / <?= $sectionModuleItemsCount;?> = <?= $equivalent;?>)</span>
                                                            <h4 class='card-title'><?php echo "$i. $topic"?> <span>(<?php echo $subject_period_name?>)</span> </h4>
                                                            <h6 class='card-subtitle text-muted'><?php echo $description?></h6>
                                                            <p class='card-text p-y-1'>Some quick example text to build on the card title.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <table class='a'>
                                                            <thead>
                                                                <tr class='bg-success text-center'>
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

                                                                    $subjectCodeHandoutStudent = new SubjectCodeHandoutStudent($con);

                                                                    
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


                                                                            $checkStudentHandoutViewed = $subjectCodeHandoutStudent
                                                                                ->CheckHandoutIfAlreadyViewed($subject_code_handout_id, $studentLoggedInId, $current_school_year_id);


                                                                                // var_dump($checkStudentHandoutViewed);
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
                                                                            
                                                                            $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                                                $subject_code_assignment_id,
                                                                                $studentLoggedInId, $current_school_year_id);

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
    


                                                                            $submission_grade = NULL;
                                                                            $submitted_grade = NULL;
                                                                            $score_output = "";

                                                                            $submitted_graded_status = "
                                                                                <i class='fas fa-arrow-right'></i>
                                                                            ";

                                                                            if($checkStudentHandoutViewed){
                                                                                $submitted_graded_status = "
                                                                                    <i style='color: green;' class='fas fa-check'></i>
                                                                                ";
                                                                            }

                                                                            if($statusSubmission !== NULL){

                                                                                $submitted_status = "
                                                                                    <i style='color: green;' class='fas fa-check'></i>
                                                                                ";

                                                                                // Increment if assignment pass.

                                                                                $submitted_graded_status = "
                                                                                    <i style='color: green;' class='fas fa-check'></i>
                                                                                ";
                                                                                

                                                                                // var_dump($statusSubmission);

                                                                                $submitted_grade =  $statusSubmission['subject_grade'];
                                                                                $date_graded =  $statusSubmission['date_graded'];

                                                                                if($submitted_grade != NULL && $date_graded != NULL){

                                                                                    // $submitted_graded_status =  $submitted_status;
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
                                                                            if(
                                                                                
                                                                                $now >= $due_date 
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

                                                                    $assignment = $con->prepare("SELECT *
                                                    
                                                                        FROM subject_code_assignment
                                                                        WHERE subject_period_code_topic_id=:subject_period_code_topic_id
                                                                    ");

                                                                    $assignment->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
                                                                    $assignment->execute();

                                                                    if($sql->rowCount() == 0){

                                                                        while($row_ass = $assignment->fetch(PDO::FETCH_ASSOC)){

                                                                            $assignment_name = $row_ass['assignment_name'];
                                                                            
                                                                            $subject_code_assignment_id = $row_ass['subject_code_assignment_id'];
                                                                            $due_date = $row_ass['due_date'];

                                                                            $assignment_picture = "";
                                                                            $max_score = $row_ass['max_score'];

                                                                            $task_view_url = "task_submission.php?sc_id=$subject_code_assignment_id";

                                                                            $submitted_status = "
                                                                                <i class='fas fa-arrow-right'></i>
                                                                            ";


                                                                            echo "
                                                                                <tr class='text-center'>
                                                                                    <td>
                                                                                        <a style='color: blue;' href='$task_view_url'>
                                                                                            $assignment_name
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>
                                                                                        
                                                                                    </td>

                                                                                    <td>$max_score</td>
                                                                                    <td>$due_date</td>
                                                                                    <td>$submitted_status</td>
                                                                                </tr>
                                                                            ";
                                                                        }
                                                                    }


                                                                                    
                                                                ?>

                                                            </tbody>
                                                        </table>

                                                        
                                                    </div>

                                                </div>


                                            </div>

                                        <?php
                                    }

                                }
                            
                            ?>
                        </div>
                    </div>

                    
                    <br>
                    <hr>
                    <div class="col-md-3">
                        <div class='card'>
                            <div class='card-header'>

                                <?php if(count($assignmentTodoIds) == 1 && $get_only_one_assignmentTodoId !== NULL):?>
                                    <h5 style="margin-bottom: 7px;">Assignments</h5>
                                    <p>
                                        <?php
                                            echo "<a style='color: blue' href='task_submission.php?sc_id=$get_only_one_assignmentTodoId&ss_id=$student_subject_id'>".count($assignmentTodoIds)."  assignment due</a>";
                                        ?>
                                    </p>

                                <?php elseif (count($assignmentTodoIds) > 1):?>
                                    <h5 style="margin-bottom: 7px;">Assignments</h5>
                                    <p>
                                        <?php
                                            $url = "../lms/assignment_due.php?c=$subject_code";

                                            echo "<a style='color: blue' href='$url'>".count($assignmentTodoIds)."  assignments due</a>";
                                        ?>
                                    </p>

                                <?php else:?>

                                    <h5 style="margin-bottom: 7px;">No assignments</h5>

                                <?php endif;?>

                                
                            </div>
                        </div>

                        <hr>
                        <div class='card'>
                            <div class='card-header'>
                                <?php if(count($studentAnnouncementList) > 0):?>

                                    <p style="margin-bottom: 7px;"><?php echo count($studentAnnouncementList); ?> Announcement</p>
                                    <?php 

                                        $i=0;

                                        foreach ($studentAnnouncementList as $key => $value) {

                                            $title = $value['title'];
                                            $announcement_id = $value['announcement_id'];
                                            $i++;

                                            # code...
                                            echo "
                                                <a href='student_subject_announcement.php?id=$announcement_id'>
                                                    <span>$i. $title</span>
                                                </a>
                                            <br>";
                                        }
                                    ?>
                                <?php else:?>
                                    <p style="margin-bottom: 7px;">No announcement</p>
                                <?php endif;?>
                            </div>
                        </div>

                        <hr>
                        <div class='card'>
                            <div class='card-header'>
                                <p style="margin-bottom: 7px;">Upcoming</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php
    }
?>




