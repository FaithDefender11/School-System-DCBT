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
        return isset($this->sqlData['lrn']) ? $this->sqlData["lrn"] : NULL; 
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

    public function FinishedFormAutoRedirect(){

        if($this->GetPendingIsFinished() == 1){
            // echo "qwe";
            header("Location: profile.php?fill_up_state=finished");
            exit();
        }
    }

    public function GetEnrolleeStatus() {
        return isset($this->sqlData['student_status']) ? ucfirst($this->sqlData["student_status"]) : ""; 
    }

    public function GetPendingEnrollmentStatus() {
        return isset($this->sqlData['enrollment_status']) ? ucfirst($this->sqlData["enrollment_status"]) : ""; 
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

    public function GetAcceptanceCondition() {
        return isset($this->sqlData['condition_acceptance']) ? $this->sqlData["condition_acceptance"] : 0; 
    }


    public function GetIsActivated() {
        return isset($this->sqlData['activated']) && $this->sqlData['activated'] == 1 ? true : false;
    }

    public function GetIsFinished() {
        return isset($this->sqlData['is_finished']) && $this->sqlData['is_finished'] == 1 ? true : false;
    }

    public function VerifyNewEnrolleeCredentials($email, $password){

        # If New Enrollee has been enrolled, His login route would be
        # in Student Enrollment Login student_enrollment.
        # Using their username generated by the system.
        
        $arr = [];

        $email = strtolower($email);
        
        $activated = 1;

        $query = $this->con->prepare("SELECT 

            pending_enrollees_id,
            firstname,
            password,
            email,
            is_finished
        
            FROM pending_enrollees

            WHERE student_status !=:student_status
            AND email=:email
            AND activated=:activated
            LIMIT 1");
    
        $query->bindValue(":student_status", "REJECTED");
        $query->bindParam(":email", $email);
        $query->bindParam(":activated", $activated);
        $query->execute();

        if($query->rowCount() > 0){

            $userPending = $query->fetch(PDO::FETCH_ASSOC); 
            $user_password = $userPending['password'];

            $email = $userPending['email'];
            $firstname = $userPending['firstname'];
            $is_finished = $userPending['is_finished'];
            
            if($userPending && password_verify($password, $user_password)) {
                
                // Password is correct, log in the user
                array_push($arr, $firstname); // [0]
                array_push($arr, true);
                array_push($arr, "pending");
                array_push($arr,  $userPending['pending_enrollees_id']);
                array_push($arr,  $email);
                array_push($arr,  $is_finished); // [5]
            }else{
                // echo "not cocrrect pending";
            }
        }
        else{
            // echo "Credentials Error";
        }

        return $arr;

    }

    public function GetEnrolleeSchoolHistory($pending_enrollees_id){
        
        $sql = $this->con->prepare("SELECT * FROM student_school_history
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");
        
        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        return NULL;

    }

    public function CheckEnrolleeHasSchoolHistory($pending_enrollees_id){
        
        $sql = $this->con->prepare("SELECT * FROM student_school_history
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");
        
        $sql->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function PendingFormEmail($fname, $lname, $mi,
        $password, $email_address, $token, $current_school_year_id){

        $expiration_time = strtotime("+60 minutes");
        $expiration_time = date('Y-m-d H:i:s', $expiration_time);

        $query = $this->con->prepare("INSERT INTO pending_enrollees 
            (firstname, lastname, middle_name, password, email,
                token, expiration_time, school_year_id) 
            VALUES (:firstname, :lastname, :middle_name, :password, :email,
                :token, :expiration_time, :school_year_id)");
        
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $fname = ucwords($fname);
        $lname = ucwords($lname);
        $mi = ucwords($mi);

        $query->bindValue(":firstname", $fname);
        $query->bindValue(":lastname", $lname);
        $query->bindValue(":middle_name", $mi);
        $query->bindValue(":password", $hash_password);
        $query->bindValue(":email", $email_address);
        $query->bindValue(":token", $token);
        $query->bindValue(":expiration_time", $expiration_time);
        $query->bindValue(":school_year_id", $current_school_year_id);

        $query->execute();
        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function InitializePendingDataFromManualEnrollment($firstname, $lastname,
        $middle_name, $password, $civil_status, $nationality,
        $contact_number, $birthday, $birthplace, $sex, $suffix, $program_id,
        $religion, $course_level, $type, $admission_status,
        $school_year_id, $lrn, $email, $address)
        {

        $type = $type == 1 ? "Tertiary" : "SHS";

        $enrollment_status = $admission_status == "Transferee" ? "Irregular" : "Regular";

        $query = $this->con->prepare("INSERT INTO pending_enrollees 
            (firstname, lastname, middle_name, password, email, civil_status, nationality,
                contact_number, birthday, birthplace, sex, religion, lrn, suffix, program_id, course_level,
                type, admission_status, school_year_id, activated, is_finished, condition_acceptance,
                student_status, enrollment_status, address) 

            VALUES (:firstname, :lastname, :middle_name, :password, :email, :civil_status, :nationality,
                :contact_number, :birthday, :birthplace, :sex, :religion, :lrn, :suffix, :program_id, :course_level,
                :type, :admission_status, :school_year_id, :activated, :is_finished, :condition_acceptance,
                :student_status, :enrollment_status, :address)");
        
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $fname = ucwords($firstname);
        $lname = ucwords($lastname);
        $mi = ucwords($middle_name);
        $address = ucwords($address);

        $query->bindValue(":firstname", $fname);
        $query->bindValue(":lastname", $lname);
        $query->bindValue(":middle_name", $mi);
        $query->bindValue(":password", $hash_password);
        $query->bindValue(":email", $email);
        $query->bindValue(":civil_status", $civil_status);
        $query->bindValue(":nationality", $nationality);
        $query->bindValue(":contact_number", $contact_number);
        $query->bindValue(":birthday", $birthday);
        $query->bindValue(":birthplace", $birthplace);
        $query->bindValue(":sex", $sex);
        $query->bindValue(":religion", $religion);
        $query->bindValue(":lrn", $lrn);
        $query->bindValue(":suffix", $suffix);
        $query->bindValue(":program_id", $program_id);
        $query->bindValue(":course_level", $course_level);
        $query->bindValue(":type", $type);
        $query->bindValue(":admission_status", $admission_status);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":activated", 1);
        $query->bindValue(":is_finished", 1);
        $query->bindValue(":condition_acceptance", 1);
        $query->bindValue(":student_status", "APPROVED");
        $query->bindValue(":enrollment_status", $enrollment_status);
        $query->bindValue(":address", $address);
        


        $query->execute();
        if($query->rowCount() > 0){
            return true;
        }
        return false;
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
        $lrn = null,
        $religion,
        $pending_enrollees_id,
        $email
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
            -- age = :age,
            sex = :sex,
            address = :address,
            lrn = :lrn,
            religion = :religion,
            email = :email
            WHERE pending_enrollees_id = :pending_enrollees_id");

        $lrn = $lrn == "" ? NULL : $lrn;
        
        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":middle_name", $middle_name);
        $query->bindParam(":suffix", $suffix);
        $query->bindParam(":civil_status", $civil_status);
        $query->bindParam(":nationality", $nationality);
        $query->bindParam(":contact_number", $contact_number);
        $query->bindParam(":birthday", $birthday);
        $query->bindParam(":birthplace", $birthplace);
        // $query->bindParam(":age", $age);
        $query->bindParam(":sex", $sex);
        $query->bindParam(":address", $address);
        $query->bindParam(":lrn", $lrn);
        $query->bindParam(":religion", $religion);
        $query->bindParam(":email", $email);
        
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateNewEnrolleeSchoolHistory(
        $pending_enrollees_id, $school_name,
        $year_started, $year_ended, $address
    ) {

        $query = $this->con->prepare("UPDATE pending_enrollees SET 
            school_name = :school_name,
            year_started = :year_started,
            year_ended = :year_ended,
            address = :address
            
            WHERE pending_enrollees_id = :pending_enrollees_id
        ");

        $query->bindParam(":school_name", $school_name);
        $query->bindParam(":year_started", $year_started);
        $query->bindParam(":year_ended", $year_ended);
        $query->bindParam(":address", $address);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

 
    public function InsertSchoolHistoryAsPending($pending_enrollees_id, $school_name,
        $year_started, $year_ended, $address){

        $create = $this->con->prepare("INSERT INTO student_school_history
            (pending_enrollees_id, school_name, address, year_started, year_ended)
            VALUES (:pending_enrollees_id, :school_name, :address, :year_started, :year_ended)");
        
        $create->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $create->bindValue(":school_name", $school_name);
        $create->bindValue(":address", $address);
        $create->bindValue(":year_started", $year_started);
        $create->bindValue(":year_ended", $year_ended);

        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }
        return false;

    }

    public function UpdateSchoolHistory(
        $student_school_history_id, $pending_enrollees_id,
        $school_name, $year_started, $year_ended, $address) {

        $update = $this->con->prepare("UPDATE parent
            SET school_name = :school_name, year_started = :year_started, year_ended = :year_ended, address = :address
            WHERE student_school_history_id = :student_school_history_id
            AND pending_enrollees_id = :pending_enrollees_id
            ");

        $update->bindValue(":school_name", $school_name);
        $update->bindValue(":year_started", $year_started);
        $update->bindValue(":year_ended", $year_ended);
        $update->bindValue(":address", $address);

        $update->bindValue(":student_school_history_id", $student_school_history_id);
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);


        $update->execute();

        if ($update->rowCount() > 0) {
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

    public function CheckUniqueEnrolleesEmail($email){

        $query = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE email = :email
            AND is_graduated = 0
            ");

        $query->bindParam(":email", $email);
        $query->execute();
        
        if($query->rowCount() > 0){
            return false;
        }

        return true;
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
                <select style='width: 200px' required class='form-control' name='choose_level' id='choose_level'>

                    <option value='' selected disabled>Choose Academic level</option>
                    <option value='11' " . ($course_level == '11' ? 'selected' : '') . ">Grade 11</option>
                    <option value='12' " . ($course_level == '12' ? 'selected' : '') . ">Grade 12</option> 
                </select>
            ";
        } else if ($pending_type == "Tertiary") {

            $output .= "
                <select required style='width: 200px' class='form-control' name='choose_level' id='choose_level'>
                    <option value='1' " . ($course_level == '1' ? 'selected' : '') . ">1st Year</option>
                    <option value='2' " . ($course_level == '2' ? 'selected' : '') . ">2nd Year</option>
                    <option value='3' " . ($course_level == '3' ? 'selected' : '') . ">3rd Year</option>
                    <option value='4' " . ($course_level == '4' ? 'selected' : '') . ">4th Year</option>
                </select>
            ";
        }else{
            $output .= "
                <select style='width: 200px' class='form-control' name='choose_level' id='choose_level'>
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
                        -- age=:age,
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
        // $query->bindValue(":age", $age);
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

    public function CheckUniqueEnrolleeEmail($email, $pending_enrollees_id){

        $query = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE email = :email
            AND pending_enrollees_id != :pending_enrollees_id
            
            ");

        $query->bindParam(":email", $email);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            // echo "false CheckUniqueStudentEmail";
            return false;

        }

        return true;
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
            AND activated = 1
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
            return $update->execute();
        }
        return false;
    }

    public function GetSchoolHistoryMatchPendingStudentId(
        $pending_enrollees_id, $student_id){

        $query = $this->con->prepare("SELECT student_school_history_id 
        
            FROM student_school_history
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){

            $row = $query->fetch(PDO::FETCH_ASSOC);

            $student_school_history_id = $row['student_school_history_id'];

            $update = $this->con->prepare("UPDATE student_school_history

                SET student_id=:update_student_id
                WHERE student_school_history_id=:student_school_history_id
            
                ");
            
            $update->bindValue(":update_student_id", $student_id);
            $update->bindValue(":student_school_history_id", $student_school_history_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
        }
        return false;
    }

    public function SchoolHistoryEnrolleeSetAsNullAndStudentIdUpdated(
        $pending_enrollees_id, $student_id){

        $query = $this->con->prepare("SELECT student_school_history_id
        
            FROM student_school_history
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){

            $row = $query->fetch(PDO::FETCH_ASSOC);

            $student_school_history_id = $row['student_school_history_id'];

            $update = $this->con->prepare("UPDATE student_school_history

                SET student_id=:update_student_id,
                    pending_enrollees_id=:set_pending_enrollees_id
                WHERE student_school_history_id=:student_school_history_id
            
                ");
            
            $update->bindValue(":update_student_id", $student_id);
            $update->bindValue(":set_pending_enrollees_id", NULL);
            $update->bindValue(":student_school_history_id", $student_school_history_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
        }
        return false;
    }

    public function SetPendingApprove($pending_enrollees_id) {

        $now = date("Y-m-d H:i:s");

        $query = $this->con->prepare("UPDATE pending_enrollees
        
            SET student_status = :student_status,
                date_approved=:date_approved
            WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindValue(":date_approved", $now);
        $query->bindValue(":student_status", "APPROVED");

        return $query->execute();
    }

    public function PendingProgramLevelSectionAvailable($program_id,
        $course_level, $school_year_term, $period){

        $validCount = NULL;
        $sectionCount = NULL;

        if($period == "First"){

            $sql = $this->con->prepare("SELECT * 

                FROM course
                
                WHERE program_id=:program_id
                AND course_level = :course_level
                AND is_full = :is_full
                AND school_year_term = :school_year_term
                AND active = :active
                AND (first_period_room_id IS NOT NULL
                    AND first_period_room_id != 0)
            ");

            $sql->bindParam(":program_id", $program_id);
            $sql->bindParam(":course_level", $course_level);
            $sql->bindValue(":is_full", "no");
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->execute();

            if($sql->rowCount() > 0){
                $validCount = $sql->rowCount();
            }

            $createdSections = $this->con->prepare("SELECT * 

                    FROM course
                    
                    WHERE program_id=:program_id
                    AND course_level = :course_level
                    AND school_year_term = :school_year_term
                    AND active = :active
                ");

            $createdSections->bindParam(":program_id", $program_id);
            $createdSections->bindParam(":course_level", $course_level);
            $createdSections->bindParam(":school_year_term", $school_year_term);
            $createdSections->bindValue(":active", "yes");
            $createdSections->execute();

            if($createdSections->rowCount() > 0){
                $sectionCount = $createdSections->rowCount();
            }

           if($sectionCount == $validCount){
                return true;
           }

        }

        return false;

    }
    public function MarkAsValidated($pending_enrollees_id, $school_year_id) {

        $set_student_status = "EVALUATION";

        $pending = new Pending($this->con);

        // $pending_program_id = $pending->GetPendingProgramId();
        // $pending_level = $pending->GetCourseLevel();

        $query = $this->con->prepare("UPDATE pending_enrollees
        
            SET is_finished = :set_is_finished,
                student_status = :set_student_status

            WHERE pending_enrollees_id = :pending_enrollees_id
            AND is_finished = 0
            AND activated = 1
            AND student_status IS NULL
            AND school_year_id =:school_year_id
            ");

        $query->bindValue(":set_is_finished", 1);
        $query->bindParam(":set_student_status", $set_student_status);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindParam(":school_year_id", $school_year_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function MarkAsRejected($pending_enrollees_id) {

        
        $query = $this->con->prepare("UPDATE pending_enrollees
            SET student_status=:student_status
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":student_status", "REJECTED");
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function MarkAsEnrolled($pending_enrollees_id) {

        
        $query = $this->con->prepare("UPDATE pending_enrollees
            SET is_enrolled=:is_enrolled
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":is_enrolled", 1, PDO::PARAM_INT);
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function CheckEnrolleeAccountVerified($email, $password) {
 
        $query = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE email = :email
        ");

        $query->bindParam(":email", $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $stored_hash = $row['password'];
            $activated = $row['activated'];
            $pending_enrollees_id = $row['pending_enrollees_id'];

            if (password_verify($password, $stored_hash)) {

                // echo $pending_enrollees_id;
                if($activated != 1){

                    # Enrollee is not activated.
                    return false;
                }
                if($activated == 1){

                    # Enrollee is not activated.
                    return true;
                }
            } 
        }
        return false;
    }

    public function CheckEnrolleAccountExist($email, $password) {
 
        $query = $this->con->prepare("SELECT * FROM pending_enrollees
            WHERE email = :email
        ");

        $query->bindParam(":email", $email);
        $query->execute();

        # Email is does not exists
        if ($query->rowCount() == 0) {
            return false;
        }
        # Email is does exists
        # Wrong password.
        
        if ($query->rowCount() > 0) {

            $row = $query->fetch(PDO::FETCH_ASSOC);

            $stored_hash = $row['password'];

            $activated = $row['activated'];

            if (password_verify($password, $stored_hash) == false) {
                return false;
            } 
        }
        return true;
    }

    public function RemoveAllPendingEnrolleeWithinSemester($school_year_id) {

        $allEnrolleeInSemester = $this->GetNewEnrolleeWithinSemester($school_year_id);

        $isDone = false;
        foreach ($allEnrolleeInSemester as $key => $value) {
            # code...
            $pending_enrollees_id = $value['pending_enrollees_id'];

            $successRemoved = $this->RemoveNewEnrollee($pending_enrollees_id);
            if($successRemoved){
                $isDone = true;
            }
        }

        return $isDone;
    }

    public function GetNewEnrolleeWithinSemester($school_year_id) {

        $query = $this->con->prepare("SELECT pending_enrollees_id 
        
            FROM pending_enrollees
            WHERE school_year_id = :school_year_id
        ");

        $query->bindParam(":school_year_id", $school_year_id);
        $query->execute();

        if ($query->rowCount() > 0) {
           return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function RemoveNewEnrollee($pending_enrollees_id) {

        $query = $this->con->prepare("DELETE FROM pending_enrollees
            WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function MarkAsWithDraw($pending_enrollees_id) {

        
        $query = $this->con->prepare("UPDATE pending_enrollees
            SET student_status=:student_status
        WHERE pending_enrollees_id = :pending_enrollees_id");

        $query->bindValue(":student_status", "WITHDRAW");
        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function GetPendingAccountByStudentTable($email,
        $firstname, $lastname) {

        $lastname = strtolower($lastname);
        $firstname = strtolower($firstname);
        $email = strtolower($email);

        $sql = $this->con->prepare("SELECT pending_enrollees_id 

            FROM pending_enrollees
            
            WHERE email=:email
            AND firstname = :firstname
            AND lastname = :lastname
            LIMIT 1
        ");

        $sql->bindParam(":email", $email);
        $sql->bindParam(":firstname", $firstname);
        $sql->bindParam(":lastname", $lastname);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }

    public function GetStudentAccountByStudentTable($email,
        $firstname, $lastname) {

        $lastname = strtolower($lastname);
        $firstname = strtolower($firstname);
        $email = strtolower($email);

        $sql = $this->con->prepare("SELECT student_id 

            FROM student
            
            WHERE email=:email
            AND firstname = :firstname
            AND lastname = :lastname
            LIMIT 1
        ");

        $sql->bindParam(":email", $email);
        $sql->bindParam(":firstname", $firstname);
        $sql->bindParam(":lastname", $lastname);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }
    
    public function CheckValidTokenEnrolleeNonActivated($token) {
 
        $sql = $this->con->prepare("SELECT 

            expiration_time,
            firstname,
            pending_enrollees_id,
            token

            FROM pending_enrollees
            WHERE FIND_IN_SET(:tokenToCheck, token) > 0
            AND email != ''
            AND firstname != ''
            AND lastname != ''
            AND activated = 0

        ");

        $sql->bindParam(":tokenToCheck", $token);
        $sql->execute();

        $actual_result = $sql->rowCount() > 0;

        if ($actual_result) {

            $row = $sql->fetch(PDO::FETCH_ASSOC);
            return $row;
            // echo "Test Token: $token is present in the database tokens";
        } else {
            // echo "Test Token: $token is not present in the database tokens";
        }

        return null;

    }

    public function CheckEnrolleeEmailAlreadyActivated($email){

        $get = $this->con->prepare("SELECT * FROM pending_enrollees

            WHERE email=:email
            AND activated = 1
            AND token != ''
            -- AND school_year_id = :school_year_id
        ");

        $get->bindValue(":email", $email);
        // $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        // if($get->rowCount() > 0){

        //     # Resen
        // }


        return $get->rowCount() > 0;
    }

     public function CheckEnrolleeEmailIsNotExists($email){

        $get = $this->con->prepare("SELECT * FROM pending_enrollees

            WHERE email=:email
            -- AND activated = 0
            -- AND token != ''
            -- AND school_year_id = :school_year_id
        ");

        $get->bindValue(":email", $email);
        // $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        // if($get->rowCount() > 0){

        //     # Resen
        // }


        return $get->rowCount() == 0;
    }

    public function CheckEnrolleeHasToken($email, $school_year_id){

        $get = $this->con->prepare("SELECT * FROM pending_enrollees

            WHERE email=:email
            AND activated = 0
            AND is_finished = 0
            AND token != ''
            AND school_year_id = :school_year_id
        ");

        $get->bindValue(":email", $email);
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        // if($get->rowCount() > 0){

        //     # Resen
        // }


        return $get->rowCount() > 0;
    }

    public function EnrolleeResetPassword($pending_enrollees_id){

        $array = [];


        $new_password =  $this->GenerateRandomPassword();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the student's password in the database
        $query = $this->con->prepare("UPDATE pending_enrollees 

            SET password=:password
            WHERE pending_enrollees_id=:pending_enrollees_id

        ");

        $query->bindValue(":password", $hashed_password);

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);

        if($query->execute()){
            // echo "<br>";
            // echo "Temporary Password: $new_password";
            // echo "<br>";

            // Sent via email
            // return $new_password;
            array_push($array, $new_password);
            array_push($array, true);
        }

        return $array;
    }

    function GenerateRandomPassword() {
        $uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';

        // Generate the first letter (uppercase)
        $firstLetter = $uppercaseLetters[rand(0, strlen($uppercaseLetters) - 1)];

        // Generate the next two letters (lowercase)
        $nextTwoLetters = substr(str_shuffle($lowercaseLetters), 0, 2);

        // Generate 5 random numbers
        $randomNumbers = substr(str_shuffle($numbers), 0, 5);

        // Concatenate the letters and numbers
        $password = $firstLetter . $nextTwoLetters . $randomNumbers;

        return $password;
    }

    public function RemoveInActivatedEnrollee($token) {

        $sql = $this->con->prepare("DELETE FROM pending_enrollees 
                WHERE token=:token");

        $sql->bindParam(':token', $token);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function ActivateEnrolleeAccount($token, $pending_enrollees_id) {


        $update = $this->con->prepare("UPDATE pending_enrollees
            SET activated =:activated
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND activated=:not_active
            -- AND token=:token
            ");

        $update->bindValue(":activated", 1);
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $update->bindValue(":not_active", 0);
        // $update->bindValue(":token", $token);
        $update->execute();
        
        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function ProcessRejectedEnrollee($pending_enrollees_id) {


        $update = $this->con->prepare("UPDATE pending_enrollees
            SET student_status =:student_status
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $update->bindValue(":student_status", "EVALUATION");
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $update->execute();
        
        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function ToggleEnrolleeEnrollmentForm($pending_enrollees_id, $type) {


        $update = $this->con->prepare("UPDATE pending_enrollees
            SET enrollment_status =:enrollment_status
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $update->bindValue(":enrollment_status", $type);
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $update->execute();
        
        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateAnotherToken($email, $newToken) {
        // Fetch the current token
        $select = $this->con->prepare("SELECT token FROM pending_enrollees WHERE email = :email");
        $select->bindValue(":email", $email);
        $select->execute();
        
        if($select->rowCount() > 0){

            $currentToken = $select->fetchColumn();

            // Concatenate the current token with the new one
            $updatedToken = $currentToken . ',' . $newToken;

            // Update the row with the new token
            $update = $this->con->prepare("UPDATE pending_enrollees
                SET token = :updatedToken
                WHERE email = :email");

            $update->bindValue(":updatedToken", $updatedToken);
            $update->bindValue(":email", $email);
            $update->execute();

            if ($update->rowCount() > 0) {
                return true;
            }
        }


        return false;
    }

    public function ToggleAdmissionEnrollmentForm($pending_enrollees_id, $type) {


        $update = $this->con->prepare("UPDATE pending_enrollees
            SET admission_status =:admission_status
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $update->bindValue(":admission_status", $type);
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $update->execute();
        
        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function TermAcceptance($pending_enrollees_id) {


        $update = $this->con->prepare("UPDATE pending_enrollees
            SET condition_acceptance =:condition_acceptance
            WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $update->bindValue(":condition_acceptance", 1);
        $update->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $update->execute();
        
        if($update->rowCount() > 0){
            return true;
        }

        return false;
    }
    
   
    public function generateTokenCompre($token) {

        // $token = bin2hex(random_bytes(16));
        do {
            $token = bin2hex(random_bytes(16));
            $doesTokenExists = $this->isTokenExistsInDatabase($token);
        } while ($doesTokenExists);

        return $token;
    }

    public function isTokenExistsInDatabase($token) {

        $sql = $this->con->prepare("SELECT token

            FROM pending_enrollees
            WHERE token=:token
        ");

        $sql->bindParam(":token", $token);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }


    public function PromptToken($token) {

        $tokenExists = $this->isTokenExistsInDatabase($token);

        // Check if the token is a valid hexadecimal string and has the correct length
        
        if (!preg_match('/^[a-f0-9]{32}$/i', $token)
            || !$tokenExists) {
            echo "
                <div class='col-md-12'>
                    <h3>Invalid Token.</h3>
                </div>
            ";
            return;
            exit();
        }
    }

    public function CheckIdExists($pending_enrollees_id) {

        $query = $this->con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        if($query->rowCount() == 0){
            echo "
                <div class='col-md-12'>
                    <h4 class='text-center text-warning'>Enrollee ID Doesnt Exists.</h4>
                </div>
            ";
            exit();
        }
    }

    public function RemovingSchoolHistoryDataOfNewStudent($student_id){

        $delete = $this->con->prepare("DELETE FROM student_school_history 
            WHERE student_id = :student_id
            ");

        $delete->bindParam(":student_id", $student_id);
        $delete->execute();

        if($delete->rowCount() > 0){
           return true;
        }
        return false;
    }

    
    function isStrongPassword($password) {
        // Define the password requirements using a regular expression
        $pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()]).{8,}$/';

        // Use preg_match to check if the password matches the pattern
        return preg_match($pattern, $password);
    }

   

}

?>