<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    
    echo Helper::RemoveSidebar();


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

        // subject_period_code_topic_template_id
        
        $back_url = "handout_index.php?id=$subjectPeriodCodeTopicTemplateId&sct_id=$subject_period_code_topic_id";

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
                            <h4 class='text-center text-muted mb-3'>Add Handout to: <?php echo $subjectPeriodCodeTopic->GetTopic(); ?></h4>
                        </div>

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="handout_name" class='mb-2'>* Handout Name</label>

                                    <input required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>* File</label>

                                    <input class='form-control' type='file' id="assignment_image" 
                                        name='assignment_image'>

                                </div>
                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='add_handout_topic_<?php echo $subject_period_code_topic_id; ?>'>Save Section</button>
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
    // $(document).ready(function () {
    //     $('.summernote').summernote({
    //         height:250
    //     });
    // });
</script>