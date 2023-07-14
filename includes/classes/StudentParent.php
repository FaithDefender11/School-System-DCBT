<?php

    class StudentParent{

    private $con, $sqlData, $parent_id;

    public function __construct($con, $parent_id = null){

        $this->con = $con;
        $this->parent_id = $parent_id;

        $query = $this->con->prepare("SELECT * FROM parent
                WHERE parent_id=:parent_id");

        $query->bindValue(":parent_id", $parent_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

        if($this->sqlData == null){

            $parent_student_id = $parent_id;

            $query = $this->con->prepare("SELECT * FROM parent
                WHERE student_id=:student_id");

            $query->bindValue(":student_id", $parent_student_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetFirstName() {
        return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
    }

    public function GetLastName() {
        return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
    }
    public function GetMiddleName() {
        return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
    }

    public function GetContactNumber() {
        return isset($this->sqlData['contact_number']) ? $this->sqlData["contact_number"] : ""; 
    }
    public function GetSuffix() {
        return isset($this->sqlData['suffix']) ? ucfirst($this->sqlData["suffix"]) : ""; 
    }

    public function GetOccupation() {
        return isset($this->sqlData['occupation']) ? ucfirst($this->sqlData["occupation"]) : ""; 
    }

    public function GetEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }
    
}
?>