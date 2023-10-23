<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['task_type_id'])) {

        $task_type_id = $_POST['task_type_id'];
       
        $query = $con->prepare("DELETE FROM task_type 
            WHERE task_type_id = :task_type_id");
            
        $query->bindValue(":task_type_id", $task_type_id);

        if($query->execute()){
            echo "success_delete";
            return;
        }


    }
    else{
    }
?>