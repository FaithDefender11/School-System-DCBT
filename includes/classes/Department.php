<?php

    class Department{

        private $con, $department_id, $sqlData;


        public function __construct($con, $department_id = null){
            $this->con = $con;
            $this->department_id = $department_id;

            $query = $this->con->prepare("SELECT * FROM department
                 WHERE department_id=:department_id");

            $query->bindValue(":department_id", $department_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetDepartmentName(){
            return isset($this->sqlData['department_name']) ? ucfirst($this->sqlData["department_name"]) : ""; 
        }

        public function GetDepartmentIdByName($department_name){

            $query = $this->con->prepare("SELECT department_id FROM department
                WHERE department_name=:department_name");

            $query->bindParam(":department_name", $department_name);
            $query->execute();

            if($query->rowCount() > 0){
                return $query->fetchColumn();
            }

            return 0;
        }

     



        public function GetOfferedDepartment(){

            $query = $this->con->prepare("SELECT department_id, department_name FROM department
                WHERE department_name=:department_name1
                OR department_name=:department_name2
                ");

            $query->bindValue(":department_name1", "Senior High School");
            $query->bindValue(":department_name2", "Tertiary");

            $query->execute();

            if($query->rowCount() > 0){
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        }

        public function CreateDepartmentDropdown($department_id = null){

            $SHS = "Senior High School";
            $Tertiary = "Tertiary";

            $query = $this->con->prepare("SELECT * FROM department
                WHERE department_name=:department_name1
                OR department_name=:department_name2
            ");

            $query->bindParam(":department_name1", $SHS);
            $query->bindParam(":department_name2", $Tertiary);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Department</label>

                    <select id='department_id' class='form-control' name='department_id'>";

                $html .= "<option value='Course-Section' disabled selected>Choose</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $selected = "";
                    if($row['department_id'] == $department_id){
                        $selected = "selected";
                    }
                    $html .= "
                        <option value='".$row['department_id']."' $selected>".$row['department_name']."</option>
                    ";
                }

                $html .= "</select>
                        </div>";
                return $html;

            }
 
            return "";
             
        }
    }

?>