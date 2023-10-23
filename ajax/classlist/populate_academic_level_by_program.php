<?php 

    // require_once("../../includes/config.php");

    require_once("../../includes/config.php");

    if(isset($_POST['program_id'])){


        $program_id = $_POST['program_id'];

        $get_program = $con->prepare("SELECT t2.* 

            FROM program as t1

            INNER JOIN department as t2 ON t2.department_id = t1.department_id
            WHERE program_id=:program_id
            -- LIMIT 1

        ");

        $get_program->bindValue(":program_id", $program_id);
        $get_program->execute();

        if($get_program->rowCount() > 0){

            $row = $get_program->fetch(PDO::FETCH_ASSOC);

            if($row['department_name'] == "Tertiary"){

                $data = array(
                    array(
                        'level' => "1"
                    ),
                    array(
                        'level' => "2"
                    ),
                    array(
                        'level' => "3"
                    ),
                    array(
                        'level' => "4"
                    )
                );
            }
            else if($row['department_name'] == "Senior High School"){

               $data = array(
                    array(
                        'level' => "11"
                    ),
                    array(
                        'level' => "12"
                    )
                );


            }

            echo json_encode($data);
        }
    }

?>