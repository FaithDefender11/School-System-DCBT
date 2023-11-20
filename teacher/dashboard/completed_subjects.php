<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Student.php');

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();


    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];

    $subjectCodeAssignment = new SubjectCodeAssignment($con);
    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
    $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);



    $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects(
        $teacherLoggedInId,
        $current_school_year_id);

    $teachingSubjects = [];


    foreach ($teachingSubjectCode as $key => $value) {

        $teachingCode = $value['subject_code'];
        array_push($teachingSubjects, $teachingCode);
    }


    $teachingSubjectCodeAnnouncement = $subjectCodeAssignment->GetTeacherTeachingSubjectsWithAnnouncement(
        $teacherLoggedInId,
        $current_school_year_id);

    $teachingSubjectCodeAnnouncementCount = count($teachingSubjectCodeAnnouncement);


    $topicCodeCount = [];

    # Getall Previous handled subjectes.
    $teachingPreviousSubjectCode = $subjectCodeAssignment->GetTeacherTeachingPreviousSubjects(
        $teacherLoggedInId,
        $current_school_year_id);

        // var_dump($teachingPreviousSubjectCode);

    $enrollment = new Enrollment($con);

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

            <div class="content-header">
                <header>
                    <div class="title">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="action">
                        <div class="dropdown">
                            <div class="icon">
                                <i class="bi bi-three-dots-vertical"></i>
                            </div>
                            <div class="dropdown-menu">
                                <a 
                                    href="announcement_index.php?sy_id=<?= $current_school_year_id;?>"
                                    class="dropdown-item" style="color: inherit">
                                    <i class="bi bi-megaphone-fill"></i>
                                    Announcement
                                </a>
                            </div>
                        </div>
                    </div>
                </header>
            </div>

            <div class="tabs">
                <button
                    class="tab"
                    style="background-color: var(--theme); color: white"
                    onclick="window.location.href='index.php'">
                    Current subject teaching
                </button>
                <button
                    class="tab"
                    onclick="window.location.href='completed_subjects.php'">
                    Previous subject teaching
                </button>
            </div>

            <main>
                <?php if(count($teachingPreviousSubjectCode) > 0): ?>
                    <?php
                        foreach ($teachingPreviousSubjectCode as $key => $row) {

                            $school_year_id = $row['school_year_id'];
    
                            $subject_code = $row['subject_code'];
                            $subject_title = $row['subject_title'];
                            $course_id = $row['course_id'];
                            $program_section = $row['program_section'];
    
                            // $class_url = "../class/index.php?c_id=$course_id&c=$subject_code&sy_id=$school_year_id";
                            $class_url = "../class/index.php?c=$subject_code&sy_id=$school_year_id";
    
                            $sy = new SchoolYear($con, $school_year_id);
    
                            $term = $sy->GetTerm();
                            $period = $sy->GetPeriod();
    
                            $fomatTerm = $enrollment->changeYearFormat($term);
                            $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");
    
                            ?>
    
                                <div style="width: 100%" class="floating noOutline">
                                    
                                    <a href="<?php echo $class_url; ?>">
                                        <header>
                                            <div class="title">
                                                <h3><?= $subject_title; ?> <em >  <?= "SY$fomatTerm-$period_short";?></em></h3>
                                                <small><?= "$subject_code" ?></small>
                                            </div>
                                        </header>
                                    </a>
    
                                    <main>
    
                                        <div class="progress" style="height: 20px">
                                            <div class="progress-bar" style="width: 25%">25%</div>
                                        </div>
    
                                        <div class="action">
                                            <button
                                            class="task"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="No Assignments Due"
                                            >
                                            <i class="bi bi-file-earmark">0</i>
                                            </button>
                                        </div>
                                    </main>
                                </div>
                            <?php
                        }
                    ?>
                <?php else: ?>
                    <h4 class="text-center">No history of teaching subjects.</h4>
                <?php endif; ?>
            </main>
        </div>
        <script src="../../assets/js/dropdownMenu.js"></script>
    </body>
</html>