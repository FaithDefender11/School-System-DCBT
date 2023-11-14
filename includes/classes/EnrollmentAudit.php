<?php

    class EnrollmentAudit{

    private $con, $sqlData;

    public function __construct($con, $enrollment_audit_id = null)
    {
        $this->con = $con;
        $this->sqlData = $enrollment_audit_id;

        if(!is_array($enrollment_audit_id)){
            
            $query = $this->con->prepare("SELECT * FROM enrollment_audit
                WHERE enrollment_audit_id=:enrollment_audit_id");

            $query->bindValue(":enrollment_audit_id", $enrollment_audit_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function EnrollmentAuditInsert(
        $student_enrollment_id, $description,
        $current_school_year_id, $registrarUserId){


        $stmt = $this->con->prepare("INSERT INTO enrollment_audit 

            (enrollment_id, description, school_year_id, registrar_id) 
            VALUES (:enrollment_id, :description, :school_year_id, :registrar_id)
        ");

        $stmt->bindParam(':enrollment_id', $student_enrollment_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':school_year_id', $current_school_year_id);
        $stmt->bindParam(':registrar_id', $registrarUserId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
         
    }

}

?>