<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');



    if(isset($_GET['id'])){

        $announcement_id = $_GET['id'];


        if(
            isset($_GET['n_id'])
            && isset($_GET['notification'])
            && $_GET['notification'] == "true")
            {

            $notification_id = $_GET['n_id'];
            $notification = new Notification($con);

            $markAsNotified = $notification->StudentNotificationMarkAsViewed($notification_id, $studentLoggedInId);
            // echo "marked";
            
        }

        $announcement = new Announcement($con, $announcement_id);

        $title = $announcement->GetTitle();
        $content = $announcement->GetContent();
        $subject_code = $announcement->GetSubjectCode();
        $creation = $announcement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));

        # Only in announcement not notification viewed indication
        $markAsSViewed = $announcement->StudentAnnouncementAsViewed($announcement_id, $studentLoggedInId);

        $back_url = "index.php?c=$subject_code";
        ?>

        <div class="content">
            <nav>
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <div class="row col-md-10">
                <div class="offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-center"><?php  echo $title;?></h4>
                            <span><?php echo $creation; ?></span>
                        </div>
                        <div class="card-body">
                            <p>
                                <?php  echo $content;?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
                
        </div>
          
        <?php
    }

?>