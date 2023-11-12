<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/TaskType.php');

    if(
        isset($_GET['id'])
        && isset($_GET['t_id'])
        && isset($_GET['task_id'])
        
        ){

        $subject_period_code_topic_id = $_GET['id'];
        $subject_period_code_topic_template_id = $_GET['t_id'];

        $task_type_id = $_GET['task_id'];

        $taskType = new TaskType($con, $task_type_id);

        $taskName = $taskType->GetTaskName();

        $school_year = new SchoolYear($con);

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate(
            $con);


        $subjectCodeAssignment = new SubjectCodeAssignment($con);

        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();
        $topic_name = $subjectPeriodCodeTopic->GetTopic();


        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

        $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList($subjectPeriodCodeTopicTemplateId);

        // print_r($codeAssignmentTemplateList);

        // $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con,
        //     $subjectPeriodCodeTopicTemplateId);

        // echo $topic_assigned_teacher_id;

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];


        // $back_url = "assignment_index.php?id=$subject_period_code_topic_template_id&sct_id=$subject_period_code_topic_id";
        $back_url = "task.php?id=$task_type_id&sctt_id=$subject_period_code_topic_template_id&sct_id=$subject_period_code_topic_id";
        
        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['add_assignment_topic_'. $subject_period_code_topic_id])
            && isset($_POST['assignment_name'])
            && isset($_POST['max_score'])
            && isset($_POST['allow_late_submission'])
            && isset($_POST['type'])
            && isset($_POST['max_attempt'])
            && isset($_POST['due_date'])
            // && isset($_POST['task_type_id'])
            
            ){

                // echo "hey";
                // echo 'assignment_name: ' . ($_POST['assignment_name'] ?? '') . '<br>';
                // echo 'max_score: ' . ($_POST['max_score'] ?? '') . '<br>';
                // echo 'allow_late_submission: ' . ($_POST['allow_late_submission'] ?? '') . '<br>';
                // echo 'type: ' . ($_POST['type'] ?? '') . '<br>';
                // echo 'max_attempt: ' . ($_POST['max_attempt'] ?? '') . '<br>';
                // echo 'due_date: ' . ($_POST['due_date'] ?? '') . '<br>';
                // echo 'task_type_id: ' . ($_POST['task_type_id'] ?? '') . '<br>';

                // echo $task_type_id;

                // return;

                $assignment_name = $_POST['assignment_name'];
                $max_score = $_POST['max_score'];

                $allow_late_submission = $_POST['allow_late_submission'];
                $due_date = $_POST['due_date'];

                // $task_type_id = $_POST['task_type_id'];


                $max_attempt = $_POST['max_attempt'];
                $type = $_POST['type'];

                # Not Required field = NULL as default.
                $description = $_POST['description'] ?? NULL;
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

                $successCreate = $subjectCodeAssignment->InsertAssignment(
                    $subject_period_code_topic_id,
                    $assignment_name, $description, $max_score, 
                    $allow_late_submission, $due_date, $type, $max_attempt, $task_type_id);

                if($successCreate){
                    $subject_code_assignment_id = $con->lastInsertId();
                }
                
                // var_dump($assignment_images);

                // if (false) {

                if ($assignment_images
                     && $subject_code_assignment_id !== 0
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

                            // Process $imagePath as needed (e.g., store in a database).
                        }
                        else {
                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }
                    }
                } 

                if($successCreate){

                    Alert::success("Assignment has been successfully Inserted",
                        $back_url);
                    exit();
                }
 
            }
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
                            <h3>Add Assignment to: <?php echo $subjectPeriodCodeTopic->GetTopic(); ?></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class='form-group mb-2'>
                                <label for="type" class='mb-2'>* Type</label>
                                <select required class='form-control' name="type" id="type">
                                    <!-- <option value="" disabled selected>Choose Type</option> -->
                                    <!-- <option value="text">Text</option> -->
                                    <option selected value="upload">Upload</option>
                                </select>
                            </div>

                            <!-- taskName -->
                            <div class='form-group mb-2'>
                                <label for="task_type_id" class='mb-2'>* Category</label>

                                <input disabled class='form-control' type='text' 
                                    id="task_type_id" value="<?= $taskName;?>"'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="assignment_name" class='mb-2'>* <?= ucwords($taskName)?> Name</label>

                                <input required class='form-control' type='text' 
                                    placeholder='Add Assignment' id="assignment_name" name='assignment_name'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="assignment_images" class='mb-2'> Files</label>

                                <input class='form-control' type='file' id="assignment_images" 
                                    multiple name='assignment_images[]'>

                            </div>

                            <div class='form-group mb-2'>
                                <label for="description" class='mb-2'>Instructions <span style="font-size: 12px">(Optional)</span></label>

                                <textarea class="form-control summernote" type='text' 
                                    placeholder='Optional' id="description" name='description'></textarea>
                            </div>
                                 
                            <div class='form-group mb-2'>
                                <label for="max_score" class='mb-2'>* Max Score</label>

                                <input maxlength="3" value="100" required class='form-control' type='text' 
                                    placeholder='Max is 100 points' id="max_score" name='max_score'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="due_date" class='mb-2'>* Set Due Date</label>
                                <input required class='form-control' type="datetime-local"
                                    id="due_date" name="due_date">

                            </div>


                            <div class='form-group mb-2'>

                                <label>* Allow Late Submission</label><br>
                                <input type="radio" id="late_submission_yes" required name="allow_late_submission" value="yes">
                                    
                                <label for="late_submission_yes">Yes</label> &nbsp;
                                <input type="radio" checked id="late_submission_no" required name="allow_late_submission" value="no">
                                    
                                <label for="late_submission_no">No</label><br>
                            </div>

                                

                            <div class='form-group mb-2'>
                                <label for="max_attempt" class='mb-2'>* Submission Count</label>
                                <input value="1" required class='form-control' type="text"
                                    id="max_attempt" name="max_attempt">
                            </div>

                            <div class="action">
                                <button type="submit" class="clean large" name='add_assignment_topic_<?php echo $subject_period_code_topic_id; ?>'>
                                    Save Section
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