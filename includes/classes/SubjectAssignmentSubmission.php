<?php

class SubjectAssignmentSubmission{

    private $con, $sqlData, $subject_assignment_submission_id;

    public function __construct($con, $subject_assignment_submission_id = null){

        $this->con = $con;
        $this->subject_assignment_submission_id = $subject_assignment_submission_id;

        $query = $this->con->prepare("SELECT * FROM subject_assignment_submission
                WHERE subject_assignment_submission_id=:subject_assignment_submission_id");

        $query->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectCodeAssignmentId() {
        return isset($this->sqlData['subject_code_assignment_id']) ? $this->sqlData["subject_code_assignment_id"] : NULL; 
    }
    public function GetStudentId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : NULL; 
    }
    public function GetDateCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : NULL; 
    }

    public function GetDateGraded() {
        return isset($this->sqlData['date_graded']) ? $this->sqlData["date_graded"] : NULL; 
    }

    public function GetSubjectGrade() {
        return isset($this->sqlData['subject_grade']) ? $this->sqlData["subject_grade"] : NULL; 
    }

    public function GetSubjectAssignmentSubmissionIdNonGraded(
        $subject_code_assignment_id,
        $school_year_id,) {


        // $condition = "";

        // // if($hasGraded == true){
        // //     $condition = "AND t1.date_graded IS NOT NULL
        // //                   AND t1.subject_grade IS NOT NULL";
        // // }
       
        // $query = $this->con->prepare("SELECT subject_assignment_submission_id 
        //         FROM subject_assignment_submission AS t1 

        //         WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
        //         AND t1.school_year_id=:school_year_id
        //         ORDER BY t1.subject_assignment_submission_id DESC
        //         LIMIT 1 
        //         ");

        // $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        // $query->bindValue(":school_year_id", $school_year_id);
        // $query->execute();

        // if($query->rowCount() > 0){

        //     return $query->fetchColumn();
        // }

        #

        $first_query = $this->con->prepare("SELECT subject_assignment_submission_id 
                FROM subject_assignment_submission AS t1 

                WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
                AND t1.school_year_id=:school_year_id
                AND t1.date_graded IS NOT NULL
                ORDER BY subject_assignment_submission_id DESC

                LIMIT 1
                ");

        $first_query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $first_query->bindValue(":school_year_id", $school_year_id);
        $first_query->execute();

        if($first_query->rowCount() > 0){

            return $first_query->fetchColumn();

        }
        else if($first_query->rowCount() == 0){


            $second = $this->con->prepare("SELECT subject_assignment_submission_id 
                FROM subject_assignment_submission AS t1 

                WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
                AND t1.school_year_id=:school_year_id
                ORDER BY subject_assignment_submission_id DESC

                LIMIT 1
                ");

            $second->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $second->bindValue(":school_year_id", $school_year_id);
            $second->execute();

            if($second->rowCount() > 0){

                return $second->fetchColumn();

            }
        } 

        return 0;

    }

    public function GetAssignmentList($subject_assignment_submission_id,
        ) {

        $query = $this->con->prepare("SELECT * FROM subject_assignment_submission_list
                WHERE subject_assignment_submission_id=:subject_assignment_submission_id
                 
                ");

        $query->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        // $query->bindValue(":school_year_id", $school_year_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetchAll(PDO::FETCH_ASSOC);

        }

        return [];

    }

    public function GetSubmissionList($subject_code_assignment_id,
        $school_year_id, $student_id) {

        $query = $this->con->prepare("SELECT * FROM subject_assignment_submission

                WHERE subject_code_assignment_id=:subject_code_assignment_id
                AND student_id=:student_id
                 
                ");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function CheckStudentHasSubmissionOnAssignment(
        $subject_code_assignment_id,
        $school_year_id, $student_id) {

        $query = $this->con->prepare("SELECT * FROM subject_assignment_submission
            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND school_year_id=:school_year_id
            AND student_id=:student_id
            ");
        

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        // if($query->rowCount() > 0){

        //     return $query->fetchColumn();
        // }

        return $query->rowCount() > 0;
    }

    public function GetSubjectAssignmentSubmission(
        $subject_code_assignment_id,
        $school_year_id, $student_id) {

        $query = $this->con->prepare("SELECT * 
        
            FROM subject_assignment_submission
            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND school_year_id=:school_year_id
            AND student_id=:student_id

            ORDER BY subject_assignment_submission_id DESC
            LIMIT 1
            ");
        

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return NULL;
    }

    public function DoesStudentSubmittedAssignmentAndGraded(
        $subject_code_assignment_id,
        $school_year_id, $student_id) {

        $query = $this->con->prepare("SELECT 

            * FROM subject_assignment_submission

            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND school_year_id=:school_year_id
            AND student_id=:student_id
            AND date_graded IS NOT NULL
            AND subject_grade IS NOT NULL

            ");
        

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        // if($query->rowCount() > 0){

        //     return $query->fetchColumn();
        // }

        return $query->rowCount() > 0;
    }


    public function GetNumberOfAssignmentAttempt(
        $subject_code_assignment_id,
        $school_year_id,
        $student_id) {

        $query = $this->con->prepare("SELECT * 

                FROM subject_assignment_submission
                WHERE subject_code_assignment_id=:subject_code_assignment_id
                AND school_year_id=:school_year_id
                AND student_id=:student_id

                ");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        // if($query->rowCount() > 0){

        //     return $query->fetchColumn();
        // }

        return $query->rowCount();
    }

    public function DoesStudentGradedSubmissionAssignment(
        $subject_assignment_submission_id,
        $school_year_id, $student_id) {

        $query = $this->con->prepare("SELECT * FROM subject_assignment_submission
                WHERE subject_assignment_submission_id=:subject_assignment_submission_id
                AND school_year_id=:school_year_id
                AND student_id=:student_id
                AND date_graded IS NOT NULL

                ORDER BY subject_assignment_submission_id DESC
                LIMIT 1
                ");

        $query->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        // if($query->rowCount() > 0){

        //     return $query->fetchColumn();
        // }

        return $query->rowCount() > 0;
    }

    public function GetStudentGradedAssignmentSubmissionId(
        $subject_code_assignment_id,
        $school_year_id) {

        $query = $this->con->prepare("SELECT subject_assignment_submission_id

                FROM subject_assignment_submission
                WHERE subject_code_assignment_id=:subject_code_assignment_id
                AND school_year_id=:school_year_id
                AND date_graded IS NOT NULL
                LIMIT 1
                ");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetchColumn();
        }

        // return $query->rowCount() > 0;
    }


    public function AssignGrade($subject_assignment_submission_id,
        $subject_grade, $max_score) {

        // The record exists, so update it

        if($subject_grade > $max_score){
            Alert::error("Given grade has reached the established max score.", "");
            exit();
        }

        $date_graded = date("Y-m-d H:i:s");

        $update = $this->con->prepare("UPDATE subject_assignment_submission
            SET subject_grade = :subject_grade,
                date_graded = :date_graded
            WHERE subject_assignment_submission_id = :subject_assignment_submission_id
            ");

        $update->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $update->bindValue(":subject_grade", $subject_grade);
        $update->bindValue(":date_graded", $date_graded);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    

    public function CreateSubmissionAssignment($subject_code_assignment_id,
        $student_id, $school_year_id) {

        // The record exists, so update it

        $date_creation = date("Y-m-d H:i:s");

        $update = $this->con->prepare("INSERT INTO subject_assignment_submission
            (subject_code_assignment_id, student_id, school_year_id)
            VALUES(:subject_code_assignment_id, :student_id, :school_year_id)
            ");

        $update->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $update->bindValue(":student_id", $student_id);
        $update->bindValue(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function GradingUnSubmitAssignment($subject_code_assignment_id,
        $student_id, $school_year_id, $subject_grade) {

        // The record exists, so update it

        $date_graded = date("Y-m-d H:i:s");

        $update = $this->con->prepare("INSERT INTO subject_assignment_submission
            (subject_code_assignment_id, student_id, school_year_id, date_graded, subject_grade)
            VALUES(:subject_code_assignment_id, :student_id, :school_year_id, :date_graded, :subject_grade)
            ");

        $update->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $update->bindValue(":student_id", $student_id);
        $update->bindValue(":school_year_id", $school_year_id);
        $update->bindValue(":date_graded", $date_graded);
        $update->bindValue(":subject_grade", $subject_grade);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        
        return false;
    }


    public function SubmitWrittenAssignment($subject_assignment_submission_id,
        $output_text) {

        // The record exists, so update it

        $date_creation = date("Y-m-d H:i:s");

        $update = $this->con->prepare("INSERT INTO subject_assignment_submission_list
            (subject_assignment_submission_id, output_text)
            VALUES(:subject_assignment_submission_id, :output_text)
            ");

        $update->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $update->bindValue(":output_text", $output_text);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function SubmitImagesAssignment($subject_assignment_submission_id,
        $output_file) {

        // The record exists, so update it

        $date_creation = date("Y-m-d H:i:s");

        $update = $this->con->prepare("INSERT INTO subject_assignment_submission_list
            (subject_assignment_submission_id, output_file)
            VALUES(:subject_assignment_submission_id, :output_file)
            ");

        $update->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $update->bindValue(":output_file", $output_file);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }
 

    public function CheckStatusSubmission($subject_code_assignment_id,
        $student_id, $current_school_year_id){

        $checkSubmission = $this->con->prepare("SELECT t1.*
                                         
            FROM subject_assignment_submission as t1
            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.student_id=:student_id
            AND t1.school_year_id=:school_year_id
            LIMIT 1
        ");

        $checkSubmission->bindParam(":subject_code_assignment_id", $subject_code_assignment_id);
        $checkSubmission->bindParam(":student_id", $student_id);
        $checkSubmission->bindParam(":school_year_id", $current_school_year_id);
        $checkSubmission->execute();

        // $subject_code_assignment_id

        if($checkSubmission->rowCount() > 0){
            $row = $checkSubmission->fetch(PDO::FETCH_ASSOC);
            return $row;

            // return true;
        }

        return NULL;
    }

}
?>