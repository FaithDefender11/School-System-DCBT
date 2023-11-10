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
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/TaskType.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectModuleAudit.php');

    echo Helper::RemoveSidebar();


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

    if(
        isset($_GET['sc_id']) &&
        isset($_GET['ss_id'])
        ){

        $subject_code_assignment_id = $_GET['sc_id'];
        $student_subject_id = $_GET['ss_id'];

        # 
        if(isset($_GET['n_id'])
            && isset($_GET['notification'])
            && $_GET['notification'] == "true"){

            $notification_id = $_GET['n_id'];

            $notification = new Notification($con);

            $markAsNotified = $notification->StudentNotificationMarkAsViewed(
                $notification_id, $studentLoggedInId);
            // echo "marked";
        }

        if(isset($_GET['n_id'])
            && isset($_GET['notification_due'])
            && $_GET['notification_due'] == "true"){

            $notification_id = $_GET['n_id'];

            $notification = new Notification($con);

            $markAsNotified = $notification->StudentDueNotificationUpdateViewed(
                $notification_id, $studentLoggedInId);
            // echo "marked";
        }

        $notification = new Notification($con);

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        
        $doesAssignmentGiven = $subjectCodeAssignment->GetIsGiven();
        $task_type_id = $subjectCodeAssignment->GetTaskTypeId();

        $taskType = new TaskType($con, $task_type_id);

        $taskname = $taskType->GetTaskName();

        # This will prompt student if he had accesed the ungiven assignment.
        $prompt = $subjectCodeAssignment->PromptAssignmentIsNotGiven();
 
        # TIME NOW
        $now = date("Y-m-d H:i:s");

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);
        
        $subject_period_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
        $assignment_type = $subjectCodeAssignment->GetType();
        $assignment_max_attempt = $subjectCodeAssignment->GetAssignmentMaxAttempt();
        $assignment_max_score = $subjectCodeAssignment->GetMaxScore();

        $assignment_due_db = $subjectCodeAssignment->GetDueDate();
        $assignment_due = date("M d", strtotime($assignment_due_db));

        $assignment_creation_db = $subjectCodeAssignment->GetDateCreation();
        $assignment_creation = date("M d, h:i a", strtotime($assignment_creation_db));

        $doesPastDue = $now > $assignment_due_db;

        // var_dump($doesPastDue);
        // echo "<br>";
        
        // var_dump($assignment_due_db);
        // echo "<br>";

        $assignment_description = $subjectCodeAssignment->GetDescription();
        $assignment_name = $subjectCodeAssignment->GetAssignmentName();

        $subject_code_assignment_template_id = $subjectCodeAssignment->GetSubject_code_assignment_template_id();

      
        $assignment_upload_files = $subjectCodeAssignment->GetUploadAssignmentFiles($subject_code_assignment_id);
        
        $getAllTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles(
            $subject_code_assignment_template_id);



        // print_r($assignment_upload_files);

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


        # Adding Audit Trail.

        $subjectModuleAudit = new SubjectModuleAudit($con);

        $assignment_audit_name = "Viewed $assignment_name under $topic_name";

        // var_dump($assignment_audit_name);

        $doesAuditSuccess = $subjectModuleAudit->InsertAuditOfSubjectModule(
            $student_subject_id, $current_school_year_id,
            $assignment_audit_name);



        // $back_url = "index.php?c=$subject_code";
        $back_url = "subject_module.php?id=$student_subject_id";
 
        // echo $subject_assignment_submission_id;

        $get_subject_assignment_submission = $subjectAssignmentSubmission->GetSubjectAssignmentSubmission(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);

        $get_subject_assignment_submission_id = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['subject_assignment_submission_id'] : NULL;

        $get_subject_assignment_submission_date = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['date_creation'] : NULL;


        $hasSubmittedAssignment = $subjectAssignmentSubmission->CheckStudentHasSubmissionOnAssignment(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);

        // var_dump($hasSubmittedAssignment);

        $doesSubmittedAndGraded = $subjectAssignmentSubmission->DoesStudentSubmittedAssignmentAndGraded(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);


            // var_dump($doesSubmittedAndGraded);

        $assignmentAttempts = $subjectAssignmentSubmission->GetNumberOfAssignmentAttempt(
            $subject_code_assignment_id, $school_year_id,
            $studentLoggedInId);

        $submission_data = $subjectAssignmentSubmission->GetSubmission(
            $subject_code_assignment_id,
            $school_year_id,
            $studentLoggedInId);

        $submissionCount = $subjectAssignmentSubmission->GetSubmissionCount(
            $subject_code_assignment_id,
            $school_year_id,
            $studentLoggedInId);

        // echo "submission count: ";
        // var_dump($submissionCount);
        // echo "<br>";

        // $submission_remarks = NULL;
        // $subject_assignment_submission_id = NULL;
        // $submission_remark_percentage = NULL;

        $submission_remarks = $subject_assignment_submission_id = $submission_remark_percentage = NULL;

        if($submission_data != NULL){

            $subject_assignment_submission_id = $submission_data['subject_assignment_submission_id'];
            $submission_remarks = $submission_data['subject_grade'];
            $submission_remark_percentage = $subjectAssignmentSubmission->calculatePercentage($submission_remarks,
                $assignment_max_score);

        }

        // var_dump($submission_remark_percentage);
        // var_dump($subject_assignment_submission_id);

        // $check = $notification->RemovePrevSubmittedNotification(
        //     $subject_assignment_submission_id, $studentLoggedInId, $subject_code, $current_school_year_id
        // );

        $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
            $subject_code_assignment_id,
            $studentLoggedInId, $school_year_id
        );

        // var_dump($statusSubmission);

        $assignmentEnded = false;
        if($now >= $assignment_due_db){        
            $assignmentEnded = true;
        }

        // var_dump($assignmentEnded);

        if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['insert_assignment_btn_' . $subject_code_assignment_id . '_user_' . $studentLoggedInId])
            ){
                
            $output_text = isset($_POST['output_text']) ?  $_POST['output_text'] : NULL;

            // var_dump($output_text);
            // return;

            $assignment_images = $_FILES['assignment_images'] ?? NULL;
            $image_upload = NULL;

            // if(empty($assignment_images['name'][0] == false)){
            //     echo "images not emprty";
            // }else{
            //     echo "images empty";
            // }
            // return;

            if (!is_dir('../../assets')) {
                mkdir('../../assets');
            }

            if (!is_dir('../../assets/images')) {
                mkdir('../../assets/images');
            }

            if (!is_dir('../../assets/images/student_assignment_images')) {
                mkdir('../../assets/images/student_assignment_images');
            }

            // $subject_assignment_submission_id = NULL;
        
            $hasInserted = false;

            if($output_text != NULL && $assignment_type === "text"){

                // $doesCreated = $subjectAssignmentSubmission->CreateSubmissionAssignment(
                //     $subject_code_assignment_id,
                //     $studentLoggedInId, $current_school_year_id);

                // $subject_assignment_submission_id = $con->lastInsertId();
                
                // if($subject_assignment_submission_id != 0){

                //     $wasInserted = $subjectAssignmentSubmission->SubmitWrittenAssignment(
                //         $subject_assignment_submission_id,
                //         $output_text);

                //     if($wasInserted){
                //         $hasInserted = true;
                //     }
                // }
            }

            if ($assignment_images 
                && $assignment_type === "upload"
                && empty($assignment_images['name'][0] == false)
                && is_array($assignment_images['tmp_name'])) {

                if($assignment_max_attempt > $submissionCount){

                    $doesCreated = $subjectAssignmentSubmission->CreateSubmissionAssignment(
                        $subject_code_assignment_id,
                        $studentLoggedInId, $current_school_year_id,
                        $subject_code,
                        $subject_assignment_submission_id);


                    $subject_assignment_submission_id = $con->lastInsertId();

                    # Adding notification.

                    $notification = new Notification($con);

                    if($subject_assignment_submission_id !== NULL){

                        $wasAddedNotif = $notification->StudentSubmitTaskNotification(
                            $subject_code, $current_school_year_id,
                            $subject_assignment_submission_id);

                        
                    }

                    $uploadDirectory = '../../assets/images/student_assignment_images/';

                    if($subject_assignment_submission_id != 0){

                        for ($i = 0; $i < count($assignment_images['tmp_name']); $i++) {
                            //
                            $originalFilename = $assignment_images['name'][$i];

                            // Generate a unique filename
                            $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
                            $targetPath = $uploadDirectory . $uniqueFilename;

                            if (move_uploaded_file($assignment_images['tmp_name'][$i], $targetPath)) {
                                $imagePath = $targetPath;

                                // Remove Directory Path in the Database.
                                $imagePath = str_replace('../../', '', $imagePath);

                                $fileUpload = $subjectAssignmentSubmission->SubmitImagesAssignment(
                                    $subject_assignment_submission_id, $imagePath
                                );

                                if($fileUpload){
                                    $hasInserted = true;
                                }

                            } 
                            // else {
                            //     // Handle the case where file upload failed.
                            //     echo "Error uploading file: " . $originalFilename . "<br>";
                            // }
                        }

                    }

                }

            } 

                
            if($hasInserted && $subject_code_assignment_id != 0){

                $taskname = strtolower($taskname);

                // $submitted_assignment_audit_name = "Viewed $assignment_name under $topic_name";
                $submitted_assignment_audit_name = "Submitted an $taskname on $assignment_name under $topic_name";
                $doesAuditSuccess = $subjectModuleAudit->InsertAuditOfSubjectModule(
                    $student_subject_id, $current_school_year_id,
                    $submitted_assignment_audit_name);

                Alert::successAutoRedirect("Submission has been delivered successfully.",
                    "submission_view.php?sc_id=$subject_code_assignment_id&s_id=$subject_assignment_submission_id&ss_id=$student_subject_id");
                exit();
            }
                

        } 

        $doesAvailabeToAnswer = $assignment_max_attempt > $assignmentAttempts;
        $doesNotAvailableToAnswer = $assignment_max_attempt == $assignmentAttempts;
        
        $doesSchoolYearMatch = $school_year_id == $current_school_year_id;

        // var_dump($doesSchoolYearMatch);

        $buttontext = "";
        $button_type = "";
        $button_name = "";

        $file_input_does_display = NULL;

        if ($doesAvailabeToAnswer && $doesSubmittedAndGraded == false
            && $assignmentEnded == false
            && $doesSchoolYearMatch
            ) {
            $buttontext = "Prepare answer";
            $button_type = "submit";
            $button_name = "insert_assignment_btn_$subject_code_assignment_id" . '_user_' . $studentLoggedInId;
            $file_input_does_display = true;
        
        } 
        
        else if ($doesAvailabeToAnswer && $doesSubmittedAndGraded == false
            && $assignmentEnded == true
            && $doesSchoolYearMatch
            
            ) {

            $buttontext = "Assignment Due";
            $button_name = "";
            $button_type = "button";
            $file_input_does_display = false;

        } 
        else if ($doesNotAvailableToAnswer == false
            && $doesAvailabeToAnswer 
            && $doesSubmittedAndGraded == true
            && $doesSchoolYearMatch
            
            ) {
            $buttontext = "Graded";
            $button_name = "";
            $button_type = "button";
            $file_input_does_display = false;
        } 
        else if ($doesNotAvailableToAnswer
            && $doesSchoolYearMatch
            ) {
            $buttontext = "No more submission";
            $button_name = "";
            $button_type = "button";
            $file_input_does_display = false;
        }

        // $statusSubmission == NULL && $assignmentEnded
  
        // var_dump($assignmentEnded);

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

        // echo $subject_code_assignment_id;

        // var_dump($school_year_id);
        // echo "<br>";
        // var_dump($current_enrollment_id);
        
        $enrolledSubjectList = [];

        foreach ($allEnrolledSubjectCode as $key => $value) {
            # code...
            $subject_codeGet = $value['student_subject_code'];
            array_push($enrolledSubjectList, $subject_codeGet);
        }
        

        // var_dump($enrolledSubjectList);


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
                        $logout_url
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
                    <button disabled
                    class="tab"
                    style="background-color: var(--mainContentBG); color: black"
                    onclick="window.location.href = 'task_submission.php?sc_id=<?php echo $subject_code_assignment_id; ?>&ss_id=<?= $student_subject_id;?>'"
                    >
                        Instructions
                    </button>

                    <?php if($hasSubmittedAssignment):?>
                        <button
                        class="tab"
                        style="background-color: var(--theme); color: white"
                        onclick="location.href= 'submission_view.php?sc_id=<?php echo $subject_code_assignment_id; ?>&s_id=<?php echo $get_subject_assignment_submission_id; ?>&ss_id=<?= $student_subject_id;?>'">
                            Submissions
                        </button>
                        <?php else:?>
                            <button
                                class="tab"
                                style="pointer-events: none; background-color: var(--theme); color: white"
                                onclick="location.href= 'submission_view.php?sc_id=<?php echo $subject_code_assignment_id; ?>&s_id=<?php echo $get_subject_assignment_submission_id; ?>&ss_id=<?= $student_subject_id;?>'">
                                    Submissions
                                </button>

                    <?php endif;?>
                    
                </div>

                <nav>
                    <a href="<?= $back_url;?>"
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
                                <h3 class="mb-2">Submission</h3>

                                <?php if($doesPastDue) :?>
                                    <small><i style="color: orangered;" class="fas fa-flag"></i> <em>Past Due</em></small>
                                    <?php else:?>
                                        <small>Submitted: <em>
                                            <?php 
                                                $get_subject_assignment_submission_date =  $get_subject_assignment_submission_date; 


                                                if($get_subject_assignment_submission_date == NULL){
                                                    echo "
                                                        <i style='color:orangered;'class='fas fa-times'></i>
                                                    ";
                                                }else{
                                                    echo $submission_creation = date("M d, h:i a", strtotime($get_subject_assignment_submission_date));
                                                }
                                            
                                            ?>
                                        </em></small>

                                <?php endif;?>

                                

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
                                <h3 class="mb-3" style="font-size: 22px;">Instructions</h3>
                                <textarea class="form-control summernote_disable" type='text' 
                                    id="description" name='description'><?php echo $assignment_description; ?></textarea>
                            </div>
                        </header>

                        <main>
                            <form method='POST' enctype="multipart/form-data">
                                <div class="row">
                                    <span>
                                        <div>
                                        <?php 

                                            if($assignment_type === "upload" && $subject_code_assignment_template_id === NULL){

                                                if(count($assignment_upload_files) > 0){

                                                    $image_extensions = ['jpg', 'jpeg', 'png'
                                                    // , 'gif', 'bmp', 'svg', 'webp'
                                                    ];

                                                    foreach ($assignment_upload_files as $key => $photo) {

                                                        $uploadFile = $photo['image'];

                                                        $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                        $pos = strpos($uploadFile, "img_");
                                                        $original_file_name = "";

                                                        // Check if "img_" was found
                                                        if ($pos !== false) {
                                                            $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                        }

                                                        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                        ?>
                                                            <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                <img style="margin-left:8px; width: 120px;" 
                                                                    src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                            </a>
                                                            <br>
                                                        <?php
                                                        } elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc', 'txt'])) {
                                                            ?>
                                                                <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                                <br>
                                                            <?php
                                                        }
                                                        
                                                    }
                                                } 

                                                if(($doesSubmittedAndGraded == false 
                                                    && $assignmentEnded == false 
                                                    && $doesSchoolYearMatch
                                                    && $file_input_does_display == true

                                                ) 
                                                    || (
                                                $assignmentEnded == false
                                                && $doesSchoolYearMatch
                                                && $file_input_does_display == true
                                                )){
                                                
                                                    # Assignment Button hide includes when:

                                                    # Deadline TRUE
                                                    # Graded TRUE

                                                    // var_dump($assignmentEnded);

                                                    ?>
                                                        <div class='form-group mb-2'>
                                                            <label for="assignment_images" class='mb-2'> Upload Image, PDF, Word, .txt</label>
                                                            <input class='form-control' type='file' id="assignment_images" 
                                                                multiple name='assignment_images[]'>
                                                        </div>
                                                    <?php
                                                }

                                            }

                                            if($subject_code_assignment_template_id !== NULL){

                                                if(count($getAllTemplateUploadFiles) > 0){

                                                    $image_extensions = ['jpg', 'jpeg', 'png'
                                                    // , 'gif', 'bmp', 'svg', 'webp'
                                                    ];

                                                    foreach ($getAllTemplateUploadFiles as $key => $photo) {

                                                        $uploadFile = $photo['image'];

                                                        $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                        if (strtolower($extension) === 'pdf') {

                                                            $parts = explode('_', $uploadFile);

                                                            // Get the last part of the resulting array (the original file name)
                                                            $original_file_name = end($parts);
                                                            // echo $original_file_name;
                                                            ?>
                                                                <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                            <?php
                                                            // echo 'The file has a .pdf extension.';
                                                        }

                                                        if (strtolower($extension) === 'docx' ||
                                                            strtolower($extension) === 'doc') {

                                                            $parts = explode('_', $uploadFile);

                                                            // Get the last part of the resulting array (the original file name)
                                                            $original_file_name = end($parts);
                                                            // echo $original_file_name;
                                                            ?>
                                                                <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                            <?php
                                                            // echo 'The file has a .docx, .doc extension.';
                                                        }

                                                        if (in_array(strtolower($extension), $image_extensions)) {
                                                            ?>
                                                                <a title="View File" href='<?php echo "../../". $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <img style="margin-left:8px; width: 120px;" 
                                                                        src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                                </a>
                                                            <?php
                                                        }
                                                    }
                                                    
                                                } 

                                                if(($doesSubmittedAndGraded == false && $assignmentEnded == false) 
                                                    || $assignmentEnded == false){
                                                
                                                    ?>
                                                        <div class='form-group mb-2'>
                                                            <label for="assignment_images" class='mb-2'> Upload Image, PDF, Word, .txt</label>
                                                            <input class='form-control' type='file' id="assignment_images" 
                                                                multiple name='assignment_images[]'>
                                                        </div>
                                                    <?php
                                                }

                                            }
                                        ?>
                                        </div>

                                        

                                    </span>
                                </div>

                                <div class="modal-footer">
                                    <?php if($doesSchoolYearMatch):?>
                                        <button type='<?php echo $button_type; ?>' class='btn btn-primary' name="<?php echo $button_name;?>">
                                            <?php echo $buttontext; ?>
                                        </button>
                                    <?php endif;?>

                                </div>

                            </form>
                        </main>

                    </div>
                </main>


            </div>
        <?php
    }
?>

<script>

    $(document).ready(function () {

       

        $('.summernote_disable').summernote({
            height:250
        });

        $('.summernote_disable').next().find(".note-editable").attr("contenteditable", false);
   
   });
</script>


