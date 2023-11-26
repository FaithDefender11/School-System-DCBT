<?php

    class Requirement{

    private $con, $sqlData, $requirement_id;

     public function __construct($con, $requirement_id = null){

        $this->con = $con;
        $this->requirement_id = $requirement_id;

        $query = $this->con->prepare("SELECT * FROM requirement
                WHERE requirement_id=:requirement_id");

        $query->bindValue(":requirement_id", $requirement_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }
        
    public function GetRequirementName() {
        return isset($this->sqlData['requirement_name']) ? $this->sqlData["requirement_name"] : ""; 
    }

    public function GetStatus() {
        return isset($this->sqlData['status']) ? $this->sqlData["status"] : ""; 
    }

    public function GetEducationType() {
        return isset($this->sqlData['educationType']) ? $this->sqlData["educationType"] : ""; 
    }

    public function GetAcronym() {
        return isset($this->sqlData['acronym']) ? $this->sqlData["acronym"] : ""; 
    }

        public function GetIs_enabled() {
        return isset($this->sqlData['is_enabled']) ? $this->sqlData["is_enabled"] : ""; 
    }



}
?>