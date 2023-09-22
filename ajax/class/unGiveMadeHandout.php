<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    require_once("../../includes/classes/SubjectCodeHandout.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_POST['subject_code_handout_id'])
        && isset($_POST['subject_period_code_topic_id'])
        && isset($_POST['teacher_id'])
    
    ) {

        
        $subject_code_handout_id = $_POST['subject_code_handout_id'];
        $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];
        $teacher_id = $_POST['teacher_id'];

        $subjectCodeHandout = new SubjectCodeHandout($con);

        $wasUnGiven = $subjectCodeHandout->UnGiveHandout
            ($subject_period_code_topic_id,
            $teacher_id,
            $subject_code_handout_id);
        
        if($wasUnGiven){

            echo "success";
            return;
        }

    }
?>