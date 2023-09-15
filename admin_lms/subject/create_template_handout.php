<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandoutTemplate.php');

    
    if(
        isset($_GET['id'])
        && isset($_GET['ct_id'])
        ){

        $subject_program_id = $_GET['id'];
        $subject_period_code_topic_template_id = $_GET['ct_id'];
 
        $back_url = "template_topic_handout_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
            $con, $subject_period_code_topic_template_id);

        $topic_name = $subjectPeriodCodeTopicTemplate->GetTopic();  
        
        $subjectCodeHandoutTemplate = new SubjectCodeHandoutTemplate($con);



        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['add_handout_topic_'. $subject_period_code_topic_template_id])
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
 

                if ($image && $image['tmp_name']) {

                    $uploadDirectory = '../../assets/images/handout/';

                    $originalFilename = $image['name'];

                    $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                    $targetPath = $uploadDirectory . $uniqueFilename;

                    move_uploaded_file($image['tmp_name'], $targetPath);

                    $imagePath = $targetPath;

                    // Remove Directory Path in the Database.
                    $imagePath = str_replace('../../', '', $imagePath);

                }

                $handoutCreate = $subjectCodeHandoutTemplate->AddHandout(
                    $subject_period_code_topic_template_id,
                    $handout_name,
                    $imagePath);
                    
                if($handoutCreate){

                    Alert::success("Handout created successfully", $back_url);
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

                                    <input required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>* File</label>

                                    <input class='form-control' type='file' id="assignment_image" 
                                        name='assignment_image'>

                                </div>
                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='add_handout_topic_<?php echo $subject_period_code_topic_template_id; ?>'>Save Section</button>
                                </div>


                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
?>

