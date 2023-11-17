<?php 

    # 
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

    // echo $student_id;
    // echo $school_year_id;

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
    $subjectCodeAssignment = new SubjectCodeAssignment($con);
    $subjectCodeAssignmentsArray = array();

    $getEnrolledSubjects = $subjectPeriodCodeTopic->GetAllSubjectTopicEnrolledBased(
        $school_year_id, $student_id, $enrollment_id
    );

    // var_dump($getEnrolledSubjects);

    foreach ($getEnrolledSubjects as $key => $subject_period_code_topic_id) {
        
        # All assignments based on enrolled subjects (Not Due assignment)
        $assignmentList = $subjectCodeAssignment->
            GetAllAssignmentopicBased($subject_period_code_topic_id);

        // print_r($assignmentList);

        if (!empty($assignmentList)) {

            foreach ($assignmentList as $key => $value) {

              // if($value['subject_code_assignment_id'] !==)
              $subjectCodeAssignment_id = $value['subject_code_assignment_id'];
              $subjectCodeAssignment_date_creation = $value['date_creation'];
              $subjectCodeAssignment_assignment_name = $value['assignment_name'];

              // array_push($subjectCodeAssignmentsArray, 
              //     $subjectCodeAssignment_id);

              $subjectCodeAssignmentx = array(
                'subject_code_assignment_id' => $subjectCodeAssignment_id,
                'title' => $subjectCodeAssignment_assignment_name,
                'date_creation' => $subjectCodeAssignment_date_creation
              );

              // Add the associative array to $subjectCodeAssignmentsArray
              $subjectCodeAssignmentsArray[] = $subjectCodeAssignmentx;

            }
        }
    }


    // $calendarMerge = array_merge($mergeAnnouncement, $subjectCodeAssignmentsArray);
    $calendarMerge = array_merge($subjectCodeAssignmentsArray, $mergeAnnouncement);
    
    function sortByDateCreation($a, $b) {
        return strtotime($a['date_creation']) - strtotime($b['date_creation']);
    }
    
    usort($calendarMerge, 'sortByDateCreation');

    $title_result = "";
    $end_hour_Result = "";
    $due_date_result = "";
        
    foreach ($calendarMerge as $key => $value) {

        $subject_code_assignment_id = isset($value['subject_code_assignment_id']) ? $value['subject_code_assignment_id'] : "";
        $title = isset($value['title']) ? $value['title'] : "";
        $date_creation = isset($value['date_creation']) ? $value['date_creation'] : "";

        // $due_date = isset($value['due_date']) ? $value['due_date'] : "";

        
        // $creation_db = $value['date_creation'] ?? "";

        $creation = date("Y-m-d", strtotime($date_creation));
        // $due_date = $creation;

        $time_hours = date("h:i a", strtotime($date_creation));

        // var_dump($date_creation);
            
        $url = "";

        $dataCalendar[] = array(
            'id' => "",
            'title' => $time_hours . " " . $title,
            'start' => $creation,
            'end' => $creation,
            'url' => $url
        );
    }

    // foreach ($calendarMerge as $key => $value) {

    //     $announcement_creation_db = $value['date_creation'] ?? "";
    //     $announcement_creation = date("Y-m-d", strtotime($announcement_creation_db));
    //     // $due_date = $announcement_creation;

    //     $announcement_time_hours = date("h:i a", strtotime($announcement_creation_db));

    //     $title = isset($value['title']) ? $value['title'] : "";

    //     $announcement_id = $value['announcement_id'] ?? "";
 
    //     $url = "../../student/dashboard/announcement.php?id=$announcement_id&calendar_clicked=true";

    //     #
    //     #
    //     #
    //     #
    //     if($title != ""){
    //         $title_result = $title;
    //         $end_hour_Result = $announcement_time_hours;
    //         $due_date_result = $announcement_creation;
    //     }

    //     # annoucement array END

    //     $subjectCodeAssignment_id = $value['subject_code_assignment_id'] ?? "";

    //     $subjectCodeAssignmentExec = new SubjectCodeAssignment($con,
    //         $subjectCodeAssignment_id);

    //     $assignment_name = $subjectCodeAssignmentExec->GetAssignmentName();

    //     #

    //     // var_dump($title_result);

    //     $assignment_topic_id = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
        
    //     $start_date = $subjectCodeAssignmentExec->GetDateCreation();
    //     $start_date = date("Y-m-d", strtotime($start_date));

    //     $due_date_db = $subjectCodeAssignmentExec->GetDueDate();
    //     $due_date = date("Y-m-d", strtotime($due_date_db));


    //     $due_time_hours = date("h:i a", strtotime($due_date_db));

    //     #
    //     // #

    //     if($assignment_name != ""){
    //         // $title_result = $title;
    //         $title_result = $assignment_name;
    //         $end_hour_Result = $due_time_hours;
    //         $due_date_result = $due_date;

    //     }

    //     // var_dump($due_date_result);

    //     $url = "../../student/courses/task_submission.php?sc_id=$subjectCodeAssignment_id&ss_id=";


    //     $dataCalendar[] = array(
    //         'id' => "",
    //         'title' => $end_hour_Result . " " . $title_result,
    //         'start' => $due_date_result,
    //         'end' => $due_date_result,
    //         'url' => $url
    //     );
    // }
     
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