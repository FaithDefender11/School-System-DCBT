<?php
class User {

    private $con, $sqlData;

    public function __construct($con, $username = null) {
        
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users 
            WHERE username = :username");

        $query->bindParam(":username", $username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

        if($this->sqlData == null){

            $user_id = $username;

            $query = $this->con->prepare("SELECT * FROM users
                WHERE user_id=:user_id");

            $query->bindValue(":user_id", $user_id);
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public static function isCashierLoggedIn(){
        return isset($_SESSION['cashierLoggedIn']);
    }

    public static function isRegistrarLoggedIn(){
        return isset($_SESSION['registrarLoggedIn']);
    }

    public static function IsStudentEnrolledAuthenticated(){
        return isset($_SESSION['studentLoggedIn'])
            && isset($_SESSION['status']) 
            && $_SESSION['status'] == "enrolled"
            
            && isset($_SESSION['applicaton_status']) 
            && $_SESSION['applicaton_status'] == "ongoing";
    }

    public static function IsStudentEnrolledAuthenticatedLMS(){
        return isset($_SESSION['studentLoggedIn'])
            && isset($_SESSION['status']) 
            && isset($_SESSION['role']) 
            && $_SESSION['status'] == "enrolled"
            && $_SESSION['role'] == "student";
            
    }

    public static function IsTeacherAuthenticated(){
        return isset($_SESSION['teacherLoggedIn'])
            && isset($_SESSION['role']) 
            && $_SESSION['role'] == "teacher";
    }

    public static function IsStudentPendingAuthenticated(){
        
        // return isset($_SESSION['studentLoggedIn']) 
        //     && isset($_SESSION['status']) && $_SESSION['status'] == "pending";

        return isset($_SESSION['status']) && $_SESSION['status'] == "pending";
    }



    public static function isAdminLoggedIn(){
        return isset($_SESSION['adminLoggedIn']);
    }

        public static function isSuperAdminLoggedIn(){
        return isset($_SESSION['superAdminLoggedIn']);
    }


    public function getUsername() {
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : ""; 
    }

    public function getName() {
        return ucwords($this->sqlData["firstName"]) . " " . ucwords($this->sqlData["lastName"]);
    }

    public function getFirstName() {
        return isset($this->sqlData['firstName']) ? $this->sqlData["firstName"] : ""; 

    }
    public function GetUniqueId() {
        return isset($this->sqlData['unique_id']) ? $this->sqlData["unique_id"] : ""; 

    }

    public function getLastName() {
        return isset($this->sqlData['lastName']) ? $this->sqlData["lastName"] : ""; 

    }

    public function GetEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }

    public function GetPhoto() {
        return isset($this->sqlData['photo']) ? $this->sqlData["photo"] : null; 
    }

        public function GetRole() {
        return isset($this->sqlData['role']) ? $this->sqlData["role"] : ""; 
    }
    

    public function CreateUserAccount($firstName, $lastName, $email,
        $role, $password, $imagePath, $last = "", $username, $unique_id) {

        $firstName = trim(strtolower($firstName));
        $lastName = trim(strtolower($lastName));
        $firstName = trim(ucwords($firstName));
        $lastName = trim(ucfirst($lastName));

        // var_dump($firstName);
        // var_dump($lastName);
        // return;

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);


        $create = $this->con->prepare("INSERT INTO users
            (firstName, lastName, email, role, password,
            photo, last_activity, username, unique_id)

            VALUES(:firstName, :lastName, :email, :role, :password,
            :photo, :last_activity, :username, :unique_id)");

        $create->bindParam(":firstName", $firstName);
        $create->bindParam(":lastName", $lastName);
        $create->bindParam(":email", $email);
        $create->bindParam(":role", $role);
        $create->bindParam(":password", $hashed_password);
        $create->bindParam(":photo", $imagePath);
        $create->bindParam(":last_activity", $last);
        $create->bindParam(":username", $username);
        $create->bindParam(":unique_id", $unique_id);
        
        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }
        return false;

    }

    public function GenerateUniqueUsersId() {

        // Loop until a unique number is generated

        $lastUsersUniqueID = "";

        $result = $this->con->prepare("SELECT unique_id 
            FROM users

            -- WHERE unique_id IS NOT NULL
            ORDER BY user_id DESC
            LIMIT 1

        ");

        $result->execute();

        if ($result->rowCount() > 0) {

            $row = $result->fetch(PDO::FETCH_ASSOC);

            $lastUsersUniqueID = $row['unique_id'];


            // Increment the last student ID and remove leading zeros
            // $nextStudentID = ltrim((int)$lastUsersUniqueID + 1, '0');

            // // Add leading zeros to ensure it's 6 characters
            // $nextStudentID = str_pad($nextStudentID, 6, '0', STR_PAD_LEFT);

        }

        if($lastUsersUniqueID == ""){
            $lastUsersUniqueID = '000001';


        }

        if($lastUsersUniqueID != ""){

            while (false) {

                // Generate the next student number
                // Get the last student_unique_id

                $result = $this->con->prepare("SELECT unique_id 
                    FROM users

                    -- WHERE unique_id IS NOT NULL
                    ORDER BY user_id DESC
                    LIMIT 1

                ");

                $result->execute();

                if ($result->rowCount() > 0) {

                    $row = $result->fetch(PDO::FETCH_ASSOC);

                    $lastUsersUniqueID = $row['unique_id'];

                    // var_dump($lastUsersUniqueID);

                    // Increment the last student ID and remove leading zeros
                    $nextStudentID = ltrim((int)$lastUsersUniqueID + 1, '0');

                    // Add leading zeros to ensure it's 6 characters
                    $nextStudentID = str_pad($nextStudentID, 6, '0', STR_PAD_LEFT);

                } else {
                    // If no students are found, generate the first student ID
                    $nextStudentID = '000001';
                }

                // Check if the generated student number is unique

                // $result = $this->con->prepare("SELECT student_id 
                
                //     FROM student 
                //     WHERE student_unique_id = :nextStudentID
                // ");

                // $result->bindParam(':nextStudentID', $nextStudentID);
                // $result->execute();

                // if ($result->rowCount() == 0) {
                //     return $nextStudentID; // Unique student number
                // }
                
            }

        }


    }

    public function EditUserAccount($firstName, $lastName, $email,
        $role, $user_id, $imagePath) {



        $create = $this->con->prepare("UPDATE users
        
            SET firstName=:firstName,
                lastName=:lastName,
                email=:email,
                role=:role,
                photo=:photo

                WHERE user_id=:user_id
            ");

        $create->bindParam(":firstName", $firstName);
        $create->bindParam(":lastName", $lastName);
        $create->bindParam(":email", $email);
        $create->bindParam(":role", $role);
        $create->bindParam(":photo", $imagePath);
        $create->bindParam(":user_id", $user_id);
        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }
        
        return false;

    }

    public function UserResetPassword($user_id){

        $array = [];

        $new_password =  $this->generateRandomPassword();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the student's password in the database
        $query = $this->con->prepare("UPDATE users 

            SET password=:password
                -- suffix=:suffix
            WHERE user_id=:user_id

        ");

        $query->bindValue(":password", $hashed_password);
        // $query->bindValue(":suffix", $new_password);

        $query->bindValue(":user_id", $user_id);

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


    public function MarkStudentAsApplicable() {

        $school_year = new SchoolYear($this->con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        // As the Final Exam has ended
        $studentQuery = $this->con->prepare("SELECT *
            
            FROM student as t1

            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND school_year_id=:school_year_id
            AND enrollment_status=:enrollment_status

            WHERE t1.active = 1
            AND t1.nsy_applicable = 0
            
        ");
        $studentQuery->bindParam(":school_year_id", $current_school_year_id);
        $studentQuery->bindValue(":enrollment_status", "enrolled");
        $studentQuery->execute();

        if($studentQuery->rowCount() > 0){

            $enrollment = new Enrollment($this->con);
            $student_subject = new StudentSubject($this->con);

            while($row = $studentQuery->fetch(PDO::FETCH_ASSOC)){

                $student_name = $row['firstname'];
                $student_id = $row['student_id'];

                echo $student_id;

                // // Get student enrollment form id within current semester & S.Y
                // $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
                //     $current_school_year_id);

                // $applicableStudentId = $student_subject->CheckCurrentSemesterSubjectAllPassed($student_enrollment_id,
                //     $student_id, $current_school_year_id);

                // if($applicableStudentId != 0){

                //     $student = new Student($this->con, $applicableStudentId);

                //     $applicable = $student->DoesApplicableToApplyNextYear();

                //     if($applicable == 0){

                //         if($student->UpdateStudentApplicableApplyNextSY($applicableStudentId) == true){

                //             // Student id that has qualified requirements.
                //             // Enrollment form based.
                //             echo "Student ID: ". $applicableStudentId . " has been eligible to apply next s_y";

                //         }
                //     }
                // }
                // else{
                //     // echo "nothing eligible";
                // }
            }
        }
    }

    // public function getEmail() {
    //     return $this->sqlData["email"];
    // }

    // public function getProfilePic() {
    //     return isset($this->sqlData["profilePic"]) ? $this->sqlData["profilePic"] : "";
    // }

    public function getSignUpDate() {
        return $this->sqlData["signUpDate"];
    }

    public function generateNextUniqueUserId()
    {
        $query = "SELECT 
        
            MAX(unique_id) AS max_id 
            FROM users

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

    function generateRandomPassword() {

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

}
?>