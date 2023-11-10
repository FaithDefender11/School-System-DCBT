<?php

class SubjectModuleAudit{

    private $con, $subject_module_audit_id, $sqlData;


    public function __construct($con, $subject_module_audit_id = null){
        $this->con = $con;
        $this->subject_module_audit_id = $subject_module_audit_id;

        $query = $this->con->prepare("SELECT * FROM subject_module_audit
                WHERE subject_module_audit_id=:subject_module_audit_id");

        $query->bindValue(":subject_module_audit_id", $subject_module_audit_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetLatestHistoryOfAction($student_subject_id) {

        $query = $this->con->prepare("SELECT * FROM subject_module_audit
            WHERE student_subject_id=:student_subject_id

            ORDER BY date_creation DESC
            LIMIT 3
        ");

        $query->bindParam(":student_subject_id", $student_subject_id);
        $query->execute();

        if($query->rowCount() > 0){
           return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function InsertAuditOfSubjectModule(
        $student_subject_id, $school_year_id, $description) {

        $add = $this->con->prepare("INSERT INTO subject_module_audit
            (student_subject_id, school_year_id, description)
            VALUES(:student_subject_id, :school_year_id, :description)
        ");
        
        $add->bindValue(":student_subject_id", $student_subject_id);
        $add->bindValue(":school_year_id", $school_year_id);
        $add->bindValue(":description", $description);
        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        return false;
    }

}
?>