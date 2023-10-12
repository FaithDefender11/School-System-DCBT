<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopicTemplate.php");
 
                   

    if (
        isset($_POST['course_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['subject_code'])
        ) {


        $course_id = $_POST['course_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $subject_code = $_POST['subject_code'];

        $section = new Section($con, $course_id);

        // $subjectProgram = new SubjectProgram($con, $subjectProgramId);
        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);


        $sectionName = $section->GetSectionName();


        $getAllSubjectCodeTopics = $subjectPeriodCodeTopic
            ->GetSubjectCodeDefaultTopics($subject_code, $course_id, $current_school_year_id);

        // var_dump($getAllSubjectCodeTopics);

        $doesFinish = false;

        if(count($getAllSubjectCodeTopics) > 0){

            foreach ($getAllSubjectCodeTopics as $key => $value) {

                # code...
                $subject_period_code_topic_id = $value['subject_period_code_topic_id'];


                $doesRemovedTopic = $subjectPeriodCodeTopic
                    ->RemovalOfDefaultSubjectCodeTopics($subject_period_code_topic_id);
                if($doesRemovedTopic){
                    $doesFinish = true;
                }
            }
        }


        if($doesFinish == true){
            echo "success";
            return;
        }
    }

?>