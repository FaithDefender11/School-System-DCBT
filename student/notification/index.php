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

    $notif = new Notification($con);


    $studentEnrolledSubjectAssignmentNotif = $notif->GetStudentAssignmentNotification(
        $enrolledSubjectList, $school_year_id);

        // var_dump($studentEnrolledSubjectAssignmentNotif);

    $allAdminNotification = $notif->GetAdminAnnouncement($school_year_id);

    // print_r($allAdminNotification);

    // print_r($allAdminNotifcation);

    $mergedArray = array_merge($studentEnrolledSubjectAssignmentNotif,
        $allAdminNotification);

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
?>

                <nav>
                    <a href="<?php echo $back_url; ?>">
                        <i class="bi bi-arrow-return-left"></i>
                        Back
                    </a>
                </nav>
                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3>Notifications</h3>
                            </div>
                            <div class="action">
                                <a href="create.php">
                                    <button class="clean">Mark all as read</button>
                                </a>
                            </div>
                        </header>
                        <main>
                            <table class="a" id="notification_table">
                                <thead>
                                    <tr>
                                    <th>From</th>
                                    <th class="type">Type</th>
                                    <th>Subject</th>
                                    <th class="sent">Sent</th>
                                    <th class="read">Read?</th>
                                    <th>Action</th>
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
            
                                            $sender_role = $notification['sender_role'];
            
                                            $subject_code = $notification['subject_code'];
                                            // $users_id = $notification['users_id'];
            
                                            // var_dump($subject_code);
            
                                            
            
                                            // echo "get_student_subject_id: $get_student_subject_id";
                                            // echo "<br>";
            
            
            
                                            $subject_code_assignment_id = $notification['subject_code_assignment_id'];
                                            $announcement_id = $notification['announcement_id'];
                                            $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];
            
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
                                            if($subject_code_assignment_id != NULL && 
                                                $subject_code != NULL &&
                                                $announcement_id == NULL){
            
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
                                                $title = "Add $type: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_code</span>";
            
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
                                            $studentViewed = $notif_exec->CheckStudentViewedNotification($notification_id, $studentLoggedInId);
            
                                            if($studentViewed){
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
        </body>
    </html>