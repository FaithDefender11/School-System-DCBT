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

            <?php
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "second",
                    "second"
                );
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
                            <h3>Add announcement on: <?php echo $subject_code;?></h3>
                        </div>
                    </header>
                    <main>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="title">Title</label>
                                    <div>
                                        <input type="text" name="title" id="title" class="form-control" required>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="content">Content</label>
                                    <div>
                                        <textarea name="content" id="content" class="form-control"></textarea>
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button type="submit" class="btn btn-success" name="add_announcement_code_<?php echo $subject_code; ?>">Add announcement</button>
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