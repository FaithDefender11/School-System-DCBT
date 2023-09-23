<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentRequirement.php");
    

    # Good Moral
    if (isset($_POST['student_requirement_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type'])
        && $_POST['type'] === "Good moral"
        
        ){

        
        $student_id = intval($_POST['student_id']);
        $student_requirement_id = intval($_POST['student_requirement_id']);
        $type = intval($_POST['type']);

        
        $requirement = new StudentRequirement($con, $student_requirement_id);

        $updateSuccess = $requirement->GoodMoralToggle($student_id,
            $student_requirement_id);

        if($updateSuccess === true){
            echo "success";
        }else{
            echo "nothing";
        }
    }

    # Form 137
    if (isset($_POST['student_requirement_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type'])
        && $_POST['type'] === "Form 137"
        
        ){

        
        $student_id = intval($_POST['student_id']);
        $student_requirement_id = intval($_POST['student_requirement_id']);
        $type = intval($_POST['type']);

        
        $requirement = new StudentRequirement($con, $student_requirement_id);

        $updateSuccess = $requirement->Form137Toggle($student_id,
            $student_requirement_id);

        if($updateSuccess === true){
            echo "success";
        }else{
            echo "nothing";
        }
    }

    # PSA
    if (isset($_POST['student_requirement_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type'])
        && $_POST['type'] === "Psa"
        
        ){

        
        $student_id = intval($_POST['student_id']);
        $student_requirement_id = intval($_POST['student_requirement_id']);
        $type = intval($_POST['type']);

        
        $requirement = new StudentRequirement($con, $student_requirement_id);

        $updateSuccess = $requirement->PSAToggle($student_id,
            $student_requirement_id);

        if($updateSuccess === true){
            echo "success";
        }else{
            echo "nothing";
        }
    }

?>