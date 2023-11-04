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


    $enrollment = new Enrollment($con);

    // print_r($allTeachingTopicIds);

    // echo $current_school_year_id;

    $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects(
        $teacher_id,
        $current_school_year_id);

    $teachingTopicIdsArr = [];


    foreach ($teachingSubjectCode as $key => $value) {

        $teachingCode = $value['subject_code'];

        $allTeachingTopicIds = $subjectPeriodCodeTopic->GetAllsubjectPeriodCodeTopics(
            $teachingCode,
            $current_school_year_id);

        if(count($allTeachingTopicIds) > 0){
            
            foreach ($allTeachingTopicIds as $key => $topicIds) {
                array_push($teachingTopicIdsArr, $topicIds);

            }
        }
       
    }

  
    $subjectCodeAssignmentIdsArr = [];

    foreach ($teachingTopicIdsArr as $key => $topicIds) {
        # code...
        // $assignmentsBasedFromSubjectTopic = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);
        $assignmentsBasedFromSubjectTopicList = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);
        // $assignmentsBasedFromSubjectTopicList = $subjectCodeAssignment->GetAllAssignmentOnTopicBased($topicIds);

        if(count($assignmentsBasedFromSubjectTopicList) > 0){

            foreach ($assignmentsBasedFromSubjectTopicList as $key => $assignmentList) {
                
                $subject_code_assignment_ids = $assignmentList['subject_code_assignment_id'];
                // echo $topicIds;
                // echo "<br>";
                array_push($subjectCodeAssignmentIdsArr,
                    $subject_code_assignment_ids);

                // echo "hey";
                // echo "<br>";
            }
        }

        // $subject_code_assignment_ids = $assignmentsBasedFromSubjectTopicList['subject_code_assignment_id'];
        // var_dump($subject_code_assignment_ids);
    }

    // print_r($subjectCodeAssignmentIdsArr);
    // echo "<br>";


    $ungradedSubmissionArr = [];

    if(count($subjectCodeAssignmentIdsArr) > 0){

        foreach ($subjectCodeAssignmentIdsArr as $key => $codeAssignmentId) {
            
            // echo $codeAssignmentId;
            // echo "<br>";

            $submissionList = $subjectAssignmentSubmission->GetSubmittedUngradedSubmission($codeAssignmentId);

            foreach ($submissionList as $key => $submissions) {
                
                // $subject_assignment_submission_id = $submissions['subject_assignment_submission_id'];
                $subject_assignment_submission_id = $submissions;
                
                array_push($ungradedSubmissionArr,
                    $subject_assignment_submission_id);

            }
        }

    }


    $fomatTerm = $enrollment->changeYearFormat($current_school_year_term);
    $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");


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


    $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

    if ($_SERVER['SERVER_NAME'] === 'localhost') {

        $base_url = 'http://localhost/school-system-dcbt/teacher/';
    } else {

        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/teacher/';
    }

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {

        $new_url = str_replace("/teacher/", "", $base_url);
        $logout_url = "$new_url/lms_logout.php";
    }

    // var_dump($logout_url);
