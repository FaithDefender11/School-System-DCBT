<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeHandout.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Notification.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['subject_code_assignment_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])) {
        
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];

        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];
        $ungive = "ungiven";

        $removedNotification = $notification->RemoveGivenAssignmentNotification(
            $subject_code_assignment_id,
            $current_school_year_id);


        $wasGiven = $subjectCodeAssignment->GiveAssignment
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_assignment_id,
            $ungive);
        
        if($wasGiven){

            echo "success";
            return;
        }

    }
?>