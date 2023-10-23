<?php 

    require_once("../../includes/config.php");
    
    if (
        isset($_POST['teacher_id'])
    ) {

        $teacher_id = $_POST['teacher_id'];
        
        $get = $con->prepare("SELECT 
            t1.*
            
            FROM school_year AS t1

        ");

        // $get->bindValue(":teacher_id", $teacher_id);
        // $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $school_year_id = $row['school_year_id'];
                $term = $row['term'];
                $period = $row['period'];

                $data[] = array(
                    'school_year_id' => $school_year_id,
                    'term' => $term,
                    'period' => $period
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