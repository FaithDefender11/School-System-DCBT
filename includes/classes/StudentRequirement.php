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

    public function CheckEnrolleeExisted($pending_enrollees_id){

        $query= $this->con->prepare("SELECT pending_enrollees_id FROM student_requirement
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND student_id IS NULL
            ");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function CheckSubmittedRequirementCount(
        $requirement_id, $student_requirement_id, $maxAllowed){

        $query= $this->con->prepare("SELECT t1.*
        
            FROM student_requirement_list AS t1

            INNER JOIN student_requirement AS t2 ON t2.student_requirement_id = t1.student_requirement_id
            
            WHERE t1.requirement_id=:requirement_id
            AND t1.student_requirement_id=:student_requirement_id
            -- AND student_id IS NULL
            ");

        $query->bindParam(":requirement_id", $requirement_id);
        $query->bindParam(":student_requirement_id", $student_requirement_id);
        $query->execute();

        $count = $query->rowCount();

        if($count < $maxAllowed){
            return true;
        }

        return false;

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

    public function InitializedPendingEnrolleeRequirement(
        $pending_enrollees_id, $student_type, $school_year_id) {


        if($this->CheckEnrolleeExisted($pending_enrollees_id) == false){

            $create = $this->con->prepare("INSERT INTO student_requirement
                    (pending_enrollees_id, student_type, school_year_id)
                    VALUES (:pending_enrollees_id, :student_type, :school_year_id)");

            $create->bindParam(':pending_enrollees_id', $pending_enrollees_id);
            $create->bindParam(':student_type', $student_type);
            $create->bindParam(':school_year_id', $school_year_id);
            $create->execute();

            if ($create->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function InsertStudentRequirement(
        $student_requirement_id, $requirement_id, $file, $maxUploadAllowed) {

    
        $now = date("Y-m-d H:i:s");
        if($this->CheckSubmittedRequirementCount($requirement_id,
            $student_requirement_id, $maxUploadAllowed) === true){

            $create = $this->con->prepare("INSERT INTO student_requirement_list
                    (student_requirement_id, requirement_id, date_creation, file)
                    VALUES (:student_requirement_id, :requirement_id, :date_creation, :file)");

            $create->bindParam(':student_requirement_id', $student_requirement_id);
            $create->bindParam(':requirement_id', $requirement_id);
            $create->bindParam(':date_creation', $now);
            $create->bindParam(':file', $file);
            $create->execute();

            if ($create->rowCount() > 0) {
                return true;
            }

        }


        return false;
    }
    public function GetStudentRequirementList(
        $student_requirement_id, $requirement_id) {

        $query = $this->con->prepare("SELECT * 

            FROM student_requirement_list
            WHERE student_requirement_id=:student_requirement_id
            AND requirement_id=:requirement_id
            ");
        
        $query->bindValue(":student_requirement_id", $student_requirement_id);
        $query->bindValue(":requirement_id", $requirement_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
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

    public function GetUniversalRequirements() {

        $status = "Universal";

        $query = $this->con->prepare("SELECT * FROM requirement

            WHERE status=:status
            AND is_enabled=:is_enabled
        ");

     
        $query->bindParam(":status", $status);
        $query->bindValue(":is_enabled", 1);
        $query->execute();

        if($query->rowCount() > 0){
           return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetRequirements($status) {

        $query = $this->con->prepare("SELECT * FROM requirement
          
            WHERE status=:status
            AND is_enabled=:is_enabled
        ");
     
        $query->bindValue(":status", $status);
        $query->bindValue(":is_enabled", 1);
        $query->execute();

        if($query->rowCount() > 0){
           return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function GetStudentRequirement($pending_enrollees_id, $school_year_id) {

        $sql = $this->con->prepare("SELECT student_requirement_id

            FROM student_requirement
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND school_year_id=:school_year_id
        ");

        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }

    public function RemoveSelectedRequirement(
        $student_requirement_list_id, $student_requirement_id) {

        $sql = $this->con->prepare("DELETE FROM student_requirement_list

            WHERE student_requirement_list_id=:student_requirement_list_id
            AND student_requirement_id=:student_requirement_id
        ");

        $sql->bindParam(":student_requirement_list_id", $student_requirement_list_id);
        $sql->bindParam(":student_requirement_id", $student_requirement_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function RemovedRequirementFile(
        $requirement_id) {

        $sql = $this->con->prepare("DELETE FROM requirement

            WHERE requirement_id=:requirement_id
        ");

        $sql->bindParam(":requirement_id", $requirement_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function GetStudentRequirementListFile($student_requirement_list_id){

        $query= $this->con->prepare("SELECT file FROM student_requirement_list
            WHERE student_requirement_list_id=:student_requirement_list_id");

        $query->bindParam(":student_requirement_list_id", $student_requirement_list_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetchColumn();

        }

        return NULL;
    }

    public function InsertRequirement($requirement_name,
        $status, $is_enabled) {

        $statement = $this->con->prepare("INSERT INTO requirement (requirement_name, status, is_enabled) VALUES (:requirement_name, :status, :is_enabled)");

        $statement->bindParam(":requirement_name", $requirement_name);
        $statement->bindParam(":status", $status);
        $statement->bindParam(":is_enabled", $is_enabled);

        if ($statement->execute()) {
            return true; // Insert was successful
        } else {
            return false; // Insert failed
        }
    }

}

?>