<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    

    if(
        isset($_GET['id'])
        && isset($_GET['ct_id'])
        && isset($_GET['t_id'])
        
        ){

        $subject_code_assignment_template_id = $_GET['id'];
        $subject_period_code_topic_id = $_GET['ct_id'];
        $subject_period_code_topic_template_id = $_GET['t_id'];



        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);
        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();


        $school_year = new SchoolYear($con);
 
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con, $subject_code_assignment_template_id);


        $template_topic_id = $subjectCodeAssignmentTemplate->GetSubjectPeriodCodeTopicId();

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con, $template_topic_id);
        
        $topic = $subjectPeriodCodeTopicTemplate->GetTopic();


        $description = $subjectCodeAssignmentTemplate->GetDescription();

        $max_score = $subjectCodeAssignmentTemplate->GetMaxScore();
        $assignment_type = $subjectCodeAssignmentTemplate->GetType();
        $assignment_name = $subjectCodeAssignmentTemplate->GetAssignmentName();

        // echo $assignment_type;

        $back_url = "section_topics.php?id=$subject_period_code_topic_template_id&ct_id=$subject_period_code_topic_id";

        $getAllTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles(
            $subject_code_assignment_template_id);


        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['add_assignment_topic_template_'. $subject_code_assignment_template_id])
            && isset($_POST['assignment_name'])
            && isset($_POST['max_score'])
            && isset($_POST['allow_late_submission'])
            && isset($_POST['type'])
            && isset($_POST['max_attempt'])
            && isset($_POST['due_date'])){


                $assignment_name = $_POST['assignment_name'];
                $max_score = $_POST['max_score'];

                $allow_late_submission = $_POST['allow_late_submission'];
                $due_date = $_POST['due_date'];

                $max_attempt = $_POST['max_attempt'];
                $type = $_POST['type'];

                $description = $_POST['description'] ?? NULL;


                // echo "Assignment Name: $assignment_name<br>";
                // echo "Max Score: $max_score<br>";
                // echo "Allow Late Submission: $allow_late_submission<br>";
                // echo "Due Date: $due_date<br>";
                // echo "Max Attempt: $max_attempt<br>";
                // echo "Type: $type<br>";
                // echo "Description: $description<br>";



                $assignment_images = $_FILES['assignment_images'] ?? NULL;
                $image_upload = NULL;

                if (!is_dir('../../assets')) {
                    mkdir('../../assets');
                }
                if (!is_dir('../../assets/images')) {
                    mkdir('../../assets/images');
                }
                if (!is_dir('../../assets/images/assignments_images')) {
                    mkdir('../../assets/images/assignments_images');
                }

                if($topic_assigned_teacher_id !== $teacher_id){
                    Alert::error("You`re not teacher of this Subject Code.",
                        "");
                    exit();
                }

                $subject_code_assignment_id = 0;

                $subjectCodeAssignment = new SubjectCodeAssignment($con);


                $successCreate = $subjectCodeAssignment->InsertAssignmentTemplate(
                    $subject_period_code_topic_id,
                    $subject_code_assignment_template_id,
                    $assignment_name, $description, $max_score, 
                    $allow_late_submission, $due_date, $type, $max_attempt);

                // if($successCreate){
                //     $subject_code_assignment_id = $con->lastInsertId();

                // }

                if (false) {
                // if ($assignment_images && $subject_code_assignment_id !== 0 && is_array($assignment_images['tmp_name'])) {
                    $uploadDirectory = '../../assets/images/assignments_images/';

                    for ($i = 0; $i < count($assignment_images['tmp_name']); $i++) {
                        //
                        $originalFilename = $assignment_images['name'][$i];

                        // Generate a unique filename
                        $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                        $targetPath = $uploadDirectory . $uniqueFilename;

                        if (move_uploaded_file($assignment_images['tmp_name'][$i], $targetPath)) {
                            $imagePath = $targetPath;

                            // Remove Directory Path in the Database.
                            $imagePath = str_replace('../../', '', $imagePath);

                            $fileUpload = $subjectCodeAssignment->UploadAssignmentFiles(
                                $subject_code_assignment_id, $imagePath
                            );

                            // Process $imagePath as needed (e.g., store in a database).
                        } else {
                            // Handle the case where file upload failed.
                            echo "Error uploading file: " . $originalFilename . "<br>";
                        }
                    }
                } 
               

                if($successCreate){

                    $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

                    Alert::success("Assignment has been successfully Inserted",
                        $back_url);
                    exit();
                }

              
        }
        
        
        $getTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles($subject_code_assignment_template_id);

        // print_r($getTemplateUploadFiles);

        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>


                <div class='col-md-10 offset-md-1'>
                        
                    <div class='card'>
 
                        <div class='card-header'>
                            <h5  class='text-muted text-center mb-3'>Assignment Type: <?php echo ucwords($assignment_type) ?></h5>
                        </div>

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">
 
                                 <div class='form-group mb-2'>
                                    <label for="assignment_name" class='mb-2'>* Assignment Name</label>
                                    
                                    <input readonly value="<?php echo $assignment_name; ?>" type="text" name="assignment_name"
                                        id="assignment_name" 
                                        class="form-control">
                                          
                                </div>

                                


                                <?php if (count($getTemplateUploadFiles) > 0 && $assignment_type == "upload"): ?>
                                    <div class='form-group mb-2'>
                                        <label for="assignment_images" class='mb-2'>Files</label>
                                        <br>
                                        <?php foreach ($getTemplateUploadFiles as $key => $photo): ?>
                                            <?php 
                                                $uploadFile = $photo['image'];
                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                $parts = explode('_', $uploadFile);

                                                $original_file_name = end($parts);

                                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                    ?>
                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <img style="margin-left:8px; width: 120px;" 
                                                                src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                        </a>
                                                        <br>
                                                    <?php
                                                } elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>
                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                        <br>
                                                    <?php
                                                }
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                

                                <div class='form-group mb-2'>
                                    <label for="description" class='mb-2'>Instructions <span style="font-size: 12px"></span></label>

                                    <textarea class="form-control summernote" type='text' 
                                        placeholder='Optional' id="description" name='description'><?php echo $description ?></textarea>
                                </div>
                                 
                                <div class='form-group mb-2'>
                                    <label for="max_score" class='mb-2'>* Max Score</label>

                                    <input readonly value="<?php echo $max_score; ?>" maxlength="3"   required class='form-control' type='text' 
                                        placeholder='Max is 100 points' id="max_score" name='max_score'>
                                </div>


                                <?php if ($subject_period_code_topic_template_id !== NULL &&
                                    count($getAllTemplateUploadFiles) > 0): ?>

                                    <div class='form-group mb-2'>
                                        <label for="assignment_images" class='mb-2'>Files</label>
                                        <br>
                                        <?php foreach ($getAllTemplateUploadFiles as $key => $photo): ?>
                                            <?php 

                                                $uploadFile = $photo['image'];
                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                $parts = explode('_', $uploadFile);

                                                $original_file_name = end($parts);

                                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                    ?>
                                                       
                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <img style="margin-left:8px; width: 120px;" 
                                                                src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                        </a>
                                                        <br>
                                                    <?php
                                                } elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>
                                                      
                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                        <br>
                                                    <?php
                                                }
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class='form-group mb-2'>
                                    <label for="due_date" class='mb-2'>* Set Due Date</label>
                                    <input required class='form-control'
                                        type="datetime-local" id="due_date" name="due_date">
                                </div>


                                <div class='form-group mb-2'>

                                    <label>* Allow Late Submission</label><br>
                                    <input type="radio" id="late_submission_yes" required name="allow_late_submission" value="yes">
                                    
                                    <label for="late_submission_yes">Yes</label> &nbsp;
                                    <input type="radio" checked id="late_submission_no" required name="allow_late_submission" value="no">
                                    
                                    <label for="late_submission_no">No</label><br>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="type" class='mb-2'>* Type</label>
                                    <select readonly required class='form-control' name="type" id="type">
                                        <option value="text" <?php echo $assignment_type === "text" ? "selected" : "" ?>>Text</option>
                                        <option value="upload" <?php echo $assignment_type === "upload" ? "selected" : "" ?>>Upload</option>
                                    </select>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="max_attempt" class='mb-2'>* Submission Count</label>
                                    <input required class='form-control' type="text"
                                       id="max_attempt" name="max_attempt">
                                </div>

                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='add_assignment_topic_template_<?php echo $subject_code_assignment_template_id; ?>'>Give</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                
            </div>
        <?php

    }
?>

<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            height:250
        });
    });
</script>