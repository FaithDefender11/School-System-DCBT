<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectCodeAssignmentTemplate.php");
    require_once("../../includes/classes/SubjectCodeHandoutTemplate.php");
    
    if (isset($_POST['subject_code_handout_template_id'])
        && isset($_POST['subject_period_code_topic_id'])
    
    ) {

        
    $subject_code_handout_template_id = $_POST['subject_code_handout_template_id'];
    $subject_period_code_topic_id = $_POST['subject_period_code_topic_id'];

    $subjectCodeHandoutTemplate = new SubjectCodeHandoutTemplate($con, $subject_code_handout_template_id);

    // $subject_period_code_topic_id  = "";
    $handout_name = $subjectCodeHandoutTemplate->GetHandoutName();
    $file = $subjectCodeHandoutTemplate->GetFile();

    $addHandoutTemplateToHandoutTopic = $subjectCodeHandoutTemplate->AddHandoutTemplateToHandoutTopic(
        $subject_period_code_topic_id,
        $subject_code_handout_template_id,
        $handout_name,
        $file
    );
    if($addHandoutTemplateToHandoutTopic){
        echo "success";
        return;
    }

}
?>