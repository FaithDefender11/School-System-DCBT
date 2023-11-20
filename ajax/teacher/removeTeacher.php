<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Teacher.php");
    
    if (
        isset($_POST['teacher_id'])
        && isset($_POST['type'])){

        $teacher_id = $_POST['teacher_id'];
        $type = trim($_POST['type']);

        $teacher = new Teacher($con, $teacher_id);

        if(isset($_SESSION['role']) == "admin"){

            // echo "type: $type";
            // return;

            if($type == "Active"){

                $wasSuccess = $teacher->TeacherSetAsInactive($teacher_id, "Active");

                if($wasSuccess){
                    echo "success";
                    return;
                }
            }
            if($type == "Inactive"){

                $wasSuccess = $teacher->TeacherSetAsInactive($teacher_id, "Inactive");
                
                if($wasSuccess){
                    echo "success";
                    return;
                }
            }

        }
    }
?>