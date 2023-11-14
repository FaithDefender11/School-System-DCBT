<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Announcement.php');

    $subjectCodeAssignment = new SubjectCodeAssignment($con);

    $notification = new Notification($con);
    
    $announcement = new Announcement($con);
    
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    // echo "Qwe";

    $mergedArray = [];
    
    $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects(
        $teacherLoggedInId,
        $current_school_year_id);

    $teachingSubjects = [];


    foreach ($teachingSubjectCode as $key => $value) {

        $teachingCode = $value['subject_code'];
        array_push($teachingSubjects, $teachingCode);
    }

    // print_r($teachingSubjects);

    $studentListSubmittedNotification = $notification->GetStudentSubmittedAssignmentNotification(
        $teachingSubjects, $current_school_year_id);

    // print_r($studentListSubmittedNotification);

    $adminAnnouncement = $announcement->CheckTeacherIdBelongsToAdminAnnouncement($current_school_year_id,
        $teacherLoggedInId);

    // var_dump($adminAnnouncement);

    $studentSubmittedAndAdminAnnouncement = array_merge(
        $studentListSubmittedNotification,
        $adminAnnouncement);
    
    // var_dump($adminAnnouncement);
    // echo "<br>";
    

    $viewedCount = $notification->GetTeacherViewedNotificationCount(
        $studentListSubmittedNotification, $teacherLoggedInId);

    $unViewedCount = $notification->GetTeacherUnViewedNotificationCount(
        $studentListSubmittedNotification, $teacherLoggedInId);

        

    $unViewedAdminNotifCount = $notification->GetTeacherViewedNotificationFromAdminCount(
        $adminAnnouncement, $teacherLoggedInId);

    // echo "<br>";
    // var_dump($unViewedAdminNotifCount);


    // echo "viewedCount: $viewedCount";
    // echo "<br>";

    // echo "unViewedCount: $unViewedCount";
    // echo "<br>";

    usort($studentSubmittedAndAdminAnnouncement, function($a, $b) {
        $dateA = strtotime($a['date_creation']);
        $dateB = strtotime($b['date_creation']);

        if ($dateA == $dateB) {
            return 0;
        }
        
        return ($dateA > $dateB) ? -1 : 1; // Change from 1 to -1 for descending order
    });
    

    // var_dump($unViewedCount);

    // echo count($adminAnnouncement);
