<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['course_id'])
        && isset($_POST['term'])
        && isset($_POST['school_year_id'])
        
        ) {


        
        $course_id = $_POST['course_id'];
        $term = $_POST['term'];
        $school_year_id = $_POST['school_year_id'];


        $section = new Section($con, $course_id);
        $school_year = new SchoolYear($con, $school_year_id);

        $current_school_year_period = $school_year->GetPeriod();

        $program_id = $section->GetSectionProgramId($course_id);
        $sectionLevel = $section->GetSectionGradeLevel();


        // echo $current_school_year_period;
        // return;

        $get = $con->prepare("SELECT t1.subject_code,t1.subject_title,
            t1.subject_program_id
            
            FROM subject_program as t1

            -- INNER JOIN course as t2 ON t2.course_id = t1.course_id
            WHERE t1.program_id=:program_id
            AND t1.semester=:semester
            AND t1.course_level=:course_level

        ");

        $get->bindValue(":program_id", $program_id);
        $get->bindValue(":semester", $current_school_year_period);
        $get->bindValue(":course_level", $sectionLevel);
        $get->execute();

        if($get->rowCount() > 0){

            // print_r($get->fetchAll(PDO::FETCH_ASSOC));

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $subject_program_id = $row['subject_program_id'];
                $subject_code = $row['subject_code'];
                $subject_title = $row['subject_title'];

                // $createSectionSubjectCode = $section->CreateSectionSubjectCode()

                $data[] = array(
                'subject_program_id' => $subject_program_id,
                'subject_code' => $subject_code,
                'subject_title' => $subject_title
                );
            }
           
           
        }
        // echo $program_id;

        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }

    }
?>