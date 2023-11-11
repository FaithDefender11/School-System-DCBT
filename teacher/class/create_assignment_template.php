<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/Notification.php');

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
        $notification = new Notification($con);
 
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

        // $back_url = "section_topics.php?id=$subject_period_code_topic_template_id&ct_id=$subject_period_code_topic_id";
        
        $back_url = "section_topic.php?id=9&ct_id=36";

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic);


        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

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
 
                if($successCreate){

                    $subject_code_assignment_id = $con->lastInsertId();

                    // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);
                    // $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        

                    $wasNotifInserted = $notification->InsertNotificationForTeacherGivingAssignment(
                        $current_school_year_id,
                        $subject_code_assignment_id,
                        $topic_subject_code);
                    
                    Alert::success("Assignment has been successfully Inserted",
                        $back_url);
                    exit();
                }

              
        }
        
        
        $getTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles($subject_code_assignment_template_id);

        // print_r($getTemplateUploadFiles);
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
                            <h3>Assignment Type: <?php echo ucwords($assignment_type) ?></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="assignment_name">* Assignment Name</label>
                                    <div>
                                        <input 
                                            readonly 
                                            type="text" 
                                            name="assignment_name" 
                                            id="assignment_name" 
                                            class="form-control" 
                                            required
                                            value="<?php echo $assignment_name; ?>"
                                        >
                                    </div>
                                </span>
                            </div>
                            <?php if (count($getTemplateUploadFiles) > 0 && $assignment_type == "upload"): ?>
                                <div class="row">
                                    <span>
                                        <label for="assignment_images">Files</label>
                                        <div>
                                            <?php foreach ($getTemplateUploadFiles as $key => $photo): ?>
                                                <?php
                                                   $uploadFile = $photo['image'];
                                                   $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);
   
                                                   $parts = explode('_', $uploadFile);
   
                                                   $original_file_name = end($parts);
   
                                                   if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                       ?>
                                                           <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                               <img style="width: 120px;" 
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
                                                ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <span>
                                   <label for="description">Instructions</label>
                                    <div>
                                        <textarea 
                                            name="description" 
                                            id="description" 
                                            type="text" 
                                            placeholder="Optional" 
                                            class="form-control summernote"
                                        >
                                            <?php echo $description ?>
                                        </textarea>
                                    </div> 
                                </span>
                                
                            </div>
                            <div class="row">
                                <span>
                                    <label for="max_score">* Max Score</label>
                                    <div>
                                        <input 
                                        readonly 
                                        value="<?php echo $max_score; ?>" 
                                        required 
                                        class='form-control' 
                                        type='text' 
                                        placeholder='Max is 100 points' 
                                        id="max_score" 
                                        name='max_score'
                                        >
                                    </div>
                                </span>
                            </div>
                            <?php if ($subject_period_code_topic_template_id !== NULL &&
                                    count($getAllTemplateUploadFiles) > 0): ?>
                                    <div class="row">
                                        <span>
                                            <label for="assignment_images">Files</label>
                                            <div>
                                                <?php foreach ($getAllTemplateUploadFiles as $key => $photo): ?>
                                                    <?php
                                                        $uploadFile = $photo['image'];
                                                        $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        
                                                        $parts = explode('_', $uploadFile);
        
                                                        $original_file_name = end($parts);
        
                                                        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                            ?>
                                                               
                                                                <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <img style="width: 120px;" 
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
                                                    ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </span>
                                    </div>
                            <?php endif; ?>
                            <div class="row">
                                <span>
                                    <label for="due_date">* Set Due Date</label>
                                    <div>
                                        <input required class='form-control'
                                            type="datetime-local" id="due_date" name="due_date">
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="allow_late_submission">* Allow Late Submission</label>
                                    <div>
                                        <input type="radio" id="late_submission_yes" required name="allow_late_submission" value="yes">
                                    </div>
                                </span>
                                <span>
                                    <label for="late_submission_yes">Yes</label>
                                    <div>
                                        <input type="radio" checked id="late_submission_no" required name="allow_late_submission" value="no">
                                    </div>
                                </span>
                                <span>
                                    <label for="late_submission_no">No</label>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="type">* Type</label>
                                    <div>
                                        <select readonly required class='form-control' name="type" id="type">
                                            <option value="text" <?php echo $assignment_type === "text" ? "selected" : "" ?>>Text</option>
                                            <option value="upload" <?php echo $assignment_type === "upload" ? "selected" : "" ?>>Upload</option>
                                        </select>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="max_attempt" class='mb-2'>* Submission Count</label>
                                    <div>
                                        <input value="1" required class='form-control' type="text"
                                        id="max_attempt" name="max_attempt">
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button 
                                    type="submit" 
                                    class="clean"
                                    name='add_assignment_topic_template_<?php echo $subject_code_assignment_template_id; ?>'
                                >
                                    Give
                                </button>
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
            $('.summernote').summernote({
                height:250
            });
        });
    </script>
    </body>
</html>