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


        if(isset($_GET['n_id'])
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

            <nav>
                <a href="<?php echo $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Announcement</h3>
                        </div>
                    </header>
                    <main>
                        <table class="a">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $title; ?></td>
                                    <td><?php echo $creation; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo $content; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>