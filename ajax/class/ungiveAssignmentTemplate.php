<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Notification.php");
    require_once("../../includes/classes/SchoolYear.php");

    
    
    if (isset($_POST['subject_code_assignment_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])
    
    ) {


        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];
        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        $notification = new Notification($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];


        $removedNotification = $notification->RemoveGivenAssignmentNotification(
            $subject_code_assignment_id,
            $current_school_year_id);


        $wasUnGiven = $subjectCodeAssignment->RemoveAssignment
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_assignment_id);
        
        if($wasUnGiven){

            echo "success";
            return;
        }

    }
?>