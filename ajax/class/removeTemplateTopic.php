<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    
                            
    if (isset($_POST['subject_period_code_topic_template_id'])
        && isset($_POST['school_year_id'])) {

        $subject_period_code_topic_template_id = $_POST['subject_period_code_topic_template_id'];
        $school_year_id = $_POST['school_year_id'];

        $query = $con->prepare("DELETE FROM subject_period_code_topic_template 
            WHERE subject_period_code_topic_template_id = :subject_period_code_topic_template_id
            -- AND school_year_id = :school_year_id
            ");

        $query->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        // $query->bindValue(":school_year_id", $school_year_id);
        $query->execute();

        if($query->rowCount() > 0){

            echo "success_delete";
            return;

        }



    }
   
?>