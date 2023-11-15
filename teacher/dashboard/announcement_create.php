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

    echo Helper::RemoveSidebar();



    if(
        // isset($_GET['c'])
        isset($_GET['sy_id'])
    ){

        // $subject_code = $_GET['c'];

        $school_year_id = $_GET['sy_id'];

        $subjectPeriodCodeTopic = "";

        // index.php?c_id=HUMSS11-A-UCSP&c=1279
        // $back_url = "index.php?c_id=$course_id&c=$subject_code";

        $announcement = new Announcement($con);
        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        $back_url = "announcement_index.php?sy_id=$school_year_id";

        $subjectCodeAssignment = new SubjectCodeAssignment($con);

        $teachingSubjectCode = $subjectCodeAssignment
            ->GetTeacherTeachingSubjects($teacherLoggedInId,
                $school_year_id);

        // $teachingSubjects = [];


        // foreach ($teachingSubjectCode as $key => $value) {

        //     $teachingCode = $value['subject_code'];
        //     array_push($teachingSubjects, $teachingCode);
        // }

        // var_dump($teachingSubjectCode);

        if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['add_announcement_' . $school_year_id])
            && isset($_POST['title'])
            && isset($_POST['content'])
            ){

            // echo "qwe";

            $title = trim(ucfirst($_POST['title']));

            $content = trim(ucfirst($_POST['content']));

            $isDone = false;
            if (isset($_POST['selectedSubjects']) && is_array($_POST['selectedSubjects'])) {

                $sectionSubjectCode = "";
                // Loop through the selected teacher IDs
                foreach ($_POST['selectedSubjects'] as $selectedSubjects) {

                    $sectionSubjectCode = $selectedSubjects;
                    // $sectionSubjectCode = implode('', $_POST['selectedSubjects']);
                   
                    // echo "Added: $sectionSubjectCode";
                    // echo "<br>";
                    
                    $wasAdded = $announcement->InsertAnnouncement($teacherLoggedInId,
                        $current_school_year_id,
                        $sectionSubjectCode, $title, $content);

                    if($wasAdded){
                        
                        $announcement_id = $con->lastInsertId();

                        # Add notification
                        $wasNotifInserted = $notification->InsertNotificationForTeacherAnnouncement(
                            $current_school_year_id,
                            $sectionSubjectCode, $announcement_id);
                            
                        if($wasNotifInserted){

                            $isDone = true;
                        }
                    }

                }

                if($isDone){
                    Alert::successAutoRedirect("Announcement has been added.", $back_url);
                    exit();
                }
                
            }

            // $wasAdded = $announcement->InsertAnnouncement($teacherLoggedInId,
            //     $current_school_year_id,
            //     $subject_code, $title, $content);

            // if($wasAdded){
                
            //     $announcement_id = $con->lastInsertId();

            //     # Add notification
            //     $wasNotifInserted = $notification->InsertNotificationForTeacherAnnouncement(
            //         $current_school_year_id,
            //         $subject_code, $announcement_id);
                
            //     Alert::successAutoRedirect("Successfully added announcement", $back_url);
            //     exit();
            // }



        }

        ?>

        <div class='content'>

            <nav style="min-width: 100%; margin-bottom: 7px;
                display: flex;flex-direction: row;">
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>

            <div class='col-md-10 offset-md-1'>
                <div class='card'>

                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Add announcement</h4>
                    </div>

                    <div class="card-body">
                        <form method='POST' enctype="multipart/form-data">

                            <div class='form-group mb-2'>
                                <label for="title"  class='mb-2'>* Subject</label>

                                <input required class='form-control' type='text' 
                                    placeholder='Add Assignment' id="title" name='title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="content" class='mb-2'>* Content</label>
        
                                <textarea class="form-control" name="content" id="content"></textarea>
                            </div>

                            <div class='form-group mb-2'>
                                <label for="title" class='mb-2'>* Teaching Subjects</label>
                                <?php

                                    echo "<br>";
                                    echo "
                                        <input type='checkbox' id='select-all' class='select-all-checkbox'>
                                        <span>All Subjects: </span> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp; 
                                    ";
                                    echo "<br>";

                                    foreach ($teachingSubjectCode as $row) {

                                        $section = new Section($con, $row['course_id']);

                                        $sectionName = $section->GetSectionName();

                                        $subjectProgram = new SubjectProgram($con, $row['subject_program_id']);
                                        
                                        $programCode = $subjectProgram->GetSubjectProgramRawCode();
                                        $subject_title = $subjectProgram->GetTitle();

                                        $sectionSubjectCode = $section->CreateSectionSubjectCode($sectionName, $programCode);
                                    
                                        echo '<input type="checkbox" name="selectedSubjects[]" value="' . $sectionSubjectCode . '"> '.$subject_title. ' ('. $sectionSubjectCode .')<br>';
                                    
                                    }

                                

                                ?>
                            </div>
                        
                            
                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='add_announcement_<?php echo $school_year_id; ?>'>Add announcement</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

        <?php
    }

?>

<script>

    // JavaScript to handle "Select All" checkbox
    document.querySelector('.select-all-checkbox').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="selectedSubjects[]"]');
        for (const checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>
