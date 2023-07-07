<?php

    class Pending{

    private $con, $sqlData, $pending_enrollees_id;

    public function __construct($con, $pending_enrollees_id = null){

        $this->con = $con;
        $this->pending_enrollees_id = $pending_enrollees_id;

        $query = $this->con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetPendingFirstName() {
        return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
    }

    public function GetPendingLastName() {
        return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
    }

    public function GetPendingMiddleName() {
        return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
    }

    public function GetPendingEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }

    public function GetPendingToken() {
        return isset($this->sqlData['token']) ? $this->sqlData["token"] : ""; 
    }

    public function GetIsActivated() {
        return isset($this->sqlData['activated']) && $this->sqlData['activated'] == 1 ? true : false;
    }

    public function GetIsFinished() {
        return isset($this->sqlData['is_finished']) && $this->sqlData['is_finished'] == 1 ? true : false;
    }


    public function PendingFormEmail($fname, $lname, $mi,
        $password, $email_address, $token){

        $expiration_time = strtotime("+5 minutes");
        $expiration_time = date('Y-m-d H:i:s', $expiration_time);

        $query = $this->con->prepare("INSERT INTO pending_enrollees 
            (firstname, lastname, middle_name, password, email, token, expiration_time) 
            VALUES (:firstname, :lastname, :middle_name, :password, :email, :token, :expiration_time)");
        
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $query->bindValue(":firstname", $fname);
        $query->bindValue(":lastname", $lname);
        $query->bindValue(":middle_name", $mi);
        $query->bindValue(":password", $hash_password);
        $query->bindValue(":email", $email_address);
        $query->bindValue(":token", $token);
        $query->bindValue(":expiration_time", $expiration_time);

        $execute =  $query->execute();

        return $execute;
    }


    public function CreateRegisterStrand($program_id = null){

        $query = $this->con->prepare("SELECT * FROM program
            -- WHERE department_id != 1
            ");

        $query->execute();

        $html = "
            <div class='form-group'>
                <select class='form-control' name='STRAND' required>
                    <option value=''>Choose Strand</option>"; // Add required attribute to the <select> tag

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $row_program_id = $row['program_id'];

                if ($row['program_name']) {
                    // $program_name = "STEM";
                }
                $selected = ($row_program_id == $program_id) ? 'selected' : '';

                $html .= "
                    <option $selected value='" . $row['program_id'] . "'>" . $row['program_name'] . "</option>
                ";
            }
        }
        $html .= "</select>
                </div>";

        return $html;
    }

    public function UpdatePendingNewStep1($admission_status, $type,
            $program_id, $pending_enrollees_id){
        
        $db_status = "";
        if($admission_status == "Regular"){
            $db_status = "Standard";
        }else if($admission_status == "Transferee"){
            $db_status = "Transferee";
        }

        $query = $this->con->prepare("UPDATE pending_enrollees

            SET 
                admission_status=:admission_status,
                -- student_status=:student_status,
                type=:type, program_id=:program_id
            
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindValue(":admission_status", $db_status);
        // $query->bindValue(":student_status", $student_status);
        $query->bindValue(":type", $type);
        $query->bindValue(":program_id", $program_id);
        $execute =  $query->execute();
        
        return $execute;
    }

    public function UpdatePendingNewStep2($pending_enrollees_id, $firstname, 
        $middle_name, $lastName, $civil_status, $nationality, $sex,
        $birthday, $birthplace, $religion, $address,
            $contact_number, $email, $age, $lrn, $suffix) {
            
            $query = $this->con->prepare("UPDATE pending_enrollees
                    SET firstname=:firstname,
                        middle_name=:middle_name,
                        lastName=:lastName,
                        civil_status=:civil_status,
                        nationality=:nationality,
                        sex=:sex,
                        birthday=:birthday,
                        birthplace=:birthplace,
                        religion=:religion,
                        address=:address,
                        contact_number=:contact_number,
                        email=:email,
                        age=:age,
                        lrn=:lrn,
                        suffix=:suffix
                    WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindValue(":firstname", $firstname);
        $query->bindValue(":middle_name", $middle_name);
        $query->bindValue(":lastName", $lastName);
        $query->bindValue(":civil_status", $civil_status);
        $query->bindValue(":nationality", $nationality);
        $query->bindValue(":sex", $sex);
        $query->bindValue(":birthday", $birthday);
        $query->bindValue(":birthplace", $birthplace);
        $query->bindValue(":religion", $religion);
        $query->bindValue(":address", $address);
        $query->bindValue(":contact_number", $contact_number);
        $query->bindValue(":email", $email);
        $query->bindValue(":age", $age);
        $query->bindValue(":lrn", $lrn);
        $query->bindValue(":suffix", $suffix);

        $execute = $query->execute();

        return $execute;
    }

    public function CheckIfSuppliedPendingStep2StudentInfo($pending_enrollees_id) {
            
        $query = $this->con->prepare("SELECT * FROM pending_enrollees
        
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND firstname != ''
            AND middle_name != ''
            AND lastname != ''
            AND civil_status != ''
            AND nationality != ''
            AND sex != ''
            AND birthday IS NOT NULL
            AND birthplace != ''
            AND religion != ''
            AND address != ''
            AND contact_number != ''
            AND email != ''
            AND age != 0
            AND lrn != ''
            -- AND suffix != ''
            ");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0)
            return true;

        return false;
    }

    public function CheckIfSuppliedPendingStep2ParentData($pending_enrollees_id) {
            
        $get_parent = $this->con->prepare("SELECT * FROM parent
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND firstname != ''
            AND middle_name != ''
            AND lastname != ''
            AND contact_number != ''
            AND email != ''
            ");
                
        $get_parent->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $get_parent->execute();

        if($get_parent->rowCount() > 0)
            return true;

        return false;
    }



    public function UpdatePendingNewStep3($pending_enrollees_id, $firstname, 
        $middle_name, $lastName, $civil_status, $nationality, $sex,
        $birthday, $birthplace, $religion, $address,
        $contact_number, $email, $lrn) {
            
            $is_finished = 1;

            $query = $this->con->prepare("UPDATE pending_enrollees
                    SET firstname=:firstname,
                        middle_name=:middle_name,
                        lastName=:lastName,
                        civil_status=:civil_status,
                        nationality=:nationality,
                        sex=:sex,
                        birthday=:birthday,
                        birthplace=:birthplace,
                        religion=:religion,
                        address=:address,
                        contact_number=:contact_number,
                        email=:email,
                        lrn=:lrn,
                        is_finished=:is_finished
                    WHERE pending_enrollees_id=:pending_enrollees_id");

            $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $query->bindValue(":firstname", $firstname);
            $query->bindValue(":middle_name", $middle_name);
            $query->bindValue(":lastName", $lastName);
            $query->bindValue(":civil_status", $civil_status);
            $query->bindValue(":nationality", $nationality);
            $query->bindValue(":sex", $sex);
            $query->bindValue(":birthday", $birthday);
            $query->bindValue(":birthplace", $birthplace);
            $query->bindValue(":religion", $religion);
            $query->bindValue(":address", $address);
            $query->bindValue(":contact_number", $contact_number);
            $query->bindValue(":email", $email);
            $query->bindValue(":lrn", $lrn);
            $query->bindValue(":is_finished", $is_finished);

            $execute = $query->execute();

            return $execute;
        
    }

    public function CheckFormStep1Complete($pending_enrollees_id){

        // $sql = $this->con->prepare("SELECT * FROM pending_enrollees
        //     WHERE firstname != ''
        //     AND lastname != ''
        //     AND middle_name != ''
        //     AND email != ''
        //     AND token != ''
        //     AND is_finished = 0
        //     AND activated = 1
        //     AND password != ''
        //     AND program_id != 0
        //     AND student_status != ''
        //     AND type != ''
        //     AND pending_enrollees_id =:pending_enrollees_id
        //     ");
    
        // $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        // $sql->execute();

        // 

        $sql = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE type IS NOT NULL
            AND admission_status != ''
            AND program_id != 0
            AND pending_enrollees_id =:pending_enrollees_id

            ");
    
        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }

    }

    public function CheckAllStepsComplete($pending_enrollees_id){

        # PARENT

        $sql_parent = $this->con->prepare("SELECT * FROM parent
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND firstname != '' 
            AND middle_name != '' 
            AND lastname != '' 
            AND contact_number != '' 
            ");
        $sql_parent->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql_parent->execute();


        $sql = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE firstname != ''
            AND lastname != ''
            AND middle_name != ''
            AND email != ''
            AND token != ''
            AND is_finished = 0
            AND activated = 1
            AND password != ''
            AND program_id != 0
            AND civil_status != ''
            AND nationality != ''
            AND contact_number != ''
            AND birthday != ''
            AND age != 0
            -- AND student_status != ''
            AND admission_status != ''
            AND address != ''
            AND lrn != ''
            AND type != ''
            AND religion != ''
            AND birthplace != ''
            ");
    
        $sql->execute();
        

        if($sql_parent->rowCount() > 0){
            if($sql->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }

    }

    public function UpdateParentData($parent_id, $fname, $lname,
        $mi, $contact_number) {

        $query = $this->con->prepare("UPDATE parent 
        
            SET firstname = :firstname, 
                lastname = :lastname,
                middle_name = :middle_name, 
                contact_number = :contact_number 

            WHERE parent_id = :parent_id");

        $query->bindParam(":parent_id", $parent_id);
        $query->bindParam(":firstname", $fname);
        $query->bindParam(":middle_name", $mi);
        $query->bindParam(":lastname", $lname);
        $query->bindParam(":contact_number", $contact_number);

        return $query->execute();
    }

    public function CreateParentData($pending_enrollees_id, $fname,
            $lname, $mi, $contact_number, $parent_email, $parent_occupation, $parent_suffix){

        if($this->CheckParentExists($pending_enrollees_id) == false){

            $query = $this->con->prepare("INSERT INTO parent 
                (pending_enrollees_id, firstname, lastname, middle_name, contact_number, email, suffix, occupation) 
                VALUES (:pending_enrollees_id, :firstname, :lastname, :middle_name, :contact_number, :email, :suffix, :occupation)");
            
            $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $query->bindValue(":firstname", $fname);
            $query->bindValue(":middle_name", $mi);
            $query->bindValue(":lastname", $lname);
            $query->bindValue(":contact_number", $contact_number);
            $query->bindValue(":email", $parent_email);
            $query->bindValue(":occupation", $parent_occupation);
            $query->bindValue(":suffix", $parent_suffix);

            return $query->execute();
        }
        
        else{
            // echo "Parent Exists";
            return false;
        }

    }

    public function CreateParentDatav2($pending_enrollees_id, $fname,
            $mi, $lname, $contact_number, $parent_email, $parent_occupation, $parent_suffix){

        if($this->CheckParentExists($pending_enrollees_id) == false){

            $query = $this->con->prepare("INSERT INTO parent 
                (pending_enrollees_id, firstname, lastname, middle_name, contact_number, email, suffix, occupation) 
                VALUES (:pending_enrollees_id, :firstname, :lastname, :middle_name, :contact_number, :email, :suffix, :occupation)");
            
            $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $query->bindValue(":firstname", $fname);
            $query->bindValue(":middle_name", $mi);
            $query->bindValue(":lastname", $lname);
            $query->bindValue(":contact_number", $contact_number);
            $query->bindValue(":email", $parent_email);
            $query->bindValue(":occupation", $parent_occupation);
            $query->bindValue(":suffix", $parent_suffix);

            return $query->execute();
        }
        else if($this->CheckParentExists($pending_enrollees_id) === true){

            # pending_enrollees_id in the db should be UNIQUE.
            # UPDATE

            $query = $this->con->prepare("UPDATE parent 
                SET firstname=:firstname,
                    lastname=:lastname,
                    middle_name=:middle_name,
                    contact_number=:contact_number,
                    email=:email,
                    suffix=:suffix,
                    occupation=:occupation
                WHERE pending_enrollees_id=:pending_enrollees_id");

            $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
            $query->bindParam(":firstname", $fname);
            $query->bindParam(":middle_name", $mi);
            $query->bindParam(":lastname", $lname);
            $query->bindParam(":contact_number", $contact_number);
            $query->bindParam(":email", $parent_email);
            $query->bindParam(":suffix", $parent_suffix);
            $query->bindParam(":occupation", $parent_occupation);

            return $query->execute();
        }
        
        

    }

    public function CheckInitialStatus($pending_enrollees_id){
        
        $sql = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND activated=1
            AND firstname != ''
            AND lastname != ''
            AND password != ''
            AND email != ''
            ");
        
        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;

    }
    public function CheckParentExists($pending_enrollees_id){

        $sql = $this->con->prepare("SELECT parent_id FROM parent
            WHERE pending_enrollees_id=:pending_enrollees_id");
        
        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function CalculateAge($b_day){

        $age = -1;
    
        $birthdate = $b_day;

        $birth_date = new DateTime($birthdate);

        $current_date = new DateTime();

        $interval = $current_date->diff($birth_date);

        $age = $interval->y;

        return $age;
    }

    public function CheckStudentFinishedForm($pending_enrollees_id){

        $sql = $this->con->prepare("SELECT is_finished FROM pending_enrollees
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND is_finished=1
            ");
        
        $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }
}

?>