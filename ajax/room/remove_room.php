<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['room_id'])) {

        $room_id = $_POST['room_id'];
       
        $query = $con->prepare("DELETE FROM room 
            WHERE room_id = :room_id");
        $query->bindValue(":room_id", $room_id);

        if($query->execute()){
            echo "success_delete";
        }


    }
    else{
        echo "Something went wrong on the department_id";
    }
?>