<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');

    if(isset($_GET['id'])){

        $teacher_announcement_id = $_GET['id'];

        $teacherAnnouncement = new Announcement($con, $teacher_announcement_id);

        $title = $teacherAnnouncement->GetTitle();
        $content = $teacherAnnouncement->GetContent();
        $users_id = $teacherAnnouncement->GetUserId();
        $subject_code = $teacherAnnouncement->GetSubjectCode();
        $creation = $teacherAnnouncement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));




        // $markAsSViewed = $teacherAnnouncement->StudentAnnouncementAsViewed($teacher_announcement_id, $studentLoggedInId);

        $back_url = "index.php?c=$subject_code";
        // index.php?c_id=1253&c=STEM11-A-STEM101
?>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3><?php  echo $title;?></h3>
                            <small><?php echo $creation; ?></small>

                            <?php if($users_id != NULL):?>
                                <small><?php 
                                    $user = new User($con, $users_id);
                                    
                                    $adminName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                                    echo "By: $adminName (Administrator)";
                                ?></small>
                            <?php endif;?>
                        </div>
                        <div class="action">
                            <button class="btn btn-sm btn-info">
                                <a href="announcement_views.php?id=<?php echo $teacher_announcement_id ?>">
                                    <i class="fas fa-eye" style="color: white"></i>
                                </a>
                            </button>
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