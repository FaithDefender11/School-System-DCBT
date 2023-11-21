<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['registrarId'])) {

        $registrarId = $_POST['registrarId'];

        $user = new User($con, $registrarId);
 

        // echo "registrarId: $registrarId";
       
        $query = $con->prepare("UPDATE enrollment 

            SET currently_registrar_id = :set_currently_registrar_id

            WHERE currently_registrar_id = :currently_registrar_id");
        
        $query->bindValue(":set_currently_registrar_id", NULL);
        $query->bindValue(":currently_registrar_id", $registrarId);

        if($query->execute()){
            echo "success_update";
        }



    }
    else{
        echo "Something went wrong on the department_id";
    }
?>