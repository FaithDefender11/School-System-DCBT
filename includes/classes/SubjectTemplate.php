<?php

    class SubjectTemplate{

    private $con, $subject_id, $sqlData;

    public function __construct($con, $subject_id = null)
    {
        $this->con = $con;
    }
    
    public function createFormModified($type, $programDropdown){

        if(isset($_POST['create_subject_template'])){

            $program_type = $type === "shs" ? 0 : ($type === "tertiary" ? 1 : "");

            $pre_requisite_title = $_POST['pre_requisite_title'];
            $subject_title = $_POST['subject_title'];
            $subject_type = $_POST['subject_type'];
            $unit = $_POST['unit'];
            $description = $_POST['description'];
            $subject_code = $_POST['subject_code'];

            $create = $this->con->prepare("INSERT INTO subject_template
                (subject_title, unit, subject_type,
                pre_requisite_title, description, subject_code, program_type)

                VALUES(:subject_title, :unit, :subject_type,
                :pre_requisite_title, :description, :subject_code, :program_type)");
                
            $create->bindParam(':subject_title', $subject_title);
            $create->bindParam(':pre_requisite_title', $pre_requisite_title);
            $create->bindParam(':subject_type', $subject_type);
            $create->bindParam(':unit', $unit);
            $create->bindParam(':description', $description);
            $create->bindParam(':subject_code', $subject_code);
            $create->bindParam(':program_type', $program_type);

            if($create->execute()){

                $template_id = $this->con->lastInsertId();


                $template = new Template($this->con, $template_id);

                $template_subject = $template->GetTemplateSubjectName();

                Alert::success("Template Subject: $template_subject has been created in the system.", "list.php");
                exit();
            }

        }

        $department_type = strtoupper($type);

        return "
            <div class='card'>
                <div class='card-header'>
                    <h4 class='text-center mb-3'>Create Template Subject ($department_type)</h4>
                </div>
                <div class='card-body'>
                    <form method='POST'>

                        <div class='form-group mb-2'>
                            <label for=''>Subject Code</label>
                            <input class='form-control' type='text' placeholder='Subject Code' name='subject_code'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>Title</label>
                            <input class='form-control' type='text' placeholder='Subject Title' name='subject_title'>
                        </div>

                        <div class='form-group mb-2'>
                            
                            <label for=''>Description</label>
                            <textarea class='form-control' placeholder='Subject Description' name='description'></textarea>
                        </div>
                
                        <div class='form-group mb-2'>
                            <label for=''>Pre-requisite</label>
                            <input class='form-control' type='text' placeholder='Pre-Requisite' name='pre_requisite_title'>
                        </div>
    
                        <div class='form-group mb-2'>
                            
                            <label for=''>Choose Subject Type</label>
                            <select class='form-control' name='subject_type'>
                                <option value='Core'>Core</option>
                                <option value='Applied'>Applied</option>
                                <option value='Specialized'>Specialized</option>
                            </select>
                        </div>

                        $programDropdown

                        <div class='form-group mb-2'>
                            <label for=''>Units</label>
                            <input class='form-control' value='3' type='text' placeholder='Unit' name='unit'>
                        </div>

                        <button type='submit' class='btn btn-primary'
                            name='create_subject_template'>Save</button>
                    </form>
                </div>
            </div>
        ";
    }

    public function SelectTemplateSubjectTitle($department_name,
        $program_id){

        if($department_name == "Senior High School"){
            // 
            $program_type = 0;
            $subject_type = "Core";

            $query = $this->con->prepare("SELECT * FROM subject_template
                WHERE program_id=:program_id
                OR (
                    program_type=:program_type
                    AND subject_type=:subject_type
                )
            ");
            $query->bindValue(":program_id", $program_id);
            $query->bindValue(":program_type", $program_type);
            $query->bindValue(":subject_type", $subject_type);
            $query->execute();

            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label   class='mb-2'>Subject Title</label>
                    <select id='subject_template_id' class='form-control' name='subject_template_id'>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $html .= "
                        <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                    ";
                }

                $html .= "</select>
                        </div>";
                return $html;
                
            }
        }

        else if($department_name == "Tertiary"){
            // 
            $program_type = 1;
            $subject_type = "Core";

            $query = $this->con->prepare("SELECT * FROM subject_template
                WHERE program_id=:program_id
                OR (
                    program_type=:program_type
                    AND subject_type=:subject_type
                )
            ");
            $query->bindParam(":program_id", $program_id);
            $query->bindParam(":program_type", $program_type);
            $query->bindParam(":subject_type", $subject_type);
            $query->execute();

            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label   class='mb-2'>Subject Title</label>
                    <select id='subject_template_id' class='form-control' name='subject_template_id'>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $html .= "
                        <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                    ";
                }
                $html .= "</select>
                        </div>";
                return $html;
            }
        }

        return null;
    }

    public function SelectSubjectTitleEdit($department_name,
        $program_id){

        // echo $program_id;

        $html = "";

        $subject_type = "Core";

        if($department_name == "Senior High School"){

            $shs_program_type = 0;

            $query = $this->con->prepare("SELECT * FROM subject_template
                    WHERE program_id=:program_id
                    OR (
                        program_type=:program_type
                        AND subject_type=:subject_type
                    )
                ");

            $query->bindParam(":program_id", $program_id);
            $query->bindParam(":program_type", $shs_program_type);
            $query->bindParam(":subject_type", $subject_type);
            $query->execute();

            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label   class='mb-2'>Template</label>
                    <select id='edit_subject_template_id' class='form-control'
                        name='edit_subject_template_id'>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $html .= "
                        <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                    ";
                }
                $html .= "</select>
                        </div>";
                return $html;
            }
        }

        // echo $department_name;
        if($department_name == "Tertiary"){

            $tertiary_program_type = 1;

            $tertiary_query = $this->con->prepare("SELECT * FROM subject_template
                    WHERE program_id=:program_id
                    OR (
                        program_type=:program_type
                        AND subject_type=:subject_type
                    )
                ");

            $tertiary_query->bindParam(":program_id", $program_id);
            $tertiary_query->bindParam(":program_type", $tertiary_program_type);
            $tertiary_query->bindParam(":subject_type", $subject_type);

            $tertiary_query->execute();

            if($tertiary_query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label   class='mb-2'>Template</label>
                    <select id='edit_subject_template_id' class='form-control'
                        name='edit_subject_template_id'>";

                while($row = $tertiary_query->fetch(PDO::FETCH_ASSOC)){
                    $html .= "
                        <option value='".$row['subject_template_id']."'>".$row['subject_title']."</option>
                    ";
                }
                $html .= "</select>
                        </div>";
                return $html;
            }
        }
        
 
       return $html;
    }


}

?>