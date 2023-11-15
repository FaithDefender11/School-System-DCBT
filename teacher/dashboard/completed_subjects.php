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

    // echo Helper::RemoveSidebar();
    
    ?>
        <head>
 
            <!--Link JavaScript-->
            <script src="../../assets/js/elms-sidebar.js" defer></script>
            <script src="../../assets/js/elms-dropdown.js" defer></script>
            <script src="../../assets/js/table-dropdown.js" defer></script>
            <!-- <script src="../../assets/js/calendar.js" defer></script> -->
            <!--Link styleshets-->
            <link rel="stylesheet" href="../../assets/css/fonts.css" />
            <link rel="stylesheet" href="../../assets/css/content.css" />
            <link rel="stylesheet" href="../../assets/css/buttons.css" />
            <link rel="stylesheet" href="../../assets/css/table.css" />
            <link rel="stylesheet" href="../../assets/css/calendar.css" />
            <!--Custom CSS-->
            <!-- <link
                rel="stylesheet"
                href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous"
            /> -->
            <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
            />
            <!--Link Fonts-->
            <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Lato"
            />
            <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Arimo"
            />

            <style>
            body {
                background-color: white;
                margin: 0;
            }
            </style>

        </head>
    <?php

 
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


 <div class="content">

    <?php 
        echo Helper::lmsTeacherNotificationHeader(
            $con, $teacherLoggedInId,
            $current_school_year_id,
            $teachingSubjects,
            "second",
            "second",
            "second");
        
    ?>

    <div class="content-header">

        <header>
            <div class="title">
                <h1>Dashboard</h1>
            </div>
            <div class="action">
                    <div class="dropdown">
                        <button class="icon">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
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
            Enrolled
        </button>

        <button
            class="tab"
                onclick="window.location.href='completed_subjects.php'">
                Completed
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
            <?php else:?>
                <div class="col-md-12">
                    <br>
                    <br>
                    <br>
                    <h2 class="text-center text-primary">No history of teaching subjects</h2>
                </div>
            <?php endif;?>

    </main>
</div>




