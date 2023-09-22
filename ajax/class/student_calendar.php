<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/StudentSubject.php");
    

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

    // echo $student_id;
    // echo $school_year_id;

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
    $subjectCodeAssignment = new SubjectCodeAssignment($con);
    $subjectCodeAssignmentsArray = [];

    $getEnrolledSubjects = $subjectPeriodCodeTopic->GetAllSubjectTopicEnrolledBased(
        $school_year_id, $student_id, $enrollment_id
    );

    foreach ($getEnrolledSubjects as $key => $subject_period_code_topic_id) {
        
        # All assignments based on enrolled subjects (Not Due assignment)
        $assignmentList = $subjectCodeAssignment->
            GetAllAssignmentopicBased($subject_period_code_topic_id);

        // print_r($assignmentList);

        if (!empty($assignmentList)) {

            foreach ($assignmentList as $key => $value) {

                // if($value['subject_code_assignment_id'] !==)
                $subjectCodeAssignment_id = $value['subject_code_assignment_id'];
        
                array_push($subjectCodeAssignmentsArray, 
                    $subjectCodeAssignment_id);
            }
        }
    }


    foreach ($subjectCodeAssignmentsArray as $key => $subjectCodeAssignmentIds) {

        $subjectCodeAssignmentExec = new SubjectCodeAssignment($con,
            $subjectCodeAssignmentIds);

        $assignment_name = $subjectCodeAssignmentExec->GetAssignmentName();
        $assignment_topic_id = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
        
        $start_date = $subjectCodeAssignmentExec->GetDateCreation();
        $start_date = date("Y-m-d", strtotime($start_date));

        $due_date = $subjectCodeAssignmentExec->GetDueDate();
        $due_date = date("Y-m-d", strtotime($due_date));

        $url = "../../student/courses/task_submission.php?sc_id=$subjectCodeAssignmentIds";

        $dataCalendar[] = array(
            'subject_code_assignment_id' => $subjectCodeAssignmentIds,
            'title' => $assignment_name,
            'start' => $due_date,
            'end' => $due_date,
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