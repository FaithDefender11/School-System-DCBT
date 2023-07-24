<?php

class Program{

    private $con, $program_id, $sqlData;


    public function __construct($con, $program_id = null){
        $this->con = $con;
        $this->program_id = $program_id;

        $query = $this->con->prepare("SELECT * FROM program
                WHERE program_id=:program_id");

        $query->bindParam(":program_id", $program_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }


    public function CheckIdExists($program_id) {

        $query = $this->con->prepare("SELECT * FROM program
                WHERE program_id=:program_id");

        $query->bindParam(":program_id", $program_id);
        $query->execute();

        if($query->rowCount() == 0){
            echo "
                <div class='col-md-12'>
                    <h4 class='text-center text-warning'>ID Doesnt Exists.</h4>
                </div>
            ";
            exit();
        }
    }

    public function GetProgramName() {
        return isset($this->sqlData['program_name']) ? ucfirst($this->sqlData["program_name"]) : ""; 
    }

    public function GetProgramDean() {
        return isset($this->sqlData['dean']) ? ucfirst($this->sqlData["dean"]) : ""; 
    }

    public function GetProgramTrack() {
        return isset($this->sqlData['track']) ? ucfirst($this->sqlData["track"]) : ""; 
    }

    public function GetProgramAcronym() {
        return isset($this->sqlData['acronym']) ? ucfirst($this->sqlData["acronym"]) : ""; 
    }

    public function GetProgramDepartmentId() {
        return isset($this->sqlData['department_id']) ? $this->sqlData["department_id"] : 0; 
    }



    public function GetProgramSectionName() {
        return isset($this->sqlData['acronym']) ? ucfirst($this->sqlData["acronym"]) : ""; 
    }

    public function CreateProgramDropdownDepartmentBased($department_name = null){

        $html = "<div class='form-group mb-2'>
            <label class='mb-2'>Program</label>
            <select id='program_id' class='form-control' name='program_id'>";

        if($department_name == "Senior High School"){

            $query = $this->con->prepare("SELECT t1.* 
            
                FROM program as t1
                INNER JOIN department as t2 ON t2.department_id=t1.department_id
                WHERE t2.department_name=:department_name
            ");

            $query->bindValue(":department_name", $department_name);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html .= "<option value='Course-Section' disabled selected>Select-Program</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $selected = "";
                    
                    $html .= "
                        <option value='".$row['program_id']."' $selected>".$row['program_name']."</option>
                    ";
                }
                
            }
        }
        else if($department_name == "Tertiary"){

            $query = $this->con->prepare("SELECT t1.* 
            
                FROM program as t1
                INNER JOIN department as t2 ON t2.department_id=t1.department_id
                WHERE t2.department_name=:department_name
            ");

            $query->bindValue(":department_name", $department_name);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html .= "<option value='Course-Section' disabled selected>Select-Program</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $selected = "";
                    
                    $html .= "
                        <option value='".$row['program_id']."' $selected>".$row['program_name']."</option>
                    ";
                }
                
            }
        }

        $html .= "</select>
            </div>";
        return $html;
    }


    public function CreateProgramDropdown($program_id = null, $department_id){

        $html = "<div class='form-group mb-2'>
            <label class='mb-2'>Program</label>
            <select id='program_id' class='form-control' name='program_id'>";

            $query = $this->con->prepare("SELECT t1.* 
            
                FROM program as t1
                INNER JOIN department as t2 ON t2.department_id = t1.department_id
                WHERE t2.department_id=:department_id
            ");

            // $query->bindValue(":program_id", $program_id);

            $query->bindParam(":department_id", $department_id);

            $query->execute();
            
            if($query->rowCount() > 0){

                // $html .= "<option value='' disabled selected>Select-Program</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $selected = "";

                    if($row['program_id'] == $program_id){
                        $selected = "selected";
                    }
                    $html .= "
                        <option value='".$row['program_id']."' $selected>".$row['program_name']."</option>
                    ";
                }
            }

        $html .= "</select>
            </div>";
        return $html;
    }

    public function GetAllOfferedPrograms(){

        $offeredPrograms = [];

        $sql = $this->con->prepare("SELECT 
            t1.acronym,
            t1.program_id,
            t2.department_name 

            FROM program as t1

            INNER JOIN department as t2 ON t2.department_id = t1.department_id
            
            -- WHERE t1.department_id=:department_id
            ");
            
        // $sql->bindParam(":");
        $sql->execute();

        if($sql->rowCount() > 0){
            $offeredPrograms = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // print_r($course_ids);
        return $offeredPrograms;


    }
}
?>