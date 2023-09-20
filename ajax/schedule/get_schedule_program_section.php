<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['chosen_school_year_id'])
        && isset($_POST['program_id'])
    ) {

        $chosen_school_year_id = $_POST['chosen_school_year_id'];
        $program_id = $_POST['program_id'];

        // echo $chosen_school_year_id;
        // echo $program_id;


        $get = $con->prepare("SELECT 

            t1.subject_schedule_id,
            t1.course_id,
            t2.program_section
            
            FROM subject_schedule as t1

            INNER JOIN course as t2 ON t2.course_id = t1.course_id
            AND t2.program_id=:program_id
            AND t1.school_year_id=:school_year_id

            GROUP BY t1.course_id
        ");

        $get->bindValue(":program_id", $program_id);
        $get->bindValue(":school_year_id", $chosen_school_year_id);
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