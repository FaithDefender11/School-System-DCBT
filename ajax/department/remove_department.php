<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['department_id']) &&
        isset($_POST['department_status'])) {

        $department_id = $_POST['department_id'];
        $status = $_POST['department_status'];

        echo $status;
        return;
       
        $query = $con->prepare("UPDATE department 
            SET status=:status
            WHERE department_id = :department_id");

        $query->bindValue(":status", $status);
        $query->bindValue(":department_id", $department_id);

        if($query->execute()){
            echo "success_delete";
        }


    }
    else{
        echo "Something went wrong on the department_id";
    }
?>