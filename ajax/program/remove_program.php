<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['program_id'])) {

        $program_id = $_POST['program_id'];
       
        $query = $con->prepare("DELETE FROM program 
            WHERE program_id = :program_id");
        $query->bindValue(":program_id", $program_id);

        if($query->execute()){
            echo "success_delete";
        }
    }
    else{
        echo "Something went wrong on the program_id";
    }
?>