<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Notification.php");

    if (isset($_POST['announcement_id'])
        && isset($_POST['teacher_id'])
        && isset($_POST['sy_id'])
        ) {

        $notification = new Notification($con);
            


        $announcement_id = $_POST['announcement_id'];
        $teacher_id = $_POST['teacher_id'];
        $school_year_id = $_POST['sy_id'];

        $notification_id = $notification
            ->GetNotificationIdByAnnouncementId(
                $announcement_id, $school_year_id);

        // var_dump($notification_id);
        // return;

        // echo "Hey";

         
       
        $deleteAnnouncement = $con->prepare("DELETE FROM announcement 

            WHERE teacher_id = :teacher_id
            AND announcement_id = :announcement_id
            AND school_year_id = :school_year_id
            
        ");

        $deleteAnnouncement->bindValue(":teacher_id", $teacher_id);
        $deleteAnnouncement->bindValue(":announcement_id", $announcement_id);
        $deleteAnnouncement->bindValue(":school_year_id", $school_year_id);
        $deleteAnnouncement->execute();


        if($deleteAnnouncement->rowCount() > 0){

            # Get Notification ID



            # Remove notification.
            $deleteNotification = $con->prepare("DELETE FROM notification 

                WHERE announcement_id = :announcement_id
                AND school_year_id = :school_year_id
                AND sender_role = :sender_role
                
            ");

            $deleteNotification->bindValue(":announcement_id", $announcement_id);
            $deleteNotification->bindValue(":school_year_id", $school_year_id);
            $deleteNotification->bindValue(":sender_role", "teacher");
            $deleteNotification->execute();

            if($deleteNotification->rowCount() > 0){

                // $notification_id = $notification
                //     ->GetNotificationIdByAnnouncementId(
                //         $announcement_id, $school_year_id);

                if($notification_id != NULL){

                    # Remove users who clicked the notification.
                    $deleteNotificationUsers = $con->prepare("DELETE FROM notification_view 

                        WHERE notification_id = :notification_id
                        
                    ");

                    $deleteNotificationUsers->bindValue(":notification_id", $notification_id);
                    $deleteNotificationUsers->execute();

                    if($deleteNotificationUsers->rowCount() > 0){

                        
                    }
                }

                echo "success_delete";
                return;

            }

        }


    }
?>