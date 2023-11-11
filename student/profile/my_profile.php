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


    if(isset($_GET['id'])) {
        $student_id = $_GET['id'];

        // Fetch the student data using the ID
        $student = new Student($con, $student_id);

        if($student) {
            $profilePic = $student->GetStudentProfile();
            $address = $student->GetStudentAddress();
            $contact_number = $student->GetContactNumber();
        } else {
            echo "Student not found.";
        }
    }

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
?>

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
                    "second",
                    "second",
                    "second"
                );
            ?>

            <main>
                <div class="floating profile">
                    <div class="img-container">
                        <?php if ($profilePic): ?>
                            <img src="<?= "../../" . $profilePic; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="../../assets/images/users/Blank.png" alt="Profile Picture">
                        <?php endif; ?>
                    </div>
                    <div class="user-description">
                        <h4><?php echo $studentLoggedInObj->GetLastName(); ?>, <?php echo $studentLoggedInObj->GetFirstName(); ?></h4>
                        <small>Daehan College Of Business & Technology</small>
                    </div>
                    <div class="action">
                        <button 
                            class="information"
                            onclick="window.location.href='edit_profile.php?id=<?= $studentLoggedInObj->GetStudentId(); ?>'"
                        >
                            Edit
                        </button>
                    </div>
                </div>

                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Info</h3>
                        </div>
                    </header>
                    <main>
                        <ul>
                            <?php
                                if($studentLoggedInObj->CheckIfTertiary() == 0) {
                                    echo "<li>Academic level: <span>Senior High</span></li>";
                                } else {
                                    echo "<li>Academic level: <span>Tertiary</span></li>";
                                }
                            ?>
                            <li>Section: <span></span></li>
                            <li>Program: <span></span></li>
                            <li>Year level: <span><?= $studentLoggedInObj->GetStudentCourseLevel(); ?></span></li>
                            <li>Student ID: <span><?= $studentLoggedInObj->GetStudentId(); ?></span></li>
                            <li>Address: <span><?= $studentLoggedInObj->GetStudentAddress(); ?></span></li>
                        </ul>
                    </main>
                    <header>
                        <div class="title">
                            <h3>Contact</h3>
                        </div>
                    </header>
                    <main>
                        <ul>
                            <li>Email: <a href="#"><?= $studentLoggedInObj->GetEmail(); ?></a></li>
                            <li>Contact No: <span><?= $studentLoggedInObj->GetContactNumber(); ?></span></li>
                        </ul>
                    </main>
                </div>
            </main>
        </div>
    </body>
</html>