<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentRequirement.php");
    

    # Good Moral
    if (isset($_POST['student_requirement_list_id'])
        && isset($_POST['student_requirement_id']))
        // 
        {
        
        $student_requirement_list_id = $_POST['student_requirement_list_id'];
        $student_requirement_id = $_POST['student_requirement_id'];


        $requirement = new StudentRequirement($con, $student_requirement_id);

        $file = $requirement->GetStudentRequirementListFile(
            $student_requirement_list_id);

        if($file !== NULL){

            // echo $selected_uploaded_photo;
            // return;

            $file = "../../" . $file;

            if (file_exists($file)) {
                if(unlink($file)){
                    // echo $file;
                    // $hasRemoved = true;

                    $wasRemoveSuccess = $requirement->RemoveSelectedRequirement(
                        $student_requirement_list_id, $student_requirement_id);
                    
                    if($wasRemoveSuccess){
                        echo "success_delete";
                        return;
                    }
                }

                echo "deletion_error";
                return;

            }
        }
    }
?>