<?php
class User {

    private $con, $sqlData;

    public function __construct($con, $username) {
        
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users 
            WHERE username = :username");

        $query->bindParam(":username", $username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function isCashierLoggedIn(){
        return isset($_SESSION['cashierLoggedIn']);
    }

    public static function isRegistrarLoggedIn(){
        return isset($_SESSION['registrarLoggedIn']);
    }

    public static function IsStudentEnrolledAuthenticated(){
        return isset($_SESSION['studentLoggedIn'])
            && isset($_SESSION['status']) && $_SESSION['status'] == "enrolled";;
    }

    public static function IsStudentPendingAuthenticated(){
        return isset($_SESSION['studentLoggedIn']) 
            && isset($_SESSION['status']) && $_SESSION['status'] == "pending";
    }

    public static function isAdminLoggedIn(){
        return isset($_SESSION['adminLoggedIn']);
    }

    public function getUsername() {
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : ""; 
    }

    public function getName() {
        return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }

    public function getFirstName() {
        return $this->sqlData["firstName"];
        // return isset($this->sqlData['firstName']) ? $this->sqlData["firstName"] : ""; 

    }

    public function getLastName() {
        return $this->sqlData["lastName"];
    }

    // public function getEmail() {
    //     return $this->sqlData["email"];
    // }

    // public function getProfilePic() {
    //     return isset($this->sqlData["profilePic"]) ? $this->sqlData["profilePic"] : "";
    // }

    // public function getSignUpDate() {
    //     return $this->sqlData["signUpDate"];
    // }
}
?>