<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['announcement_id'])
        && isset($_POST['users_id'])) {

        $announcement_id = $_POST['announcement_id'];
        $users_id = $_POST['users_id'];
       
        $query = $con->prepare("DELETE FROM announcement 

            WHERE users_id = :users_id
            AND announcement_id = :announcement_id
            
        ");
        $query->bindValue(":users_id", $users_id);
        $query->bindValue(":announcement_id", $announcement_id);

        if($query->execute()){
            echo "success_delete";
            return;
        }


    }
?>