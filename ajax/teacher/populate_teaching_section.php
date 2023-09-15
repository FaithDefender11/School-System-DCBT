<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['school_year_id'])

        && isset($_POST['teacher_id'])) {

        $school_year_id = $_POST['school_year_id'];
        $teacher_id = $_POST['teacher_id'];

        $get = $con->prepare("SELECT 

            t1.subject_schedule_id,
            t1.course_id,
            t3.program_section
            
            FROM subject_schedule as t1

            INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
            INNER JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE t1.school_year_id=:school_year_id
            AND t1.teacher_id=:teacher_id

            GROUP BY t1.course_id
        ");

        $get->bindParam(":school_year_id", $school_year_id);
        $get->bindParam(":teacher_id", $teacher_id);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $course_id = $row['course_id'];
                $program_section = $row['program_section'];
                
                $data[] = array(
                    'course_id' => $course_id,
                    'program_section' => $program_section
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