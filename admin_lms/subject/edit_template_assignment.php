<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');

    
    if(
        isset($_GET['id'])
        && isset($_GET['t_id'])
        ){

        $subject_program_id = $_GET['id'];
        $subject_code_assignment_template_id = $_GET['t_id'];


        // echo $subject_code_assignment_template_id;
        // $back_url = "code_topics.php?id=$subject_program_id";


        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate(
            $con, $subject_code_assignment_template_id);


        $subject_period_code_topic_template_id = $subjectCodeAssignmentTemplate->GetSubjectPeriodCodeTopicTemplate_id();
        $assignment_name = $subjectCodeAssignmentTemplate->GetAssignmentName();
        $description = $subjectCodeAssignmentTemplate->GetDescription();
        $max_Score = $subjectCodeAssignmentTemplate->GetMaxScore();
        $assignment_type = $subjectCodeAssignmentTemplate->GetType();
        
        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
            $con, $subject_period_code_topic_template_id);

        $topic_name = $subjectPeriodCodeTopicTemplate->GetTopic();   

        $back_url = "template_topic_assignment_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";

        $getAllTemplateUploadFiles = $subjectCodeAssignmentTemplate->GetTemplateUploadAssignmentFiles(
            $subject_code_assignment_template_id);


            // var_dump($getAllTemplateUploadFiles) ;

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['edit_assignment_template_' . $subject_code_assignment_template_id])
            && isset($_POST['assignment_name'])
            && isset($_POST['description'])
            && isset($_POST['max_score']) 
            && isset($_POST['type'])  ){

                $assignment_name = $_POST['assignment_name'];
                $description = $_POST['description'];
                $max_score = $_POST['max_score'];
                $type = $_POST['type'];

                // echo "Assignment Name: " . $assignment_name . "<br>";
                // echo "Description: " . $description . "<br>";
                // echo "Max Score: " . $max_score . "<br>";
                // echo "Type: " . $type . "<br>";
                // return;

                $assignment_images = $_FILES['assignment_images'] ?? NULL;
                $image_upload = NULL;

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
                
                // $subject_code_assignment_template_id = 0;

                $successCreate = $subjectCodeAssignmentTemplate->UpdateAssignmentTemplate(
                    $subject_code_assignment_template_id,
                    $assignment_name, $description,
                    $max_score, $type);

                // if($successCreate){
                //     $subject_code_assignment_template_id = $con->lastInsertId();

                // }

                $fileUploadSuccess = false;

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

                            if($fileUpload){
                                $fileUploadSuccess = true;
                            }
                        } else {
                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }
                    }
                } 
                
                if($successCreate || $fileUploadSuccess){

                    Alert::success("Success Update ", $back_url);
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
                            <h5 class="text-center text-muted">Editing template assignment <?php echo $assignment_name; ?></h5>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>

                                    <label for="type" class='mb-2'>Type *</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="text" <?php echo $assignment_type == "text" ? "selected" : "" ?>>Text</option>
                                        <option value="upload" <?php echo $assignment_type == "upload" ? "selected" : "" ?>>Upload</option>
                                    </select>

                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_name" class='mb-2'>Assignment Name *</label>
                                    <input value="<?php echo $assignment_name ?>" required id="assignment_name" class='form-control' type='text' placeholder='' name='assignment_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="description" class='mb-2'>Description *</label>
                                    <input value="<?php echo $description ?>" required id="description" class='form-control' type='text' placeholder='' name='description'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="max_score" class='mb-2'>Max Score *</label>
                                    <input value="<?php echo $max_Score ?>" required id="max_score" class='form-control' type='text' placeholder='' name='max_score'>
                                </div>


                                <div class='form-group mb-2'>
                                    <label for="assignment_images" class='mb-2'>File</label>

                                    <input class='form-control' type='file' id="assignment_images" 
                                    multiple name='assignment_images[]'>
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

                                                        <span onclick="uploadImageRemoval(<?php echo $photo['subject_code_assignment_template_list_id'] ?>, <?php echo $photo['subject_code_assignment_template_id'] ?>)" style="cursor: pointer;">
                                                            <i style="color: orange;" class="fas fa-times"></i>
                                                        </span>

                                                        <!-- <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <img style="margin-left:8px; width: 120px;" 
                                                                src='<?php echo "../../".$photo['image']; ?>' alt='Given Photo' class='preview-image'>
                                                        </a> -->

                                                        <a title="View File" href='<?php echo "../../".  $photo['image'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $original_file_name; ?>
                                                        </a>
                                                        <br>
                                                    <?php
                                                } else if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>
                                                        <span onclick="uploadImageRemoval(<?php echo $photo['subject_code_assignment_template_list_id'] ?>, <?php echo $photo['subject_code_assignment_template_id'] ?>)" style="cursor: pointer;">
                                                            <i style="color: orange;" class="fas fa-times"></i>
                                                        </span>
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


                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_assignment_template_<?php echo $subject_code_assignment_template_id; ?>'>Save Section</button>
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

    // $('#datetime').datetimepicker({
    //     format: 'hh:mm A'
    // });

    function uploadImageRemoval(subject_code_assignment_template_list_id,
        subject_code_assignment_template_id){

        var subject_code_assignment_template_list_id = parseInt(subject_code_assignment_template_list_id);
        var subject_code_assignment_template_id = parseInt(subject_code_assignment_template_id);

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
                        url: "../../ajax/template/removeTemplateAssignmentUploadedFile.php",
                        type: 'POST',
                        data: {
                            subject_code_assignment_template_list_id,
                            subject_code_assignment_template_id
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



