<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    require_once("../../includes/classes/Section.php");
    
    if (isset($_POST['program_code'])
        && isset($_POST['teacher_id'])
        && isset($_POST['course_id'])
        && isset($_POST['school_year_id'])
        ) {

        $program_code = $_POST['program_code'];

        $teacher_id = $_POST['teacher_id'];

        $course_id = $_POST['course_id'];

        $school_year_id = $_POST['school_year_id'];

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con); 

        $section = new Section($con, $course_id); 

        $sectionName = $section->GetSectionName();

        $subject_code = $section->CreateSectionSubjectCode($sectionName, $program_code);

        $templateList = $subjectPeriodCodeTopic->GetDefaultTopicTemplate($program_code);
        
        // print_r($templateList);

        $teacher_id = $teacher_id === 0 ? NULL : $teacher_id;
        $isDone = false;

        foreach ($templateList as $key => $row) {

            # code...
            $topic = $row['topic'];
            $description = $row['description'];
            $subject_period_name = $row['subject_period_name'];
            $program_code = $row['program_code'];

            $create = $subjectPeriodCodeTopic->AddTopic($course_id,
                $teacher_id, $school_year_id, $topic, $description,
                $subject_period_name, $subject_code, $program_code, 0);

                if($create){
                    $isDone = true;
                }
        }

        if($isDone == true){

            echo "success";
            return;
        }

    }

?>