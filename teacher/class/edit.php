<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');

    if(isset($_GET['id'])
        && $_GET['id'] != ""
        ){

        $subject_code_assignment_id = $_GET['id'];

        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);
        
        $getAssignmentName = $subjectCodeAssignment->GetAssignmentName();
        $getAssignmentImage = $subjectCodeAssignment->GetAssignmentImage();
        $getDescription = $subjectCodeAssignment->GetDescription();
        $assignment_type = $subjectCodeAssignment->GetType();
        $max_attempt = $subjectCodeAssignment->GetAssignmentMaxAttempt();

        $checkOwned = $subjectCodeAssignment->CheckAssignmentBelongsToTeacher(
            $subject_code_assignment_id, $teacherLoggedInId);
        

        $subject_code_assignment_template_id = $subjectCodeAssignment->GetSubject_code_assignment_template_id();

        // echo $subject_code_assignment_template_id;

        $max_score = $subjectCodeAssignment->GetMaxScore();
        $getAllowLateSubmission = $subjectCodeAssignment->GetAllowLateSubmission();
        $due_date = $subjectCodeAssignment->GetDueDate();

        // echo $getAllowLateSubmission;
        
        $subject_period_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
        
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();
        $topic_name = $subjectPeriodCodeTopic->GetTopic();


        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
        
        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

        // echo $topic_subject_code;



        // echo $teacher_id;
        // $back_url = "index.php?c=$topic_subject_code";

        $getAllUploadFiles = $subjectCodeAssignment->GetUploadAssignmentFiles($subject_code_assignment_id);
        
        // var_dump($subject_period_code_topic_template_id);

        $getAllTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles(
            $subject_code_assignment_template_id);

        // echo $subject_period_code_topic_template_id;

        // print_r($getAllTemplateUploadFiles);
        
        // $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";
        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['edit_assignment_topic_'. $subject_period_code_topic_id])
            && isset($_POST['assignment_name'])
            && isset($_POST['max_score'])
            && isset($_POST['allow_late_submission'])
            && isset($_POST['due_date'])
            && isset($_POST['max_attempt'])
            
            ){

                $assignment_name = $_POST['assignment_name'];
                $max_score = $_POST['max_score'];
                $max_attempt = $_POST['max_attempt'];

                $allow_late_submission = $_POST['allow_late_submission'];
                $due_date = $_POST['due_date'];

                # Not Required field = NULL as default.
                $description = $_POST['description'] ?? NULL;
                $assignment_images = $_FILES['assignment_images'] ?? NULL;


                // echo count($assignment_images);

                $fileUploadSuccess = false;
                
                if (!empty($assignment_images['name'][0])
                    && is_array($assignment_images['tmp_name'])) {
                
                    $uploadDirectory = '../../assets/images/assignments_images/';

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

                            $fileUpload = $subjectCodeAssignment->UploadAssignmentFiles(
                                $subject_code_assignment_id, $imagePath
                            );

                            if($fileUpload){
                                $fileUploadSuccess = true;
                            }

                            // Process $imagePath as needed (e.g., store in a database).
                        } else {

                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }
                    }

                }

                // if($fileUploadSuccess){
                //     echo "Uploaded";
                // }

                // if ($assignment_images
                //     && is_array($assignment_images['tmp_name'])
                //     ) {


                // }

                // var_dump($assignment_image);
                // echo "assignment_name: $assignment_name";
                // echo "<br>";
                // echo "max_score: $max_score";
                // echo "<br>";
                // echo "allow_late_submission: $allow_late_submission";
                // echo "<br>";
                // echo "due_date: $due_date";
                // echo "<br>";
                // echo "description: $description";
                // echo "assignment_image: $assignment_image";

                if($topic_assigned_teacher_id !== $teacher_id){
                    Alert::error("You`re not teacher of this Subject Code.",
                        "");
                    exit();
                }

                $successEdit = $subjectCodeAssignment->UpdateAssignment(
                    $subject_period_code_topic_id,
                    $subject_code_assignment_id,
                    $assignment_name, $description, $max_score, 
                    $allow_late_submission, $due_date, $max_attempt);

                if($successEdit || $fileUploadSuccess){

                    Alert::success("Assignment has been successfully Edited",
                        $back_url);
                    exit();
                }

        }
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
                            <h3><?php echo $subjectPeriodCodeTopic->GetTopic(); ?> <em><?php echo $subject_code_assignment_template_id === NULL ? "Non-template" : "Template" ?></em></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="assignment_name">* Assignment Name</label>
                                    <div>
                                        <input readonly value="<?php echo $getAssignmentName; ?>" required class='form-control' type='text' 
                                            placeholder='Add Assignment' id="assignment_name" name='assignment_name'>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="description" class='mb-2'>Description</label>
                                    <div>
                                        <textarea class="form-control summernote" type='text' 
                                            placeholder='Add Description Here.' id="description" name='description'><?php echo $getDescription; ?></textarea>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="max_score">* Max Score</label>
                                    <div>
                                        <input readonly value="<?php echo $max_score; ?>" maxlength="3" value="100" required class='form-control' type='text' 
                                            placeholder='Max is 100 points' id="max_score" name='max_score'>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="due_date">* Set Due Date</label>
                                    <div>
                                        <input value="<?php echo $due_date; ?>" required class='form-control' type="datetime-local"
                                            id="due_date" name="due_date">
                                    </div>
                                </span>
                            </div>
                            <header>
                                <div class="title">
                                    <h4>* Allow Late Submission</h4>
                                </div>
                            </header>
                            <div class="row">
                                <span>
                                    <input <?php echo $getAllowLateSubmission === "yes" ? "checked" : ""; ?> type="radio" id="late_submission_yes" required name="allow_late_submission" value="yes">
                                    <label for="late_submission_yes">Yes</label>

                                    <input <?php echo $getAllowLateSubmission === "no" ? "checked" : ""; ?> type="radio" id="late_submission_no" required name="allow_late_submission" value="no">
                                    <label for="late_submission_no">No</label>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <?php if ($assignment_type === "upload" && $subject_code_assignment_template_id === NULL) : ?>
                                        <label for="assignment_images">Files</label>
                                        <div>
                                            <input value="<?php echo $getAssignmentImage; ?>" class='form-control' type='file' 
                                                placeholder='' id="assignment_images" multiple name='assignment_images[]'>
                                        </div>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php if ($subject_code_assignment_template_id === NULL && count($getAllUploadFiles) > 0 ): ?>
                                <div class="row">
                                    <span>
                                        <label for="assignment_images">Files</label>
                                        <div>
                                            <?php foreach ($getAllUploadFiles as $key => $photo): ?>
                                                <?php
                                                    $uploadFile = $photo['image'];

                                                    // echo $uploadFile;
                                                    // echo "<br>";
        
                                                    $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        
                                                    // $parts = explode('_', $uploadFile);
                                                    // $original_file_name = end($parts);
        
                                                    $pos = strpos($uploadFile, "img_");
        
                                                    $original_file_name = "";
        
                                                    // Check if "img_" was found
                                                    if ($pos !== false) {
                                                        $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                    }

                                                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                        ?>
                                                        <span onclick="uploadImageRemoval(<?php echo $photo['subject_code_assignment_list_id'] ?>, <?php echo $photo['subject_code_assignment_id'] ?>)" style="cursor: pointer;">
                                                            <i style="color: orange;" class="fas fa-times"></i>
                                                        </span>

                                                        <!-- <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <img style="margin-left:8px; width: 120px; heigt: 120px" 
                                                                src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                        </a> -->

                                                        <a  title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                    <?php
                                                    } elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc', 'txt'])) {
                                                        ?>
                                                        <span onclick="uploadImageRemoval(<?php echo $photo['subject_code_assignment_list_id'] ?>, <?php echo $photo['subject_code_assignment_id'] ?>)" style="cursor: pointer;">
                                                            <i style="color: orange;" class="fas fa-times"></i>
                                                        </span>
                                                            
                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                    <?php
                                                    }
                                                ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <!-- For Admin Assignment Template Content -->
                            <?php if ($subject_code_assignment_template_id !== NULL 
                                && count($getAllTemplateUploadFiles) > 0): ?>
                                <div class="row">
                                    <label for="assignment_images">Files</label>
                                    <div>
                                        <?php foreach ($getAllTemplateUploadFiles as $key => $photo): ?>
                                            <?php 
                                                $uploadFile = $photo['image'];
                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                // $parts = explode('_', $uploadFile);
                                                // $original_file_name = end($parts);
                                                    
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
                                                <?php
                                                } elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc', 'txt'])){
                                                    ?>
                                                    <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                <?php
                                                }
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="action">
                                <button type="submit" class="clean large" name="edit_assignment_topic_<?php echo $subject_period_code_topic_id; ?>"> Save Section</button>
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
        // $('#datetime').datetimepicker({
        //     format: 'hh:mm A'
        // });

        function uploadImageRemoval(subject_code_assignment_list_id,
            subject_code_assignment_id){

            var subject_code_assignment_list_id = parseInt(subject_code_assignment_list_id);
            var subject_code_assignment_id = parseInt(subject_code_assignment_id);

            Swal.fire({
                    icon: 'question',
                    title: `Are you sure you want remove the selected photo?`,
                    text: 'Important! This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "../../ajax/teacher/removeUploadedFile.php",
                            type: 'POST',
                            data: {
                                subject_code_assignment_list_id,
                                subject_code_assignment_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success_delete"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Successfully Deleted`,
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

                                    // $('#shs_program_table').load(
                                    //     location.href + ' #shs_program_table'
                                    // );

                                    location.reload();
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

        $(document).ready(function () {
            $('.summernote').summernote({
                height:250
            });
        });
        </script>
    </body>
</html>