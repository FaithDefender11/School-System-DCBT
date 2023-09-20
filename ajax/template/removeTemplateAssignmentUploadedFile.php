<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    
    if (
        isset($_POST['subject_code_assignment_template_list_id'])
        && isset($_POST['subject_code_assignment_template_id'])
    ) {

        $subject_code_assignment_template_list_id = $_POST['subject_code_assignment_template_list_id'];
        $subject_code_assignment_template_id = $_POST['subject_code_assignment_template_id'];
       
        // echo $subject_code_assignment_template_list_id; 
        // echo "<br>";
        // echo $subject_code_assignment_template_id; 
        

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);

        $selected_uploaded_photo = $subjectCodeAssignmentTemplate->GetSingleTemplateUploadAssignmentFile(
            $subject_code_assignment_template_list_id, $subject_code_assignment_template_id
        );

        $hasRemoved = false;

        if($selected_uploaded_photo !== NULL){

            // echo $selected_uploaded_photo;
            $db_selected_uploaded_photo = "../../" . $selected_uploaded_photo;
          
            if (file_exists($db_selected_uploaded_photo)) {
                unlink($db_selected_uploaded_photo);
                $hasRemoved = true;
            }
        }

        // $hasRemoved = false;
        if($hasRemoved == true){

            $successRemove = $subjectCodeAssignmentTemplate->RemovingAssignmentTemplateFiles(
                $subject_code_assignment_template_list_id,
                $subject_code_assignment_template_id);

            if ($successRemove == true) {
                echo "success_delete";
            }

            // $query = $con->prepare("DELETE FROM subject_code_assignment_template_list
            // WHERE subject_code_assignment_template_list_id = :subject_code_assignment_template_list_id
            // AND subject_code_assignment_template_id = :subject_code_assignment_template_id
            // ");
            
            // $query->bindValue(":subject_code_assignment_template_list_id", $subject_code_assignment_template_list_id);
            // $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
            // $query->execute();

            // if ($query->rowCount() > 0) {
            //     echo "success_delete";
            // }
        }
        
    }
   
?>