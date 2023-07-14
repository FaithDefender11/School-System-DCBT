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
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : ""; 
    }
    public function GetEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }
    public function GetFirstName() {
        return isset($this->sqlData['firstname']) ? $this->sqlData["firstname"] : ""; 
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

    public function GetStudentCourseLevel() {
        return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : ""; 
    }

    public function GetLastName() {
        return isset($this->sqlData['lastname']) ? $this->sqlData["lastname"] : ""; 
    }

    public function GetFullName() {

        return $this->GetFirstName() . " " . $this->GetLastName();
    }

    public function GetStudentId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : 0; 

    }

  


    public function GetMiddleName() {
        return isset($this->sqlData['middle_name']) ? $this->sqlData["middle_name"] : ""; 
    }

    public function GetSuffix() {
        return isset($this->sqlData['suffix']) ? $this->sqlData["suffix"] : ""; 
    }


    public function GetStudentAddress() {
        return isset($this->sqlData['address']) ? $this->sqlData["address"] : "N/A"; 
    }

    public function GetAdmissionStatus() {
        return isset($this->sqlData['admission_status']) ? $this->sqlData["admission_status"] : "N/A"; 
    }


    public function GetIsTertiary() {
        return isset($this->sqlData['is_tertiary']) ? $this->sqlData["is_tertiary"] : ""; 
    }

    public function GetStudentBirthdays() {
        return isset($this->sqlData['birthday']) ? $this->sqlData["birthday"] : ""; 
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
        return isset($this->sqlData['lrn']) ? $this->sqlData["lrn"] : ""; 
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

        return isset($this->sqlData['student_unique_id']) ? $this->sqlData["student_unique_id"] : "N/A"; 
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
        $current_course_id, $to_change_course_id, $student_enrollment_student_status){

        // Update the student's password in the database

        $query = $this->con->prepare("UPDATE student 
            SET course_id=:change_course_id,
                student_statusv2=:change_student_statusv2
            WHERE student_id=:student_id
            AND course_id=:course_id
            ");

        $query->bindParam(":student_id", $student_id);
        $query->bindParam(":change_student_statusv2", $student_enrollment_student_status);
        $query->bindParam(":course_id", $current_course_id);
        $query->bindParam(":change_course_id", $to_change_course_id);
        
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
            echo "<br>";
            echo "Temporary Password: $new_password";
            echo "<br>";

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

            student_unique_id, username, password 

            FROM student
            WHERE username=:username
            AND active !=:inactive

            LIMIT 1");
     
        $query_student->bindParam(":username", $username);
        $query_student->bindParam(":inactive", $in_active);
        $query_student->execute();

        if($query_student->rowCount() > 0){
            
            $user = $query_student->fetch(PDO::FETCH_ASSOC);    
            // echo $user['password'];
            if($user['password'] == $password){
                // echo "<br>";
                // echo "equal";
                // echo "<br>";
            }
            if ($user && password_verify($password, $user['password'])) {
                // echo "we";
                // Password is correct, log in the user
                array_push($arr, $username);
                array_push($arr, true);
                array_push($arr, "enrolled");
            }

            else{
                echo "not cocrrect";
            }
        }
        
        if($query_student->rowCount() == 0){

            $activated = 1;
            $query = $this->con->prepare("SELECT firstname, password 
            
                FROM pending_enrollees

                WHERE student_status !=:student_status
                AND firstname=:firstname
                AND activated=:activated
                LIMIT 1");
        
            $query->bindValue(":student_status", "APPROVED");
            $query->bindParam(":firstname", $username);
            $query->bindParam(":activated", $activated);
            $query->execute();

            if($query->rowCount() > 0){
                // echo "wee";

                $userPending = $query->fetch(PDO::FETCH_ASSOC);    
                echo $userPending['password'];
                if($userPending && password_verify($password, $userPending['password'])) {
                    
                    // Password is correct, log in the user
                    array_push($arr, $username);
                    array_push($arr, true);
                    array_push($arr, "pending");
                }else{
                    echo "not cocrrect pending";
                }
            }
            else{
                // Display alert box with two options
                // Constants::error("Credentials Error", "");

                echo "Credentials Error";

            }
        }

        return $arr;

    }

    public function GenerateUniqueStudentNumber(){

        // Get the last student_unique_id

        $result = $this->con->prepare("SELECT student_unique_id FROM student
            ORDER BY student_id DESC 
            LIMIT 1");
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
                // $new_id = '100' . $new_six_digits;

                // echo 'Studet User ID: ' . $result['student_id'] . '<br>';
                return $new_six_digits;
            }
        }else{
            // If no student in the student table, the first student_unique_id = 100001
            // Follow by 100002 so on,
            return 100001;
        }
        

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

        $stmt_insert = $this->con->prepare("INSERT INTO student 
            (firstname, lastname, middle_name, password, civil_status, nationality,
            contact_number, birthday, age, sex, course_id, student_unique_id,
            course_level, username, address, lrn, religion, birthplace, email,
            student_statusv2, is_tertiary, new_enrollee) 
            
            VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, 
            :nationality, :contact_number, :birthday, :age, :sex, :course_id,
            :student_unique_id, :course_level, :username, :address, :lrn, :religion,
            :birthplace, :email, :student_statusv2, :is_tertiary,:new_enrollee)");

        $stmt_insert->bindParam(':firstname', $firstname);
        $stmt_insert->bindParam(':lastname', $lastname);
        $stmt_insert->bindParam(':middle_name', $middle_name);
        $stmt_insert->bindParam(':password', $password);
        $stmt_insert->bindParam(':civil_status', $civil_status);
        $stmt_insert->bindParam(':nationality', $nationality);
        $stmt_insert->bindParam(':contact_number', $contact_number);
        $stmt_insert->bindParam(':birthday', $birthday);
        $stmt_insert->bindParam(':age', $age);
        $stmt_insert->bindParam(':sex', $sex);
        $stmt_insert->bindParam(':course_id', $course_id);
        $stmt_insert->bindParam(':student_unique_id', $student_unique_id);
        $stmt_insert->bindParam(':course_level', $course_level);
        $stmt_insert->bindParam(':username', $username);
        $stmt_insert->bindParam(':address', $address);
        $stmt_insert->bindParam(':lrn', $lrn);
        $stmt_insert->bindParam(':religion', $religion);
        $stmt_insert->bindParam(':birthplace', $birthplace);
        $stmt_insert->bindParam(':email', $email);
        $stmt_insert->bindValue(':student_statusv2', "");
        $stmt_insert->bindValue(':is_tertiary', $type == "Tertiary" ? 1 : 0);
        // $stmt_insert->bindParam(':citizenship', $nationality);
        $stmt_insert->bindValue(':new_enrollee', $new_enrollee);

        // Execute the prepared statement
        return $stmt_insert->execute();
    }

}
?>