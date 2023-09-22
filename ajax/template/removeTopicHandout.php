<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeHandoutTemplate.php");
    
    if (
        isset($_POST['subject_period_code_topic_template_id'])
        && isset($_POST['subject_code_handout_template_id'])
    ) {

        $subject_period_code_topic_template_id = $_POST['subject_period_code_topic_template_id'];
        $subject_code_handout_template_id = $_POST['subject_code_handout_template_id'];
       
        // echo $subject_code_handout_template_id; 
        // echo "<br>";
        // // echo $subject_code_handout_template_id; 
        
        // return;

        $subjectCodeHandoutTemplate = new SubjectCodeHandoutTemplate($con, $subject_code_handout_template_id);

        $selected_uploaded_photo = $subjectCodeHandoutTemplate->GetFile();

        $hasRemoved = false;

        if($selected_uploaded_photo !== NULL){

            // echo $selected_uploaded_photo;
            $db_selected_uploaded_photo = "../../" . $selected_uploaded_photo;
          
            if (file_exists($db_selected_uploaded_photo)) {
                unlink($db_selected_uploaded_photo);
                // $hasRemoved = true;
            }
        }
        
        $hasRemoved = true;
        if($hasRemoved == true){

            $successRemove = $subjectCodeHandoutTemplate->RemovingHandoutTemplateFile(
                $subject_period_code_topic_template_id,
                $subject_code_handout_template_id);

            if ($successRemove == true) {
                echo "success_delete";
            }

        }
        
    }
   
?>