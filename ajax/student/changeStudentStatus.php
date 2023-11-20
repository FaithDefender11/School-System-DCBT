<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['student_id'])
        && isset($_POST['userStatus'])

    ){

        $student_id = $_POST['student_id'];
        $userStatus = $_POST['userStatus'];

        $statusToChange = NULL;
        $statusToChangeSearch = NULL;
        if($userStatus == "Active"){
            $statusToChange = 1;
            $statusToChangeSearch = "Active";

        }else{
            $statusToChange = 0;
            $statusToChangeSearch = "Inactive";

        }

        $student = new Student($con, $student_id);

        $wasSuccess = $student->UpdateStudentStatus($student_id, $statusToChange, $statusToChangeSearch);

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }

?>