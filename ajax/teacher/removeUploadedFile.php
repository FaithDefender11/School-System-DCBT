<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    
    if (isset($_POST['subject_code_assignment_list_id'])
        && isset($_POST['subject_code_assignment_id'])) {

        $subject_code_assignment_list_id = $_POST['subject_code_assignment_list_id'];
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];

        $subjectCodeAssignment = new SubjectCodeAssignment($con,
            $subject_code_assignment_id);

        $selected_uploaded_photo = $subjectCodeAssignment->GetSingleUploadAssignmentFile(
            $subject_code_assignment_list_id, $subject_code_assignment_id
        );

        $hasRemoved = false;

        if($selected_uploaded_photo !== NULL){

            // echo $selected_uploaded_photo;
            $db_selected_uploaded_photo = "../../" . $selected_uploaded_photo;

            // echo "<br>";
            // var_dump($db_user_photo);

            if (file_exists($db_selected_uploaded_photo)) {
                unlink($db_selected_uploaded_photo);
                $hasRemoved = true;

            }
        }

        if($hasRemoved){

            $query = $con->prepare("DELETE FROM subject_code_assignment_list 
                WHERE subject_code_assignment_list_id = :subject_code_assignment_list_id");
            $query->bindValue(":subject_code_assignment_list_id", $subject_code_assignment_list_id);
            $query->execute();

            if($query->rowCount() > 0){

                echo "success_delete";
            }
        }

    }
        
?>