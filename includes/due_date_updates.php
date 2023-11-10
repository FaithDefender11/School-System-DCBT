

<?php 

    require_once("config.php");
    require_once("../includes/classes/SchoolYear.php");
    require_once("../includes/classes/Enrollment.php");
    require_once("../includes/classes/StudentSubject.php");
    require_once("../includes/classes/SubjectCodeAssignment.php");
    require_once("../includes/classes/SubjectAssignmentSubmission.php");
    require_once("../includes/classes/Notification.php");
    require_once("../includes/classes/SubjectPeriodCodeTopic.php");
 

    if(isset($_GET['last_count'])
        && isset($_GET['studentLoggedInId'])
        && isset($_GET['enrollment_id'])
        ){

     

            $school_year = new SchoolYear($con);
            $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

            $school_year_id = $school_year_obj['school_year_id'];

            $student_id = $_GET['studentLoggedInId'];
            $enrollment_id = $_GET['enrollment_id'];

            // echo "student_id: $student_id";
            // return;

            $studentSubject = new StudentSubject($con);

            $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
                ($student_id, $school_year_id, $enrollment_id);

            $enrolledSubjectList = [];

            foreach ($allEnrolledSubjectCode as $key => $value) {
                # code...
                $subject_code = $value['student_subject_code'];
                array_push($enrolledSubjectList, $subject_code);
            }

            $subjectCodeAssignment = new SubjectCodeAssignment($con);

            // var_dump($enrolledSubjectList);

            $getAllIncomingDueAssignmentsIds = $subjectCodeAssignment->GetAllIncomingDueAssignmentsIds(
                $enrolledSubjectList, $school_year_id, $student_id
            );

            $assignmentCount = count($getAllIncomingDueAssignmentsIds);
            // $assignmentCount = 1;

            // $assignmentCount = 3;


       

            $clientLastCount = isset($_GET['last_count']) ? $_GET['last_count'] : null;
            $clientLastCount = intval($clientLastCount);



            // var_dump($clientLastCount);
            // var_dump($assignmentCount);
            // return;

            // if ($clientLastCount === null || $clientLastCount < $assignmentCount) {
            // if ($clientLastCount === null || $clientLastCount != $assignmentCount) {

            // Check if the current page is task_submission.php
            $currentPageIsTaskSubmission = basename($_SERVER['PHP_SELF']) === 'task_submission.php';

                // echo $currentPageIsTaskSubmission;
                
            if (!$currentPageIsTaskSubmission && ($clientLastCount === null || $clientLastCount != $assignmentCount)) {
                echo 'update_available';

                $notif = new Notification($con);

                $dueDateNotifPresentButStudentDoesnt = $notif->CheckStudentEnrolledCodeHasIncludedInDueDateNotificationv2(
                    $enrolledSubjectList, $school_year_id, $student_id, $getAllIncomingDueAssignmentsIds
                );

            } else if (!$currentPageIsTaskSubmission && ($clientLastCount === null || $clientLastCount == $assignmentCount)){
                echo 'no_update';
            }

        
    }

 

?>
