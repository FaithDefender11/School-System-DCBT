<?php
class Account {

    private $con;
    private $errorArray = array();

    public function __construct($con) {
        $this->con = $con;
    }

    public function login($un, $pw) {

        $pw = hash("sha512", $pw);
        $query = $this->con->prepare("SELECT * FROM users 
            WHERE username=:un AND password=:pw");

        $query->bindParam(":un", $un);
        $query->bindParam(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }
        else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }

    public function enrollmentLogIn($username, $password,
        $rememberMe = null){

        $array = [];

// echo $username;
//             echo "<br>";

//             echo $password;
//             echo "<br>";

        $query = $this->con->prepare("SELECT * FROM users
            WHERE username=:username
            LIMIT 1");

        $query->bindParam(":username", $username);
        // $query->bindParam(":password", $password);

        $query->execute();

        if($query->rowCount() > 0){
            
            $row = $query->fetch(PDO::FETCH_ASSOC);

            $user_id = $row['user_id'];
            $username = $row['username'];

            // echo $username;
            // echo "<br>";

            // echo $password;
            // echo "<br>";


            if ($row && password_verify($password, $row['password'])) {
                
                $role = $row['role'];
                $user_id = $row['user_id'];

                array_push($array, true);
                array_push($array, $role);
                array_push($array, $user_id);
            }

        }else{
            array_push($this->errorArray, Constants::$loginFailed);
        }

        return $array;
    }

    public function GetUserIdByRememberMeToken($remember_me_token) {
        $query = $this->con->prepare("SELECT user_id 
            FROM users 
            WHERE remember_me_token = :remember_me_token");

        $query->bindParam(":remember_me_token", $remember_me_token);
        $query->execute();

        if ($query->rowCount() > 0) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['user_id'];
        }

        return false; // Token not found
    }

    function generateUniqueToken() {
        return bin2hex(random_bytes(32)); // Generates a 64-character hexadecimal token
    }

    public function clearRememberMeToken($user_id) {
        $query = $this->con->prepare("UPDATE users 
            SET remember_me_token = NULL WHERE user_id = :user_id");
        $query->bindParam(":user_id", $user_id);
        $query->execute();
    }

    private function storeTokenInDatabase($user_id, $token) {

        $query = $this->con->prepare("UPDATE users 
            SET remember_me_token=:token WHERE user_id=:user_id");

        $query->bindParam(":token", $token);
        $query->bindParam(":user_id", $user_id);
        $query->execute();

    }


    public function studentLogIn($username, $password){

        $array = [];

        $query = $this->con->prepare("SELECT * FROM student
            WHERE username=:username AND password=:password
            LIMIT 1");

        $query->bindValue(":username", $username);
        $query->bindValue(":password", $password);

        $query->execute();

        if($query->rowCount() > 0){
            $row = $query->fetch(PDO::FETCH_ASSOC);

            $role = "student";

            array_push($array, true);
            array_push($array, $role);

            // return true;
        }else{
            array_push($this->errorArray, Constants::$loginFailed);
        }

        return $array;
    }
    
    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {

        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)) {
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }
        else {
            return false;
        }
    }

    public function insertUserDetails($fn, $ln, $un, $em, $pw) {
        
        $pw = hash("sha512", $pw);
        $profilePic = "assets/images/profilePictures/default.png";

        $query = $this->con->prepare("INSERT INTO users (firstName, lastName,
            username, email, password, profilePic)
            VALUES(:fn, :ln, :un, :em, :pw, :pic)");

        $query->bindParam(":fn", $fn);
        $query->bindParam(":ln", $ln);
        $query->bindParam(":un", $un);
        $query->bindParam(":em", $em);
        $query->bindParam(":pw", $pw);
        $query->bindParam(":pic", $profilePic);
        
        return $query->execute();
    }
    
    private function validateFirstName($fn) {
        if(strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }
    }

    private function validateLastName($ln) {
        if(strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }

    private function validateUsername($un) {
        if(strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }
        $query = $this->con->prepare("SELECT username FROM users WHERE username=:un");
        $query->bindParam(":un", $un);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    private function validateEmails($em, $em2) {
        
        if($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDoNotMatch);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em", $em);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validatePasswords($pw, $pw2) {
        if($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }

        if(preg_match("/[^A-Za-z0-9]/", $pw)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }

        if(strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordLength);
            return;
        }
    }
    
    public function getError($error) {
        
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

}
?>