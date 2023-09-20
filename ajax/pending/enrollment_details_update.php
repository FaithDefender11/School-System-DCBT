<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['program_id'])
        && isset($_POST['pending_enrollees_id'])
        && isset($_POST['strand'])
        // && !isset($_POST['course_level'])

    ) {

        // echo "im not";
        $program_id = $_POST['program_id'];
        $pending_enrollees_id = $_POST['pending_enrollees_id'];
       
        $query = $con->prepare("UPDATE pending_enrollees
            SET program_id=:program_id
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":program_id", $program_id);
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            echo "success_update";
            return;
        }
    }


    if (isset($_POST['course_level'])
        && isset($_POST['pending_enrollees_id'])
        // && !isset($_POST['program_id'])
        && isset($_POST['level'])

    ) {

        $course_level = $_POST['course_level'];
        $pending_enrollees_id = $_POST['pending_enrollees_id'];
       
        $query = $con->prepare("UPDATE pending_enrollees
            SET course_level=:course_level
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":course_level", $course_level);
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            echo "success_update_level";
            return;
        }
    }


    if (isset($_POST['department_type'])
        && isset($_POST['pending_enrollees_id'])
        // && !isset($_POST['program_id'])
        && isset($_POST['department'])

    ) {

        $department_type = $_POST['department_type'];
        $pending_enrollees_id = $_POST['pending_enrollees_id'];

        # Remove previous course_level
       
        $query = $con->prepare("UPDATE pending_enrollees
            SET type=:type,
                course_level=:course_level,
                program_id=:program_id
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":type", $department_type);
        $query->bindValue(":course_level", NULL);
        $query->bindValue(":program_id", NULL);
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            echo "success_update";
            return;
        }
    }
   
   
?>