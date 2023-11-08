<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/User.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');

    echo Helper::RemoveSidebar();


    $student_id = $_SESSION['studentLoggedInId'];

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    $enrollment = new Enrollment($con);


    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        $school_year_id);

    // echo $enrollment_id;


    $studentSubject = new StudentSubject($con);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($student_id, $school_year_id, $enrollment_id);

    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_code = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_code);
    }

    // print_r($enrolledSubjectList);
    // echo "<br>";

    $notif = new Notification($con);

    // var_dump($studentLoggedInId);

    // $studentEnrolledSubjectAssignmentNotif = $notif->GetStudentAssignmentNotification(
    //     $enrolledSubjectList, $school_year_id);

    $studentEnrolledSubjectAssignmentNotif = $notif->GetStudentAssignmentNotificationv2(
        $enrolledSubjectList, $school_year_id);

    $gradedAssignments = $notif->GetStudentGradedAssignmentNotification(
        $enrolledSubjectList, $school_year_id, $studentLoggedInId);


    $studentsDueDateNotif = $notif->GetStudentDueDateNotifications(
        $enrolledSubjectList, $school_year_id, $studentLoggedInId);

    // var_dump($studentsDueDateNotif);
    // echo "<br>";
    
    // var_dump($studentEnrolledSubjectAssignmentNotif);

    $allAdminNotification = $notif->GetAdminAnnouncement($school_year_id);

    // print_r($allAdminNotification);

    // print_r($studentEnrolledSubjectAssignmentNotif);
    // echo "<br>";

    $mergedArray = array_merge($studentEnrolledSubjectAssignmentNotif,
        $allAdminNotification, $gradedAssignments, $studentsDueDateNotif);

    // function sortByDateCreation($a, $b) {
    //     return strtotime($a['date_creation']) - strtotime($b['date_creation']);
    // }

    // Sort the merged array by 'date_creation'
    // usort($mergedArray, 'sortByDateCreation');

    # Call the static function of Notification to sort the merge array by date_creation (DESC)
    // usort($mergedArray, ['Notification', 'SortByDateCreation']);

    usort($mergedArray, function($a, $b) {
        $dateA = strtotime($a['date_creation']);
        $dateB = strtotime($b['date_creation']);

        if ($dateA == $dateB) {
            return 0;
        }
        
        return ($dateA > $dateB) ? -1 : 1; // Change from 1 to -1 for descending order
    });


    $back_url = "../lms/student_dashboard.php";


    # NOTIFICATION FOR UPCOMING DEADLINE of ASSIGNMENT

    # 1. 2 Days before the deadline.
    # Once detected, this should triggered the NOTIF.

    # Get all assignments ( Due date > to now date ) that are not submitted.
    # Check all GIVEN assignments due date If due date is less than to 1 day.
    # If we got the assignments less than to 1 day. Create a notification table

    $subjectCodeAssignment = new SubjectCodeAssignment($con);

    // var_dump($enrolledSubjectList);

    $getAllIncomingDueAssignmentsIds = $subjectCodeAssignment->GetAllIncomingDueAssignmentsIds(
        $enrolledSubjectList, $school_year_id, $studentLoggedInId
    );

    // echo "<br>";
    // echo "getAllIncomingDueAssignmentsIds: ";
    // var_dump($getAllIncomingDueAssignmentsIds);
    // echo "<br>";
    // echo "<br>";


    # For Creation of Logic in notification table.

    # if notification for deadline together with 
    # ( enrolled subject code and subject_code_assignment id ) not exists
    # Create notification and notification_view for deadline category


    # if notification for deadline,( enrolled subject code and subject_code_assignment_id)already exists
    # ^ meaning, other students have generated a due date notification earlier than you ( They log in earlier than you )
    # just create only notification_view for deadline category.

    // $dueDateNotifPresentButStudentDoesnt = $notif->CheckStudentEnrolledCodeHasIncludedInDueDateNotification(
    //     $enrolledSubjectList, $school_year_id, $studentLoggedInId, $getAllIncomingDueAssignmentsIds
    // );

    $dueDateNotifPresentButStudentDoesnt = $notif->CheckStudentEnrolledCodeHasIncludedInDueDateNotificationv2(
        $enrolledSubjectList, $school_year_id, $studentLoggedInId, $getAllIncomingDueAssignmentsIds
    );


    
?>


