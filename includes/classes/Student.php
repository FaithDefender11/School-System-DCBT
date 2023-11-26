<?php

class Student{

    private $con, $sqlData;

    public function __construct($con, $student_id = null)
    {
        $this->con = $con;
        $this->sqlData = $student_id;

        // echo "hey";
        // print_r($student_id);
        if(!is_array($student_id)){
            
            $query = $this->con->prepare("SELECT * FROM student
            WHERE username=:username");

            $query->bindValue(":username", $student_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        if($this->sqlData == null){

            $query = $this->con->prepare("SELECT * FROM student
                WHERE student_id=:student_id");

            $query->bindValue(":student_id", $student_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        if($this->sqlData == null){

            $student_unique_id = $student_id;

            $query = $this->con->prepare("SELECT * FROM student
                WHERE student_unique_id=:student_unique_id");

            $query->bindValue(":student_unique_id", $student_unique_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function CheckIdExists($student_id) {

        $query = $this->con->prepare("SELECT * FROM student
                WHERE student_id=:student_id");

        $query->bindParam(":student_id", $student_id);
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

    public function GetId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : 0; 
    }

    public function DoesApplicableToApplyNextYear() {
        return isset($this->sqlData['nsy_applicable']) ? $this->sqlData["nsy_applicable"] : 0; 
    }
    public function GetStudentProfile() {
        return isset($this->sqlData['profilePic']) ? $this->sqlData["profilePic"] : "";
    }
 
    public function GetIsGraduated() {
        return isset($this->sqlData['is_graduated']) ? $this->sqlData["is_graduated"] : null; 
    }

    

    public function CheckIfActive() {
        return isset($this->sqlData['active']) ? $this->sqlData["active"] : null; 
    }

    public function CheckIfTertiary() {
        return isset($this->sqlData['is_tertiary']) ? $this->sqlData["is_tertiary"] : null; 
    }
    public function GetUsername() {
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : NULL; 
    }
    public function GetEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }
    public function GetFirstName() {
        return isset($this->sqlData['firstname']) ? ucwords($this->sqlData["firstname"]) : ""; 
    }

    public function GetStudentLevel($student_id) {

        $sql = $this->con->prepare("SELECT course_level FROM student
            WHERE student_id=:student_id");
        
        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return -1;
    }

    public function GetNonEnrolledStudentPendingId($firstname, $lastname, $middle_name, $email) {

        $sql = $this->con->prepare("SELECT pending_enrollees_id FROM pending_enrollees
            WHERE firstname=:firstname
            AND lastname=:lastname
            AND middle_name=:middle_name
            AND email=:email
        ");
        
        $sql->bindValue(":firstname", $firstname);
        $sql->bindValue(":lastname", $lastname);
        $sql->bindValue(":middle_name", $middle_name);
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return NULL;
    }

    public function GetStudentCourseLevel() {
        return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : ""; 
    }

    public function GetLastName() {
        return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
    }

    public function GetFullName() {

        return $this->GetFirstName() . " " . $this->GetLastName();
    }

    public function GetStudentId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : 0; 

    }

    public function GetStudentIdByUniqueId($student_unique_id) {

        $query = $this->con->prepare("SELECT student_id FROM student
                WHERE student_unique_id=:student_unique_id");

        $query->bindParam(":student_unique_id", $student_unique_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }
        return NULL;

    }

    public function GetMiddleName() {
        return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
    }

    public function GetSuffix() {
        return isset($this->sqlData['suffix']) ? ucfirst($this->sqlData["suffix"]) : ""; 
    }


    public function GetStudentAddress() {
        return isset($this->sqlData['address']) ? $this->sqlData["address"] : "N/A"; 
    }

    public function GetAdmissionStatus() {
        return isset($this->sqlData['admission_status']) ? $this->sqlData["admission_status"] : ""; 
    }


    public function GetIsTertiary() {
        return isset($this->sqlData['is_tertiary']) ? $this->sqlData["is_tertiary"] : ""; 
    }

    public function GetStudentGender() {
        return isset($this->sqlData['sex']) ? $this->sqlData["sex"] : ""; 
    }

    public function GetStudentBirthdays() {
        return isset($this->sqlData['birthday']) ? $this->sqlData["birthday"] : NULL; 
    }

    public function GetStudentBirthPlace() {
        return isset($this->sqlData['birthplace']) ? $this->sqlData["birthplace"] : ""; 
    }

    public function GetCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : ""; 
    }

    public function GetStudentCurrentCourseId() {
        return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : ""; 
    }

    public function GetStudentLRN() {
        return isset($this->sqlData['lrn']) ? $this->sqlData["lrn"] : NULL; 
    }

    public function GetStudentSex() {
        return isset($this->sqlData['sex']) ? $this->sqlData["sex"] : "N/A"; 
    }

    public function GetName() {
        // return isset($this->sqlData['firstname']) ? $this->sqlData["firstname"] ." " . $this->sqlData["lastname"]: "N/A"; 
    return isset($this->sqlData['firstname']) ? ucfirst(strtolower($this->sqlData["firstname"])) ." " . $this->sqlData["lastname"]: "N/A";

    }
    public function GetGuardianName() {
        return isset($this->sqlData['guardian_name']) ? $this->sqlData["guardian_name"] : "N/A"; 

    }
    public function GetGuardianNameContact() {
        return isset($this->sqlData['guardian_contact_number']) ? $this->sqlData["guardian_contact_number"] : "N/A"; 

    } 

    public function GetContactNumber() {
        return isset($this->sqlData['contact_number']) ? $this->sqlData["contact_number"] : "N/A"; 

    } 

  
    public function GetNationality() {
        return isset($this->sqlData['nationality']) ? $this->sqlData["nationality"] : "N/A"; 
    } 
    public function GetReligion() {
        return isset($this->sqlData['religion']) ? $this->sqlData["religion"] : "N/A"; 

    } 

        public function GetCivilStatus() {
        return isset($this->sqlData['civil_status']) ? $this->sqlData["civil_status"] : "N/A"; 

    } 

    public function GetStudentUniqueId(){

        return isset($this->sqlData['student_unique_id']) ? $this->sqlData["student_unique_id"] : NULL;; 
    }

    public function GetStudentStatus(){

        return isset($this->sqlData['student_statusv2']) ? $this->sqlData["student_statusv2"] : "N/A"; 
    }

    public function GetStudentAdmissionStatus(){

        return isset($this->sqlData['admission_status']) ? $this->sqlData["admission_status"] : "N/A"; 
    }


    public function GetStudentNewEnrollee(){

        return isset($this->sqlData['new_enrollee']) ? $this->sqlData["new_enrollee"] : "N/A"; 
    }

    public function CreateRegisterStrand($program_id = null, $disabled){

        $query = $this->con->prepare("SELECT * FROM program
            -- WHERE department_id != 1
            ");

        $query->execute();

        $disabled = $disabled == "true" ? "disabled" : "";

        $html = "
            <div class='form-group'>
                <select $disabled class='form-control' name='STRAND' required>
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

    public function UpdateStudentCourseId($student_id,
        $current_course_id, $coursen_level, $to_change_course_id, 
        $student_enrollment_student_status){

        // Update the student's password in the database

        $query = $this->con->prepare("UPDATE student 
            SET course_id=:change_course_id,
                student_statusv2=:change_student_statusv2,
                course_level=:change_course_level

            WHERE student_id=:student_id
            AND course_id=:course_id
            ");

        $query->bindParam(":student_id", $student_id);
        $query->bindParam(":change_student_statusv2", $student_enrollment_student_status);
        $query->bindParam(":change_course_level", $student_enrollment_student_status);
        $query->bindParam(":course_id", $coursen_level);
        $query->bindParam(":change_course_id", $to_change_course_id);
        
        return $query->execute();
    }

    public function UpdateStudentToBeIrregular($student_id){

        // Update the student's password in the database

        $query = $this->con->prepare("UPDATE student 
            SET student_statusv2=:change_student_status_v2

            WHERE student_id=:student_id
            ");

        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":change_student_status_v2", "Irregular");
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function UpdateStudentToBeRegular($student_id){

        // Update the student's password in the database

        $query = $this->con->prepare("UPDATE student 
            SET student_statusv2=:change_student_status_v2

            WHERE student_id=:student_id
            ");

        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":change_student_status_v2", "Regular");
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function UpdateStudentAcademicType($student_id, $academic_type){

        $query = $this->con->prepare("UPDATE student 
            SET is_tertiary=:change_is_tertiary

            WHERE student_id=:student_id");

        $query->bindParam(":change_is_tertiary", $academic_type);
        $query->bindParam(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        
        return false;
    }

    public function CheckEnrolleeLRNCompleted($student_id){

        $query = $this->con->prepare("SELECT * FROM student as t1

            WHERE student_id = :student_id
            AND lrn IS NOT NULL
        ");

        $query->bindParam(":student_id", $student_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateStudentDetails($student_id, $firstname, $lastname,
        $middle_name, $suffix, $civil_status, $nationality, $sex,
        $birthday, $birthplace, $religion, $address, $contact_number,
        $email, $lrn = null) {

        $lrn = $lrn == "" ? NULL : $lrn;

        $query = $this->con->prepare("UPDATE student 
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
                email=:email,
                lrn=:lrn

            WHERE student_id=:student_id
            -- AND active=
        ");

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
        $query->bindParam(":lrn", $lrn);
        $query->bindParam(":student_id", $student_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;

    }


    public function UpdateStudentEnrollmentFormBased($student_id,
        $student_enrollment_course_level, $to_change_course_id, 
        $student_enrollment_student_status,
        $created_student_unique_id = null,
        $created_student_username = null,
        $generate_password = ""){

        // Update the student's password in the database


        $hashed_password = password_hash($generate_password, PASSWORD_BCRYPT);

        $query = $this->con->prepare("UPDATE student 
            SET course_id=:change_course_id,
                student_statusv2=:change_student_statusv2,
                course_level=:change_course_level,
                student_unique_id=:change_student_unique_id,
                username=:change_username,
                password=:generate_username

            WHERE student_id=:student_id
            AND student_unique_id IS NULL
            -- AND course_id=:course_id
            ");

        $query->bindParam(":change_course_id", $to_change_course_id);
        $query->bindParam(":change_student_statusv2", $student_enrollment_student_status);
        $query->bindParam(":change_course_level", $student_enrollment_course_level);
        $query->bindParam(":change_student_unique_id", $created_student_unique_id);
        $query->bindParam(":change_username", $created_student_username);
        $query->bindParam(":generate_username", $hashed_password);

        $query->bindParam(":student_id", $student_id);
        // $query->bindParam(":course_id", $coursen_level);
        
        if($query->execute()){
            return true;
        }
        return false;
    }

    public function UpdateOldStudentEnrollmentForm($student_id,
        $student_enrollment_course_level, $to_change_course_id,
        $student_enrollment_student_status){

        // Update the student's password in the database

        $query = $this->con->prepare("UPDATE student 
            SET course_id=:change_course_id,
                course_level=:change_course_level,
                student_statusv2=:change_student_statusv2

            WHERE student_id=:student_id
            AND student_unique_id IS NOT NULL

            ");

        $query->bindParam(":change_course_id", $to_change_course_id);
        $query->bindParam(":change_course_level", $student_enrollment_course_level);
        $query->bindParam(":change_student_statusv2", $student_enrollment_student_status);

        $query->bindParam(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateStudentApplicableApplyNextSY($student_id){

        // Update the student's password in the database
        // Check 
        $isExec = false;

        // $applicable = $this->DoesApplicableToApplyNextYear();
        $change_nsy_applicable = 1;

        $query = $this->con->prepare("UPDATE student 
            SET nsy_applicable=:change_nsy_applicable
            WHERE student_id=:student_id");

        $query->bindParam(":change_nsy_applicable", $change_nsy_applicable);
        $query->bindParam(":student_id", $student_id);
        $isExec =  $query->execute();  

        return $isExec;

    }

    public function UpdateStudentAdmissionStatusToOld($student_id){

        $old = 0;
        $oldStatus = "Old";

        $query = $this->con->prepare("UPDATE student 

            SET new_enrollee=:change_new_enrollee,
                admission_status=:change_admission_status

            WHERE student_id=:student_id
            AND new_enrollee = 1
            
            ");

        $query->bindParam(":change_new_enrollee", $old);
        $query->bindParam(":change_admission_status", $oldStatus);
        $query->bindParam(":student_id", $student_id);

        return $query->execute();  

    }
    public function ResetPassword($student_username){

        $array = [];


        $new_password =  $this->GenerateBirthdayAsPassword();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the student's password in the database
        $query = $this->con->prepare("UPDATE student 
            SET password=:password
            WHERE username=:username
            ");
        $query->bindValue(":password", $hashed_password);
        $query->bindValue(":username", $student_username);

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

    public function GenerateBirthdayAsPassword($length = 8) {


        $birthday = $this->GetStudentBirthdays();

        $date = new DateTime($birthday);
        $formattedDate = $date->format("Ymd");

        return $formattedDate;
    }

    private function generate_random_password($length = 8) {
        $password = '';

        try {
            // Generate a string of random bytes
            $bytes = random_bytes($length);
            // Convert the random bytes to a string of ASCII characters
            $password = bin2hex($bytes);
        } catch (Exception $e) {
            // Handle the exception if the random_bytes() function fails
            // For example, you can fallback to using the original function
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            for ($i = 0; $i < $length; $i++) {
                $password .= $characters[rand(0, strlen($characters) - 1)];
            }
        }

        return $password;
    }

    public function verifyStudentLoginCredentials($username, $password){

        $in_active = 0;

        $arr = [];

        $username = strtolower($username);

        $query_student = $this->con->prepare("SELECT 

            student_unique_id, username, password, student_id

            FROM student
            WHERE username=:username
            AND active !=:inactive

            LIMIT 1");
     
        $query_student->bindParam(":username", $username);
        $query_student->bindParam(":inactive", $in_active);
        $query_student->execute();

        if($query_student->rowCount() > 0){
            
            $user = $query_student->fetch(PDO::FETCH_ASSOC);    

            $student_id = $user['student_id'];

            // echo $user['password'];
            if($user['password'] == $password){
            }
            if ($user && password_verify($password, $user['password'])) {
                array_push($arr, $username); // [0]
                array_push($arr, true);
                array_push($arr, "enrolled");
                array_push($arr, $student_id); // [3]
                array_push($arr, "student"); // [4]
            }

            else{
                echo "not cocrrect";
            }
        }
        
        // if($query_student->rowCount() == 0){

        //     $activated = 1;
        //     $query = $this->con->prepare("SELECT 
        //         pending_enrollees_id,
        //         firstname, password 
            
        //         FROM pending_enrollees

        //         WHERE student_status !=:student_status
        //         AND firstname=:firstname
        //         AND activated=:activated
        //         LIMIT 1");
        
        //     $query->bindValue(":student_status", "APPROVED");
        //     $query->bindParam(":firstname", $username);
        //     $query->bindParam(":activated", $activated);
        //     $query->execute();

        //     if($query->rowCount() > 0){

        //         $userPending = $query->fetch(PDO::FETCH_ASSOC);  

        //         // echo $userPending['password'];
                
        //         if($userPending && password_verify($password, $userPending['password'])) {
                    
        //             // Password is correct, log in the user
        //             array_push($arr, $username);
        //             array_push($arr, true);
        //             array_push($arr, "pending");
        //             array_push($arr,  $userPending['pending_enrollees_id']);
        //         }else{
        //             echo "not cocrrect pending";
        //         }
        //     }
        //     else{
               
        //         echo "Credentials Error";

        //     }
        // }

        return $arr;

    }

    # Student LMS Login Verification
    public function ELMSVerifyStudentLoginCredentials($username, $password){

        $in_active = 0;

        $arr = [];

        $username = strtolower($username);

        $query_student = $this->con->prepare("SELECT 

            student_unique_id, username, password, student_id

            FROM student
            WHERE username=:username
            AND active !=:inactive

            LIMIT 1");
     
        $query_student->bindParam(":username", $username);
        $query_student->bindParam(":inactive", $in_active);
        $query_student->execute();

        if($query_student->rowCount() > 0){
            
            $user = $query_student->fetch(PDO::FETCH_ASSOC);    

            $student_id = $user['student_id'];

            // echo $user['password'];
            if($user['password'] == $password){
            }
            if ($user && password_verify($password, $user['password'])) {
                array_push($arr, $username); // [0]
                array_push($arr, true);
                array_push($arr, "enrolled");
                array_push($arr, $student_id); // [3]
            }
            else{
                // echo "not cocrrect";
            }
        }else{
            return "Username or Password is Incorrect.";
        }
        
        return $arr;

    }

    public function GenerateUniqueStudentNumber(){

        // Get the last student_unique_id

        $result = $this->con->prepare("SELECT student_unique_id FROM student
            ORDER BY student_id DESC 
            LIMIT 1

        ");

        $result->execute();

        if($result->rowCount() > 0){
        
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                
                // echo $row['student_unique_id'];

                // Extract the last six digits from the ID, or set it to 0 if no students exist
                $last_id = ($row['student_unique_id']) ? $row['student_unique_id'] : 0;
                $last_six_digits = substr($last_id, -6);

                // echo $last_six_digits;
                // echo "<br>";

                // If the last ID was less than 100000, set it to 99999 to start a new series
                if ($last_six_digits < 100000) {
                    $last_six_digits = 99999;
                }

                // Generate a new seven-digit ID by adding 1 to the last six digits
                $new_six_digits = sprintf('%06d', intval($last_six_digits) + 1);
              
                // echo 'Studet User ID: ' . $result['student_id'] . '<br>';
                return $new_six_digits;
            }
        }else{
            // If no student in the student table, the first student_unique_id = 100001
            // Follow by 100002 so on,
            return 100001;
        }
        

    }

    public function generateNexStudentUniqueId()
        {
            // Fetch the highest school_teacher_id from the database
            $query = "SELECT 
            
                MAX(student_unique_id) AS max_id 
                FROM student

            ";

            $result = $this->con->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);

            // Extract the maximum id and increment it
            $maxId = $row['max_id'];
            $nextId = $maxId + 1;

            // Format the id as '000001', '000002', etc.
            $formattedId = sprintf('%06d', $nextId);

            return $formattedId;
    }
    public function GenerateUniqueStudentNumberV2() {

        // Loop until a unique student number is generated
        while (true) {

            // Generate the next student number
            // Get the last student_unique_id
            $result = $this->con->prepare("SELECT student_unique_id 
                FROM student
                WHERE student_unique_id IS NOT NULL
                ORDER BY student_id DESC
                LIMIT 1
            ");
            $result->execute();

            if ($result->rowCount() > 0) {

                $row = $result->fetch(PDO::FETCH_ASSOC);
                $lastStudentID = $row['student_unique_id'];

                // Increment the last student ID and remove leading zeros
                $nextStudentID = ltrim((int)$lastStudentID + 1, '0');

                // Add leading zeros to ensure it's 6 characters
                $nextStudentID = str_pad($nextStudentID, 6, '0', STR_PAD_LEFT);
            } else {
                // If no students are found, generate the first student ID
                $nextStudentID = '000001';
            }

            // Check if the generated student number is unique
            $result = $this->con->prepare("SELECT student_id 
            
                FROM student 
                WHERE student_unique_id = :nextStudentID
            ");

            $result->bindParam(':nextStudentID', $nextStudentID);
            $result->execute();

            if ($result->rowCount() == 0) {
                return $nextStudentID; // Unique student number
            }
        }
    }


    public function GenerateUniqueStudentHexaDecimalNumber(){

        $byteCount = 2;
        $min = 100000;
        $max = 999999;
        
        do {
            // Generate a new random number
            $randomBytes = random_bytes($byteCount);
            $randomNumber = hexdec(bin2hex($randomBytes));
            $randomNumber = $min + ($randomNumber % ($max - $min + 1));

            // Check if the generated number already exists
            $checkIfHas = $this->con->prepare("SELECT student_unique_id FROM student WHERE student_unique_id=:student_unique_id");
            $checkIfHas->bindValue(":student_unique_id", $randomNumber);
            $checkIfHas->execute();

        } while ($checkIfHas->rowCount() > 0);

        // At this point, $randomNumber is guaranteed to be unique
        return $randomNumber;
    }

    public function GenerateStudentUsername($lastname, $generateStudentUniqueId){
                        
        $username_student_role = "S";

        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . $username_student_role . 'dcbt.edu.ph';
        return $username;
    }

    public function StudentResetPassword($student_id){

        $array = [];


        $new_password =  $this->generateRandomPassword();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the student's password in the database
        $query = $this->con->prepare("UPDATE student 

            SET password=:password
                -- suffix=:suffix
            WHERE student_id=:student_id

        ");

        $query->bindValue(":password", $hashed_password);
        // $query->bindValue(":suffix", $new_password);

        $query->bindValue(":student_id", $student_id);

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

    public function CalculateAge($birthdate) {

        $birthdateObj = new DateTime($birthdate);
        $today = new DateTime();
        $ageInterval = $today->diff($birthdateObj);
        return $ageInterval->y;

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

    public function CheckEnrolleeExists($firstname, $lastname, $middle_name,
         $birthday, $email){

        $query = $this->con->prepare("SELECT * FROM student
            WHERE firstname = :firstname
            AND lastname = :lastname
            AND middle_name = :middle_name
            AND email = :email
            AND birthday = :birthday");

        $query->bindValue(":firstname", $firstname);
        $query->bindValue(":lastname", $lastname);
        $query->bindValue(":middle_name", $middle_name);
        $query->bindValue(":email", $email);
        $query->bindValue(":birthday", $birthday);
        $query->execute();
        
        return $query->rowCount() > 0;
    }

    public function InsertStudentFromPendingTable($firstname, $lastname, $middle_name, $password, $civil_status, $nationality, $contact_number, $birthday, $age, $sex, $course_id, $student_unique_id, $course_level, $username, $address, $lrn, $religion, $birthplace, $email, $type, $new_enrollee) {

        // $checkIfUniqueCredentials = $this->CheckStudentCredentialsUnique(
        //     $firstname, $lastname, $middle_name,
        //     $birthday, $email, $username, $lrn
        // );

        # Pending Email should be unique in student data.
        # (Registrar provided or Student)
        # Pending LRN should be unique in student data LRN.

        if(true){
        // if($checkIfUniqueCredentials == true){

            $wasNewStudentInserted = $this->InsertNewStudentTable(
                $firstname,
                $lastname,
                $middle_name,
                $password,
                $civil_status,
                $nationality,
                $contact_number,
                $birthday, $age, $sex, $course_id, $student_unique_id,
                $course_level,
                $username,
                $address,
                $lrn,
                $religion,
                $birthplace,
                $email,
                $type,
                $new_enrollee);

            if($wasNewStudentInserted == true){
                 return true;
            }

        }else{

            Alert::error("Student email or username is already used.", "");
            return false;
        }

        
        return false;
    }

    function GenerateEnrolleePassword($birthday, $lastName) {
        // Convert birthday to the desired format
        $formattedBirthday = (new DateTime($birthday))->format('mdY');

        // Create the password by combining the last name and formatted birthday
        $password = ucfirst($lastName) . $formattedBirthday;

        return trim($password);
    }

    public function InsertStudentFromEnrollmentForm($firstname, $lastname,
        $middle_name, $password, $civil_status, $nationality,
        $contact_number, $birthday, $age, $sex, $course_id,
        $student_unique_id, $course_level, $username, $address, $lrn,
        $religion, $birthplace, $email, $is_tertiary, $new_enrollee, $suffix = "") {

        $lrn = $lrn == "" ? NULL : $lrn;

        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        $student_statusv2 = "";

        $firstname = ucwords($firstname);
        $lastname = ucwords($lastname);

        // $username = ucwords($username);

        $email = strtolower($email);
        $nationality = ucwords($nationality);
        $suffix = ucfirst($suffix);
        $address = ucwords($address);


        # Check student firstname, lastname and email to be unique

        $checkIfUniqueCredentials = $this->CheckStudentCredentialsUnique(
            $firstname, $lastname, $middle_name,
            $birthday, $email, $username, $lrn
        );

        if($checkIfUniqueCredentials == true){

            $stmt_insert = $this->con->prepare("INSERT INTO student 
                (firstname, lastname, middle_name, password, civil_status, nationality,
                contact_number, birthday, sex, course_id, student_unique_id,
                course_level, username, address, lrn, religion, birthplace, email,
                student_statusv2, is_tertiary, new_enrollee, active_search, admission_status, suffix) 
                
                VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, 
                :nationality, :contact_number, :birthday, :sex, :course_id,
                :student_unique_id, :course_level, :username, :address, :lrn, :religion,
                :birthplace, :email, :student_statusv2, :is_tertiary,:new_enrollee, :active_search, :admission_status, :suffix)");

            $stmt_insert->bindParam(':firstname', $firstname);
            $stmt_insert->bindParam(':lastname', $lastname);
            $stmt_insert->bindParam(':middle_name', $middle_name);
            $stmt_insert->bindParam(':password', $hash_password);
            $stmt_insert->bindParam(':civil_status', $civil_status);
            $stmt_insert->bindParam(':nationality', $nationality);
            $stmt_insert->bindParam(':contact_number', $contact_number);
            $stmt_insert->bindParam(':birthday', $birthday);
            // $stmt_insert->bindParam(':age', $age);
            $stmt_insert->bindParam(':sex', $sex);
            $stmt_insert->bindParam(':course_id', $course_id);
            $stmt_insert->bindParam(':student_unique_id', $student_unique_id);
            $stmt_insert->bindParam(':course_level', $course_level);
            $stmt_insert->bindParam(':username', $username);
            $stmt_insert->bindParam(':address', $address);
            $stmt_insert->bindValue(':lrn', $lrn);
            $stmt_insert->bindParam(':religion', $religion);
            $stmt_insert->bindParam(':birthplace', $birthplace);
            $stmt_insert->bindParam(':email', $email);
            $stmt_insert->bindValue(':student_statusv2', $student_statusv2);
            $stmt_insert->bindValue(':is_tertiary', $is_tertiary);
            $stmt_insert->bindValue(':new_enrollee', $new_enrollee);
            $stmt_insert->bindValue(':active_search', "Active");
            $stmt_insert->bindValue(':admission_status', "New");
            $stmt_insert->bindValue(':suffix', $suffix);

            // Execute the prepared statement
            $stmt_insert->execute();

            if($stmt_insert->rowCount() > 0){
                return true;
            }
        }
        
        return false;
    }

    public function InsertNewStudentTable(
        $firstname,
        $lastname,
        $middle_name,
        $hash_password,
        $civil_status,
        $nationality,
        $contact_number,
        $birthday,
        $age,
        $sex,
        $course_id,
        $student_unique_id,
        $course_level,
        $username,
        $address,
        $lrn,
        $religion,
        $birthplace,
        $email,
        $type,
        $new_enrollee) {

        $stmt_insert = $this->con->prepare("INSERT INTO student 
            (firstname, lastname, middle_name, password, civil_status, nationality,
            contact_number, birthday, sex, course_id, student_unique_id,
            course_level, username, address, lrn, religion, birthplace, email,
            student_statusv2, is_tertiary, new_enrollee, active_search, admission_status) 
            VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, 
            :nationality, :contact_number, :birthday, :sex, :course_id,
            :student_unique_id, :course_level, :username, :address, :lrn, :religion,
            :birthplace, :email, :student_statusv2, :is_tertiary, :new_enrollee, :active_search, :admission_status)");

        $stmt_insert->bindParam(':firstname', $firstname);
        $stmt_insert->bindParam(':lastname', $lastname);
        $stmt_insert->bindParam(':middle_name', $middle_name);
        $stmt_insert->bindParam(':password', $hash_password);
        $stmt_insert->bindParam(':civil_status', $civil_status);
        $stmt_insert->bindParam(':nationality', $nationality);
        $stmt_insert->bindParam(':contact_number', $contact_number);
        $stmt_insert->bindParam(':birthday', $birthday);
        // $stmt_insert->bindParam(':age', $age);
        $stmt_insert->bindParam(':sex', $sex);
        $stmt_insert->bindParam(':course_id', $course_id);
        $stmt_insert->bindParam(':student_unique_id', $student_unique_id);
        $stmt_insert->bindParam(':course_level', $course_level);
        $stmt_insert->bindParam(':username', $username);
        $stmt_insert->bindParam(':address', $address);
        $stmt_insert->bindValue(':lrn', $lrn);
        $stmt_insert->bindParam(':religion', $religion);
        $stmt_insert->bindParam(':birthplace', $birthplace);
        $stmt_insert->bindParam(':email', $email);
        $stmt_insert->bindValue(':student_statusv2', "");
        $stmt_insert->bindValue(':is_tertiary', $type == "Tertiary" ? 1 : 0);
        $stmt_insert->bindValue(':new_enrollee', $new_enrollee);
        $stmt_insert->bindValue(':active_search', "Active");
        $stmt_insert->bindValue(':admission_status', "New");

        $stmt_insert->execute();

        if($stmt_insert->rowCount() > 0){
            return true;
        }
        return false;
    }
  
    public function CheckStudentCredentialsUnique($firstname, $lastname, $middle_name,
         $birthday, $email, $username, $lrn){

        $boolReturn = false;

        $firstLastMiddleBirthdayEmailUnique = $this->CheckStudentFirstLastMiddleBirthdayEmailUnique(
            $firstname, $lastname, $middle_name,$birthday, $email
        );

        // $checkUniqueStudentUsername = $this->CheckUniqueStudentUsername($username);
        $checkUniqueStudentEmail = $this->CheckUniqueStudentEmail($email);
        $checkUniqueStudentLRN = $this->CheckUniqueStudentLRN($lrn);

        // if($firstLastMiddleBirthdayEmailUnique == false) return false;
        // if($checkUniqueStudentUsername == false) return false;
        // if($checkUniqueStudentEmail == false) return false;
        // if($checkUniqueStudentLRN == false) return false;
        

        if(
            // $checkUniqueStudentUsername === true 
            // && $firstLastMiddleBirthdayEmailUnique === true
            $checkUniqueStudentEmail === true
            // && $checkUniqueStudentLRN === true
            ){
            return true;
        }

        return $boolReturn;
    }

    public function CheckStudentFirstLastMiddleBirthdayEmailUnique($firstname, $lastname, $middle_name,
         $birthday, $email){

        $query = $this->con->prepare("SELECT * FROM student
            WHERE firstname = :firstname
            AND lastname = :lastname
            AND middle_name = :middle_name
            AND email = :email
            AND birthday = :birthday");

        $query->bindValue(":firstname", $firstname);
        $query->bindValue(":lastname", $lastname);
        $query->bindValue(":middle_name", $middle_name);
        $query->bindValue(":email", $email);
        $query->bindValue(":birthday", $birthday);
        $query->execute();
        
        if($query->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function CheckUniqueStudentUsername($username){

        $query = $this->con->prepare("SELECT * FROM student
            WHERE username = :username");

        $query->bindValue(":username", $username);
     
        $query->execute();
        
        if($query->rowCount() > 0){
            // echo "false CheckUniqueStudentUsername";

            return false;
        }
        return true;
    }

    public function CheckUniqueStudentEmail($email){

        $query = $this->con->prepare("SELECT * FROM student
            WHERE email = :email
            AND is_graduated = 0
            ");

        $query->bindParam(":email", $email);
        $query->execute();
        
        if($query->rowCount() > 0){
            // echo "false CheckUniqueStudentEmail";

            return false;

        }

        return true;
    }

    public function CheckUniqueStudentLRN($lrn){

        $query = $this->con->prepare("SELECT * FROM student
            WHERE lrn = :lrn");

        $query->bindParam(":lrn", $lrn);
        $query->execute();
        
        if($query->rowCount() > 0){
            // echo "false CheckUniqueStudentLRN";
            return false;
        }

        return true;
    }

    public function ValidateIfOngoingStudent($student_unique_id){

        $new_enrollee = 0;

        $query = $this->con->prepare("SELECT * FROM student
            WHERE student_unique_id = :student_unique_id
            AND new_enrollee = :new_enrollee
            AND course_id != 0
            AND active = 1
            ");

        $query->bindParam(":student_unique_id", $student_unique_id);
        $query->bindParam(":new_enrollee", $new_enrollee);
        $query->execute();
        
        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function GetAllOngoingActive(){

        $new_enrollee = 0;

        $query = $this->con->prepare("SELECT 
            t1.*,
            t2.program_section
            FROM student as t1
            LEFT JOIN course as t2 ON t2.course_id = t1.course_id

            WHERE new_enrollee = :new_enrollee
            AND t1.course_id != 0
            AND t1.active = 1

        ");

        // $query->bindParam(":student_id", $student_id);
        $query->bindParam(":new_enrollee", $new_enrollee);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function CheckUnEnrolledStudentDoesntHavePrevForm($student_id,
        $current_school_year_id){


        $query = $this->con->prepare("SELECT *

            FROM enrollment as t1
            WHERE t1.student_id=:student_id
            AND t1.school_year_id=:school_year_id
        ");

        $query->bindParam(":student_id", $student_id);
        $query->bindParam(":school_year_id", $current_school_year_id);
        $query->execute();
        
        if($query->rowCount() == 0){
            // echo "true";
            return true;
        }

        return false;
    }

    public function RemovingNewStudentFromEnrollmentForm($student_id){

        $new_enrollee = 1;

        $delete = $this->con->prepare("DELETE FROM student 
            WHERE student_id = :student_id
            AND new_enrollee = :new_enrollee
            AND course_id = :course_id
            ");

        $delete->bindParam(":student_id", $student_id);
        $delete->bindParam(":new_enrollee", $new_enrollee);
        $delete->bindValue(":course_id", 0);
        $delete->execute();

        if($delete->rowCount() > 0){
           return true;
        }
        return false;
    }

    public function RemovingNewEnrolledStudent($student_id){

        $new_enrollee = 1;

        $delete = $this->con->prepare("DELETE FROM student 
            WHERE student_id = :student_id
            AND new_enrollee = :new_enrollee
            ");

        $delete->bindParam(":student_id", $student_id);
        $delete->bindParam(":new_enrollee", $new_enrollee);
        $delete->execute();

        if($delete->rowCount() > 0){
           return true;
        }
        return false;
    }

    public function WithdrawingNewEnrolledStudent($student_id){

        $update = $this->con->prepare("UPDATE student 
            SET active=:active,
                active_search=:active_search,
                admission_status=:admission_status,
                student_statusv2=:student_statusv2,
                course_id=:course_id,
                course_level=:course_level

            WHERE student_id = :student_id
            ");

        $update->bindValue(":active", 0);
        $update->bindValue(":active_search", "Inactive");
        $update->bindValue(":admission_status", "withdraw");
        $update->bindParam(":student_id", $student_id);
        $update->bindValue(":student_statusv2", "");
        $update->bindValue(":course_id", 0);
        $update->bindValue(":course_level", 0);
        $update->execute();

        if($update->rowCount() > 0){
           return true;
        }
        return false;
    }

    

    public function UpdateStudentAsActive($student_id,
        $student_admission_status = null){

        if($student_admission_status == null){

            $update = $this->con->prepare("UPDATE student 
                SET active=:active,
                    active_search=:active_search

                WHERE student_id = :student_id
                ");

            $update->bindValue(":active", 1);
            $update->bindValue(":active_search", "Active");
            $update->bindValue(":student_id", $student_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
        }

        if($student_admission_status != null){

            $update = $this->con->prepare("UPDATE student 
                SET active=:active,
                    active_search=:active_search,
                    admission_status=:admission_status

                WHERE student_id = :student_id
                ");

            $update->bindValue(":active", 1);
            $update->bindValue(":active_search", "Active");
            $update->bindValue(":admission_status", $student_admission_status == 1 ? "New" : "Old");
            $update->bindValue(":student_id", $student_id);

            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
        }

        return false;
    }

    public function UpdateStudentAsInActive($student_id){


        $update = $this->con->prepare("UPDATE student 
            SET active=:active,
                active_search=:active_search

            WHERE student_id = :student_id
            ");

        $update->bindValue(":active", 0);
        $update->bindValue(":active_search", "InActive");
        $update->bindValue(":student_id", $student_id);

        $update->execute();

        if($update->rowCount() > 0){
           return true;
        }
        return false;
    }
    public function UpdateStudentCourseFromWaitlistForm($student_id, 
        $selected_course_id, $sectionLevel){

        $update_student_course = $this->con->prepare("UPDATE student 

                SET course_id=:change_course_id,
                    course_level=:change_course_level

                WHERE student_id=:student_id
                AND active = 1
                ");

        $update_student_course->bindParam(":change_course_id", $selected_course_id);
        $update_student_course->bindParam(":change_course_level", $sectionLevel);
        $update_student_course->bindParam(":student_id", $student_id);
        $update_student_course->execute();

        if($update_student_course->rowCount() > 0){
           return true;
        }
        
        return false;
    }

    public function GetPendingEnrolleeId($student_email,
        $school_year_id){


        $update = $this->con->prepare("SELECT pending_enrollees_id 
        
            FROM pending_enrollees 

            WHERE email = :email
            AND school_year_id = :school_year_id

        ");

        $update->bindValue(":email", $student_email);
        $update->bindValue(":school_year_id", $school_year_id);

        $update->execute();

        if($update->rowCount() > 0){

           return $update->fetchColumn();

        }

        return NULL;
    }

    // public function UpdateWithDrawNewStudent($student_id){

    //     // Update the student's password in the database

    //     $query = $this->con->prepare("UPDATE student 
    //         SET course_id=:change_course_id,
    //             student_statusv2=:change_student_statusv2,
    //             course_level=:change_course_level

    //         WHERE student_id=:student_id
    //         AND course_id=:course_id
    //         ");

    //     $query->bindParam(":student_id", $student_id);
    //     $query->bindParam(":change_student_statusv2", $student_enrollment_student_status);
    //     $query->bindParam(":change_course_level", $student_enrollment_student_status);
    //     $query->bindParam(":course_id", $coursen_level);
    //     $query->bindParam(":change_course_id", $to_change_course_id);
        
    //     return $query->execute();
    // }

    public function GetAllStudentCount($active){
     
        $sql = $this->con->prepare("SELECT t1.* 
        
            FROM student AS t1
            WHERE active=:active
        ");

        $sql->bindValue(":active", $active);
        $sql->execute();

        return $sql->rowCount();

    }

    public function UpdateStudentStatus($student_id,
        $active, $active_search){

        $update = $this->con->prepare("UPDATE student 
            SET active=:active,
                active_search=:active_search

            WHERE student_id = :student_id
        ");

        $update->bindValue(":active", $active);
        $update->bindValue(":active_search", $active_search);
        $update->bindValue(":student_id", $student_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
    }

}
?>