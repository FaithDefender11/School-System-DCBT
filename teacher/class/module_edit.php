<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');

    ?>
        <head>
            <!-- <script src=
                "https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js">
            </script> -->
        
            <!-- Include Moment.js CDN -->
            <script type="text/javascript" src=
                "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js">
            </script>
        
            <!-- Include Bootstrap DateTimePicker CDN -->
            <link
                href=
                "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
                rel="stylesheet">
        
            <script src=
                "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
                </script>
                
        </head>
    <?php

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

        // $back_url = "";

        $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

        if ($_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['edit_handout_' . $subject_code_handout_id])
            && isset($_POST['handout_name'])
            && isset($_FILES['assignment_image'])
            ) {

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
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
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
                } else {
                    $imagePath = $db_image; // Changed $photo to $db_image
                }
            }

            $handoutEdit = $subjectCodeHandout->UpdateHandout(
                    $subject_code_handout_id,
                    $handout_name,
                    $imagePath
                );

                if ($handoutEdit) {
                    Alert::success("Handout edited successfully", "");
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
                            <h4 class='text-center mb-3'><?php echo ucwords($topic_name); ?> <span class="text-muted text-right" style="font-size: 17px;"><?php echo $handout_template_id === NULL ? "Non-template" : "Template" ?></span> </h4>
                        </div> 

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">

                                 <div class='form-group mb-2'>
                                    <label for="handout_name" class='mb-2'>* Handout Name</label>

                                    <input value="<?php echo $handout_name ?>" required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>File</label>
                                    
                                    <?php if ($handout_template_id == NULL): ?>
                                        <input value="<?php echo $handout_file; ?>" id="assignment_image" class='form-control' type='file' placeholder='' name='assignment_image'>

                                    <?php endif; ?>
                                    <p>
                                        <?php 

                                            // $uploadFile = $photo['image'];

                                            $extension = pathinfo($handout_file, PATHINFO_EXTENSION);
                                            
                                            $parts = explode('_', $handout_file);

                                            $original_file_name = end($parts);
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
                                    <button type='submit' class='btn btn-success' name='edit_handout_<?php echo $subject_code_handout_id; ?>'>Save Changes</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>
        <?php
    }
?>