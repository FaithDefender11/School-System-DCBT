<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeHandout.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_POST['subject_code_assignment_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])) {
        
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];

        $subjectCodeAssignment = new SubjectCodeAssignment($con);

        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];

        $give = "give";

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