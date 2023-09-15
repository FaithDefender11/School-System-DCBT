<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['student_id'])
    ){

        $student_id = $_POST['student_id'];

        $student = new Student($con, $student_id);

        $wasSuccess = $student->UpdateStudentAsActive($student_id);

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }

?>