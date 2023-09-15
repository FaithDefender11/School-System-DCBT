<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    
    if (isset($_POST['subject_code_assignment_template_id'])
    ) {

        $subject_code_assignment_template_id = $_POST['subject_code_assignment_template_id'];
       
        # Remove All Files associate with subject_code_assignment_template_id

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);



        $allAssignmentTemplateListFiles = $subjectCodeAssignmentTemplate->GetAssignmentTemplateListFiles(
            $subject_code_assignment_template_id);

        $hasRemoved = false;
        $hasFinishedRemoval = false;

        # List all allAssignmentTemplateListFiles
        # Check uploaded photo is in the files, if so, unlink
        # Remove all data associate with subject_code_assignment_template_list_id
        # (either with unlink success or unlink fail)
        if(count($allAssignmentTemplateListFiles) > 0){

            foreach ($allAssignmentTemplateListFiles as $key => $value) {

                $subject_code_assignment_template_list_id = $value['subject_code_assignment_template_list_id'];

                $selected_uploaded_photo = $subjectCodeAssignmentTemplate->GetSingleTemplateUploadAssignmentFile(
                    $subject_code_assignment_template_list_id, $subject_code_assignment_template_id
                );

                if($selected_uploaded_photo !== NULL){

                    // echo $selected_uploaded_photo;
                    $db_selected_uploaded_photo = "../../" . $selected_uploaded_photo;
                
                    if (file_exists($db_selected_uploaded_photo)) {
                        unlink($db_selected_uploaded_photo);
                        $hasRemoved = true;

                    //    if($hasRemoved == true){
                    //         $successRemove = $subjectCodeAssignmentTemplate->RemovingAssignmentTemplateFiles(
                    //             $subject_code_assignment_template_list_id,
                    //              $subject_code_assignment_template_id);
                            
                    //         // $hasFinishedRemoval = $successRemove;
                            
                    //         if($successRemove){
                    //             $hasFinishedRemoval = true;
                    //         }
                            
                    //     }
                    }
                }

                $successRemove = $subjectCodeAssignmentTemplate->RemovingAssignmentTemplateFiles(
                    $subject_code_assignment_template_list_id,
                        $subject_code_assignment_template_id);
                
                // $hasFinishedRemoval = $successRemove;
                
                if($successRemove){
                    $hasFinishedRemoval = true;
                }
                    
            }


        }
        

        // if($hasFinishedRemoval){
        //     echo "success_delete";
        // }else{
        //     echo "wrong";
        // }

        $query = $con->prepare("DELETE FROM subject_code_assignment_template
            WHERE subject_code_assignment_template_id = :subject_code_assignment_template_id
        ");
    
        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo "success_delete";
        }
       
    }
   
?>