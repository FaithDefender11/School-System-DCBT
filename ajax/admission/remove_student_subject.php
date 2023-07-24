<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['student_subject_id'])) {

        $student_subject_id = $_POST['student_subject_id'];
       
        // echo $student_subject_id;
        
        $query = $con->prepare("DELETE FROM student_subject 
            WHERE student_subject_id = :student_subject_id");
        $query->bindValue(":student_subject_id", $student_subject_id);

        if($query->execute()){
            echo "success_delete";
        }

    }
?>