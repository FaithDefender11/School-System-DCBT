<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['subject_program_id'])) {

        $subject_program_id = $_POST['subject_program_id'];
       
        $query = $con->prepare("DELETE FROM subject_program 
            WHERE subject_program_id = :subject_program_id");
        $query->bindValue(":subject_program_id", $subject_program_id);

        if($query->execute()){
            echo "success_delete";
        }


    }
    else{
        echo "Something went wrong on the subject_program_id";
    }
?>