<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopicTemplate.php");
 
                   

    if (
        isset($_POST['teacher_id'])
        && isset($_POST['course_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['subjectProgramId'])
        ) {


        $course_id = $_POST['course_id'];
        $teacher_id = $_POST['teacher_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $subjectProgramId = $_POST['subjectProgramId'];

        $section = new Section($con, $course_id);

        

        // echo $subjectProgramId;

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);
        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

        $rawCode = $subjectProgram->GetSubjectProgramRawCode();

        $sectionName = $section->GetSectionName();

        $subject_code = $section->CreateSectionSubjectCode($sectionName, $rawCode);

        $getAllDefaultTopicTemplate = $subjectPeriodCodeTopicTemplate->GetTopicTemplateDefaultTopics($rawCode);


        $doesFinish = false;

        if(count($getAllDefaultTopicTemplate) > 0){

            foreach ($getAllDefaultTopicTemplate as $key => $row) {

                $topic = $row['topic'];
                $description = $row['description'];
                $subject_period_name = $row['subject_period_name'];
                $program_code = $row['program_code'];

                #
                $wasSuccess = $subjectPeriodCodeTopic->AddTopic(
                    $course_id, $teacher_id, $current_school_year_id,
                    $topic, $description,
                    $subject_period_name, $subject_code,
                    $program_code, $subjectProgramId);
                
                if($wasSuccess){

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