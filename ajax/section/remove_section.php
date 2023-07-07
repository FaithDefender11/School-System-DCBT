<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['course_id'])) {

        $course_id = $_POST['course_id'];
       
        $query = $con->prepare("DELETE FROM course 
            WHERE course_id = :course_id");
            
        $query->bindParam(":course_id", $course_id);

        if($query->execute()){
            echo "success_delete";
        }
    }
    else{
        echo "Something went wrong on the course_id";
    }
?>