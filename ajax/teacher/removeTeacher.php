<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Teacher.php");
    
    if (isset($_POST['teacher_id'])){

        $teacher_id = $_POST['teacher_id'];

        $teacher = new Teacher($con, $teacher_id);

        if(isset($_SESSION['role']) == "admin"){


            $wasSuccess = $teacher->TeacherSetAsInactive($teacher_id);

            if($wasSuccess){
                echo "success";
                return;
            }

        }
    }
?>