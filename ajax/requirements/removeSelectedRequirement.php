<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentRequirement.php");
    

    # Good Moral
    if (isset($_POST['requirement_id'])
        && isset($_POST['requirement_name'])){

        
        $requirement_name = $_POST['requirement_name'];
        $requirement_id = intval($_POST['requirement_id']);

        $studentRequirement = new StudentRequirement($con);

        $wasSuccess = $studentRequirement->RemovedRequirementFile($requirement_id);
        if($wasSuccess){
            echo "success_delete";
            return;
        }
    }
?>