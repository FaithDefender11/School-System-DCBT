<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');

    if(isset($_GET['id'])){

        $subject_period_code_topic_id = $_GET['id'];

        $school_year = new SchoolYear($con);

        $subjectCodeHandout = new SubjectCodeHandout($con);

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate(
            $con);


        $subjectCodeAssignment = new SubjectCodeAssignment($con);

        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();
        $topic_name = $subjectPeriodCodeTopic->GetTopic();


        // $subjectPeriodCodeTopicTemplateId = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);
        $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

        $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList($subjectPeriodCodeTopicTemplateId);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        // $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

        // $subject_period_code_topic_template_id = $_GET['id'];
        // $subject_period_code_topic_id = $_GET['ct_id'];

        $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['add_handout_topic_'. $subject_period_code_topic_id])
            && isset($_POST['handout_name'])
            && isset($_FILES['assignment_image'])
            ){

                $handout_name = $_POST['handout_name'];
                $image = $_FILES['assignment_image'] ?? NULL;
                $imagePath = NULL;

                if (!is_dir('../../assets')) {
                    mkdir('../../assets');
                }
                if (!is_dir('../../assets/images')) {
                    mkdir('../../assets/images');
                }
                if (!is_dir('../../assets/images/handout')) {
                    mkdir('../../assets/images/handout');
                }

                if($topic_assigned_teacher_id !== $teacher_id){
                    Alert::error("You`re not teacher of this Subject Code.",
                        "");
                    exit();
                }

                $subject_code_assignment_id = 0;

                if ($image && $image['tmp_name']) {

                    $uploadDirectory = '../../assets/images/handout/';

                    $originalFilename = $image['name'];

                    $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
                    $targetPath = $uploadDirectory . $uniqueFilename;

                    move_uploaded_file($image['tmp_name'], $targetPath);

                    $imagePath = $targetPath;

                    // Remove Directory Path in the Database.
                    $imagePath = str_replace('../../', '', $imagePath);

                }
                

                $handoutCreate = $subjectCodeHandout->AddHandout(
                    $subject_period_code_topic_id,
                    $handout_name,
                    $imagePath);
                    
                if($handoutCreate){

                    Alert::success("Handout created successfully", $back_url);
                    exit();
                }
                




                // if ($assignment_image && $assignment_image['tmp_name']) {

                //     $uploadDirectory = '../../assets/images/assignments_images/';
                //     $originalFilename = $image['name'];

                //     $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                //     $targetPath = $uploadDirectory . $uniqueFilename;

                //     move_uploaded_file($image['tmp_name'], $targetPath);

                //     $imagePath = $targetPath;

                //     // Remove Directory Path in the Database.
                //     $imagePath = str_replace('../../', '', $imagePath);
                // }
        }
?>


            <?php 
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "first",
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
                            <h3>Add Handout to: <?php echo $subjectPeriodCodeTopic->GetTopic(); ?></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="handout_name">* Handout Name</label>
                                    <div>
                                        <input required class='form-control' type='text' 
                                            placeholder='Add Handout' id="handout_name" name='handout_name'>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                   <label for="assignment_image">* File</label>
                                    <div>
                                        <input class='form-control' type='file' id="assignment_image" 
                                            name='assignment_image'>
                                    </div> 
                                </span>
                            </div>
                            <div class="action">
                                <button 
                                    type="submit" 
                                    class="clean large"
                                    name="add_handout_topic_<?php echo $subject_period_code_topic_id; ?>"
                                >
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
        // $(document).ready(function () {
        //     $('.summernote').summernote({
        //         height:250
        //     });
        // });
    </script>
    </body>
</html>