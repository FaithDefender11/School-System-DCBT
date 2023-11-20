<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Notification.php");
    require_once("../../includes/classes/Announcement.php");
    
    if (isset($_POST['announcement_id'])
        && isset($_POST['users_id'])) {


        $announcement_id = $_POST['announcement_id'];
        $users_id = $_POST['users_id'];
       
        $announcement = new Announcement($con, $announcement_id);

        $school_year_id = $announcement->GetSchoolYearId();


        $query = $con->prepare("DELETE FROM announcement 

            WHERE users_id = :users_id
            AND announcement_id = :announcement_id
        ");
        $query->bindValue(":users_id", $users_id);
        $query->bindValue(":announcement_id", $announcement_id);
        $query->execute();

        if($query->rowCount() > 0){

            # remove Notif.

            $notification = new Notification($con);

            $removeNotifcationFromAnnouncement = $notification->RemoveGivenAnnouncement(
                $announcement_id, $school_year_id);

            echo "success_delete";
            return;
        }


    }
?>