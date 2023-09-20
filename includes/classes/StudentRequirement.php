<?php

    class StudentRequirement{

    private $con, $sqlData, $student_requirement_id;

     public function __construct($con, $student_requirement_id = null){

        $this->con = $con;
        $this->student_requirement_id = $student_requirement_id;

        $query = $this->con->prepare("SELECT * FROM student_requirement
                WHERE student_requirement_id=:student_requirement_id");

        $query->bindValue(":student_requirement_id", $student_requirement_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);


        if($this->sqlData == null){

            $pending_enrollees_id = $student_requirement_id;

            $query = $this->con->prepare("SELECT * FROM student_requirement
                WHERE pending_enrollees_id=:pending_enrollees_id");

            $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        if($this->sqlData == null){

            $student_id = $student_requirement_id;

            $query = $this->con->prepare("SELECT * FROM student_requirement
                WHERE student_id=:student_id");

            $query->bindValue(":student_id", $student_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
      
    }
        
    public function GetStudentId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : null; 
    }

    public function GetForm137() {
        return isset($this->sqlData['form_137']) ? $this->sqlData["form_137"] : null; 
    }

    public function GetGoodMoral() {
        return isset($this->sqlData['good_moral']) ? $this->sqlData["good_moral"] : null; 
    }
    public function GetPSA() {
        return isset($this->sqlData['psa']) ? $this->sqlData["psa"] : null; 
    }

    public function GetGoodMoralValid() {
        return isset($this->sqlData['good_moral_valid']) ? $this->sqlData["good_moral_valid"] : 0; 
    }

    public function GetPSAValid() {
        return isset($this->sqlData['psa_valid']) ? $this->sqlData["psa_valid"] : 0; 
    }

    public function GetForm137Valid() {
        return isset($this->sqlData['form_137_valid']) ? $this->sqlData["form_137_valid"] : 0; 
    }

    
    public function CheckEnrolleeHasRequirementData($pending_enrollees_id){

        $query= $this->con->prepare("SELECT pending_enrollees_id FROM student_requirement
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function CheckStudentHasRequirementData($student_id){

        $query= $this->con->prepare("SELECT student_id FROM student_requirement
            WHERE student_id=:student_id");

        $query->bindParam(":student_id", $student_id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function CheckTheSameForm137($pending_enrollees_id, $form_137){

        $query= $this->con->prepare("SELECT pending_enrollees_id FROM student_requirement
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND form_137=:form_137
            ");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindParam(":form_137", $form_137);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function CheckStudentExisted($student_id){

        $query= $this->con->prepare("SELECT pending_enrollees_id FROM student_requirement
            WHERE student_id=:student_id
            ");

        $query->bindParam(":student_id", $student_id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function InitializedStudentRequirementTable($student_id, $student_type) {


        if($this->CheckStudentExisted($student_id) == false){
            $create = $this->con->prepare("INSERT INTO student_requirement
                    (student_id, student_type)
                    VALUES (:student_id, :student_type)");

            $create->bindParam(':student_id', $student_id);
            $create->bindParam(':student_type', $student_type);
            $create->execute();

            if ($create->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function GoodMoralToggle($student_id,
        $student_requirement_id) {

        // echo $student_id;
        // echo $student_requirement_id;
        
        $goodMoralValid = $this->GetGoodMoralValid();

        $update = $this->con->prepare("UPDATE student_requirement
            SET good_moral_valid=:good_moral_valid
            WHERE student_id=:student_id
            AND student_requirement_id=:student_requirement_id
            ");

        $update->bindValue(":good_moral_valid",
            $goodMoralValid === 0 ? 1 : 0);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":student_requirement_id", $student_requirement_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }


    public function RemovingGoodMoral($student_id,
        $student_requirement_id) {
        
        $update = $this->con->prepare("UPDATE student_requirement
            SET good_moral=:good_moral,
                good_moral_valid=:good_moral_valid
            WHERE student_id=:student_id
            AND student_requirement_id=:student_requirement_id
            ");

        $update->bindValue(":good_moral", NULL);
        $update->bindValue(":good_moral_valid", 0);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":student_requirement_id", $student_requirement_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function RemovingRequirements($student_id,
        $student_requirement_id, $requirement, $requirement_type) {

        $update = $this->con->prepare("UPDATE student_requirement
            SET $requirement=:good_moral,
                $requirement_type=:good_moral_valid
            WHERE student_id=:student_id
            AND student_requirement_id=:student_requirement_id
            ");

        $update->bindValue(":good_moral", NULL);
        $update->bindValue(":good_moral_valid", 0);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":student_requirement_id", $student_requirement_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function PSAToggle($student_id,
        $student_requirement_id) {

        // echo $student_id;
        // echo $student_requirement_id;
        
        $psaValid = $this->GetPSAValid();

        $update = $this->con->prepare("UPDATE student_requirement
            SET psa_valid=:psa_valid
            WHERE student_id=:student_id
            AND student_requirement_id=:student_requirement_id
            ");

        $update->bindValue(":psa_valid",
            $psaValid === 0 ? 1 : 0);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":student_requirement_id", $student_requirement_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function Form137Toggle($student_id,
        $student_requirement_id) {

        // echo $student_id;
        // echo $student_requirement_id;
        
        $form137Valid = $this->GetForm137Valid();

        $update = $this->con->prepare("UPDATE student_requirement
            SET form_137_valid=:form_137_valid
            WHERE student_id=:student_id
            AND student_requirement_id=:student_requirement_id
            ");

        $update->bindValue(":form_137_valid",
            $form137Valid === 0 ? 1 : 0);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":student_requirement_id", $student_requirement_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }


}

?>