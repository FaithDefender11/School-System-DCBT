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
                
            ORDER BY subject_assignment_submission_id DESC

            ");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        if($query->rowCount() > 0){

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function CheckSubmissionIsLatest($subject_code_assignment_id,
        $school_year_id, $student_id) {

        $latestSubmission = $this->GetSubmissionList($subject_code_assignment_id,
            $school_year_id, $student_id);

        // $latestSubmission = $latestSubmission[0];

        // $latestSubmission = [];
        if(count($latestSubmission) > 0){

            $latestSubmission = $latestSubmission[0];

            $latestSubmissionId = $latestSubmission['subject_assignment_submission_id'];
            
            if($latestSubmissionId !== NULL){
                return $latestSubmissionId;
            }
        }

        
        // var_dump($latestSubmissionId);

        return 0;
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

    public function GetSubmission(
        $subject_code_assignment_id,
        $school_year_id,
        $student_id) {

        $query = $this->con->prepare("SELECT * 

            FROM subject_assignment_submission
            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND school_year_id=:school_year_id
            AND student_id=:student_id

            ORDER BY subject_assignment_submission_id DESC
                
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


    public function GetSubmissionCount(
        $subject_code_assignment_id,
        $school_year_id,
        $student_id) {

        $query = $this->con->prepare("SELECT * 

            FROM subject_assignment_submission
            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND school_year_id=:school_year_id
            AND student_id=:student_id

            ORDER BY subject_assignment_submission_id DESC
                
        ");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":student_id", $student_id);
        $query->execute();

        return $query->rowCount();

    }

    public function calculatePercentage($completedTasks, $totalTasks) {
        if ($totalTasks == 0) {
            return 0; // Avoid division by zero
        }
    
        $percentage = ($completedTasks / $totalTasks) * 100;
        // return floor($percentage); // 95.99 -> 95 Round down to the nearest integer
        return round($percentage); // 95.99 -> 96 Round normally to the nearest integer
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

        // if($subject_grade > $max_score){
        //     Alert::error("Given grade has reached the established max score.", "");
        //     exit();
        // }

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
        $student_id, $school_year_id, $subject_code,
        $subject_assignment_submission_id) {

        // The record exists, so update it

        // echo "subject_assignment_submission_id: $subject_assignment_submission_id";
        // return;
        if($subject_assignment_submission_id !== NULL){


            $notification = new Notification($this->con);

            // $check  = $notification->CheckStudentHasSubmittedNotifiedOnAssignment(
            //     $subject_assignment_submission_id, $student_id,
            //         $subject_code, $school_year_id);
        
            // var_dump($check);
            //     return;

            $remove = $notification->RemovePrevSubmittedNotification(
                $subject_assignment_submission_id, $student_id,
                $subject_code, $school_year_id);
            
        }

        $date_creation = date("Y-m-d H:i:s");

        $insert = $this->con->prepare("INSERT INTO subject_assignment_submission
            (subject_code_assignment_id, student_id, school_year_id)
            VALUES(:subject_code_assignment_id, :student_id, :school_year_id)
            ");

        $insert->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $insert->bindValue(":student_id", $student_id);
        $insert->bindValue(":school_year_id", $school_year_id);
        $insert->execute();

        if($insert->rowCount() > 0){
            
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

            ORDER BY subject_assignment_submission_id DESC
            LIMIT 1

            -- ORDER BY
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

    public function DoesAssignmentHasEnded(
        $subject_code_assignment_id,
        $current_school_year_id){

        $now = date("Y-m-d H:i:s");

        $checkAssignment = $this->con->prepare("SELECT t1.*
                                         
            FROM subject_code_assignment as t1
            INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
            AND t2.school_year_id=:school_year_id
 
            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.due_date >= :now_date

            ORDER BY subject_code_assignment_id DESC
            LIMIT 1
        ");

        $checkAssignment->bindParam(":school_year_id", $current_school_year_id);
        $checkAssignment->bindParam(":subject_code_assignment_id", $subject_code_assignment_id);
        $checkAssignment->bindParam(":now_date", $now);
        $checkAssignment->execute();

        // $subject_code_assignment_id

        return $checkAssignment->rowCount() > 0;
    }

    public function GetSubmissionCountOnAssignment(
        $subject_code_assignment_id,
        $student_id, $school_year_id) {
            

        $submission = $this->con->prepare("SELECT 
                                                                
            t1.*
            
            FROM subject_assignment_submission  as t1

            WHERE subject_code_assignment_id=:subject_code_assignment_id
            AND student_id=:student_id
            AND school_year_id=:school_year_id

        ");

        $submission->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $submission->bindValue(":student_id", $student_id);
        $submission->bindValue(":school_year_id", $school_year_id);
        $submission->execute();
         
        return $submission->rowCount();
    }

    public function GetSubmittedUngradedSubmission(
        $subject_code_assignment_id) {
            
        # This query will give you the rows of data for each student_id
        # where there are multiple rows with the same student_id
        # and it will select the row with the latest date_creation.

        # If two student has two answers, always get the latest one
        # We GROUP BY student_id -> to get only one student
        # SELECT student_id, MAX(date_creation) to retrieve the latest submitted of this multiple submission.
        
        $arr = [];

        # ORIGINAL FORM

        // $submission = $this->con->prepare("SELECT t1.*

        //     FROM subject_assignment_submission AS t1
        //     INNER JOIN (
        //         SELECT student_id, MAX(date_creation) AS latest_date_creation

        //         FROM subject_assignment_submission

        //         WHERE subject_code_assignment_id = :subject_code_assignment_id
        //         AND subject_grade IS NULL
        //         AND date_graded IS NULL
        //         GROUP BY student_id
        //     ) AS t2 ON t1.student_id = t2.student_id AND t1.date_creation = t2.latest_date_creation
            
        //     WHERE t1.subject_code_assignment_id = :subject_code_assignment_id
        //     AND t1.subject_grade IS NULL
        //     AND t1.date_graded IS NULL

        // ");

        # Has a bit issue.

        $submission = $this->con->prepare("WITH LatestSubmissions AS (
                SELECT student_id, subject_code_assignment_id, MAX(date_creation) AS latest_date_creation
                FROM subject_assignment_submission
                WHERE date_graded IS NULL
                GROUP BY student_id, subject_code_assignment_id
            )

            SELECT t1.*
            FROM subject_assignment_submission AS t1

            JOIN LatestSubmissions AS t2 ON t1.student_id = t2.student_id 

            AND t1.subject_code_assignment_id = t2.subject_code_assignment_id 
            AND t1.date_creation = t2.latest_date_creation

            WHERE t1.subject_code_assignment_id = :subject_code_assignment_id
            AND t1.subject_grade IS NULL
            AND t1.date_graded IS NULL
        ");
 


        $submission->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $submission->execute();
         
        if($submission->rowCount() > 0){

            $result = $submission->fetchAll(PDO::FETCH_ASSOC);
            // $arr = $submission->fetchAll(PDO::FETCH_ASSOC);


            $hasOtherSubmissionGraded = false;

            if(count($result) > 0){

                foreach ($result as $key => $value) {

                    # code...
                    $db_subject_code_assignment_id = $value['subject_code_assignment_id'];
                    
                   
                    
                    $db_subject_assignment_submission_id = $value['subject_assignment_submission_id'];

                    $db_student_id = $value['student_id'];
                    $db_school_year_id = $value['school_year_id'];

                    $db_subject_grade = $value['subject_grade'];
                    $db_date_graded = $value['date_graded'];

                    // echo "db_subject_assignment_submission_id: $db_subject_assignment_submission_id";
                    // echo "<br>";
                    // echo "db_subject_code_assignment_id: $db_subject_code_assignment_id";

                    // echo "<br>";


                    // $check = $this->CheckOtherSubmissionHasBeenGraded(
                    //     $db_subject_code_assignment_id,
                    //     $db_student_id, $db_school_year_id);

                    $check = $this->CheckOtherSubmissionHasBeenGraded2(
                        $db_subject_assignment_submission_id);
                    
                        // var_dump($check);
                  
                    if($check == false){

                        // echo "db_subject_assignment_submission_id: $db_subject_assignment_submission_id";
                        // echo "<br>";

                        array_push($arr, $db_subject_assignment_submission_id);
                        $hasOtherSubmissionGraded = true;


                    }

                    // if($db_subject_grade != NUll && $db_date_graded != NULL){
                    //     continue;
                    // }

                }

                // if($hasOtherSubmissionGraded == true){
                //     $arr = $result;
                // }
            }

            // $arr = $result;

        }

        
        // $submission = $this->con->prepare("SELECT 
        //     t1.*, MAX(t1.date_creation) AS latest_date_creation

        //     FROM subject_assignment_submission AS t1
        //     WHERE subject_code_assignment_id = :subject_code_assignment_id
        //     AND subject_grade IS NULL
        //     AND date_graded IS NULL
        //     GROUP BY t1.student_id
        // ");

        return $arr;
    }

    public function GetSubmittedUngradedSubmissionBasedOnAssignment(
        $subject_code_assignment_id) {
        
        $arr = array();

        $submission = $this->con->prepare("WITH LatestSubmissions AS (
                SELECT student_id, subject_code_assignment_id, MAX(date_creation) AS latest_date_creation
                FROM subject_assignment_submission
                WHERE date_graded IS NULL
                GROUP BY student_id, subject_code_assignment_id
            )

            SELECT t1.*
            FROM subject_assignment_submission AS t1

            JOIN LatestSubmissions AS t2 ON t1.student_id = t2.student_id 

            AND t1.subject_code_assignment_id = t2.subject_code_assignment_id 
            AND t1.date_creation = t2.latest_date_creation

            WHERE t1.subject_code_assignment_id = :subject_code_assignment_id
            AND t1.subject_grade IS NULL
            AND t1.date_graded IS NULL

            ORDER BY t1.date_creation ASC
        ");

        $submission->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $submission->execute();
         
        if($submission->rowCount() > 0){

            $result = $submission->fetchAll(PDO::FETCH_ASSOC);
            // $arr = $submission->fetchAll(PDO::FETCH_ASSOC);

            $hasOtherSubmissionGraded = false;

            if(count($result) > 0){

                foreach ($result as $key => $value) {

                    # code...
                    $db_subject_code_assignment_id = $value['subject_code_assignment_id'];
                    
                    $db_subject_assignment_submission_id = $value['subject_assignment_submission_id'];
 
                    $check = $this->CheckOtherSubmissionHasBeenGraded2(
                        $db_subject_assignment_submission_id);
                    
                        // var_dump($check);
                  
                    if($check == false){

                        // echo "db_subject_assignment_submission_id: $db_subject_assignment_submission_id";
                        // echo "<br>";

                        array_push($arr, $value);
                        $hasOtherSubmissionGraded = true;
                    }

                }
            }
        }
        return $arr;   
    }
    public function CheckOtherSubmissionHasBeenGraded2(
        $subject_assignment_submission_id
        // $subject_code_assignment_id,
        // $student_id, $school_year_id
        ) {

        
        $subject_assignment_submission = new SubjectAssignmentSubmission($this->con,
            $subject_assignment_submission_id);

        $get_GetSubjectCodeAssignmentId = $subject_assignment_submission->GetSubjectCodeAssignmentId();
        $get_GetStudentId = $subject_assignment_submission->GetStudentId();


        $checkSubmission = $this->con->prepare("SELECT t1.*
                                         
            FROM subject_assignment_submission as t1

            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.student_id=:student_id
            
            -- AND t1.school_year_id=:school_year_id

            AND t1.subject_grade IS NOT NULL
            AND t1.date_graded IS NOT NULL

            -- LIMIT 1
        ");

        $checkSubmission->bindParam(":subject_code_assignment_id", $get_GetSubjectCodeAssignmentId);
        $checkSubmission->bindParam(":student_id", $get_GetStudentId);

        // $checkSubmission->bindParam(":school_year_id", $school_year_id);

        $checkSubmission->execute();

        // $subject_code_assignment_id

        if($checkSubmission->rowCount() > 0){

            return true;
        }

        return false;

    }

    public function CheckOtherSubmissionHasBeenGraded(
        $subject_code_assignment_id,
        $student_id, $school_year_id) {
            
        $checkSubmission = $this->con->prepare("SELECT t1.*
                                         
            FROM subject_assignment_submission as t1

            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.student_id=:student_id
            AND t1.school_year_id=:school_year_id
            AND t1.subject_grade IS NOT NULL
            AND t1.date_graded IS NOT NULL

            -- LIMIT 1
        ");

        $checkSubmission->bindParam(":subject_code_assignment_id", $subject_code_assignment_id);
        $checkSubmission->bindParam(":student_id", $student_id);
        $checkSubmission->bindParam(":school_year_id", $school_year_id);
        $checkSubmission->execute();

        // $subject_code_assignment_id

        if($checkSubmission->rowCount() > 0){
            return true;
        }

        return false;

    }

    public function GetTotalSubmittedOnAssignment(
        $subject_code_assignment_id) {
            
        # This query will give you the rows of data for each student_id
        # where there are multiple rows with the same student_id
        # and it will select the row with the latest date_creation.

        # If two student has two answers, always get the latest one
        # We GROUP BY student_id -> to get only one student
        # SELECT student_id, MAX(date_creation) to retrieve the latest submitted of this multiple submission.
        
        $submission = $this->con->prepare("SELECT t1.*

            FROM subject_assignment_submission AS t1
            INNER JOIN (
                SELECT student_id, MAX(date_creation) AS latest_date_creation
                FROM subject_assignment_submission
                WHERE subject_code_assignment_id = :subject_code_assignment_id
                
                GROUP BY student_id
            ) AS t2 ON t1.student_id = t2.student_id AND t1.date_creation = t2.latest_date_creation
            
            WHERE t1.subject_code_assignment_id = :subject_code_assignment_id
        ");
 

        $submission->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $submission->execute();
         
        if($submission->rowCount() > 0){

            return $submission->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetSubjectSubmissionTotalPoints(
        $subjectCodeAssignmentIds,
        $student_id, $school_year_id) {

        
        # TODO. Get all student submissions based on subject CodeAssignmentIds (Default assignments)
        
        $storePoints = [];
            
        if(count($subjectCodeAssignmentIds) > 0){

            foreach ($subjectCodeAssignmentIds as $key => $subjectCodeAssignmentId) {

                $submission = $this->con->prepare("SELECT 
                                                                
                    t1.subject_grade
                    
                    FROM subject_assignment_submission  as t1

                    WHERE subject_code_assignment_id=:subject_code_assignment_id
                    AND student_id=:student_id
                    AND school_year_id=:school_year_id
                    AND subject_grade IS NOT NULL
                    LIMIT 1
                ");

                $submission->bindValue(":subject_code_assignment_id", $subjectCodeAssignmentId);
                $submission->bindValue(":student_id", $student_id);
                $submission->bindValue(":school_year_id", $school_year_id);
                $submission->execute();

                if($submission->rowCount() > 0){

                    // $all = $submission->fetchAll(PDO::FETCH_COLUMN);
                    $points = $submission->fetchColumn();

                    array_push($storePoints, $points);
                    // var_dump($all);
                    // return $all;
                    
                }
            }
        }
         
        return $storePoints;
    }

    public function CheckAssignmentIsDue(
        $subjectCodeAssignmentIds) {

        $now = date("Y-m-d H:i:s");

        
        $submission = $this->con->prepare("SELECT *
            
            FROM subject_code_assignment AS t1

            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.due_date <= :due_date
            LIMIT 1

        ");

        $submission->bindValue(":subject_code_assignment_id", $subjectCodeAssignmentIds);
        $submission->bindValue(":due_date", $now);
        $submission->execute();

        return $submission->rowCount() > 0;

    }

    public function GetOverscoreFromAssignmentAnswered(
        $subjectCodeAssignmentIds,
        $student_id, $school_year_id) {

        
        # TODO. Get all student submissions based on subject CodeAssignmentIds (Default assignments)
        
        $maxScoreArr = [];

        $max_score_output = 0;
            
        if(count($subjectCodeAssignmentIds) > 0){

            foreach ($subjectCodeAssignmentIds as $key => $subjectCodeAssignmentId) {


                $statusSubmission = $this->CheckStatusSubmission(
                    $subjectCodeAssignmentId,
                    $student_id, $school_year_id
                );
                


                $submitted_grade = false;

                if($statusSubmission !== NULL){
                    
                    $submitted_grade =  $statusSubmission['subject_grade'];

                    // echo "<br>";
                    // var_dump($submitted_grade);
                }

                # What are the overall score to be added.
                # 1. Past Due.
                # 2. Submitted with graded.

                # What are the overall score should not be added.
                # 1. Submitted but not yet graded.
                # 2. Not Due.

                $studentHasSubmittedByNotGraded = $statusSubmission !== NULL && $submitted_grade === NULL;

                # If assignment is due and student had been passed the assignment
                # then it should add the max_score.

                if($this->CheckAssignmentIsDue($subjectCodeAssignmentId) == true
                    && $studentHasSubmittedByNotGraded == false){

                    $subjectCodeAssignment = new SubjectCodeAssignment($this->con, $subjectCodeAssignmentId);

                    $overAllScore = $subjectCodeAssignment->GetMaxScore();

                    $student = new Student($this->con, $student_id);

                    $assname = $subjectCodeAssignment->GetAssignmentName();
                    $studentFirstname = $student->GetFirstName();

                    // echo "Assignment Name: $assname, studentFirstname: $studentFirstname";
                    // echo "<br>";
                    // echo "<br>";

                    $max_score_output += $overAllScore;
                }
            }
        }
         
        return $max_score_output;

    }


    public function GetAllSubmittedAssignmentIds(
        $school_year_id, $student_id) {

        
        $submission = $this->con->prepare("SELECT t1.subject_code_assignment_id
            
            FROM subject_assignment_submission AS t1

            WHERE t1.school_year_id=:school_year_id
            AND t1.student_id=:student_id

            GROUP BY t1.subject_code_assignment_id
        ");

        $submission->bindValue(":school_year_id", $school_year_id);
        $submission->bindValue(":student_id", $student_id);
        $submission->execute();

        if($submission->rowCount() > 0){
            return $submission->fetchAll(PDO::FETCH_COLUMN);
        }

        return [];
    }



}

?>