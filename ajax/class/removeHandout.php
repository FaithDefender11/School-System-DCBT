<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['subject_code_handout_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['teacher_id'])
    ) {

        $subject_code_handout_id = $_POST['subject_code_handout_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $teacher_id = $_POST['teacher_id'];
       
        $query = $con->prepare("DELETE FROM subject_code_handout
            WHERE subject_code_handout_id = :subject_code_handout_id
            
            -- AND subject_period_code_topic_id IN (
            --     SELECT subject_period_code_topic_id
            --     FROM subject_period_code_topic
            --     WHERE school_year_id = :school_year_id)
        ");
        
        $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
        // $query->bindValue(":school_year_id", $current_school_year_id);

        if ($query->execute()) {
            echo "success_delete";
        }


    }
    else{
        echo "Something went wrong on the department_id";
    }
?>