?>
            <?php
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "first",
                    "second",
                    "first"
                );
            ?>

            <main>
                <div class="floating">
                    <?php if(count($studentSubmittedAndAdminAnnouncement) > 0): ?>
                        <header>
                            <div class="title">
                                <h3>Notifications</h3>
                            </div>
                            <div class="action">
                                <button class="clean large">Mark all as read</button>
                            </div>
                        </header>
                        <main style='overflow-x: auto'>
                            <table class="a" id="notification_table">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th class="type">Type</th>
                                        <th>Subject</th>
                                        <th class="sent">Sent </th>
                                        <th>Status</th>
                                        <th class="read">Read?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($studentSubmittedAndAdminAnnouncement as $key => $notification) {
                                    
                                            // $department_id = $row['department_id'];
            
            
                                            
                                            $notification_id = isset($notification['notification_id']) ? $notification['notification_id'] : "";
            
                                            $notif_exec = new Notification($con, $notification_id);
            
                                            // $sender_role = $notification['sender_role'];
                                            $sender_role = isset($notification['sender_role']) ? $notification['sender_role'] : '';
                                            
                                            // $date_creation = $notification['date_creation'];
            
                                            $date_creation = isset($notification['date_creation']) ? $notification['date_creation'] : '';
                                            
            
                                            $date_creation = date("M d, Y h:i a", strtotime($date_creation));
            
                                        
                                            // $subject_code = $notification['subject_code'];
                                            $subject_code = isset($notification['subject_code']) ? $notification['subject_code'] : '';
            
                                            // $announcement_id = $notification['announcement_id'];
                                            $announcement_id = isset($notification['announcement_id']) ? $notification['announcement_id'] : '';
                                            
                                            // $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];
                                            $subject_assignment_submission_id = isset($notification['subject_assignment_submission_id']) ? $notification['subject_assignment_submission_id'] : '';
            
            
                                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);
                                            $subject_code_assignment_id =  $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
                                            $student_id =  $subjectAssignmentSubmission->GetStudentId();
            
            
                                            $sender_name = "";
                                            $type = "";
                                            $title = "";
                                            $button_url = "";
            
                                            $assignment_notification_url = "";
            
                                            $status = "
                                                <i style='color: orange' class='fas fa-times'></i>
                                            ";
                                            
            
                                            $admin_announcement_id = isset($notification['announcement_id']) ? $notification['announcement_id'] : '';
                                            
                                            $admin_title = isset($notification['title']) ? $notification['title'] : '';
                                            $admin_content = isset($notification['content']) ? $notification['content'] : '';
                                            
                                            $admin_users_id = isset($notification['users_id']) ? $notification['users_id'] : '';
                                            $admin_date_creation_db = isset($notification['date_creation']) ? $notification['date_creation'] : '';
            
                                            $date_creation = date("M d, Y h:i a", strtotime($admin_date_creation_db));
            
                                            $admin_role = isset($notification['role']) ? $notification['role'] : '';
            
                                            // echo $admin_users_id;
                                            // echo "<br>";
            
                                            if($admin_announcement_id != NULL && 
                                                $subject_code == NULL &&
                                                $admin_users_id != NULL &&
                                                
                                                $admin_role == "admin"
                                                ){
            
                                            
                                                    $user = new User($con, $admin_users_id);
            
                                                    $sender_name = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
            
            
                                                
                                                    // var_dump($assigment_name);
            
                                                    $type = "Announcement";
                                                    $title = "<span style='font-weight: bold;'>$admin_title</span>";
            
                                                    $announcement_url = "announcement_view.php?id=$admin_announcement_id&notification=true";
            
                                                    $button_url = "
                                                        <button onclick='window.location.href=\"$announcement_url\"' class='btn btn-info btn-sm'>
                                                            <i class='fas fa-eye'></i>
                                                        </button>
                                                    ";
            
                                                    $announcement = new Announcement($con, $admin_announcement_id);
            
            
                                                    $teacherAnnouncementViewed = $announcement->CheckTeacherViewedAnnouncement(
                                                        $admin_announcement_id, $teacherLoggedInId);
            
                                                    if($teacherAnnouncementViewed){
                                                        $status = "
                                                        <i style='color: green' class='fas fa-check'></i>
                                                    ";
            
                                                }
                                            }
            
                                            if($subject_assignment_submission_id != NULL && 
                                                $subject_code != NULL &&
                                                $sender_role == "student" ){
            
                                            
                                                $student = new Student($con, $student_id);
            
                                                $sender_name = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());
            
            
                                                $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                                $assigment_name = $assigment->GetAssignmentName();
            
                                                // var_dump($assigment_name);
            
                                                $type = "Assignment";
                                                $title = "Submitted $type: <span style='font-weight: bold;'>$assigment_name</span>";
            
                                                $assignment_notification_url = "../class/student_submission_view.php?id=$subject_assignment_submission_id&n_id=$notification_id&notification=true";
            
                                                $button_url = "
                                                    <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                                        <i class='fas fa-eye'></i>
                                                    </button>
                                                ";
                                            }
                                    
                                            
                                            $notif_exec = new Notification($con, $notification_id);
                                            $studentViewed = $notif_exec->CheckTeacherViewedNotification($notification_id,
                                                $teacherLoggedInId);
            
                                        
            
                                            if($studentViewed){
                                                $status = "
                                                    <i style='color: green' class='fas fa-check'></i>
                                                ";
                                            }
            
                                            // ($notification_id)
            
                                            echo "
                                                <tr>
                                                    <td>$sender_name</td>
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
                    <?php else: ?>
                        <h4 class='text-center'>No notification found</h4>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </body>
</html>