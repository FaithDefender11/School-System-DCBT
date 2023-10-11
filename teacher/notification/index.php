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

    $subjectCodeAssignment = new SubjectCodeAssignment($con);
    $notification = new Notification($con);
    
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
    
?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">

            <?php if(count($studentListSubmittedNotification) > 0): ?>

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
                        
                            foreach ($studentListSubmittedNotification as $key => $notification) {
                                
                                // $department_id = $row['department_id'];
                                
                                $notification_id = $notification['notification_id'];

                                $notif_exec = new Notification($con, $notification_id);

                                $sender_role = $notification['sender_role'];
                                $date_creation = $notification['date_creation'];
                                $date_creation = date("M d, Y h:i a", strtotime($date_creation));

                                $sender_role = $notification['sender_role'];

                                $subject_code = $notification['subject_code'];
                                // $users_id = $notification['users_id'];

                                // $subject_code_assignment_id = $notification['subject_code_assignment_id'];
                                $announcement_id = $notification['announcement_id'];
                                $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];

                                // var_dump($subject_assignment_submission_id);
                                // echo "<br>";

                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);
                                $subject_code_assignment_id =  $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
                                $student_id =  $subjectAssignmentSubmission->GetStudentId();

                                // echo $subject_code_assignment_id;

                                $sender_name = "";
                                $type = "";
                                $title = "";
                                $button_url = "";

                                $assignment_notification_url = "";

                                // if($sender_role === "admin" && 
                                //     $announcement_id != NULL){
                                    
                                //     $announcement = new Announcement($con, $announcement_id);
                                //     $users_id = $announcement->GetUserId();

                                //     $users = new User($con, $users_id);

                                //     $sender_name = ucwords($users->getFirstName()) . " " . ucwords($users->getLastName());
                                    
                                //     $type = "Announcement";

                                //     $title = "Admin add announcement: ";

                                //     $announcementTitle = $announcement->GetTitle();

                                //     $title = "Admin add announcement: <span style='font-weight: bold;'>$announcementTitle</span>";

                                //     $announcement_url = "../dashboard/announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                //     $button_url = "
                                //         <button onclick='window.location.href=\"$announcement_url\"' class='btn btn-primary btn-sm'>
                                //             <i class='fas fa-eye'></i>
                                //         </button>
                                //     ";
                                // }

                                if($subject_assignment_submission_id != NULL && 
                                    $subject_code != NULL &&
                                    $sender_role == "student"
                                    ){

                                   
                                    $student = new Student($con, $student_id);

                                    $sender_name = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());


                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    // var_dump($assigment_name);

                                    $type = "Assignment";
                                    $title = "Submitted $type: <span style='font-weight: bold;'>$assigment_name</span>";

                                    // $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&n_id=$notification_id&notification=true";
                                    $assignment_notification_url = "../class/student_submission_view.php?id=$subject_assignment_submission_id&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                }
                           
                                $status = "
                                        <i style='color: orange' class='fas fa-times'></i>
                                    ";

                                #
                                
                                $notif_exec = new Notification($con, $notification_id);
                                $studentViewed = $notif_exec->CheckTeacherViewedNotification($notification_id, $teacherLoggedInId);

                                if($studentViewed){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }


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
                <div class="col-md-12">
                    <h3 class="text-center">No notification found</h3>
                </div>
            <?php endif;?>
        </div>
    </main>
</div>

