<?php
class UserLog {

    private $con, $sqlData;

    public function __construct($con, $users_log_id = null) {
        
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users_log 
            WHERE users_log_id = :users_log_id");

        $query->bindParam(":users_log_id", $users_log_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
 
    }

    public function AddUserLogs(
        $role, $description, $school_year_id) {

       
        $create = $this->con->prepare("INSERT INTO users_log
            (description, school_year_id, role)

            VALUES(:description, :school_year_id, :role)");

        $create->bindParam(":description", $description);
        $create->bindParam(":school_year_id", $school_year_id);
        $create->bindParam(":role", $role);
        
        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }
        return false;

    }
}
?>