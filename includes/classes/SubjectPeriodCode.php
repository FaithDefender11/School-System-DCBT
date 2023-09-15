<?php

class SubjectPeriodCode{

    private $con, $sqlData, $subject_period_code_id;

    public function __construct($con, $subject_period_code_id = null){

        $this->con = $con;
        $this->subject_period_code_id = $subject_period_code_id;

        $query = $this->con->prepare("SELECT * FROM subject_period_code
                WHERE subject_period_code_id=:subject_period_code_id");

        $query->bindValue(":subject_period_code_id", $subject_period_code_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

    }

    public function GetTeacherId() {
        return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : null; 
    }
    
    public function GetSubjectPeriodName() {
        return isset($this->sqlData['subject_period_name']) ? $this->sqlData["subject_period_name"] : null; 
    }

    public function GetSubjectCode() {
        return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : null; 
    }

    public function GetProgramCode() {
        return isset($this->sqlData['program_code']) ? $this->sqlData["program_code"] : null; 
    }

    public function AttachTeacherTeachingCode($teacher_id,
        $school_year_id,
        $subject_code, $program_code) {

       


        $insertData = [
            ['Prelim', $teacher_id, $school_year_id, $subject_code, $program_code],
            ['Midterm', $teacher_id, $school_year_id, $subject_code, $program_code],
            ['Pre-final', $teacher_id, $school_year_id, $subject_code, $program_code],
            ['Final', $teacher_id, $school_year_id, $subject_code, $program_code],
        ];

        $query = "INSERT INTO subject_period_code (teacher_id, subject_period_name, school_year_id, subject_code, program_code) 
                VALUES (:teacher_id, :subject_period_name, :school_year_id, :subject_code, :program_code)";

        $create = $this->con->prepare($query);

        $hasCreated = false;

        foreach ($insertData as $data) {
            $create->bindParam(':subject_period_name', $data[0]);
            $create->bindParam(':teacher_id', $data[1]);
            $create->bindParam(':school_year_id', $data[2]);
            $create->bindParam(':subject_code', $data[3]);
            $create->bindParam(':program_code', $data[4]);

            $create->execute();

            if ($create->rowCount() > 0) {
                // Handle success or any other logic here
                $hasCreated = true;

            }
        }

        return $hasCreated;
    }


    public function RemovalTeacherTeachingCode($teacher_id,
        $school_year_id, $subject_code) {


        $delete = $this->con->prepare("DELETE FROM subject_period_code 
                WHERE subject_code = :subject_code
                AND teacher_id = :teacher_id
                AND school_year_id = :school_year_id
                ");

        $delete->bindParam(":teacher_id", $teacher_id);
        $delete->bindParam(":school_year_id", $school_year_id);
        $delete->bindParam(":subject_code", $subject_code);
        $delete->execute();

        if($delete->rowCount() > 0){
            return true;
        }

        return false;

    }

    public function AdjustTeacherOnTeachingSubjectCode(
        $current_teacher_id, $chosen_teacher_id,
        $school_year_id, $subject_code) {

        $update = $this->con->prepare("UPDATE subject_period_code 
                SET teacher_id = :change_teacher_id
                WHERE subject_code = :subject_code
                AND teacher_id = :current_teacher_id
                AND school_year_id = :school_year_id
                ");

        $update->bindParam(":change_teacher_id", $chosen_teacher_id);
        $update->bindParam(":subject_code", $subject_code);
        $update->bindParam(":current_teacher_id", $current_teacher_id);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }

        return false;

    }

    


}
?>