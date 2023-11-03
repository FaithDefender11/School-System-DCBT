<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/StudentSubject.php");
    
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['student_id'])
    ){

        $student_id = $_POST['student_id'];


        // var_dump($student_id);
        // return;

        // $student = new Student($con, $student_id);
        
        $get2 = $con->prepare("SELECT 
                
            t1.school_year_id,
            t3.period,
            t3.term
            -- t1.enrollment_id

            FROM enrollment as t1

            INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
            AND t2.student_id=:student_id

            LEFT JOIN school_year as t3 ON t3.school_year_id = t1.school_year_id

            WHERE t1.enrollment_status = 'enrolled'

            GROUP BY t1.enrollment_id

        ");

        $get2->bindValue(":student_id", $student_id);

        $get2->execute();

        if($get2->rowCount() > 0){

            while($row = $get2->fetch(PDO::FETCH_ASSOC)){
            
                $school_year_id = $row['school_year_id'];

                $period = $row['period'];

                $term = $row['term'];

                $period_shortcut = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

                $data[] = array(
                    'school_year_id' => $school_year_id,
                    'term' => $term,
                    'period' => $period_shortcut
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