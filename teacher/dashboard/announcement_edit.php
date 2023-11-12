<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');

    if(
        // isset($_GET['c'])
        isset($_GET['id'])
    ){

        // $subject_code = $_GET['c'];

        $announcement_id = $_GET['id'];

        $subjectPeriodCodeTopic = "";

        // index.php?c_id=HUMSS11-A-UCSP&c=1279
        // $back_url = "index.php?c_id=$course_id&c=$subject_code";

        $announcement = new Announcement($con, $announcement_id);

        $title = $announcement->GetTitle();
        $content = $announcement->GetContent();
        $subject_code = $announcement->GetSubjectCode();
        $school_year_id = $announcement->GetSchoolYearId();

        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        $subjectCodeAssignment = new SubjectCodeAssignment($con);

        $teachingSubjectCode = $subjectCodeAssignment
            ->GetTeacherTeachingSubjects($teacherLoggedInId,
                $school_year_id);

        
        $back_url = "announcement_index.php?sy_id=$school_year_id";
 
        if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['edit_announcement_' . $announcement_id])
            && isset($_POST['title'])
            && isset($_POST['content'])
            && isset($_POST['selectedSubjects'])
            
            ){

            // echo "qwe";

            $title = trim(ucfirst($_POST['title']));

            $content = trim(ucfirst($_POST['content']));

        

            $chosen_subject_code = $_POST['selectedSubjects'];

            // var_dump($chosen_subject_code);
            // return;

            $isDone = false;

            $wasEdited = $announcement->UpdateAnnouncement(
                $announcement_id,
                $school_year_id,
                $teacherLoggedInId,

                $chosen_subject_code,
                $title,
                $content);

            if($wasEdited){
                
                // $announcement_id = $con->lastInsertId();

                # Edit notification
                $notificationUpdate = $notification->UpdateNotificationForTeacherAnnouncement(
                    $school_year_id,
                    $subject_code,
                    $chosen_subject_code,
                    $announcement_id
                );

                    
                if($notificationUpdate){

                    $isDone = true;
                }
            }


            if($isDone){
                Alert::successAutoRedirect("Announcement has been modified.", $back_url);
                exit();
            }
                

        }
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
                        <h3>Edit announcement: <span class="text-primary"><?= $title;?></span> from <span class="text-info"><?= $subject_code;?></span></h3>
                    </header>
                    <main>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <span>
                                    <label for="title"  class='mb-2'>* Subject</label>
                                    <div>
                                        <input value="<?= $title;?>" required class='form-control' type='text' 
                                            placeholder='Add Assignment' id="title" name='title'>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="content" class='mb-2'>* Content</label>
                                    <div>
                                        <textarea class="form-control" name="content" id="content"><?= $content;?></textarea>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                
                                <div class="form-group mb-2">
                                    <label for="title" class='mb-2'>* Teaching Subjects</label>
                                    <?php
                                        echo "<br>";
                                        echo "
                                            <span>All Subjects: </span> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp; 
                                        ";
                                        echo "<br>";

                                        foreach ($teachingSubjectCode as $row) {

                                            $section = new Section($con, $row['course_id']);

                                            $sectionName = $section->GetSectionName();

                                            $subjectProgram = new SubjectProgram($con, $row['subject_program_id']);
                                            
                                            $programCode = $subjectProgram->GetSubjectProgramRawCode();

                                            $sectionSubjectCode = $section->CreateSectionSubjectCode($sectionName, $programCode);

                                            $checked = $sectionSubjectCode == $subject_code ? "checked" : "";
                                        
                                            // echo '<input '.$checked.' type="checkbox" name="selectedSubjects[]" value="' . $sectionSubjectCode . '"> ' . $sectionSubjectCode . '<br>';
                                        
                                            echo '<input type="checkbox" class="subject-checkbox" name="selectedSubjects" value="' . $sectionSubjectCode . '" ' . $checked . '> ' . $sectionSubjectCode . '<br>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="action">
                                <button 
                                type="submit" 
                                class="clean large"
                                name='edit_announcement_<?php echo $announcement_id; ?>'
                                >
                                Add announcement
                            </button>
                        </form>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    <script>

        const checkboxes = document.querySelectorAll('.subject-checkbox');

        checkboxes.forEach(checkbox => {

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    // Uncheck all other checkboxes
                    checkboxes.forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });

        });

    </script>
    </body>
</html>