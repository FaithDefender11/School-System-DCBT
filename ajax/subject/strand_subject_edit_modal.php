<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectProgram.php");

    // echo trim("Output");

    if(isset($_GET['id'])){

        $subject_program_id = $_GET['id'];

        // echo $subject_program_id;


        $query = $con->prepare("SELECT * FROM subject_program 
            WHERE subject_program_id=:subject_program_id
            LIMIT 1");

        $query->bindParam(":subject_program_id", $subject_program_id);
        $query->execute();

        if($query->rowCount() > 0){

            $subject_program = $query->fetch(PDO::FETCH_ASSOC);

            // $res = [
            //     'data' => $subject_program
            // ];

            echo json_encode($subject_program);
        }
    }

    // TODO. Validation -> Specify first the Specification.
    
    if(
        isset($_POST['course_level'])
        && isset($_POST['semester'])
        && isset($_POST['subject_program_id'])
        && isset($_POST['edit_subject_template_id'])
        
    ){

        $subject_program = new SubjectProgram($con);

        // $subject_code = $_POST['subject_code'];
        $course_level = $_POST['course_level'];
        $semester = $_POST['semester'];
        $subject_program_id = $_POST['subject_program_id'];
        $subject_template_id = $_POST['edit_subject_template_id'];

        # GET
        $get_subject_template  = $con->prepare("SELECT * FROM subject_template
            WHERE subject_template_id=:subject_template_id");

        $get_subject_template->bindValue(":subject_template_id", $subject_template_id);
        $get_subject_template->execute();

        if($get_subject_template->rowCount() > 0){

            $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);

            $db_subject_template_id = $row['subject_template_id'];
            // echo "Selected Template Id: " . $db_subject_template_id;
            // echo "<br>";

            $subject_title = $row['subject_title'];
            $subject_code = $row['subject_code'];
            $unit = $row['unit'];
            $description = $row['description'];
            $pre_requisite_title = $row['pre_requisite_title'];
            $subject_type = $row['subject_type'];
            $subject_code = $row['subject_code'];

            $get_subject_program  = $con->prepare("SELECT * 
                FROM subject_program

                WHERE subject_program_id=:subject_program_id");

            $get_subject_program->bindParam(":subject_program_id", $subject_program_id);
            $get_subject_program->execute();

            if($get_subject_program->rowCount() > 0){

                $row_sp = $get_subject_program->fetch(PDO::FETCH_ASSOC);

                $sb_subject_title = $row_sp['subject_title'];
                $sb_subject_program_id = $row_sp['subject_program_id'];

                // echo $sb_subject_program_id;

                if($subject_title === $sb_subject_title){

                    // echo "Equal";

                    //  EDIT
                    $query = $con->prepare("UPDATE subject_program
                        SET subject_code=:subject_code,
                            course_level=:course_level,
                            semester=:semester,
                            subject_template_id=:subject_template_id,
                            subject_title=:subject_title,
                            pre_req_subject_title=:pre_req_subject_title,
                            unit=:unit,
                            subject_type=:subject_type,
                            subject_code=:subject_code,
                            description=:description

                        WHERE subject_program_id=:subject_program_id");

                    $query->bindValue(":subject_code", $subject_code);
                    $query->bindValue(":course_level", $course_level);
                    $query->bindValue(":semester", $semester);
                    $query->bindValue(":subject_template_id", $subject_template_id);

                    $query->bindValue(":subject_title", $subject_title);
                    $query->bindValue(":pre_req_subject_title", $pre_requisite_title);
                    $query->bindValue(":unit", $unit);
                    $query->bindValue(":subject_type", $subject_type);
                    $query->bindValue(":subject_code", $subject_code);
                    $query->bindValue(":description", $description);

                    $query->bindValue(":subject_program_id", $subject_program_id);

                    if($query->execute()){
                        echo "success";
                    }
                }

                if($subject_title !== $sb_subject_title){
                   

                    // Check if subject_template_id is already in the subject_program

                    $check_subject_program_exists  = $con->prepare("SELECT * 
                        FROM subject_program

                        WHERE subject_template_id=:subject_template_id");

                    $check_subject_program_exists->bindParam(":subject_template_id", $db_subject_template_id);
                    $check_subject_program_exists->execute();

                    if($check_subject_program_exists->rowCount() == 0){

                        //  ALLOW TO Modify and choose to another.
                        // echo "allow to change to other template id 
                        // in the subject program table";

                        $query = $con->prepare("UPDATE subject_program
                            SET subject_code=:subject_code,
                                course_level=:course_level,
                                semester=:semester,
                                subject_template_id=:subject_template_id,
                                subject_title=:subject_title,
                                pre_req_subject_title=:pre_req_subject_title,
                                unit=:unit,
                                subject_type=:subject_type,
                                subject_code=:subject_code,
                                description=:description

                            WHERE subject_program_id=:subject_program_id");

                        $query->bindValue(":subject_code", $subject_code);
                        $query->bindValue(":course_level", $course_level);
                        $query->bindValue(":semester", $semester);
                        $query->bindValue(":subject_template_id", $subject_template_id);

                        $query->bindValue(":subject_title", $subject_title);
                        $query->bindValue(":pre_req_subject_title", $pre_requisite_title);
                        $query->bindValue(":unit", $unit);
                        $query->bindValue(":subject_type", $subject_type);
                        $query->bindValue(":subject_code", $subject_code);
                        $query->bindValue(":description", $description);

                        $query->bindValue(":subject_program_id", $subject_program_id);

                        if($query->execute()){
                            echo "success";
                        }
                    }else{

                        // There a subject_template_id which currently existed 
                        // in the subject_program table.
                        // echo "not allow to change";

                        echo "already_registered";

                    }
                }
            }

            // if($subject_program->CheckIfSubjectProgramExists($subject_title) == true){
            //     echo "already_registered";
            // }

            // else if($subject_program->CheckIfSubjectProgramExists($subject_title) == false){

            //     $query = $con->prepare("UPDATE subject_program
            //         SET subject_code=:subject_code,
            //             course_level=:course_level,
            //             semester=:semester,
            //             subject_template_id=:subject_template_id,
            //             subject_title=:subject_title,
            //             pre_req_subject_title=:pre_req_subject_title,
            //             unit=:unit,
            //             subject_type=:subject_type,
            //             subject_code=:subject_code,
            //             description=:description

            //         WHERE subject_program_id=:subject_program_id");

            //     $query->bindValue(":subject_code", $subject_code);
            //     $query->bindValue(":course_level", $course_level);
            //     $query->bindValue(":semester", $semester);
            //     $query->bindValue(":subject_template_id", $subject_template_id);

            //     $query->bindValue(":subject_title", $subject_title);
            //     $query->bindValue(":pre_req_subject_title", $pre_requisite_title);
            //     $query->bindValue(":unit", $unit);
            //     $query->bindValue(":subject_type", $subject_type);
            //     $query->bindValue(":subject_code", $subject_code);
            //     $query->bindValue(":description", $description);

            //     $query->bindValue(":subject_program_id", $subject_program_id);

            //     if($query->execute()){
            //         echo "success";
            //     }
            // }
            
        }
    }

?>