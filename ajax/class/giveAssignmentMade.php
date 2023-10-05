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


        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $notification = new Notification($con);

        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];


        $teacher_id = $_POST['teacher_id'];

        $give = "give";

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        
        $wasNotifInserted = $notification->InsertNotificationForTeacherGivingAssignment(
            $current_school_year_id,
            $subject_code_assignment_id, $subject_code);

        $wasGiven = $subjectCodeAssignment->GiveAssignment
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_assignment_id,
            $give);
        
        if($wasGiven){

            echo "success";
            return;
        }

    }
?>