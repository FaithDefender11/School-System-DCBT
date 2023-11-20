<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['user_id'])
        && isset($_POST['userStatus'])

    ){

        $user_id = $_POST['user_id'];
        $userStatus = $_POST['userStatus'];

        $statusToChange = NULL;
        if($userStatus == "Active"){
            $statusToChange = 1;

        }else{
            $statusToChange = 0;
        }

        $user = new User($con, $user_id);

        $wasSuccess = $user->UpdateUsersStatus($user_id, $statusToChange);

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }

?>