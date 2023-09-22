<?php 

    require_once("../../includes/config.php");
    
    if (isset($_POST['subject_template_id'])) {

        $subject_template_id = $_POST['subject_template_id'];

        $get = $con->prepare("SELECT *
            
            FROM subject_template as t1
 
            WHERE t1.subject_template_id=:subject_template_id
        ");

        $get->bindParam(":subject_template_id", $subject_template_id);
        $get->execute();

        if($get->rowCount() > 0){

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $subject_template_id = $row['subject_template_id'];
                $pre_requisite_title = $row['pre_requisite_title'];
                
                $data[] = array(
                    'subject_template_id' => $subject_template_id,
                    'pre_requisite_title' => $pre_requisite_title
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