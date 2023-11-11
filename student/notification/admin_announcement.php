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


        $markAsSViewed = $announcement->StudentAnnouncementAsViewed($announcement_id, $studentLoggedInId);

        $back_url = "index.php";
?>

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
                    "second",
                    "second",
                    "second"
                );
            ?>

            <nav>
                <a href="<?php echo $back_url;?>">
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