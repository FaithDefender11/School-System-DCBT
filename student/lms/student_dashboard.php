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

    # List of all Enrolled Subject subject_period_code_topic_id(s)
    $getEnrolledSubjects = $subjectPeriodCodeTopic->GetAllSubjectTopicEnrolledBased(
        $school_year_id, $student_id, $enrollment_id
    );

    // print_r($getEnrolledSubjects);
    // echo "<br>";

    $subjectCodeAssignment = new SubjectCodeAssignment($con);


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

    // print_r($submissionCodeAssignmentArr);
    // print_r($subjectCodeAssignmentsArray);

    // $enrolledSubjectCode = [];

 
    $enrolledSubjectList = [];
    $studentAllAnnouncementIds = [];

    // print_r($allEnrolledSubjectCode);

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_code = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_code);
    }

    // print_r($enrolledSubjectList);
    // echo "<br>";


    foreach ($announcementList as $key => $value) {

        $announcement_subject_code = $value['subject_code'];
        $announcement_id = $value['announcement_id'];

        if (in_array($announcement_subject_code, $enrolledSubjectList)) {
            
            // echo $announcement_subject_code;
            // echo "<br>";
            array_push($studentAllAnnouncementIds, $announcement_id);
        }
    }
 

    
    $notif = new Notification($con);

    $studentEnrolledSubjectAssignmentNotif = $notif->GetStudentAssignmentNotification($enrolledSubjectList, $school_year_id);

    // var_dump($studentEnrolledSubjectAssignmentNotif);
?>

<div class="content">

    <div class="row col-md-12">
        <div class="col-md-9">
            <br>
            <main>
                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h4 style="font-weight: bold;" class="text-primary">Enrolled Subject</h4>
                        </div>
                    </header>
 
                    <main>
                        <?php if(count($allEnrolledSubjectCode) > 0): ?>
                            <table id="student_dashboard_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>  
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Unit</th>
                                        <th>Section</th>  
                                        <th>Instructor</th>  
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php

                                        foreach ($allEnrolledSubjectCode as $key => $row_inner) {

                                            // while($row_inner = $query->fetch(PDO::FETCH_ASSOC)){

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

                                            $instructor_name = "-";

                                            if($teacher_firstname != null){
                                                $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                            }

                                            $section_code = $section->CreateSectionSubjectCode($program_section, $sp_subjectCode);

                                            // $section_code = trim(strtolower($section_code));

                                            // $courses_url = "../courses/index.php?c=$section_code";
                                            $courses_url = "../courses/index.php?id=$student_subject_id";
                                            
                                            echo "
                                                <tr class='text-center'>
                                                    <td>
                                                        <a style='color: inherit' href='$courses_url'>
                                                            $subject_title
                                                        </a>
                                                    </td>
                                                    <td>
                                                        $sp_subjectCode
                                                    </td>
                                                    <td>$subject_type</td>
                                                    <td>$unit</td>
                                                    <td>
                                                        <a style='all:unset; cursor: pointer' href=''>
                                                            $program_section
                                                        </a>
                                                    </td>
                                                    <td>$instructor_name</td>
                                                </tr>
                                            ";
                                        }

                                    ?>
                                </tbody>
                        </table>

                        <?php else:?>
                            <h4>No enrollment form data</h4>
                        <?php endif;?>
                        
                    </main>
                </div>
            </main>
        </div>

        <div class="col-md-3">
            <div id="accordion">
                <!-- Bootstrap Accordion Item -->
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h3 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                <p>
                                Assignments Due <?php echo count($subjectCodeAssignmentsArray) ?>

                                </p>
                            </button>
                        </h3>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordion">
                        <div class="card-body">
                            <?php if(count($subjectCodeAssignmentsArray) > 0):?>
                                <div class="text-right">
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
                                        echo "<a style='color:inherit' href='assignment_due.php?c=$topic_subject_code' class='m-0 text-right'>$assignmentTitle - ($count)</a>";
                                        echo "<br>";
                                    }
                                    ?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                 <hr>
                <div class='card'>
                    <div class='card-header'>
                        <?php if(count($studentAllAnnouncementIds) > 0):?>

                            <p style="margin-bottom: 7px;"><?php echo count($studentAllAnnouncementIds); ?> Announcement</p>
                            <?php 
                                $i=0;
                                foreach ($studentAllAnnouncementIds as $key => $announcementId) {

                                    $announcement = new Announcement($con, $announcementId);

                                    $title = $announcement->GetTitle();
                                    // $announcement_id = $announcement->getan();


                                    // $title = $value['title'];
                                    // $announcement_id = $value['announcement_id'];
                                    $i++;

                                    $student_view_obj = $announcement->GetStudentViewedAnnouncementId($announcementId);

                                    $status = "
                                        <i style='color: orange' class='fas fa-times'></i>
                                    ";
                                    // if($studentViewedAnnouncementIds == true){
                                    if($student_view_obj !== NULL){
                                        $studentViewedAnnouncementIds = $student_view_obj['announcement_id'];
                                        
                                        if($studentViewedAnnouncementIds == $announcementId){

                                            $status = "
                                                <i style='color: yellow' class='fas fa-check'></i>
                                            ";
                                        } 
                                    }
                                    

                                    # code...
                                    $announcement_url = "../courses/student_subject_announcement.php?id=$announcementId";

                                    echo "
                                        <a href='$announcement_url'>
                                            <span>$i. $title ($status)</span>
                                        </a>
                                    <br>";
                                }
                            ?>
                        <?php else:?>
                            <p style="margin-bottom: 7px;">No announcement</p>
                        <?php endif;?>
                    </div>
                </div>

                <hr>
                <div class='card'>
                    <div class='card-header'>
                        <?php if(count($studentEnrolledSubjectAssignmentNotif) > 0):?>

                            <p style="margin-bottom: 7px;">Notification</p>

                            <?php 

                                $i=0;

                                foreach ($studentEnrolledSubjectAssignmentNotif as $key => $notification) {


                                    $notification_id = $notification['notification_id'];

                                    $notif_exec = new Notification($con, $notification_id);

                                    $sender_role = $notification['sender_role'];
                                    
                                    $subject_code_assignment_id = $notification['subject_code_assignment_id'];
                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

                                    $assigment_name = $assigment->GetAssignmentName();

                                    // $title = $value['title'];
                                    // $announcement_id = $value['announcement_id'];
                                    $i++;

                                    // $student_view_obj = $announcement->GetStudentViewedAnnouncementId($announcementId);

                                    $status = "
                                        <i style='color: orange' class='fas fa-times'></i>
                                    ";

                                    $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&n_id=$notification_id&notification=true";

                                    #
                                    $studentViewed = $notif_exec->CheckStudentViewedNotification($notification_id, $studentLoggedInId);

                                    if($studentViewed){
                                        $status = "
                                            <i style='color: green' class='fas fa-check'></i>
                                        ";
                                    }

                                    // var_dump($assigment_name);

                                    echo "
                                        <a href='$assignment_notification_url'>
                                            <span>$i. $assigment_name ($status)</span>
                                        </a>
                                    <br>";
                                }
                            ?>
                        <?php else:?>
                            <p style="margin-bottom: 7px;">No Notification</p>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
