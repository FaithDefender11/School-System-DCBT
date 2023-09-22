<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandoutTemplate.php');

    
    if(
        isset($_GET['id'])
        && isset($_GET['ht_id'])
        ){

        $subject_program_id = $_GET['id'];
        $subject_code_handout_template_id = $_GET['ht_id'];

        $subjectPeriodCodeTopicTemplate = new SubjectCodeHandoutTemplate(
            $con, $subject_code_handout_template_id);

        $subject_period_code_topic_template_id = $subjectPeriodCodeTopicTemplate->GetSubject_period_code_topic_template_id();
        $handout_name = $subjectPeriodCodeTopicTemplate->GetHandoutName();
        $handout_file = $subjectPeriodCodeTopicTemplate->GetFile();
 
        $back_url = "template_topic_handout_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
            $con, $subject_period_code_topic_template_id);

        $topic_name = $subjectPeriodCodeTopicTemplate->GetTopic();  
        
        $subjectCodeHandoutTemplate = new SubjectCodeHandoutTemplate($con);



        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['edit_handout_topic_'. $subject_code_handout_template_id])
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
                

                $db_image = $handout_file;
    
                $imagePath = NULL;


                if ($image && $image['tmp_name']) {

                    $uploadDirectory = '../../assets/images/handout/';
                    $originalFilename = $image['name'];

                    $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;

                    $targetPath = $uploadDirectory . $uniqueFilename;

                    if ($db_image !== NULL) { // Changed $photo to $db_image
                        $db_handout_file = "../../" . $db_image; // Changed $photo to $db_image

                        if (file_exists($db_handout_file)) {
                            unlink($db_handout_file);
                        }
                        // Upload the new file
                        move_uploaded_file($image['tmp_name'], $targetPath);
                        // $imagePath = $targetPath;
                        $imagePath = str_replace('../../', '', $targetPath);
                    } 
                }else {
                    $imagePath = $db_image; // Changed $photo to $db_image
                }

                $handoutEdit = $subjectCodeHandoutTemplate->UpdateHandout(
                    $subject_code_handout_template_id,
                    $handout_name,
                    $imagePath
                );

                if ($handoutEdit) {
                    Alert::success("Handout edited successfully", $back_url);
                    exit();
                }

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
                            <h4 class="text-center text-muted">Creating template handout for: <?php echo $topic_name; ?></h4>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="handout_name" class='mb-2'>* Handout Name</label>

                                    <input value="<?php echo $handout_name; ?>" required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>* File</label>

                                    <input value="<?php echo $handout_file ?>" class='form-control' type='file' id="assignment_image" 
                                        name='assignment_image'>

                                    <p>
                                        <?php 

                                            $original_file_name = "";
                                            // $uploadFile = $photo['image'];

                                            $extension = pathinfo($handout_file, PATHINFO_EXTENSION);
                                            
                                            // $parts = explode('_', $handout_file);

                                            // $original_file_name = end($parts);

                                            // $parts = explode('_', $handout_file);

                                            // $original_file_name = end($parts);

                                            $pos = strpos($handout_file, "img_");


                                            // Check if "img_" was found
                                            if ($pos !== false) {
                                                
                                                $original_file_name = substr($handout_file, $pos + strlen("img_"));
                                            }

                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
                                                    <a title="View File" href='<?php echo "../../".  $handout_file ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }
                                        ?>
                                    </p>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_handout_topic_<?php echo $subject_code_handout_template_id; ?>'>Save Section</button>
                                </div>


                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
?>

