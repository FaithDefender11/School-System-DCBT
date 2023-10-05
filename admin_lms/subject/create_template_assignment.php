<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');

    
    if(
        isset($_GET['id'])
        && isset($_GET['ct_id'])
        ){

        $subject_program_id = $_GET['id'];
        $subject_period_code_topic_template_id = $_GET['ct_id'];

        // $back_url = "code_topics.php?id=$subject_program_id";
        // $back_url = "template_topic_assignment_list.php?ct_id=$subject_period_code_topic_template_id";

        $back_url = "template_topic_assignment_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
            $con, $subject_period_code_topic_template_id);

        $topic_name = $subjectPeriodCodeTopicTemplate->GetTopic();   


        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['create_assignment_template_' . $subject_period_code_topic_template_id])
            && isset($_POST['assignment_name'])
            && isset($_POST['description'])
            // && isset($_POST['max_attempt']) 
            && isset($_POST['max_score']) 
            && isset($_POST['type']) 
            
            ){

                $assignment_name = $_POST['assignment_name'];
                $description = $_POST['description'];
                // $max_attempt = $_POST['max_attempt'];
                $max_score = $_POST['max_score'];
                $type = $_POST['type'];

                $assignment_images = $_FILES['assignment_images'] ?? NULL;
                $image_upload = NULL;


                $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate(
                    $con);

                // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
                    
                if (!is_dir('../../assets')) {
                    mkdir('../../assets');
                }
                if (!is_dir('../../assets/images')) {
                    mkdir('../../assets/images');
                }
                if (!is_dir('../../assets/images/assignments_images')) {
                    mkdir('../../assets/images/assignments_images');
                }
                
                $subject_code_assignment_template_id = 0;

                $successCreate = $subjectCodeAssignmentTemplate->AddAssignmentTemplate(
                    $subject_period_code_topic_template_id,
                    $assignment_name, $description,
                    $max_score, $type);

                if($successCreate){
                    $subject_code_assignment_template_id = $con->lastInsertId();

                }

                if ($assignment_images !== NULL
                    && empty($assignment_images['name'][0] == false)
                    && $subject_code_assignment_template_id !== 0
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

                            $fileUpload = $subjectCodeAssignmentTemplate->UploadAssignmentTemplateFiles(
                                $subject_code_assignment_template_id, $imagePath
                            );

                            // Process $imagePath as needed (e.g., store in a database).
                        } else {
                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }
                    }
                } 

                if($successCreate){

                    Alert::success("Success: ", $back_url);
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
                            <h4 class="text-center text-muted">Creating template assignment for: <?php echo $topic_name; ?></h4>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>

                                    <label for="type" class='mb-2'>Type *</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="" selected disabled>Choose Type</option>
                                        <option value="text">Text</option>
                                        <option value="upload">Upload</option>
                                    </select>

                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_name" class='mb-2'>Assignment Name *</label>
                                    <input required id="assignment_name" class='form-control' type='text' placeholder='' name='assignment_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="description" class='mb-2'>Description *</label>
                                    <input required id="description" class='form-control' type='text' placeholder='' name='description'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="max_score" class='mb-2'>Max Score *</label>
                                    <input maxlength="3" required id="max_score" class='form-control' type='text' placeholder='' name='max_score'>
                                </div>
                                

                                <!-- <div class='form-group mb-2'>
                                    <label for="max_attempt" class='mb-2'>Max Attempt *</label>
                                    <input required id="max_attempt" class='form-control' type='text' placeholder='' name='max_attempt'>
                                </div> -->

                                <div class='form-group mb-2'>
                                    <label for="assignment_images" class='mb-2'>File</label>

                                    <input class='form-control' type='file' id="assignment_images" 
                                        multiple name='assignment_images[]'>

                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='create_assignment_template_<?php echo $subject_period_code_topic_template_id; ?>'>Save Section</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
?>

