<?php 

    require_once("../../includes/config.php");
    
    if (
        isset($_POST['school_year_id'])
        && isset($_POST['teacher_id'])
    ) {

        $teacher_id = $_POST['teacher_id'];
        $school_year_id = $_POST['school_year_id'];
        
        $get = $con->prepare("SELECT 

            t1.subject_code,
            t1.subject_schedule_id,

            t2.teacher_id,

            t3.term,
            t3.period,

            t4.room_number

            FROM subject_schedule AS t1

            INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id

            INNER JOIN school_year as t3 ON t3.school_year_id = t1.school_year_id
            LEFT JOIN room as t4 ON t4.room_id = t1.room_id

            WHERE t1.teacher_id=:teacher_id

            AND t1.school_year_id=:school_year_id

            GROUP BY t1.subject_code
        ");

        $get->bindValue(":teacher_id", $teacher_id);
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $term = $row['term'];
                $period = $row['period'];
                $subject_code = $row['subject_code'];

                $subject_schedule_id = $row['subject_schedule_id'];

                $teacher_id = $row['teacher_id'];
                
                $data[] = array(
                    'subject_schedule_id' => $subject_schedule_id,
                    'teacher_id' => $teacher_id,
                    'term' => $term,
                    'period' => $period,
                    'subject_code' => $subject_code
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