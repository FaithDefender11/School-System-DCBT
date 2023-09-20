<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['user_id'])) {

        $user_id = $_POST['user_id'];
       
        echo $user_id;


    }
 
?>