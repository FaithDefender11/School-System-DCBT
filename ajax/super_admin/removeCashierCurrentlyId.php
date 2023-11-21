<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['cashierId'])) {

        $cashierId = $_POST['cashierId'];

        $user = new User($con, $cashierId);
 

        echo "cashierId: $cashierId";
        return;
       
        $query = $con->prepare("UPDATE enrollment 

            SET currently_cashier_id = :set_currently_cashier_id

            WHERE currently_cashier_id = :currently_cashier_id");
        
        $query->bindValue(":set_currently_cashier_id", NULL);
        $query->bindValue(":currently_cashier_id", $registrarId);

        if($query->execute()){
            echo "success_update";
        }



    }
     
?>