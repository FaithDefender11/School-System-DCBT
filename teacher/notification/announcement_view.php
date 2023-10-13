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

    // echo "Qwe";

    if(isset($_GET['id'])){

        $announcement_id = $_GET['id'];

        if(isset($_GET['notification'])
            && $_GET['notification'] == "true")
            {

            // $notification_id = $_GET['a_id'];
            $teacherAnnouncement = new Announcement($con, $announcement_id);

            $markAsNotified = $teacherAnnouncement->TeacherNotificationMarkAsViewed(
                $announcement_id, $teacherLoggedInId);
                
            echo "marked";
            
        }



        // echo $announcement_id;

        $teacherAnnouncement = new Announcement($con, $announcement_id);

        $title = $teacherAnnouncement->GetTitle();
        $content = $teacherAnnouncement->GetContent();
        $subject_code = $teacherAnnouncement->GetSubjectCode();
        $creation = $teacherAnnouncement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));


        $back_url = "";

        ?>

            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <div class="col-md-12">
                    <div class="offset-md-0">

                        <div style="max-width: 100%;" class="card">
                            <div class="card-header">

                                <!-- <button class="btn btn-sm btn-info">
                                    <a style="color: inherit;" href="announcement_views.php?id=<?php echo $announcement_id ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </button> -->

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