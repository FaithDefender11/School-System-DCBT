<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    require_once("../../includes/classes/SubjectCodeHandout.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_POST['subject_code_handout_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])) {


        
        $subject_code_handout_id = $_POST['subject_code_handout_id'];

        $subjectCodeHandout = new SubjectCodeHandout($con);

        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];


        $wasGiven = $subjectCodeHandout->GiveHandout
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_handout_id);
        
        if($wasGiven){

            echo "success";
            return;
        }

    }
?>