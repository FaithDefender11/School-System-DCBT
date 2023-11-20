<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    
    if (isset($_POST['selected_department_id'])) {

        $selected_department_id = $_POST['selected_department_id'];

        $department = new Department($con, $selected_department_id);

        $department_name = $department->GetDepartmentName();
       
        $query = $con->prepare("SELECT * FROM program
            WHERE department_id=:department_id
            AND status = 1
        ");

        $query->bindParam(":department_id", $selected_department_id);
        $query->execute();

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $program_id = $row['program_id'];
                $program_name = $row['program_name'];

                $data[] = array(
                    'program_id' => $program_id,
                    'program_name' => $program_name
                );
            }
        }

        $result = array();

        $result = array(
            'data' => $data,
            'department_name' => $department_name
        );      
        
        if(empty($result)){
            echo json_encode([]);
        }else{
            echo json_encode($result);
        }

        // if(empty($data)){
        //     echo json_encode([]);
        // }else{
        //     echo json_encode($data);
        // }
    }

    else{
        echo "Something went wrong on the selected_department_id";
    }
?>