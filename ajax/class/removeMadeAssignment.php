<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeHandout.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_POST['subject_code_assignment_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])) {
        
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];
        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];
        $ungive = "ungiven";

        $subjectCodeAssignment = new SubjectCodeAssignment($con);


        $allMadeAssignmentFiles = $subjectCodeAssignment->GetUploadAssignmentFiles(
            $subject_code_assignment_id);

        // print_r($allMadeAssignmentFiles);

        $hasRemoved = false;
        $hasFinishedRemoval = false;

        if(count($allMadeAssignmentFiles) > 0){

            foreach ($allMadeAssignmentFiles as $key => $value) {

                $subject_code_assignment_list_id = $value['subject_code_assignment_list_id'];

                $selected_uploaded_photo = $subjectCodeAssignment->GetSingleUploadAssignmentFile(
                    $subject_code_assignment_list_id, $subject_code_assignment_id);
                
                if($selected_uploaded_photo !== NULL){

                    // echo $selected_uploaded_photo;
                    $db_selected_uploaded_photo = "../../" . $selected_uploaded_photo;
                
                    // echo $db_selected_uploaded_photo . " ";

                    if (file_exists($db_selected_uploaded_photo)) {
                        unlink($db_selected_uploaded_photo);
                        $hasRemoved = true;
                    }
                    
                }

                $successRemove = $subjectCodeAssignment->RemovingAssignmentFiles(
                    $subject_code_assignment_id,
                    $subject_code_assignment_list_id
                );
                
                if($successRemove){
                    $hasFinishedRemoval = true;
                }
                
            }
        }
 
        $wasRemove = $subjectCodeAssignment->RemoveAssignment
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_assignment_id);
        
        if($wasRemove){

            echo "success";
            return;
        }

    }
?>