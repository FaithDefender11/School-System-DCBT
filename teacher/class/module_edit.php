<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');

    if(isset($_GET['id'])
         
    ){

    $subject_code_handout_id = $_GET['id'];

    $subjectCodeHandout = new SubjectCodeHandout($con, $subject_code_handout_id);
    
    $subject_period_code_topic_id = $subjectCodeHandout->GetSubject_period_code_topic_id();
    $handout_file = $subjectCodeHandout->GetFile();
    $handout_name = $subjectCodeHandout->GetHandoutName();
    $handout_template_id = $subjectCodeHandout->GetSubjectCodeHandoutTemplateId();

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);



    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];

    $topic_name = $subjectPeriodCodeTopic->GetTopic();
    $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
    $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();


    // $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";
    
    $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
    $subjectPeriodCodeTopicTemplateId = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

    // echo $handout_file;

    $back_url = "section_topic.php?id=$subjectPeriodCodeTopicTemplateId&ct_id=$subject_period_code_topic_id";

    if ($_SERVER['REQUEST_METHOD'] === "POST" &&
        isset($_POST['edit_handout_' . $subject_code_handout_id])
        && isset($_POST['handout_name'])
        && isset($_FILES['assignment_image'])) {


        $handout_name = $_POST['handout_name'];
        $image = $_FILES['assignment_image'] ?? null;
        $db_image = $handout_file;

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

        if ($image && $image['tmp_name']) {

            $uploadDirectory = '../../assets/images/handout/';
            $originalFilename = $image['name'];
            $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
            $targetPath = $uploadDirectory . $uniqueFilename;

            if ($db_image !== NULL) { // Changed $photo to $db_image

                $db_user_photo = "../../" . $db_image; // Changed $photo to $db_image

                if (file_exists($db_user_photo)) {
                    unlink($db_user_photo);
                }

                // Upload the new file
                move_uploaded_file($image['tmp_name'], $targetPath);
                // $imagePath = $targetPath;
                $imagePath = str_replace('../../', '', $targetPath);
            }
        }else {
            $imagePath = $db_image; // Changed $photo to $db_image
        }

        $handoutEdit = $subjectCodeHandout->UpdateHandout(
                $subject_code_handout_id,
                $handout_name,
                $imagePath
            );

        if ($handoutEdit) {
            Alert::success("Handout edited successfully", $back_url);
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
                            <h3><?php echo ucwords($topic_name); ?> <em><?php echo $handout_template_id === NULL ? "Non-template" : "Template" ?></em></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="handout_name" class='mb-2'>* Handout Name</label>
                                    <div>
                                        <input value="<?php echo $handout_name ?>" required class='form-control' type='text' 
                                            placeholder='Add Handout' id="handout_name" name='handout_name'>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="assignment_image" class='mb-2'>File</label>
                                    <div>
                                        <?php if ($handout_template_id == NULL): ?>
                                            <input value="<?php echo $handout_file; ?>" id="assignment_image" class='form-control' type='file' placeholder='' name='assignment_image'>
                                        <?php endif; ?>
                                        <small>
                                            <?php
                                                $extension = pathinfo($handout_file, PATHINFO_EXTENSION);
                                             
                                                $pos = strpos($handout_file, "img_");
    
                                                $original_file_name = "";
    
                                                // Check if "img_" was found
                                                if ($pos !== false) {
                                                    $original_file_name = substr($handout_file, $pos + strlen("img_"));
                                                }
                                                
                                                if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>
                                                        <a title="View File" href='<?php echo "../../".  $handout_file ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                    <?php
                                                }
                                            ?>
                                        </small>
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button 
                                    type="submit" 
                                    class="clean large"
                                    name="edit_handout_<?php echo $subject_code_handout_id; ?>"
                                >
                                    Save Changes
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
    </body>
</html>