<div class="content">

    <nav style="min-width: 100%; margin-bottom: 7px;
        display: flex;flex-direction: row;">
        <a href="<?php echo $back_url;?>">
            <i class="bi bi-arrow-return-left fa-1x"></i>
            <h3>Back</h3>
        </a>

    </nav>

    <main>
        <div class="floating" id="shs-sy">

            <header>
                <div class="title">
                    <h3>Notifications</h3>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="clean large success"> Mark all as read</button>
                    </a>
                </div>

            </header>
            
            <main>

                <table id="notification_table" class="a" style="margin: 0">

                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Type</th>
                            <th>Subject</th>
                            <th>Sent </th>
                            <th>Status</th>
                            <th>Read?</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        
                            foreach ($mergedArray as $key => $notification) {
                                
                                // $department_id = $row['department_id'];
                                
                                $notification_id = $notification['notification_id'];

                                $notif_exec = new Notification($con, $notification_id);

                                $sender_role = $notification['sender_role'];
                                $date_creation = $notification['date_creation'];
                                $date_creation = date("M d, Y h:i a", strtotime($date_creation));


                                $subject_code = $notification['subject_code'];


                                $subject_code_assignment_id = $notification['subject_code_assignment_id'];

                                $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

                                $subjectperiodcodetopicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
                                
                                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subjectperiodcodetopicId);

                                $subjectProgramId = $subjectPeriodCodeTopic->GetSubjectProgramId();

                                $subjectProgram = new SubjectProgram($con, $subjectProgramId);

                                $subject_title = $subjectProgram->GetTitle();

                                // $users_id = $notification['users_id'];

                                // var_dump($subject_code);

                                // echo "get_student_subject_id: $get_student_subject_id";
                                // echo "<br>";

                                $subject_code_assignment_id = $notification['subject_code_assignment_id'];
                                $announcement_id = $notification['announcement_id'];
                                $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];
                                
                                // var_dump($subject_assignment_submission_id);
                                // if($subject_assignment_submission_id != NULL) continue;

                                $sender_name = "";

                                $type = "";
                                $title = "";
                                $button_url = "";

                                $assignment_notification_url = "";


                                // var_dump($sender_role);
                                // echo "<br>";

                                if($sender_role === "admin" && 
                                    $announcement_id != NULL){

                                    $announcement = new Announcement($con, $announcement_id);
                                    $users_id = $announcement->GetUserId();

                                    $users = new User($con, $users_id);

                                    $sender_name = ucwords($users->getFirstName()) . " " . ucwords($users->getLastName());
                                    
                                    $type = "Announcement";

                                    $title = "Admin add announcement: ";

                                    $announcementTitle = $announcement->GetTitle();

                                    $title = "Admin add announcement: <span style='font-weight: bold;'>$announcementTitle</span>";

                                    $announcement_url = "admin_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                    $button_url = "
                                        <button title='View notification' onclick='window.location.href=\"$announcement_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                    
                                }

                                # For teacher giving assignment Notif for Student todos
                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id == NULL &&
                                    $announcement_id == NULL &&
                                    $sender_role != "auto"
                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                        $subjectPeriodCodeTopicId);

                                    
                                    $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    $teacher = new Teacher($con, $teacher_id);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Assignment";
                                    $title = "Add $type: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $student_id, $enrollment_id);

                                    }

                                    $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";

                                }

                                # For teacher graded assignment Notif for Student Submission

                                # Check if subject_assignment_submission_id student_id is equals to 
                                # notification_view student_id

                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);
                                $subject_assignment_submission_student_id = $subjectAssignmentSubmission->GetStudentId();


                                // $hey = $subject_assignment_submission_student_id == $studentLoggedInId;

                                // var_dump($hey);



                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id != NULL &&
                                    $subject_assignment_submission_student_id == $studentLoggedInId &&
                                    $announcement_id == NULL &&
                                    $sender_role != "auto"

                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                        $subjectPeriodCodeTopicId);

                                    
                                    $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    $teacher = new Teacher($con, $teacher_id);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Graded";
                                    $title = "Assignment: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $student_id, $enrollment_id);

                                    }

                                    $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";

                                }

                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id == NULL &&
                                    $announcement_id == NULL &&
                                    $sender_role == "auto"
                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    // $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                    //     $subjectPeriodCodeTopicId);

                                    // $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    // $teacher = new Teacher($con, $teacher_id);

                                    // $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    // $sender_name = trim($sender_name);

                                    $sender_name = trim("System");

                                    $type = "Due soon";
                                    $title = "Assignment $type: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $student_id, $enrollment_id);

                                    }

                                    $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&&n_id=$notification_id&notification_due=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";

                                }


                                if($announcement_id != NULL 
                                    && $subject_code != NULL
                                    && $sender_role = "teacher"
                                    ){


                                    $announcement = new Announcement($con, $announcement_id);
                                    $announcementTitle = $announcement->GetTitle();


                                    $announcementTeacherId = $announcement->GetTeacherId();

                                    $teacher = new Teacher($con, $announcementTeacherId);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Announcement";

                                    $title = "Add $type: <span style='font-weight: bold;'>$announcementTitle</span> on <span style='font-weight: bold;'>$subject_code</span>";

                                    $announcement_url = "../courses/student_subject_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                    $button_url = "
                                        <button onclick='window.location.href=\"$announcement_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                }

                      

                                $status = "
                                        <i style='color: orange' class='fas fa-times'></i>
                                    ";
 
                                #
                                $notif_exec = new Notification($con, $notification_id);
                                $studentViewed = $notif_exec->CheckStudentViewedNotification(
                                    $notification_id, $studentLoggedInId);

                                $studentViewedDue = $notif_exec->CheckStudentViewedDueDateNotification(
                                    $notification_id, $studentLoggedInId);

                                if($studentViewed){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }

                                if($studentViewedDue){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }

                                echo "
                                    <tr>
                                        <td>($notification_id) $sender_name</td>
                                        <td>$type</td>
                                        <td>$title</td>
                                        <td>$date_creation</td>
                                        <td>$status</td>
                                        
                                        <td>$button_url</td>
                                        
                                         
                                    </tr>
                                ";

                            }

                        ?>

                        
                    </tbody>
                </table>

            </main>
        </div>
    </main>
</div>

