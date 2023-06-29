<?php

    class Subject{

    private $con, $userLoggedIn, $subject_id, $sqlData;

    public function __construct($con, $userLoggedIn, $subject_id)
    {
        $this->con = $con;
        $this->userLoggedIn = $userLoggedIn;
        $this->subject_id = $subject_id;

        $query = $this->con->prepare("SELECT t1.*, t2.program_section 
        
                FROM subject as t1

                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                WHERE t1.subject_id=:subject_id

                ");

        $query->bindValue(":subject_id", $subject_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function createFormModified($type){

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

        return "
            <div class='card'>
                <div class='card-header'>
                    <h4 class='text-center mb-3'>Create Subject Program</h4>
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


    public function SelectSubjectTitle(){
        $SHS_DEPARTMENT = 4;
        $query = $this->con->prepare("SELECT * FROM subject_template
            -- WHERE course_level=:course_level
            -- WHERE semester=:semester
        ");
        // $query->bindValue(":course_level", 0);
        // $query->bindValue(":semester", "");
        $query->execute();

        if($query->rowCount() > 0){

            $html = "<div class='form-group mb-2'>
                <label   class='mb-2'>Template</label>
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
 
       return null;
    }

    public function SelectSubjectTitleEdit(){
        $SHS_DEPARTMENT = 4;
        $query = $this->con->prepare("SELECT * FROM subject_template
            -- WHERE course_level=:course_level
            -- AND semester=:semester
        ");
        // $query->bindValue(":course_level", 0);
        // $query->bindValue(":semester", "");
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
 
       return null;
    }


}

?>