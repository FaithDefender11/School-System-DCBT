<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['school_year_term'])) {

        $school_year_term = $_POST['school_year_term'];
       
        $query = $con->prepare("DELETE FROM course 
            WHERE school_year_term = :school_year_term
            AND is_remove = 1
            ");
            
        $query->bindParam(":school_year_term", $school_year_term);

        if($query->execute()){
            echo "success_delete";
        }
    }
    else{
        echo "Something went wrong on the course_id";
    }
?>