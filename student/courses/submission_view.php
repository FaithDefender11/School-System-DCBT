<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/TaskType.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SubjectProgram.php');

    // echo Helper::RemoveSidebar();


    ?>
        <head>
            <!-- SUMMER NOTE LINK -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

            <!-- SUMMER NOTE SCRIPT -->
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        
        
            <script src="../../assets/js/elms-sidebar.js" defer></script>
            <script src="../../assets/js/elms-dropdown.js" defer></script>
            <script src="../../assets/js/table-dropdown.js" defer></script>

            
        
        </head>

        <style>
            .panel-heading.note-toolbar{
                display: none;
            }
        </style>

    <?php

    if(isset($_GET['sc_id'])
        && isset($_GET['s_id'])
        && isset($_GET['ss_id'])
        ){

        // $subject_code_assignment_id = $_GET['id'];
        $subject_code_assignment_id = $_GET['sc_id'];

        $student_subject_id = $_GET['ss_id'];

        

        $subject_assignment_submission_id = $_GET['s_id'];

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        
        $subject_period_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
        $assignment_type = $subjectCodeAssignment->GetType();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $instructions = $subjectPeriodCodeTopic->GetDescription();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];



        $task_type_id = $subjectCodeAssignment->GetTaskTypeId();
        $taskType = new TaskType($con, $task_type_id);

        $taskname = $taskType->GetTaskName();

        $assignment_name = $subjectCodeAssignment->GetAssignmentName();
        $assignment_max_score = $subjectCodeAssignment->GetMaxScore();

        // $subject_assignment_submission_id = NULL;

        // $subject_assignment_submission_id = $subjectAssignmentSubmission->GetSubjectAssignmentSubmissionIdNonGraded(
        //     $subject_code_assignment_id, $current_school_year_id);

        // $graded_subject_assignment_submission_id = $subjectAssignmentSubmission->GetStudentGradedAssignmentSubmissionId(
        //     $subject_code_assignment_id, $current_school_year_id);

        $getAnsweredAssignmentList = $subjectAssignmentSubmission->GetAssignmentList(
            $subject_assignment_submission_id);

        $totalSubmissionCount = $subjectAssignmentSubmission->GetSubmissionCountOnAssignment(
            $subject_code_assignment_id, $studentLoggedInId, $school_year_id);


        // var_dump($totalSubmissionCount);
        
        $doesLatestAssigntmentGraded = $subjectAssignmentSubmission->DoesStudentGradedSubmissionAssignment(
            $subject_assignment_submission_id, $school_year_id, $studentLoggedInId);

        # subject_assignment_submission_id reflected accordingly.

        # If graded = graded subject_assignment_submission_id
        # If not graded = latest subject_assignment_submission_id

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
            $subject_assignment_submission_id);

        $get_graded_remarks = $subjectAssignmentSubmission->GetSubjectGrade();


        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);

        $assignment_creation = $subjectCodeAssignment->GetDateCreation();
        $assignment_creation = date("M d, h:i a", strtotime($assignment_creation));

        $assignment_due_db = $subjectCodeAssignment->GetDueDate();
        $assignment_due = date("M d", strtotime($assignment_due_db));
        
        $assignment_max_attempt = $subjectCodeAssignment->GetAssignmentMaxAttempt();


        $back_url = "subject_module.php?id=$student_subject_id";

        $submission_data = $subjectAssignmentSubmission->GetSubmission(
            $subject_code_assignment_id,
            $school_year_id,
            $studentLoggedInId);

        $submission_remarks = $subject_assignment_submission_id = $submission_remark_percentage = NULL;

        $now = date("Y-m-d H:i:s");


        $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
            $subject_code_assignment_id,
            $studentLoggedInId, $school_year_id
        );
        
        $assignmentEnded = false;

        if($now >= $assignment_due_db){        
            $assignmentEnded = true;
        }

        $get_subject_assignment_submission = $subjectAssignmentSubmission->GetSubjectAssignmentSubmission(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);


        $get_subject_assignment_submission_date = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['date_creation'] : NULL;

        
        $assignmentAttempts = $subjectAssignmentSubmission->GetNumberOfAssignmentAttempt(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);



        if($submission_data != NULL){

            $subject_assignment_submission_id = $submission_data['subject_assignment_submission_id'];
            $submission_remarks = $submission_data['subject_grade'];
            $submission_remark_percentage = $subjectAssignmentSubmission->calculatePercentage($submission_remarks,
                $assignment_max_score);

        }


        $studentSubject = new StudentSubject($con, $student_subject_id);

        $enrollment_id = $studentSubject->GetEnrollmentId();


        $enrollment = new Enrollment($con);

        $current_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($studentLoggedInId,
            $current_school_year_id);
            
        $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
            ($studentLoggedInId,
            $current_school_year_id,
            $current_enrollment_id
        );

        // var_dump($allEnrolledSubjectCode);
        
        $enrolledSubjectList = [];

        foreach ($allEnrolledSubjectCode as $key => $value) {
            # code...
            $subject_codeGet = $value['student_subject_code'];
            array_push($enrolledSubjectList, $subject_codeGet);
        }

        $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {

            $base_url = 'http://localhost/school-system-dcbt/student/';
        } else {
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
        }

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/student/", "", $base_url);
            $logout_url = "$new_url/lms_logout.php";
        }


        ?>

            <div class="content">
               
                <?php
                    echo Helper::lmsStudentNotificationHeader(
                        $con, $studentLoggedInId,
                        $current_school_year_id,
                        $enrolledSubjectList,
                        $enrollment_id,
                        "second",
                        "first",
                        "second",
                        $logout_url,
                        "second"
                    );
                
                ?>
                
                <div class="content-header">
                    <header>
                    <div class="title">
                        <h2><?php echo $topic_name; ?> : <em><?php echo $assignment_name ?></em></h2>

                    </div>
                    </header>
                    <div class="cards">
                    <div class="card">
                        <sup>Type</sup>
                        <sub><?php echo $assignment_type === "upload" ? "Dropbox" : "Text" ?></sub>
                    </div>
                    <div class="card">
                        <sup>Max Score</sup>
                        <sub><?= $assignment_max_score; ?></sub>
                    </div>
                    <div class="card">
                        <sup>Category</sup>
                        <sub><?= $taskname;?></sub>
                    </div>
                    <div class="card">
                        <sup>Start</sup>
                        <sub><?php echo $assignment_creation ?></sub>
                    </div>
                    <div class="card">
                        <sup>Due</sup>
                        <sub><?php 
                            
                            $result = date("M d, h:i a", strtotime($assignment_due_db));
                            echo $result; 
                        ?>
                        </sub>
                    </div>
                    </div>
                </div>
                <div class="tabs">
                    <button
                    class="tab"
                    style="background-color: var(--theme); color: white"
                    onclick="window.location.href = 'task_submission.php?sc_id=<?php echo $subject_code_assignment_id; ?>&ss_id=<?= $student_subject_id;?>'"
                    >Instructions
                    </button>
                    <button
                    class="tab"
                    style="background-color: var(--mainContentBG); color: black"
                    onclick="location.href= 'submission_view.php?sc_id=<?php echo $subject_code_assignment_id; ?>&s_id=<?php echo $get_subject_assignment_submission_id; ?>&ss_id=<?= $student_subject_id;?>'" 
                    >
                    Submissions
                    </button>
                </div>

                <nav>
                    <a href="<?=$back_url;?>"
                    ><i class="bi bi-arrow-return-left"></i>Back</a
                    >
                </nav>

                <main>
                    <div class="bars">
                        
                        <div class="floating">
                            <header>
                                <div class="title">
                                    <!-- <h3>Score: <em>50/100</em></h3> -->

                                    <h3 >Score</h3> <em>
                                        <?php if ($submission_remarks !== NULL && $submission_remark_percentage !== NULL) : ?>
                                            <p><?php echo "$submission_remarks / $assignment_max_score ($submission_remark_percentage%)" ?></p>

                                        <?php elseif ($statusSubmission == NULL 
                                            // && $assignmentEnded == true
                                        ) : ?>
                                            <p><i style="color: orangered;" class="fas fa-times"></i> Nothing submitted yet</p>

                                        <?php elseif ($statusSubmission != NULL) : ?>
                                            <p>Waiting for Grade</p>
                                        <?php endif; ?>
                                    </em>
                                    
                                </div>
                            </header>
                            <main>
                                <?php if($submission_remark_percentage != NULL):?>
                                    <div class="progress" style="position: relative; height: 20px">
                                        <div class="progress-bar" style="position: absolute; top:0; height: 30px;  width: <?= $submission_remark_percentage;?>px"><?=$submission_remark_percentage;?>%</div>
                                    </div>
                                <?php endif;?>
                            </main>
                        </div>

                        <div class="floating">
                            <header>
                            <div class="title">
                                <h3>Submission</h3>
                                <small>Submitted: <em>
                                    <?php 
                                        $get_subject_assignment_submission_date =  $get_subject_assignment_submission_date; 

                                        // var_dump($get_subject_assignment_submission_date);

                                        if($get_subject_assignment_submission_date == NULL){
                                            echo "
                                                <i style='color:orangered;'class='fas fa-times'></i>
                                            ";
                                        }else{
                                            echo $submission_creation = date("M d, h:i a", strtotime($get_subject_assignment_submission_date));
                                        }
                                    
                                    ?>
                                </em></small>
                                <small>Attempts: <em>
                                    <?php echo $assignmentAttempts; ?>
                                </em></small>
                                <small>Max Attempts: <em>
                                    <?php echo $assignment_max_attempt; ?>
                                </em></small>
                            </div>
                            </header>
                        </div>

                    </div>

                    <div class="floating">

                        <header>
                            <div class="title">

                                <div class="text-right">

                                    <?php if($totalSubmissionCount > 1): ?>
                                        <button style="width: 120px;" class="btn btn-sm" 
                                            onclick="window.location.href = 'student_submission_list.php?id=<?php echo $subject_code_assignment_id; ?>'">
                                            History
                                        </button>
                                    <?php endif;?>
                                    

                                </div>

                                <h3 style="font-size: 22px;">Submissions</h3>
                               
                                <br>
                                <?php 
                                        
                                    if(count($getAnsweredAssignmentList) > 0){

                                        if($assignment_type == "upload"){

                                            $image_extensions = ['jpg', 'jpeg', 'png'];

                                            foreach ($getAnsweredAssignmentList as $key => $value) {

                                                $uploadFile = $value['output_file'];

                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);
                                                $original_file_name = "";

                                                if (strtolower($extension) === 'pdf' ||
                                                        strtolower($extension) === 'docx' ||
                                                        strtolower($extension) === 'doc') {

                                                    $pos = strpos($uploadFile, "img_");
                                                    if ($pos !== false) {
                                                        // Extract the filename portion
                                                        $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                    }

                                                    ?>
                                                        <a title="View File" href='<?php echo "../../".  $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                    <?php
                                                }

                                                if (in_array(strtolower($extension), $image_extensions)) {
                                                    ?>
                                                        <div class="card" style="width: 18rem;">
                                                            <div style="min-width: 750px;" class="card-body">

                                                                <a title="View File" href='<?php echo "../../". $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <img style="margin-left:8px; width: 520px;" 
                                                                    src='<?php echo "../../".$value['output_file']; ?>' alt='Given Photo' class='preview-image'>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            }

                                        
                                        }
                                        

                                    }

                                ?>

                            </div>
                        </header>
                        
                    </div>
                </main>
            </div>
        <?php
    }
?>

<script>
    $(document).ready(function () {
    // Initialize Summernote with readOnly: true

    $('.summernote').summernote({
        height: 250,
       
    });

    $('.summernote').next().find(".note-editable").attr("contenteditable", false);

});
</script>


