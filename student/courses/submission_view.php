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

    ?>
        <head>
            <!-- SUMMER NOTE LINK -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

            <!-- SUMMER NOTE SCRIPT -->
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        </head>
    <?php

    if(isset($_GET['sc_id'])
        && isset($_GET['s_id'])){

        // $subject_code_assignment_id = $_GET['id'];
        $subject_code_assignment_id = $_GET['sc_id'];

        $subject_assignment_submission_id = $_GET['s_id'];

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        
        $subject_period_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
        $assignment_type = $subjectCodeAssignment->GetType();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $instructions = $subjectPeriodCodeTopic->GetDescription();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


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
            $subject_code_assignment_id, $studentLoggedInId, $current_school_year_id);


        // var_dump($totalSubmissionCount);
        
        $doesLatestAssigntmentGraded = $subjectAssignmentSubmission->DoesStudentGradedSubmissionAssignment(
            $subject_assignment_submission_id, $current_school_year_id, $studentLoggedInId);

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


        $back_url = "index.php?c=$subject_code";

        $submission_data = $subjectAssignmentSubmission->GetSubmission(
            $subject_code_assignment_id,
            $current_school_year_id,
            $studentLoggedInId);

        $submission_remarks = $subject_assignment_submission_id = $submission_remark_percentage = NULL;


        $statusSubmission   = "";
        $assignmentEnded   = "";

        $get_subject_assignment_submission = $subjectAssignmentSubmission->GetSubjectAssignmentSubmission(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);


        $get_subject_assignment_submission_date = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['date_creation'] : NULL;



        
        $assignmentAttempts = $subjectAssignmentSubmission->GetNumberOfAssignmentAttempt(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);



        if($submission_data != NULL){

            $subject_assignment_submission_id = $submission_data['subject_assignment_submission_id'];
            $submission_remarks = $submission_data['subject_grade'];
            $submission_remark_percentage = $subjectAssignmentSubmission->calculatePercentage($submission_remarks,
                $assignment_max_score);

        }

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <div>

                <div class="col-md-12 row">

                    <div class='col-md-8 offset-md-0'>

                        <div class='card'>
                            
                            <div class='card-header'>
                                <h4 class='text-left text-muted mb-3'><?php echo $topic_name; ?> : <span style="font-size: 19px;"><?php echo $assignment_name ?></span></h4>
                            
                                <button  type="button"
                                    onclick="window.location.href = 'task_submission.php?sc_id=<?php echo $subject_code_assignment_id; ?>' "
                                    class="btn btn-sm btn-outline-primary" >Instructions</button>
                            
                                <button  style="pointer-events: none;"
                                    class="btn btn-sm btn-primary">
                                    View Submission
                                </button>

                                <?php if($totalSubmissionCount > 1):?>
                                    <button onclick="window.location.href = 'student_submission_list.php?id=<?php echo $subject_code_assignment_id; ?>' "
                                        style="width: 120px;"
                                        class="btn btn-sm btn-dark" >History</button>
                                <?php endif;?>
                                
                            </div>


                            <div class="card-body">
                                <form method='POST' enctype="multipart/form-data">

                                    <div class='form-group mb-2' style="max-width: 650px;">
                                        <label style="font-size: 22px;" for="description" class='text-center mb-2'>Submissions</label>

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
                                                                <br>
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


                                </form>
                            </div>


                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class='card'>
                            
                            <div class='card-header'>
                                <!-- <p class="text-info text-center">Assignment type: <?php echo $assignment_type; ?></p> -->
                                <p>Type: Dropbox</p>
                                <p>Max Score: <?= $assignment_max_score; ?></p>
                                <p>Category: Assignment</p>
                                <p>Start: <?php echo $assignment_creation ?></p>
                                <p>Due: <?php echo $assignment_due ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class='card'>
                            <div class='card-header'>

                                <h5 style="margin-bottom: 7px;">Score</h5>
                                <?php if ($submission_remarks !== NULL && $submission_remark_percentage !== NULL) : ?>
                                    <p><?php echo "$submission_remarks / $assignment_max_score ($submission_remark_percentage%)" ?></p>

                                <?php elseif ($statusSubmission == NULL && $assignmentEnded) : ?>
                                    <p><i style="color: orangered;" class="fas fa-times"></i> Nothing submitted yet</p>

                                <?php else : ?>
                                    <p>Waiting for Grade</p>
                                <?php endif; ?>
                            </div>
                        </div>


                        <hr>


                        <div class='card'>
                            
                            <div class='card-header'>
                                <h5 style="margin-bottom: 7px;">Submission</h5>

                                <?php if($get_subject_assignment_submission_date !== NULL) :?>
                                    <p class="mb-1">Submitted: <?php 
                                    $get_subject_assignment_submission_date =  $get_subject_assignment_submission_date; 
                                        echo $submission_creation = date("M d, h:i a", strtotime($get_subject_assignment_submission_date));
                                ?> </p>
                                <?php endif;?>
                                
                                <p class="mb-1">Attempts: <?php echo $assignmentAttempts; ?> </p>
                                <p class="mb-1">Max Attempts: <?php echo $assignment_max_attempt; ?> </p>
                                <!-- <p class="mb-1">Allow late submissions: </p> -->
                            </div>
                           
                        </div>

                    </div>


                </div>

                <main>
                    <div class="floating" id="shs-sy">
                        <button style="width: 135px;" onclick="window.location.href = 'task_submission.php?sc_id=<?php echo $subject_code_assignment_id; ?>&ss_id=' "
                            class="btn btn-sm btn-outline-primary" >
                            Instructions
                        </button>

                        <button style="width: 135px;"
                            class="btn btn-sm btn-primary" >Submission</button>
                        
                        <header>
                            <div class="title">
                                <p class="text-info text-right">Assignment type: <?php echo $assignment_type; ?></p>
                                <h3>Submissions</h3>

                                <?php
                                    if($doesLatestAssigntmentGraded && $get_graded_remarks !== NULL){
                                        ?>
                                            <p>Grade Remarks: <?php echo $get_graded_remarks; ?></p>
                                        <?php
                                    }
                                ?>
                                
                                <?php 
                                
                                    // if(count($getAnsweredAssignmentList) > 1){
                                    if($totalSubmissionCount > 1){
                                        ?>
                                            <button onclick="window.location.href = 'student_submission_list.php?id=<?php echo $subject_code_assignment_id; ?>' "
                                                style="width: 120px;"
                                                class="btn btn-sm btn-dark" >History</button>
                                        <?php
                                    }
                                ?>
                                
                                
                            </div>

                        </header>

                        <main>
                            <?php 
                            
                                if(count($getAnsweredAssignmentList) > 0){

                                    if($assignment_type == "text"){


                                        foreach ($getAnsweredAssignmentList as $key => $value) {

                                            ?>
                                            <div class="card" style="width: 18rem;">
                                                
                                                <div style="min-width: 750px;" class="card-body">
                                                        
                                                        <div style="max-width: 650px;" class='form-group mb-2'>
                                                        
                                                            <label style="font-size: 20px;" for="output_text" class='text-center mb-2'>Answer</label>
                                                            <textarea class="form-control summernote" type='text' 
                                                                id="output_text" name='output_text'><?php echo $value['output_text'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    }

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
                                                    <br>
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
                            

                        </main>
                    </div>
                </main>
                </div>
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


