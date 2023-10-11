<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Pending.php");
    

    # Good Moral
    if (isset($_POST['accepted_term']) && isset($_POST['pending_enrollees_id'])){

        $accepted_term = $_POST['accepted_term'];
        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        if($accepted_term==="yes"){
            $pending = new Pending($con);

            $wasAccepted = $pending->TermAcceptance($pending_enrollees_id);

            if($wasAccepted){
                echo "success_accepted";
                return;
            }
            
        }
    }

?>