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
        $subject_code = $teacherAnnouncement->GetSubjectCode();
        $creation = $teacherAnnouncement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));


        // $markAsSViewed = $teacherAnnouncement->StudentAnnouncementAsViewed($teacher_announcement_id, $studentLoggedInId);

        $back_url = "index.php?c=$subject_code";
        // index.php?c_id=1253&c=STEM11-A-STEM101
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
                        <button class="btn btn-sm btn-info">
                            <a style="color: inherit;" href="announcement_views.php?id=<?php echo $teacher_announcement_id ?>">
                                <i class="fas fa-eye"></i>
                            </a>

                        </button>

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