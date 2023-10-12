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

    ?>
        <head>
            <!-- SUMMER NOTE LINK -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

            <!-- SUMMER NOTE SCRIPT -->
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
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

        if(isset($_GET['n_id'])
            && isset($_GET['notification'])
            && $_GET['notification'] == "true"){

        $notification_id = $_GET['n_id'];

        $notification = new Notification($con);

        $markAsNotified = $notification->StudentNotificationMarkAsViewed($notification_id, $studentLoggedInId);
        // echo "marked";
        }

        $notification = new Notification($con);

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        
        $doesAssignmentGiven = $subjectCodeAssignment->GetIsGiven();

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

        $assignment_creation = $subjectCodeAssignment->GetDateCreation();
        $assignment_creation = date("M d, h:i a", strtotime($assignment_creation));

        $assignment_description = $subjectCodeAssignment->GetDescription();

        $subject_code_assignment_template_id = $subjectCodeAssignment->GetSubject_code_assignment_template_id();

      
        $assignment_upload_files = $subjectCodeAssignment->GetUploadAssignmentFiles($subject_code_assignment_id);
        
        $getAllTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles(
            $subject_code_assignment_template_id);

        // print_r($assignment_upload_files);

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

        // $back_url = "index.php?c=$subject_code";
        $back_url = "index.php?id=$student_subject_id";
 
        // echo $subject_assignment_submission_id;

        $get_subject_assignment_submission = $subjectAssignmentSubmission->GetSubjectAssignmentSubmission(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);

        $get_subject_assignment_submission_id = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['subject_assignment_submission_id'] : NULL;

        $get_subject_assignment_submission_date = $get_subject_assignment_submission !== NULL 
            ? $get_subject_assignment_submission['date_creation'] : NULL;


        $hasSubmittedAssignment = $subjectAssignmentSubmission->CheckStudentHasSubmissionOnAssignment(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);


        $doesSubmittedAndGraded = $subjectAssignmentSubmission->DoesStudentSubmittedAssignmentAndGraded(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);


            // var_dump($doesSubmittedAndGraded);

        $assignmentAttempts = $subjectAssignmentSubmission->GetNumberOfAssignmentAttempt(
            $subject_code_assignment_id, $current_school_year_id,
            $studentLoggedInId);

        $submission_data = $subjectAssignmentSubmission->GetSubmission(
            $subject_code_assignment_id,
            $current_school_year_id,
            $studentLoggedInId);

        // var_dump($submission_data);

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

        // var_dump($subject_assignment_submission_id);

        // $check = $notification->RemovePrevSubmittedNotification(
        //     $subject_assignment_submission_id, $studentLoggedInId, $subject_code, $current_school_year_id
        // );

        $statusSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
            $subject_code_assignment_id,
            $studentLoggedInId, $current_school_year_id
        );

        // var_dump($statusSubmission);

        $assignmentEnded = false;
        if($now >= $assignment_due_db){        
            $assignmentEnded = true;
        }

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

                
            if($hasInserted && $subject_code_assignment_id != 0){
                Alert::successAutoRedirect("Submission has been delivered successfully.",
                    "submission_view.php?id=$subject_code_assignment_id&s_id=$subject_assignment_submission_id");
                exit();
            }
                

        } 

        $doesAvailabeToAnswer = $assignment_max_attempt > $assignmentAttempts;
        $doesNotAvailableToAnswer = $assignment_max_attempt == $assignmentAttempts;
        
        $buttontext = "";
        $button_type = "";
        $button_name = "";

        if ($doesAvailabeToAnswer && $doesSubmittedAndGraded == false
            && $assignmentEnded == false) {
            $buttontext = "Prepare answer";
            $button_type = "submit";
            $button_name = "insert_assignment_btn_$subject_code_assignment_id" . '_user_' . $studentLoggedInId;
        } 
        
        else if ($doesAvailabeToAnswer && $doesSubmittedAndGraded == false
            && $assignmentEnded == true) {

            $buttontext = "Assignment Ended";
            $button_name = "";
            $button_type = "button";
        } 
        else if ($doesNotAvailableToAnswer == false
            && $doesAvailabeToAnswer && $doesSubmittedAndGraded == true) {
            $buttontext = "Graded";
            $button_name = "";
            $button_type = "button";
        } 
        else if ($doesNotAvailableToAnswer) {
            $buttontext = "No more submission";
            $button_name = "";
            $button_type = "button";
        } 
  
        ?>

            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class="col-md-12 row">

                    <div class='col-md-8 offset-md-0'>
                        <div class='card'>
                            
                            <div class='card-header'>
                                <h4 class='text-left text-muted mb-3'><?php echo $topic_name; ?> : <span style="font-size: 19px;"><?php echo $assignment_name ?></span></h4>
                            
                                <button style="pointer-events: none;" type="button" "
                                    class="btn btn-sm btn-primary" >Instructions</button>

                                <?php 
                                    if($assignmentAttempts > 0){

                                        $get_subject_assignment_submission_id = $subjectAssignmentSubmission->
                                            GetSubjectAssignmentSubmissionIdNonGraded(
                                            $subject_code_assignment_id, $current_school_year_id);

                                        // echo $subject_assignment_submission_id;
                                        
                                        ?>
                                            <button onclick="location.href= 'submission_view.php?id=<?php echo $subject_code_assignment_id; ?>&s_id=<?php echo $get_subject_assignment_submission_id; ?>'" 
                                                class="btn btn-sm btn-outline-primary">Submissions</button>
                                        <?php
                                    }
                                ?>

                            </div>

                            <div class="card-body">

                                <form method='POST' enctype="multipart/form-data">

                                    <div class='form-group mb-2' style="max-width: 650px;">
                                        <label style="font-size: 22px;" for="description" class='text-center mb-2'>Instructions</label>

                                        <textarea class="form-control summernote_disable" type='text' 
                                            id="description" name='description'><?php echo $assignment_description; ?></textarea>
                                    </div>

                                    <?php 

                                        if($assignment_type === "text" ){

                                            if($doesAvailabeToAnswer){
                                                ?>
                                                    <div style="max-width: 650px;" class='form-group mb-2'>
                                                        <label style="font-size: 20px;" for="output_text" class='text-center mb-2'>Answer Here</label>
                                                        <textarea style="min-height: 250px;" class="form-control summernote" type='text' 
                                                            id="output_text" name='output_text'></textarea>
                                                    </div>
                                                <?php

                                            }
                                            
                                        }

                                        if($assignment_type === "upload" && $subject_code_assignment_template_id === NULL){

                                            if(count($assignment_upload_files) > 0){

                                                $image_extensions = ['jpg', 'jpeg', 'png'
                                                // , 'gif', 'bmp', 'svg', 'webp'
                                                ];

                                                foreach ($assignment_upload_files as $key => $photo) {

                                                    $uploadFile = $photo['image'];

                                                    $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                    if (strtolower($extension) === 'pdf') {

                                                        $parts = explode('_', $uploadFile);

                                                        // Get the last part of the resulting array (the original file name)
                                                        $original_file_name = end($parts);

                                                        

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

                                            ?>
                                                <div class='form-group mb-2'>
                                                    <label for="assignment_images" class='mb-2'> Upload Image, PDF, Word, .txt</label>
                                                    <input class='form-control' type='file' id="assignment_images" 
                                                        multiple name='assignment_images[]'>
                                                </div>
                                            <?php
                                        }

                                        if( $subject_code_assignment_template_id !== NULL){

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

                                            if($assignment_type === "upload"){
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
                                
                                    
                                    

                                    <div class="modal-footer">
                                        <button type='<?php echo $button_type; ?>' class='btn btn-primary' name="<?php echo $button_name;?>">
                                            <?php echo $buttontext; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class='card'>
                            
                            <div class='card-header'>
                                <!-- <p class="text-info text-center">Assignment type: <?php echo $assignment_type; ?></p> -->
                                <p>Type: <?php echo $assignment_type === "upload" ? "Dropbox" : "Text" ?></p>
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
                                    <p class="mb-1">Submitted: 

                                        <?php 
                                            $get_subject_assignment_submission_date =  $get_subject_assignment_submission_date; 
                                                
                                            echo $submission_creation = date("M d, h:i a", strtotime($get_subject_assignment_submission_date));
                                        ?>
                                    </p>
                                <?php endif;?>
                                
                                <p class="mb-1">Attempts: <?php echo $assignmentAttempts; ?> </p>
                                <p class="mb-1">Max Attempts: <?php echo $assignment_max_attempt; ?> </p>
                                <!-- <p class="mb-1">Allow late submissions: </p> -->
                            </div>
                           
                        </div>

                    </div>


                </div>


                
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


