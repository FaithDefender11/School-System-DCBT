<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['user_id'])) {

        $user_id = $_POST['user_id'];

        $user = new User($con, $user_id);
 
        $photo = $user->GetPhoto();

        if($photo !== NULL){
            $db_user_photo = "../../" . $photo;

            // echo "<br>";
            // var_dump($db_user_photo);
            if (file_exists($db_user_photo)) {
                unlink($db_user_photo);
            }
        }
       
        $query = $con->prepare("DELETE FROM users 
            WHERE user_id = :user_id");
        $query->bindValue(":user_id", $user_id);

        if($query->execute()){

            echo "success_delete";
        }



    }
    else{
        echo "Something went wrong on the department_id";
    }
?>