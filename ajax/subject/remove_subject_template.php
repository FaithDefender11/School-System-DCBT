<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['subject_template_id'])) {

        $subject_template_id = $_POST['subject_template_id'];
       
        // echo $subject_template_id;
        $query = $con->prepare("DELETE FROM subject_template 
            WHERE subject_template_id = :subject_template_id");

        $query->bindParam(":subject_template_id", $subject_template_id);

        if($query->execute()){
            echo "success_delete";
        }

    }
    else{
        echo "Something went wrong on the subject_template_id";
    }
?>