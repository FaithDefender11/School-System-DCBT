<?php

class SubjectCodeHandoutStudent{

    private $con, $sqlData, $subject_code_handout_student_id;

    public function __construct($con, $subject_code_handout_student_id = null){

        $this->con = $con;
        $this->subject_code_handout_student_id = $subject_code_handout_student_id;

        $query = $this->con->prepare("SELECT * FROM subject_code_handout_student
                WHERE subject_code_handout_student_id=:subject_code_handout_student_id");

        $query->bindValue(":subject_code_handout_student_id", $subject_code_handout_student_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectHandoutId() {
        return isset($this->sqlData['subject_handout_id']) ? $this->sqlData["subject_handout_id"] : null; 
    }

    public function GetStudentId () {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : null; 
    }

    public function GetSchoolYearId () {
        return isset($this->sqlData['school_year_id']) ? $this->sqlData["school_year_id"] : null; 
    }

    public function GetDateViewed () {
        return isset($this->sqlData['date_viewed']) ? $this->sqlData["date_viewed"] : NULL; 
    }


    public function CheckHandoutIfAlreadyViewed($subject_code_handout_id,
        $student_id, $school_year_id){
        
        $query = $this->con->prepare("SELECT * 
        
            FROM subject_code_handout_student
            WHERE subject_code_handout_id=:subject_code_handout_id
            AND student_id=:student_id
            AND school_year_id=:school_year_id
            ");

        $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function GetStudentWhoViewedHandout(
        $subject_code_handout_id, $student_id){
        
        $query = $this->con->prepare("SELECT * 
        
            FROM subject_code_handout_student
            WHERE subject_code_handout_id=:subject_code_handout_id
            AND student_id=:student_id
            LIMIT 1
            ");

        $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return NULL;
    }

    public function MarkStudentViewedHandout($subject_code_handout_id,
        $student_id, $school_year_id){
        
        if($this->CheckHandoutIfAlreadyViewed($subject_code_handout_id,
            $student_id, $school_year_id) === false){


            $query = $this->con->prepare("INSERT INTO subject_code_handout_student

                (subject_code_handout_id, student_id, school_year_id)
                VALUES(:subject_code_handout_id, :student_id, :school_year_id)
            ");
            
            $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
            $query->bindValue(":student_id", $student_id);
            $query->bindValue(":school_year_id", $school_year_id);
            $query->execute();

            if($query->rowCount() > 0){
                return true;
            }
        }
        return false;
     
    }

    public function DoesStudentViewedHandoutOnSectionTopic($subject_code_handout_id,
        $student_id, $school_year_id){
        
        if($this->CheckHandoutIfAlreadyViewed($subject_code_handout_id,
            $student_id, $school_year_id) === false){


            $query = $this->con->prepare("INSERT INTO subject_code_handout_student

                (subject_code_handout_id, student_id, school_year_id)
                VALUES(:subject_code_handout_id, :student_id, :school_year_id)
            ");
            
            $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
            $query->bindValue(":student_id", $student_id);
            $query->bindValue(":school_year_id", $school_year_id);
            $query->execute();

            if($query->rowCount() > 0){
                return true;
            }
        }
        return false;
     
    }

    public function CheckSingleHandoutViewed($subject_code_handout_id,
        $student_id, $school_year_id){

        $check = $this->con->prepare("SELECT t1.*
                                         
            FROM subject_code_handout_student as t1

            WHERE t1.subject_code_handout_id=:subject_code_handout_id
            AND t1.student_id=:student_id
            AND t1.school_year_id=:school_year_id

            LIMIT 1

            -- ORDER BY
        ");

        $check->bindParam(":subject_code_handout_id", $subject_code_handout_id);
        $check->bindParam(":student_id", $student_id);
        $check->bindParam(":school_year_id", $school_year_id);
        $check->execute();

 
        return $check->rowCount() > 0;
    }

}
?>