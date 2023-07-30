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


        // if($this->sqlData == null){

        //     $pending_enrollees_id = $pending_enrollees_id;

        //     $query = $this->con->prepare("SELECT * FROM pending_enrollees
        //         WHERE firstname=:firstname");

        //     $query->bindValue(":firstname", $firstname);
        //     $query->execute();

        //     $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        // }
    }

    public function GetPendingFirstName() {
        return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
    }

    public function GetPendingAdmissionStatus() {
        return isset($this->sqlData['admission_status']) ? ucfirst($this->sqlData["admission_status"]) : ""; 
    }
    public function GetPendingID() {
        return isset($this->sqlData['pending_enrollees_id']) ? $this->sqlData["pending_enrollees_id"] : ""; 
    }
    public function GetPendingLastName() {
        return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
    }

    public function GetPendingMiddleName() {
        return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
    }

    public function GetPendingLRN() {
        return isset($this->sqlData['lrn']) ? $this->sqlData["lrn"] : ""; 
    }

    public function GetPendingEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }
    public function GetPendingSuffix() {
        return isset($this->sqlData['suffix']) ? $this->sqlData["suffix"] : ""; 
    }

    public function GetCourseLevel() {
        return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : 0; 
    }

    public function GetPendingToken() {
        return isset($this->sqlData['token']) ? $this->sqlData["token"] : ""; 
    }

    public function GetPendingType() {
        return isset($this->sqlData['type']) ? $this->sqlData["type"] : ""; 
    }

    public function GetPendingNationality() {
        return isset($this->sqlData['nationality']) ? ucfirst($this->sqlData["nationality"]) : ""; 
    }

    public function GetPendingGender() {
        return isset($this->sqlData['sex']) ? ucfirst($this->sqlData["sex"]) : ""; 
    }

    public function GetPendingIsFinished() {
        return isset($this->sqlData['is_finished']) ? $this->sqlData["is_finished"] : null; 
    }


    public function GetPendingBirthday() {
        return isset($this->sqlData['birthday']) ? $this->sqlData["birthday"] : ""; 
    }
    public function GetPendingBirthplace() {
        return isset($this->sqlData['birthplace']) ? ucfirst($this->sqlData["birthplace"]) : ""; 
    }

    public function GetPendingReligion() {
        return isset($this->sqlData['religion']) ? ucfirst($this->sqlData["religion"]) : ""; 
    }

    public function GetPendingCivilStatus() {
        return isset($this->sqlData['civil_status']) ? ucfirst($this->sqlData["civil_status"]) : ""; 
    }

    public function GetPendingAddress() {
        return isset($this->sqlData['address']) ? ucfirst($this->sqlData["address"]) : ""; 
    }

    public function GetPendingContactNumber() {
        return isset($this->sqlData['contact_number']) ? ucfirst($this->sqlData["contact_number"]) : ""; 
    }

    public function GetPendingProgramId() {
        return isset($this->sqlData['program_id']) ? $this->sqlData["program_id"] : 0; 
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

   public function UpdateStudentInformation(
        $firstname,
        $lastname,
        $middle_name,
        $suffix,
        $civil_status,
        $nationality,
        $contact_number,
        $birthday,
        $birthplace,
        $age,
        $sex,
        $address,
        $lrn,
        $religion,
        $pending_enrollees_id
    ) {


        $query = $this->con->prepare("UPDATE pending_enrollees SET 
            firstname = :firstname,
            lastname = :lastname,
            middle_name = :middle_name,
            suffix = :suffix,
            civil_status = :civil_status,
            nationality = :nationality,
            contact_number = :contact_number,
            birthday = :birthday,
            birthplace = :birthplace,
            age = :age,
            sex = :sex,
            address = :address,
            lrn = :lrn,
            religion = :religion
            WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":middle_name", $middle_name);
        $query->bindParam(":suffix", $suffix);
        $query->bindParam(":civil_status", $civil_status);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":contact_number", $contact_number);
        $query->bindParam(":birthday", $birthday);
        $query->bindParam(":birthplace", $birthplace);
        $query->bindParam(":age", $age);
        $query->bindParam(":sex", $sex);
        $query->bindParam(":address", $address);
        $query->bindParam(":lrn", $lrn);
        $query->bindParam(":religion", $religion);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);


        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
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

    public function PendingCourseLevelDropdown($pending_type,
        $course_level){

        $output = "";
        if ($pending_type == "SHS") {
            $output .= "
                <select style='width: 450px' class='form-control' name='choose_level' id='choose_level'>
                    <option value='11' " . ($course_level == '11' ? 'selected' : '') . ">Grade 11</option>
                    <option value='12' " . ($course_level == '12' ? 'selected' : '') . ">Grade 12</option> 
                </select>
            ";
        } else if ($pending_type == "Tertiary") {
            $output .= "
                <select style='width: 450px' class='form-control' name='choose_level' id='choose_level'>
                    <option value='1' " . ($course_level == '1' ? 'selected' : '') . ">1st Year</option>
                    <option value='2' " . ($course_level == '2' ? 'selected' : '') . ">2nd Year</option>
                    <option value='3' " . ($course_level == '3' ? 'selected' : '') . ">3rd Year</option>
                    <option value='4' " . ($course_level == '4' ? 'selected' : '') . ">4th Year</option>
                </select>
            ";
        }else{
            $output .= "
                <select style='width: 450px' class='form-control' name='choose_level' id='choose_level'>
                </select>
            ";
        }

        return $output;
    }

    public function PreferredCourseUpdate($selected_admission_type,
            $selected_department_type, $selected_program_id,
            $selected_course_level, $pending_enrollees_id){

        $admission_status = $selected_admission_type == "New" ? "Standard" 
            : ($selected_admission_type == "Transferee" ? "Transferee" : "");
        
        $type = $selected_department_type == "Senior High School" ? "SHS" 
            : ($selected_department_type == "Tertiary" ? "Tertiary" : "");
        

        $query = $this->con->prepare("UPDATE pending_enrollees
            SET admission_status=:admission_status,
                type=:type,
                program_id=:program_id,
                course_level=:course_level
            
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindParam(":admission_status", $admission_status);
        $query->bindParam(":type", $type);
        $query->bindParam(":program_id", $selected_program_id);
        $query->bindParam(":course_level", $selected_course_level);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateEnrollmentDetails($type, $course_level,
            $program_id, $pending_enrollees_id){
        
        $query = $this->con->prepare("UPDATE pending_enrollees
            SET type=:type,
                course_level=:course_level,
                program_id=:program_id
            
            WHERE pending_enrollees_id=:pending_enrollees_id
        ");

        $query->bindParam(":type", $type);
        $query->bindParam(":course_level", $course_level);
        $query->bindParam(":program_id", $program_id);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

        if($query->execute() && $query->rowCount() > 0){
            return true;
        }

        return false;

    }

    public function UpdatePendingEnrolleeDetails(
        $pending_enrollees_id,
        $firstname,
        $lastname,
        $middle_name,
        $suffix,
        $civil_status,
        $nationality,
        $sex,
        $birthday,
        $birthplace,
        $religion,
        $address,
        $contact_number,
        $email) {

    $query = $this->con->prepare("UPDATE pending_enrollees
        SET firstname=:firstname,
            lastname=:lastname,
            middle_name=:middle_name,
            suffix=:suffix,
            civil_status=:civil_status,
            nationality=:nationality,
            sex=:sex,
            birthday=:birthday,
            birthplace=:birthplace,
            religion=:religion,
            address=:address,
            contact_number=:contact_number,
            email=:email
            
        WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":middle_name", $middle_name);
        $query->bindParam(":suffix", $suffix);
        $query->bindParam(":civil_status", $civil_status);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":sex", $sex);
        $query->bindParam(":birthday", $birthday);
        $query->bindParam(":birthplace", $birthplace);
        $query->bindParam(":religion", $religion);
        $query->bindParam(":address", $address);
        $query->bindParam(":contact_number", $contact_number);
        $query->bindParam(":email", $email);
        
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0) {
            return true;
        }

        return false;
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
        $sql_parent->bindParam(":pending_enrollees_id", $pending_enrollees_id);
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
            $mi, $lname, $contact_number, $parent_email, $parent_occupation, $parent_suffix, $relationship,
            
            $father_firstname, $father_lastname, $father_middle, $father_contact_number, $father_email,
                $father_occupation, $father_suffix,
                
                
            $mother_firstname, $mother_lastname, $mother_middle, $mother_contact_number, $mother_email,
                $mother_occupation, $mother_suffix){

        if($this->CheckParentExists($pending_enrollees_id) == false){

            $query = $this->con->prepare("INSERT INTO parent 
                (pending_enrollees_id, firstname, lastname, middle_name, contact_number, email, suffix, occupation,
                father_firstname, father_lastname, father_middle, father_contact_number, father_email,
                father_occupation, father_suffix,
                mother_firstname, mother_lastname, mother_middle, mother_contact_number, mother_email,
                mother_occupation, mother_suffix, relationship) 
                VALUES (:pending_enrollees_id, :firstname, :lastname, :middle_name, :contact_number, :email, :suffix, :occupation,
                :father_firstname, :father_lastname, :father_middle, :father_contact_number, :father_email,
                :father_occupation, :father_suffix,
                :mother_firstname, :mother_lastname, :mother_middle, :mother_contact_number, :mother_email,
                :mother_occupation, :mother_suffix, :relationship)");
            
            $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
            $query->bindParam(":firstname", $fname);
            $query->bindParam(":middle_name", $mi);
            $query->bindParam(":lastname", $lname);
            $query->bindParam(":contact_number", $contact_number);
            $query->bindParam(":email", $parent_email);
            $query->bindParam(":occupation", $parent_occupation);
            $query->bindParam(":suffix", $parent_suffix);
            $query->bindParam(":relationship", $relationship);

            $query->bindParam(":father_firstname", $father_firstname);
            $query->bindParam(":father_lastname", $father_lastname);
            $query->bindParam(":father_middle", $father_middle);
            $query->bindParam(":father_contact_number", $father_contact_number);
            $query->bindParam(":father_occupation", $father_occupation);
            $query->bindParam(":father_email", $father_email);
            $query->bindParam(":father_suffix", $father_suffix);

            $query->bindParam(":mother_firstname", $mother_firstname);
            $query->bindParam(":mother_lastname", $mother_lastname);
            $query->bindParam(":mother_middle", $mother_middle);
            $query->bindParam(":mother_contact_number", $mother_contact_number);
            $query->bindParam(":mother_occupation", $mother_occupation);
            $query->bindParam(":mother_email", $mother_email);
            $query->bindParam(":mother_suffix", $mother_suffix);

            return $query->execute();
        }
        else if($this->CheckParentExists($pending_enrollees_id) === true){

            # pending_enrollees_id in the db should be UNIQUE.
            # UPDATE

            $query = $this->con->prepare("UPDATE parent SET
                firstname = :firstname,
                lastname = :lastname,
                middle_name = :middle_name,
                contact_number = :contact_number,
                email = :email,
                suffix = :suffix,
                occupation = :occupation,
                relationship = :relationship,
                father_firstname = :father_firstname,
                father_lastname = :father_lastname,
                father_middle = :father_middle,
                father_contact_number = :father_contact_number,
                father_email = :father_email,
                father_occupation = :father_occupation,
                father_suffix = :father_suffix,
                mother_firstname = :mother_firstname,
                mother_lastname = :mother_lastname,
                mother_middle = :mother_middle,
                mother_contact_number = :mother_contact_number,
                mother_email = :mother_email,
                mother_occupation = :mother_occupation,
                mother_suffix = :mother_suffix
                WHERE pending_enrollees_id = :pending_enrollees_id");

            $query->bindParam(":firstname", $fname);
            $query->bindParam(":lastname", $lname);
            $query->bindParam(":middle_name", $mi);
            $query->bindParam(":contact_number", $contact_number);
            $query->bindParam(":email", $parent_email);
            $query->bindParam(":suffix", $parent_suffix);
            $query->bindParam(":occupation", $parent_occupation);
            $query->bindParam(":relationship", $relationship);
            $query->bindParam(":father_firstname", $father_firstname);
            $query->bindParam(":father_lastname", $father_lastname);
            $query->bindParam(":father_middle", $father_middle);
            $query->bindParam(":father_contact_number", $father_contact_number);
            $query->bindParam(":father_email", $father_email);
            $query->bindParam(":father_occupation", $father_occupation);
            $query->bindParam(":father_suffix", $father_suffix);
            $query->bindParam(":mother_firstname", $mother_firstname);
            $query->bindParam(":mother_lastname", $mother_lastname);
            $query->bindParam(":mother_middle", $mother_middle);
            $query->bindParam(":mother_contact_number", $mother_contact_number);
            $query->bindParam(":mother_email", $mother_email);
            $query->bindParam(":mother_occupation", $mother_occupation);
            $query->bindParam(":mother_suffix", $mother_suffix);
            $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

            return $query->execute();
        }
        
        

    }

    public function ValidateDetailsUpdate($pending_enrollees_id, $fname,
            $mi, $lname, $contact_number, $parent_email, $parent_occupation, $parent_suffix, $relationship,
            
            $father_firstname, $father_lastname, $father_middle, $father_contact_number, $father_email,
                $father_occupation, $father_suffix,
                
                
            $mother_firstname, $mother_lastname, $mother_middle, $mother_contact_number, $mother_email,
                $mother_occupation, $mother_suffix){

        

        if($this->CheckParentExists($pending_enrollees_id) === true){

            # pending_enrollees_id in the db should be UNIQUE.
            # UPDATE

            $is_finished = 1;



            $query = $this->con->prepare("UPDATE parent SET
                firstname = :firstname,
                lastname = :lastname,
                middle_name = :middle_name,
                contact_number = :contact_number,
                email = :email,
                suffix = :suffix,
                occupation = :occupation,
                relationship = :relationship,
                father_firstname = :father_firstname,
                father_lastname = :father_lastname,
                father_middle = :father_middle,
                father_contact_number = :father_contact_number,
                father_email = :father_email,
                father_occupation = :father_occupation,
                father_suffix = :father_suffix,
                mother_firstname = :mother_firstname,
                mother_lastname = :mother_lastname,
                mother_middle = :mother_middle,
                mother_contact_number = :mother_contact_number,
                mother_email = :mother_email,
                mother_occupation = :mother_occupation,
                mother_suffix = :mother_suffix
                -- is_finished = :is_finished
                
                WHERE pending_enrollees_id = :pending_enrollees_id");

            $query->bindParam(":firstname", $fname);
            $query->bindParam(":lastname", $lname);
            $query->bindParam(":middle_name", $mi);
            $query->bindParam(":contact_number", $contact_number);
            $query->bindParam(":email", $parent_email);
            $query->bindParam(":suffix", $parent_suffix);
            $query->bindParam(":occupation", $parent_occupation);
            $query->bindParam(":relationship", $relationship);
            $query->bindParam(":father_firstname", $father_firstname);
            $query->bindParam(":father_lastname", $father_lastname);
            $query->bindParam(":father_middle", $father_middle);
            $query->bindParam(":father_contact_number", $father_contact_number);
            $query->bindParam(":father_email", $father_email);
            $query->bindParam(":father_occupation", $father_occupation);
            $query->bindParam(":father_suffix", $father_suffix);
            $query->bindParam(":mother_firstname", $mother_firstname);
            $query->bindParam(":mother_lastname", $mother_lastname);
            $query->bindParam(":mother_middle", $mother_middle);
            $query->bindParam(":mother_contact_number", $mother_contact_number);
            $query->bindParam(":mother_email", $mother_email);
            $query->bindParam(":mother_occupation", $mother_occupation);
            $query->bindParam(":mother_suffix", $mother_suffix);
            // $query->bindParam(":is_finished", $is_finished);
            $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

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

    public function GetParentMatchPendingStudentId($pending_enrollees_id, $student_id){

        $query = $this->con->prepare("SELECT parent_id 
        
            FROM parent
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){

            $row = $query->fetch(PDO::FETCH_ASSOC);

            $parent_id = $row['parent_id'];

            $update = $this->con->prepare("UPDATE parent
                SET student_id=:update_student_id
                WHERE parent_id=:parent_id
                -- AND student_id=0
                -- AND pending_enrollees_id=$pending_enrollees_id
                ");
            
            $update->bindValue(":update_student_id", $student_id);
            $update->bindValue(":parent_id", $parent_id);
            
            // $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            return $update->execute();
        }
        return false;
    }


    public function SetPendingApprove($pending_enrollees_id) {

        $query = $this->con->prepare("UPDATE pending_enrollees
        
            SET student_status = :student_status
            WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindValue(":student_status", "APPROVED");

        return $query->execute();
    }

    public function MarkAsValidated($pending_enrollees_id) {



        $query = $this->con->prepare("UPDATE pending_enrollees
        
            SET is_finished = :set_is_finished

            WHERE pending_enrollees_id = :pending_enrollees_id
            AND is_finished = 0
            AND activated = 1
            AND student_status != 'APPROVED'
            ");

        $query->bindValue(":set_is_finished", 1);
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id, PDO::PARAM_INT);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }else{
            echo "qwe";
        }
        return false;
    }
    
    
    public function Check($pending_enrollees_id) {

    }
}

?>