<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Student.php');
?>
<?php
    $section = new Section($con, null);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    $student_id = $_SESSION['studentLoggedInId'];

    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        $school_year_id);

    $studentSubject = new StudentSubject($con);
    $announcement = new Announcement($con);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($student_id, $school_year_id, $enrollment_id);
        // public function GetAllAnnouncement($school_year_id) {

    $announcementList = $announcement->GetAllTeacherAnnouncement(
        $school_year_id);

    // print_r($allEnrolledSubjectCode);

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
    // $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

    $subjectTopicAssignmentsArray = [];
    $subjectCodeAssignmentsArray = [];


    // print_r($getEnrolledSubjects);
    // echo "<br>";

    $subjectCodeAssignment = new SubjectCodeAssignment($con);


    $submissionCodeAssignmentArr = [];

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($studentLoggedInId, $school_year_id, $enrollment_id);

    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_codeGet = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_codeGet);
    }

    # List of all Enrolled Subject subject_period_code_topic_id(s)
    $getEnrolledSubjects = $subjectPeriodCodeTopic->GetAllSubjectTopicEnrolledBased(
        $school_year_id, $student_id, $enrollment_id
    );


    $submissionCodeAssignmentArr = [];

    foreach ($getEnrolledSubjects as $key => $subject_period_code_topic_id) {
        
        # All assignments based on enrolled subjects (Not Due assignment)
        $assignmentList =  $subjectCodeAssignment->
            GetAllAssignmentNotDueTopicBased($subject_period_code_topic_id);
        
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topicSubjectCode =  $subjectPeriodCodeTopic->GetSubjectCode();

        # All submitted assignment
        $mySubmissionWithinSemester = $subjectCodeAssignment->GetAllStudentAssignmentsSubmission(
            $studentLoggedInId, $school_year_id, $topicSubjectCode);

        foreach ($mySubmissionWithinSemester as $key => $submission) {
            # code...
            $submission_subject_code_assignment_id = $submission['subject_code_assignment_id'];

            if (!in_array($submission_subject_code_assignment_id,
                    $submissionCodeAssignmentArr)) {
                        
                array_push($submissionCodeAssignmentArr,
                    $submission_subject_code_assignment_id);
            }
        }
        
        if (!empty($assignmentList)) {

            foreach ($assignmentList as $key => $value) {

                // if($value['subject_code_assignment_id'] !==)
                $subjectCodeAssignment_id = $value['subject_code_assignment_id'];

                if (!in_array($subjectCodeAssignment_id, $submissionCodeAssignmentArr)) {
                    
                    // # Get all NOT DUE Subject Code Assignment Given by your Teacher
                    //  # Based on your Enrolled Subject Code

                    // Only push if it's not in $submissionCodeAssignmentArr
                    array_push($subjectCodeAssignmentsArray, 
                        $subjectCodeAssignment_id);

                }
            }
        }
        
    }

    $ungradedSubmissionArr = [];

    $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

    if ($_SERVER['SERVER_NAME'] === 'localhost') {

        $base_url = 'http://localhost/school-system-dcbt/student/';
    } else {

        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
    }

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {

        $new_url = str_replace("/student/", "", $base_url);
        $logout_url = "$new_url/lms_logout.php";
    }

    // var_dump($logout_url);
?>
            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
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
                </header>
            </div>
            <div class="tabs">
                <button 
                    class="tab" 
                    onclick="window.location.href='student_dashboard.php'"
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
                                <h3>To-do <em>(3)</em></h3>
                            </div>
                        </header>
                        <ul>
                            <?php if(count($subjectCodeAssignmentsArray) > 0):?>
                                <?php
                                    $assignmentCounts = [];

                                    foreach ($subjectCodeAssignmentsArray as $key => $subjectCodeAssignmentIds) {
                                        $subjectCodeAssignmentExec = new SubjectCodeAssignment($con, $subjectCodeAssignmentIds);
                                        $assignment_name = $subjectCodeAssignmentExec->GetAssignmentName();
                                        $assignment_topic_id = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
    
                                        $subjectPeriodCodeTopicId = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
                                        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subjectPeriodCodeTopicId);
                                        
                                        $rawCode = $subjectPeriodCodeTopic->GetProgramCode();
                                        $subject_program_id = $subjectPeriodCodeTopic->GetSubjectProgramId();
                                        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
    
                                        $subject_program = new SubjectProgram($con, $subject_program_id);
                                        $subjectTitle = $subject_program->GetTitle();
    
                                        if (!isset($assignmentCounts[$subjectTitle])) {
                                            $assignmentCounts[$subjectTitle] = [
                                                'count' => 1,
                                                'topic_subject_code' => $topic_subject_code,
                                            ];
                                        } else {
                                            $assignmentCounts[$subjectTitle]['count']++;
                                        }
                                    }

                                    foreach ($assignmentCounts as $assignmentTitle => $data) {
                                        $count = $data['count'];
                                        $topic_subject_code = $data['topic_subject_code'];
                                     
                                        echo "
                                            <li>
                                                <a href='assignment_due.php?c=$topic_subject_code'>$assignmentTitle <span>($count)</span></a>
                                            </li>
                                        ";
                                    }
                                ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3>Announcements <em>(0)</em></h3>
                            </div>
                        </header>
                        <ul>
                            <li></li>
                        </ul>
                    </div>
                </div>

                <?php if(count($allEnrolledSubjectCode) > 0): ?>
                    <?php
                        foreach ($allEnrolledSubjectCode as $key => $row_inner) {

                            $subject_title = $row_inner['subject_title'];
                            $student_subject_id = $row_inner['student_subject_id'];
    
                            $schedule = new Schedule($con);
    
                            $student_subject_code = $row_inner['student_subject_code'];
                            $sp_subjectCode = $row_inner['sp_subjectCode'];
                            $subject_schedule_id = $row_inner['subject_schedule_id'];
    
                            $subject_schedule_course_id = $row_inner['subject_schedule_course_id'];
                            $subject_subject_program_id = $row_inner['subject_subject_program_id'];
    
                            $subject_type = $row_inner['subject_type'];
                            $unit = $row_inner['unit'];
                            $program_section = $row_inner['program_section'];
                            $remarks = $row_inner['remarks'];
                            $ss_retake = $row_inner['ss_retake'];
                            $ss_overlap = $row_inner['ss_overlap'];
    
                            $schedule_time = $row_inner['schedule_time'] != "" ? $row_inner['schedule_time'] : "-";
                            
                            $student_subject_code = $row_inner['student_subject_code'];
    
                            $teacher_firstname = $row_inner['firstname'];
                            $teacher_lastname = $row_inner['lastname'];
    
                            // $instructor_name = "-";
                            $instructor_name = "TBA";
    
                            if($teacher_firstname != null){
                                $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                            }
    
                            $section_code = $section->CreateSectionSubjectCode($program_section, $sp_subjectCode);
    
                            // $section_code = trim(strtolower($section_code));
    
                            // $courses_url = "../courses/index.php?c=$section_code";
                            $courses_url = "../courses/subject_module.php?id=$student_subject_id";
                    ?>

                    <div class="floating noOutline">
                        <a href="<?php echo $course_url; ?>">
                            <header>
                                <div class="title">
                                    <h3><?= $subject_title; ?> <em>SY2324-1T</em></h3>
                                    <small><?= $instructor_name; ?></small>
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
                                    data-toogle="tooltip"
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
                <?php endif; ?>
            </main>
        </div>
    </body>
</html>