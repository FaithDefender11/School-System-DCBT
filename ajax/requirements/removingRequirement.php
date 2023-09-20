<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentRequirement.php");
    

    # Good Moral
    if (isset($_POST['student_requirement_id'])
        && isset($_POST['type']))
        // 
        {
        
        $student_id = intval($_POST['student_id']);
        $student_requirement_id = intval($_POST['student_requirement_id']);
        $type = $_POST['type'];

        $student_requirement_id = intval($_POST['student_requirement_id']);

        $requirement = new StudentRequirement($con, $student_requirement_id);

        if($type == "Good Moral"){
            // echo $type;
            $wasRemovalSuccess = $requirement->RemovingRequirements(
                $student_id, $student_requirement_id,
                "good_moral", "good_moral_valid");
            if($wasRemovalSuccess){
                echo "success";
            }
        }

        if($type == "PSA"){
            // echo $type;
            $wasRemovalSuccess = $requirement->RemovingRequirements(
                $student_id, $student_requirement_id,
                "psa", "psa_valid");
            if($wasRemovalSuccess){
                echo "success";
            }
        }

        if($type == "Form 137"){
            // echo $type;
            $wasRemovalSuccess = $requirement->RemovingRequirements(
                $student_id, $student_requirement_id,
                "form_137", "form_137_valid");
            if($wasRemovalSuccess){
                echo "success";
            }
        }


        // $updateSuccess = $requirement->GoodMoralToggle($student_id,
        //     $student_requirement_id);

        // if($updateSuccess === true){
        //     echo "success";
        // }else{
        //     echo "nothing";
        // }
       
    }
?>