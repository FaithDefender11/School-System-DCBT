<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Announcement.php');

    if(isset($_GET['id'])){

        $announcement_id = $_GET['id'];

        if(isset($_GET['notification'])
            && $_GET['notification'] == "true")
            {

            // $notification_id = $_GET['a_id'];
            $teacherAnnouncement = new Announcement($con, $announcement_id);

            $markAsNotified = $teacherAnnouncement->TeacherNotificationMarkAsViewed(
                $announcement_id, $teacherLoggedInId);
                
            // echo "marked";
            
        }



        // echo $announcement_id;

        $teacherAnnouncement = new Announcement($con, $announcement_id);

        $title = $teacherAnnouncement->GetTitle();
        $content = $teacherAnnouncement->GetContent();
        $subject_code = $teacherAnnouncement->GetSubjectCode();
        $creation = $teacherAnnouncement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));


        $back_url = "../dashboard/index.php";

?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3><?php  echo $title;?></h3>
                            <small><?php echo $creation; ?></small>
                        </div>
                    </header>
                    <main>
                        <p><?php  echo $content;?></p>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>