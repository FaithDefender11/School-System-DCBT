<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['chosen_school_year_id'])
        && isset($_POST['program_id'])
        && isset($_POST['course_id'])
    ) {

        $chosen_school_year_id = $_POST['chosen_school_year_id'];
        $program_id = $_POST['program_id'];
        $course_id = $_POST['course_id'];

         $get = $con->prepare("SELECT 

            t1.student_subject_id,
            t1.subject_code,

            t1.course_id,
            t3.program_section
            
            FROM student_subject as t1

            -- INNER JOIN student_subject as t2 ON t2.student_subject_id = t1.student_subject_id
            INNER JOIN course as t3 ON t3.course_id = t1.course_id

            AND t3.program_id=:program_id
            AND t1.school_year_id=:school_year_id
            AND t3.course_id=:course_id

            GROUP BY t1.subject_code
        ");

        $get->bindValue(":program_id", $program_id);
        $get->bindValue(":school_year_id", $chosen_school_year_id);
        $get->bindValue(":course_id", $course_id);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $course_id = $row['course_id'];
                // $program_section = $row['program_section'];
                $subject_code = $row['subject_code'];
                $student_subject_id = $row['student_subject_id'];
                
                $data[] = array(
                    'course_id' => $course_id,
                    'subject_code' => $subject_code,
                    'student_subject_id' => $student_subject_id
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