<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');

    if(
        isset($_GET['c'])
        && isset($_GET['c_id'])
    ){

        $subject_code = $_GET['c'];
        $course_id = $_GET['c_id'];

        $subjectPeriodCodeTopic = "";

        // index.php?c_id=HUMSS11-A-UCSP&c=1279
        $back_url = "index.php?c_id=$course_id&c=$subject_code";

        $announcement = new Announcement($con);
        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['add_announcement_code_' . $subject_code])
            && isset($_POST['title'])
            && isset($_POST['content'])
            ){

            // echo "qwe";

            $title = trim(ucfirst($_POST['title']));

            $content = trim(ucfirst($_POST['content']));


            $wasAdded = $announcement->InsertAnnouncement($teacherLoggedInId,
                $current_school_year_id,
                $subject_code, $title, $content);

            if($wasAdded){
                
                $announcement_id = $con->lastInsertId();

                # Add notification
                $wasNotifInserted = $notification->InsertNotificationForTeacherAnnouncement(
                    $current_school_year_id,
                    $subject_code, $announcement_id);
                
                Alert::successAutoRedirect("Successfully added announcement", $back_url);
                exit();
            }

        }
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
                            <h3>Add announcement on: <?php echo $subject_code; ?></h3>
                        </div>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class='form-group mb-2'>
                                <label for="title" class='mb-2'>* Subject</label>

                                <input required class='form-control' type='text' 
                                    placeholder='Add Assignment' id="title" name='title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="content" class='mb-2'>* Content</label>
        
                                <textarea class="form-control" name="content" id="content"></textarea>
                            </div>

                            <div class="action">
                                <button type="submit" class="clean large" name='add_announcement_code_<?php echo $subject_code; ?>'>Add announcement</button>
                            </div>
                        </form>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>