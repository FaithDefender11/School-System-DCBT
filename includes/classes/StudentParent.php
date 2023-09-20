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

    public function GetParentID() {
        return isset($this->sqlData['parent_id']) ? ucfirst($this->sqlData["parent_id"]) : ""; 
    }
    public function GetFirstName() {
        return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
    }

    public function GetGuardianRelationship() {
        return isset($this->sqlData['relationship']) ? ucfirst($this->sqlData["relationship"]) : ""; 
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

    public function GetFatherFirstName() {
        return isset($this->sqlData['father_firstname']) ? $this->sqlData["father_firstname"] : ""; 
    }
    public function GetFatherLastName() {
        return isset($this->sqlData['father_lastname']) ? $this->sqlData["father_lastname"] : ""; 
    }

    public function GetFatherMiddleName() {
        return isset($this->sqlData['father_middle']) ? $this->sqlData["father_middle"] : ""; 
    }

    public function GetFatherSuffix() {
        return isset($this->sqlData['father_suffix']) ? $this->sqlData["father_suffix"] : ""; 
    }

    public function GetFatherOccupation() {
        return isset($this->sqlData['father_occupation']) ? $this->sqlData["father_occupation"] : ""; 
    }

    public function GetFatherContactNumber() {
        return isset($this->sqlData['father_contact_number']) ? $this->sqlData["father_contact_number"] : ""; 
    }
    public function GetFatherEmail() {
        return isset($this->sqlData['father_email']) ? $this->sqlData["father_email"] : ""; 
    }


    public function GetMotherFirstName() {
        return isset($this->sqlData['mother_firstname']) ? $this->sqlData["mother_firstname"] : ""; 
    }
    public function GetMotherLastName() {
        return isset($this->sqlData['mother_lastname']) ? $this->sqlData["mother_lastname"] : ""; 
    }

    public function GetMotherMiddleName() {
        return isset($this->sqlData['mother_middle']) ? $this->sqlData["mother_middle"] : ""; 
    }

    public function GetMotherSuffix() {
        return isset($this->sqlData['mother_suffix']) ? $this->sqlData["mother_suffix"] : ""; 
    }

    public function GetMotherOccupation() {
        return isset($this->sqlData['mother_occupation']) ? $this->sqlData["mother_occupation"] : ""; 
    }

    public function GetMotherContactNumber() {
        return isset($this->sqlData['mother_contact_number']) ? $this->sqlData["mother_contact_number"] : ""; 
    }
    public function GetMotherEmail() {
        return isset($this->sqlData['mother_email']) ? $this->sqlData["mother_email"] : ""; 
    }



    

    public function UpdateStudentParent($student_id, $parent_id, $firstname, $lastname,
        $middle_name, $suffix, $contact_number,
        $email, $occupation, $relationship,
        $father_firstname,
        $father_lastname,
        $father_middle_name,
        $father_suffix,
        $father_contact_number,
        $father_email,
        $father_occupation,
        $mother_firstname,
        $mother_lastname,
        $mother_middle_name,
        $mother_suffix,
        $mother_contact_number,
        $mother_email,
        $mother_occupation)
         {
 

        $query = $this->con->prepare("UPDATE parent 
            SET 
                firstname=:firstname,
                lastname=:lastname,
                middle_name=:middle_name,
                suffix=:suffix,

                contact_number=:contact_number,
                email=:email,
                occupation=:occupation,
                relationship=:relationship,


                father_firstname=:father_firstname,
                father_lastname=:father_lastname,
                father_middle=:father_middle,
                father_suffix=:father_suffix,
                father_contact_number=:father_contact_number,
                father_email=:father_email,
                father_occupation=:father_occupation,

                mother_firstname=:mother_firstname,
                mother_lastname=:mother_lastname,
                mother_middle=:mother_middle,
                mother_suffix=:mother_suffix,
                mother_contact_number=:mother_contact_number,
                mother_email=:mother_email,
                mother_occupation=:mother_occupation

                
            WHERE student_id=:student_id
            AND parent_id=:parent_id

            -- AND active=
        ");

        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":middle_name", $middle_name);
        $query->bindParam(":suffix", $suffix);

        $query->bindParam(":contact_number", $contact_number);
        $query->bindParam(":email", $email);
        $query->bindParam(":occupation", $occupation);
        $query->bindParam(":relationship", $relationship);


        $query->bindParam(":father_firstname", $father_firstname);
        $query->bindParam(":father_lastname", $father_lastname);
        $query->bindParam(":father_middle", $father_middle_name);
        $query->bindParam(":father_suffix", $father_suffix);
        $query->bindParam(":father_contact_number", $father_contact_number);
        $query->bindParam(":father_email", $father_email);
        $query->bindParam(":father_occupation", $father_occupation);


        $query->bindParam(":mother_firstname", $mother_firstname);
        $query->bindParam(":mother_lastname", $mother_lastname);
        $query->bindParam(":mother_middle", $mother_middle_name);
        $query->bindParam(":mother_suffix", $mother_suffix);
        $query->bindParam(":mother_contact_number", $mother_contact_number);
        $query->bindParam(":mother_email", $mother_email);
        $query->bindParam(":mother_occupation", $mother_occupation);

 
        $query->bindParam(":parent_id", $parent_id);
        $query->bindParam(":student_id", $student_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;

    }



}
?>