?>

            <?php
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "second",
                    "second",
                    $logout_url
                );
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
                                    class="dropdown-item"
                                >
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
                    onclick="window.location.href='index.php'"
                >
                    Enrolled
                </button>
                <button
                    class="tab"
                    style="background-color: var(--theme); color: white"
                    onclick="window.location.href='completed_subjects.php'"
                >
                    Completed
                </button>
            </div>

            <main>
                <div class="bars right">
                    <div class="floating">
                        <main>
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button id="prev-month">&lt;</button>
                                    <h2 id="current-month-year"></h2>
                                    <button id="next-month">&gt;</button>
                                </div>
                                <table class="calendar">
                                    <thead>
                                        <tr>
                                        <th>Sun</th>
                                        <th>Mon</th>
                                        <th>Tue</th>
                                        <th>Wed</th>
                                        <th>Thu</th>
                                        <th>Fri</th>
                                        <th>Sat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendar-body"></tbody>
                                </table>
                            </div>
                        </main>
                    </div>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <?php if(count($ungradedSubmissionArr) > 0): ?>
                                    <h3>To-do <em>(<?= count($ungradedSubmissionArr); ?>)</em></h3>
                                <?php else: ?>
                                    <h3>No To-do</h3>
                                <?php endif; ?>
                            </div>
                        </header>
                        <ul>
                            <?php
                                foreach ($ungradedSubmissionArr as $key => $submission_id) {

                                    $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $submission_id);
                                    
                                    $subjectCodeAssignmentId = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
                                
                                    $subjectCodeAssignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                    
                                    $assignment_title = $subjectCodeAssignment->GetAssignmentName();
                                    $topicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
                                    
                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $topicId);
                                    
                                    $topicName = $subjectPeriodCodeTopic->GetTopic();
                                    $getSubjectCode = $subjectPeriodCodeTopic->GetSubjectCode();


                                    $program_code = $subjectPeriodCodeTopic->GetProgramCode();
                                    $courseId = $subjectPeriodCodeTopic->GetCourseId();

                                    $subjectProgram = new SubjectProgram($con);
                                    $subjectTitle = $subjectProgram->GetSubjectProgramTitleByRawCode($program_code);

                                    if (!isset($topicCodeCount[$getSubjectCode])) {

                                        $topicCodeCount[$getSubjectCode] = [
                                            'count' => 1,
                                            'courseId' => $courseId,
                                            'teaching_code' => $getSubjectCode,
                                            'program_code' => $program_code,
                                            'subjectTitle' => $subjectTitle,
                                            
                                        ];
                                    } else {
                                        $topicCodeCount[$getSubjectCode]['count']++;
                                    }
                                }

                                if(count($topicCodeCount) > 0){


                                    foreach ($topicCodeCount as $getSubjectCode => $data) {
                                        $count = $data['count'];
                                        $teaching_code = $data['teaching_code'];
                                        $program_code = $data['program_code'];
                                        $courseId = $data['courseId'];
                                        $subjectTitle = $data['subjectTitle'];
                                    
                                        // $class_subject_url = "section_topic_grading.php?ct_id=$subject_period_code_topic_id";

                                        // $class_subject_url = "../class/index.php?c_id=$courseId&c=$teaching_code";

                                        $class_subject_url = "todos_tasks.php?c_id=$courseId&c=$teaching_code";
                                        
                                        //    <p style='margin:0'>
                                        //         <a style='color:inherit' href='$class_subject_url'
                                        //         class='m-0 text-right'>○ $subjectTitle ($count)</a>
                                        //     </p>
                                        echo "
                                            <li>
                                                <a href='$class_subject_url'>$subjectTitle <span>($count)</span></a>
                                            </li>
                                        ";
                                    }
                                }
                            ?>
                        </ul>
                    </div>

                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3>Announcements <em>(<?= $teachingSubjectCodeAnnouncementCount; ?>)</em></h3>
                            </div>
                            <div class="action">
                                <button 
                                    class="default"
                                    onclick="window.location.href = 'announcement_index.php?sy_id=<?= $current_school_year_id;?>'"
                                >
                                    View all
                                </button>
                            </div>
                        </header>
                    </div>
                </div>

                <?php if(count($teachingSubjectCode) > 0): ?>
                    <?php
                        foreach ($teachingSubjectCode as $key => $row) {

                            $subject_code = $row['subject_code'];
                            $school_year_id = $row['school_year_id'];
                            $subject_title = $row['subject_title'];
                            $course_id = $row['course_id'];
                            $program_section = $row['program_section'];
    
                            $class_url = "../class/index.php?c=$subject_code&sy_id=$school_year_id";
                            // $class_url = "../class/index.php?c_id=$course_id&c=$subject_code";
                    ?>

                    <div class="floating noOutline">
                        <a href="<?php echo $class_url; ?>">
                            <header>
                                <div class="title">
                                    <h3><?= $subject_title; ?> <em><?= "SY$fomatTerm-$period_short";?></em></h3>
                                    <small><?= "$subject_code" ?></small>
                                </div>
                            </header>
                        </a>
                        <main>
                            <div class="action">
                            <button
                                class="task"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="No Assignments to Check"
                            >
                                <i class="bi bi-file-earmark">0</i>
                            </button>
                            </div>
                        </main>
                    </div>
                    <?php
                        }
                    ?>
                <?php endif; ?>
            </main>
        </div>
        <script>
            var dropBtns = document.querySelectorAll(".icon");

            dropBtns.forEach(btn => {
                btn.addEventListener("click", (e) => {
                    const dropMenu = e.currentTarget.nextElementSibling;

                    if (dropMenu.classList.contains("show")) {
                        dropMenu.classList.toggle("show");
                    } else {
                        document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                        dropMenu.classList.add("show");
                    }
                });
            });
        </script>
    </body>
</html>