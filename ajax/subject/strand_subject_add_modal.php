<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectProgram.php");
    
    if (isset($_POST['subject_template_id']) 
            && isset($_POST['course_level']) 
            && isset($_POST['program_id'])
            && isset($_POST['semester'])
            && isset($_POST['pre_req_subject_title'])
            && isset($_POST['department_name'])
        ) {

        $subject_program = new SubjectProgram($con);

        $program_id = $_POST['program_id'];
        $subject_template_id = $_POST['subject_template_id'];
        $department_name = $_POST['department_name'] === "Senior High School" ? "SHS" : $_POST['department_name'];

        // echo $program_id;
        // $subject_code = $_POST['subject_code'];

        $course_level = $_POST['course_level'];
        $semester = $_POST['semester'];
        $pre_req_subject_title = $_POST['pre_req_subject_title'];


        $get_subject_template = $con->prepare("SELECT * FROM subject_template
                WHERE subject_template_id=:subject_template_id
                LIMIT 1");

        $get_subject_template->bindValue(":subject_template_id",
            $subject_template_id);

        $get_subject_template->execute();

        if($get_subject_template->rowCount() > 0){

            $row = $get_subject_template->fetch(PDO::FETCH_ASSOC);
            
            $subject_title = $row['subject_title'];
            $subject_code = $row['subject_code'];
            $template_program_id = $row['program_id'];

            $description = $row['description'];
            $unit = $row['unit'];
            $subject_type = $row['subject_type'];
            $pre_requisite_title_template = $row['pre_requisite_title'];


            // echo $pre_requisite_title;
            // return;

            if($subject_program->CheckIfSubjectProgramExists($subject_title, $template_program_id) == true){
                echo "already_registered";

            }else if($subject_program->CheckIfSubjectProgramExists($subject_title, $template_program_id) == false){
                $create = $con->prepare("INSERT INTO subject_program
                        (program_id, subject_code, pre_req_subject_title, 
                            subject_title, unit, description, 
                            course_level, semester, subject_type,
                            subject_template_id, department_type)

                        VALUES(:program_id, :subject_code, :pre_req_subject_title,
                            :subject_title, :unit, :description,
                            :course_level, :semester, :subject_type,
                            :subject_template_id, :department_type)");
                                            
                $create->bindValue(':program_id', $template_program_id == 0 ? $program_id : $template_program_id);
                $create->bindParam(':subject_code', $subject_code);
                // For Subject PRogram Input
                // $create->bindParam(':pre_req_subject_title', $pre_req_subject_title);
                // For Attach Subject Template
                $create->bindParam(':pre_req_subject_title', $pre_requisite_title_template);
                $create->bindParam(':subject_title', $subject_title);
                $create->bindParam(':unit', $unit);
                $create->bindParam(':description', $description);
                $create->bindParam(':course_level', $course_level);
                $create->bindParam(':semester', $semester);
                $create->bindParam(':subject_type', $subject_type);
                $create->bindParam(':subject_template_id', $subject_template_id);
                $create->bindParam(':department_type', $department_name);

                if($create->execute()){
                    echo "success";
                }
            }
        }
    }
    else{
        echo "not";
    }
?>