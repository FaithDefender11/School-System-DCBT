<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['department_id'])) {

        $department_id = $_POST['department_id'];
       
        $query = $con->prepare("DELETE FROM department 
            WHERE department_id = :department_id");
        $query->bindValue(":department_id", $department_id);

        if($query->execute()){
            echo "success_delete";
        }


    }
    else{
        echo "Something went wrong on the department_id";
    }
?>