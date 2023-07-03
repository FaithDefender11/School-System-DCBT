<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");


    if(isset($_POST['subject_type']) && isset($_POST['department_type']) ){

        $subject_type= $_POST['subject_type'];
        $department_type= $_POST['department_type'];
        
        // echo $department_type;

        $program =  new Program($con);
        // $programDropdown = $program->CreateProgramDropdownDepartmentBased($department_type);

        // echo $subject_type;
        // echo $department_type;


            $query = $con->prepare("SELECT t1.* 
            
                FROM program as t1
                INNER JOIN department as t2 ON t2.department_id=t1.department_id
                WHERE t2.department_name=:department_name
            ");

            $query->bindParam(":department_name", $department_type);

            $query->execute();
            
            if($query->rowCount() > 0){

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $selected = "";

                    $program_name = $row['program_name'];
                    $program_id = $row['program_id'];

                    $data[] = array(
                        'program_id' => $program_id,
                        'program_name' => $program_name
                    );
                }
                
            } 

        // if($department_type == "Tertiary"){

        //     $query = $con->prepare("SELECT t1.* 
            
        //         FROM program as t1
        //         INNER JOIN department as t2 ON t2.department_id=t1.department_id
        //         WHERE t2.department_name=:department_name
        //     ");

        //     $query->bindParam(":department_name", $department_type);

        //     $query->execute();
            
        //     if($query->rowCount() > 0){

        //         while($row = $query->fetch(PDO::FETCH_ASSOC)){

        //             $selected = "";

        //             $program_name = $row['program_name'];
        //             $program_id = $row['program_id'];

        //             $data[] = array(
        //                 'program_id' => $program_id,
        //                 'program_name' => $program_name
        //             );
        //         }
                
        //     } 

        // }
        
        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }
    }
?>