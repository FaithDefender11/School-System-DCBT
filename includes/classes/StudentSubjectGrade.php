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


}
?>