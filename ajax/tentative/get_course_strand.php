<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    
    if (isset($_POST['department_type'])) {

        $department_type = $_POST['department_type'];

        $department = new Department($con);

        $department_id = $department->GetDepartmentIdByName($department_type);

         $query = $con->prepare("SELECT * FROM program
            WHERE department_id=:department_id
        ");

        $query->bindParam(":department_id", $department_id);
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

        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }
    }

?>