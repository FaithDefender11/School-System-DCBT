<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Announcement.php");
    

    $student_id = NULL;
    $school_year_id = NULL;
    $enrollment_id = NULL;

    
    if(isset($_GET['st_id'])){
        $student_id = $_GET['st_id'];
    }

    if(isset($_GET['sy_id'])){
        $school_year_id = $_GET['sy_id'];
    }

    if(isset($_GET['e_id'])){
        $enrollment_id = $_GET['e_id'];
    }

    $studentSubject = new StudentSubject($con);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($student_id, $school_year_id, $enrollment_id);

    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_code = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_code);
    }

    $announcement = new Announcement($con);

    $getAllAnnouncementOnMyEnrolledSubjects = $announcement->GetAllTeacherAnnouncementUnderEnrolledSubjects(
        $school_year_id, $enrolledSubjectList);

    $getAllAnnouncementFromAdmin = $announcement->GetAllAnnouncementFromAdmin(
        $school_year_id);

    $mergeAnnouncement = array_merge($getAllAnnouncementFromAdmin,
        $getAllAnnouncementOnMyEnrolledSubjects);


    // var_dump($mergeAnnouncement);

    foreach ($mergeAnnouncement as $key => $value) {

        // $subjectCodeAssignmentExec = new SubjectCodeAssignment($con,
        //     $subjectCodeAssignmentIds);

        $announcement_creation_db = $value['date_creation'];
        $announcement_creation = date("Y-m-d", strtotime($announcement_creation_db));

        $announcement_time_hours = date("h:i a", strtotime($announcement_creation_db));

        $title = $value['title'];
        $announcement_id = $value['announcement_id'];

        // $assignment_name = $subjectCodeAssignmentExec->GetAssignmentName();
        // $assignment_topic_id = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
        
        // $start_date = $subjectCodeAssignmentExec->GetDateCreation();
        // $start_date = date("Y-m-d", strtotime($start_date));

        // $due_date_db = $subjectCodeAssignmentExec->GetDueDate();
        // $due_date = date("Y-m-d", strtotime($due_date_db));

        // $due_time_hours = date("h:i a", strtotime($due_date_db));
 

        // $url = "../../student/courses/task_submission.php?sc_id=$subjectCodeAssignmentIds";
        $url = "../../student/dashboard/announcement.php?id=$announcement_id";

        $dataCalendar[] = array(
            'announcement_id' => $announcement_id,
            'title' => $announcement_time_hours . " " . $title,
            'start' => $announcement_creation,
            'end' => "",
            'url' => $url
        );
    }
     
    $data = array(
        'status' => true,
        'msg' => 'successfully!',
        'data' => $dataCalendar
    );

    if(empty($dataCalendar)){
        echo json_encode([]);
    }else{
        echo json_encode($data);
    }

?>