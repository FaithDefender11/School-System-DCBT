<?php

    class StudentSubjectGrade{

    private $con, $userLoggedIn, $sqlData;
   
    public function __construct($con, $student_subject_grade_id = null){
        $this->con = $con;
        $this->sqlData = $student_subject_grade_id;

        if(!is_array($student_subject_grade_id)){
            
            $query = $this->con->prepare("SELECT * FROM student_subject_grade
            WHERE student_subject_grade_id=:student_subject_grade_id");

            $query->bindParam(":student_subject_grade_id", $student_subject_grade_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetFirstQuarterGrade() {
        return isset($this->sqlData['first']) ? $this->sqlData["first"] : ""; 
    }

    public function GetSecondQuarterGrade() {
        return isset($this->sqlData['second']) ? $this->sqlData["second"] : ""; 
    }

    public function GetThirdQuarterGrade() {
        return isset($this->sqlData['third']) ? $this->sqlData["third"] : ""; 
    }

    public function GetFourthQuarterGrade() {
        return isset($this->sqlData['fourth']) ? $this->sqlData["fourth"] : ""; 
    }

    public function GetRemarks() {
        return isset($this->sqlData['remarks']) ? $this->sqlData["remarks"] : ""; 
    }

    public function GetStudentSubjectId() {
        return isset($this->sqlData['student_subject_id']) ? $this->sqlData["student_subject_id"] : ""; 
    }


    public function MarkAsPassedStudentCreditedSubject($student_id, $student_subject_id){

        $remarks = "Passed";
        $is_transferee = 1;

        $mark_passed_query = $this->con->prepare("INSERT INTO student_subject_grade
            (student_id, student_subject_id, remarks, is_transferee)
            VALUES (:student_id, :student_subject_id, :remarks, :is_transferee)");
        

        $mark_passed_query->bindParam("student_id", $student_id);
        $mark_passed_query->bindParam("student_subject_id", $student_subject_id);
        $mark_passed_query->bindParam("remarks", $remarks);
        $mark_passed_query->bindParam("is_transferee", $is_transferee);

        return $mark_passed_query->execute();
    }

    public function AddGradeToSubjectCode($student_id,
        $student_subject_id,
        $first_quarter_input,
        $second_quarter_input,
        $third_quarter_input,
        $fourth_quarter_input,
        $remarks
        ){


        $addGrade = $this->con->prepare("INSERT INTO student_subject_grade
            (student_id, student_subject_id, remarks, first, 
                second,third,fourth)
            VALUES (:student_id, :student_subject_id, :remarks, :first,
                :second,:third,:fourth)");
        

        $addGrade->bindParam("student_id", $student_id);
        $addGrade->bindParam("student_subject_id", $student_subject_id);
        $addGrade->bindParam("first", $first_quarter_input);
        $addGrade->bindParam("second", $second_quarter_input);
        $addGrade->bindParam("third", $third_quarter_input);
        $addGrade->bindParam("fourth", $fourth_quarter_input);
        $addGrade->bindParam("remarks", $remarks);
        $addGrade->execute();

        if($addGrade->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function UpdateGradeForSubjectCode(
        $student_subject_grade_id,
        $student_id,
        $first_quarter_input,
        $second_quarter_input,
        $third_quarter_input,
        $fourth_quarter_input,
        $remarks
    ) {
        // Prepare the SQL query
        $updateGrade = $this->con->prepare("UPDATE student_subject_grade
            SET
                first = :first,
                second = :second,
                third = :third,
                fourth = :fourth,
                remarks = :remarks

            WHERE student_subject_grade_id = :student_subject_grade_id
            AND student_id = :student_id

            ");

        // Bind parameters
        $updateGrade->bindParam(":student_subject_grade_id", $student_subject_grade_id);
        $updateGrade->bindParam(":student_id", $student_id);
        $updateGrade->bindParam(":first", $first_quarter_input);
        $updateGrade->bindParam(":second", $second_quarter_input);
        $updateGrade->bindParam(":third", $third_quarter_input);
        $updateGrade->bindParam(":fourth", $fourth_quarter_input);
        $updateGrade->bindParam(":remarks", $remarks);

        // Execute the query
        $updateGrade->execute();
        // Check if the query was successful
        if ($updateGrade->rowCount() > 0) {
            return true;
        }
        return false;
    }

}
?>