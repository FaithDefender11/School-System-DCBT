<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Section.php");
 

                            

    if (
        isset($_POST['teacher_id'])
        && isset($_POST['course_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['subject_period_code_topic_template_id'])
        && isset($_POST['program_code'])
        && isset($_POST['topic'])
        && isset($_POST['description'])
        && isset($_POST['subject_period_name'])
        && isset($_POST['period_order'])
        
        ) {


        $teacher_id = $_POST['teacher_id'];
        $course_id = $_POST['course_id'];
        $program_code = $_POST['program_code'];
        $period_order = $_POST['period_order'];

        $topic = $_POST['topic'];
        $description = $_POST['description'];
        $subject_period_name = $_POST['subject_period_name'];

        $section = new Section($con, $course_id); 
        $sectionName = $section->GetSectionName();

        $subject_code = $section->CreateSectionSubjectCode($sectionName, $program_code);

            // echo $subject_period_name;

        $current_school_year_id = $_POST['current_school_year_id'];
        $subject_period_code_topic_template_id = $_POST['subject_period_code_topic_template_id'];

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con); 


        $create = $subjectPeriodCodeTopic->AddTopic($course_id,
                $teacher_id, $current_school_year_id, $topic, $description,
                $subject_period_name, $subject_code, $program_code, $period_order);

        if($create == true){

            echo "success";
            return;
        }
    }
?>