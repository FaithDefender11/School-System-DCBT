<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (
        isset($_POST['program_id'])
        && isset($_POST['chosen_school_year_id'])
        && isset($_POST['course_level'])
        
    ) {

        $chosen_school_year_id = $_POST['chosen_school_year_id'];
        $program_id = $_POST['program_id'];
        $course_level = $_POST['course_level'];

        // $enrolled_course_id = $value['course_id'];

        // $section = new Section($con, $course_id);
        $schoolYear = new SchoolYear($con, $chosen_school_year_id);

        $semester = $schoolYear->GetPeriod();

        // $enrolled_course_level = $section->GetSectionGradeLevel();
        // $enrolled_course_capacity = $section->GetSectionCapacity();

        // $program_id = $section->GetSectionProgramId($course_id);
        
        $get = $con->prepare("SELECT 
            t1.*
            
            FROM subject_program AS t1

            WHERE t1.program_id=:program_id
            AND t1.semester=:semester
            AND t1.course_level=:course_level
        ");

        $get->bindValue(":program_id", $program_id);
        $get->bindValue(":semester", $semester);
        $get->bindValue(":course_level", $course_level);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $subject_program_id = $row['subject_program_id'];
                $subject_title = $row['subject_title'];

                $data[] = array(
                    'subject_program_id' => $subject_program_id,
                    'subject_title' => $subject_title
                );
            }
        }

        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }

    }
        
?>