<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Notification.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['announcement_id'])
        && isset($_POST['teacher_id'])
    ) {

        $announcement_id = $_POST['announcement_id'];
        $teacher_id = $_POST['teacher_id'];
       
       

        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];


        $removedNotification = $notification->RemoveGivenAnnouncement(
            $announcement_id,
            $current_school_year_id);

        $query = $con->prepare("DELETE FROM announcement
            WHERE announcement_id = :announcement_id
            AND teacher_id=:teacher_id
        ");
            
        $query->bindValue(":announcement_id", $announcement_id);
        $query->bindValue(":teacher_id", $teacher_id);

        if ($query->execute()) {
            echo "success_delete";
            return;
        }

    }

?>
