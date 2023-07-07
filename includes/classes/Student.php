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
                WHERE username=:username");

            $query->bindValue(":username", $student_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
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

  


    public function GetMiddleName() {
        return isset($this->sqlData['middle_name']) ? $this->sqlData["middle_name"] : ""; 
    }

    

    public function GetStudentAddress() {
        return isset($this->sqlData['address']) ? $this->sqlData["address"] : "N/A"; 
    }

    public function GetStudentBirthdays() {
        return isset($this->sqlData['birthday']) ? $this->sqlData["birthday"] : "N/A"; 
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

    public function GetStudentUniqueId(){

        return isset($this->sqlData['student_unique_id']) ? $this->sqlData["student_unique_id"] : "N/A"; 
    }

    public function GetStudentStatusv2(){

        return isset($this->sqlData['student_statusv2']) ? $this->sqlData["student_statusv2"] : "N/A"; 
    }

    public function GetStudentAdmissionStatus(){

        return isset($this->sqlData['admission_status']) ? $this->sqlData["admission_status"] : "N/A"; 
    }


    public function GetStudentNewEnrollee(){

        return isset($this->sqlData['new_enrollee']) ? $this->sqlData["new_enrollee"] : "N/A"; 
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
            AND active !=:active

            LIMIT 1");
     
        $query_student->bindParam(":username", $username);
        $query_student->bindParam(":active", $in_active);
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

}
?>