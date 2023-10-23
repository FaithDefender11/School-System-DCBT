<?php

class SubjectPeriodCodeTopic{

    private $con, $sqlData, $subject_period_code_topic_id;

    public function __construct($con, $subject_period_code_topic_id = null){

        $this->con = $con;
        $this->subject_period_code_topic_id = $subject_period_code_topic_id;

        $query = $this->con->prepare("SELECT * FROM subject_period_code_topic
                WHERE subject_period_code_topic_id=:subject_period_code_topic_id");

        $query->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetTopic() {
        return isset($this->sqlData['topic']) ? $this->sqlData["topic"] : ""; 
    }

    public function GetSubjectPeriodCodeTopicTemplateId() {
        return isset($this->sqlData['subject_period_code_topic_template_id']) ? $this->sqlData["subject_period_code_topic_template_id"] : NULL; 
    }

    public function GetDescription() {
        return isset($this->sqlData['description']) ? $this->sqlData["description"] : ""; 
    }
    public function GetTeacherId() {
        return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : NULL; 
    }

    public function GetSubjectProgramId() {
        return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : NULL; 
    }
    public function GetCourseId() {
        return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : NULL; 
    }
    public function GetSchoolYearId() {
        return isset($this->sqlData['school_year_id']) ? $this->sqlData["school_year_id"] : NULL; 
    }

    public function GetSubjectCode() {
        return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : ""; 
    }
    public function GetProgramCode() {
        return isset($this->sqlData['program_code']) ? $this->sqlData["program_code"] : ""; 
    }

    public function GetSubjectPeriodName() {
        return isset($this->sqlData['subject_period_name']) ? $this->sqlData["subject_period_name"] : ""; 
    }

    public function GetImage() {
        return isset($this->sqlData['image']) ? $this->sqlData["image"] : NULL; 
    }

    public function GetDateCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : NULL; 
    }

    public function AddTopic($course_id, $teacher_id, $school_year_id,
        $topic, $description,$subject_period_name,
        $subject_code, $program_code, $subject_program_id) {

        $teacher_id = $teacher_id == 0 ? NULL : $teacher_id;


        # Cbeck if subject code is not assigned to other teacher
        if($this->CheckDefaultTopicAlreadyBeenAssigned($subject_code,
            $school_year_id, $subject_period_name) == false){

            $add = $this->con->prepare("INSERT INTO subject_period_code_topic
                (course_id,teacher_id, school_year_id,
                    topic, description,subject_period_name,
                    subject_code, program_code, subject_program_id)
                VALUES(:course_id,:teacher_id, :school_year_id,
                    :topic, :description,:subject_period_name,
                    :subject_code, :program_code, :subject_program_id)");
                
            $add->bindValue(":course_id", $course_id);
            $add->bindValue(":teacher_id", $teacher_id);
            $add->bindValue(":school_year_id", $school_year_id);
            $add->bindValue(":topic", $topic);
            $add->bindValue(":description", $description);
            $add->bindValue(":subject_period_name", $subject_period_name);
            $add->bindValue(":subject_code", $subject_code);
            $add->bindValue(":program_code", $program_code);
            $add->bindValue(":subject_program_id", $subject_program_id);
            // $add->bindValue(":period_order", $period_order);

            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }
        }

        // else{
        //     echo "Not exec";
        //     return;
        // }

       

        return false;

    }

    public function UpdateAssignTeacherOnSubjectCodeTopic(
        $course_id, $teacher_id,
        $school_year_id, $subject_code) {

        $teacher_id = $teacher_id == 0 ? NULL : $teacher_id;
     
        $updateTopic = $this->con->prepare("UPDATE subject_period_code_topic

            SET teacher_id=:teacher_id

            WHERE course_id=:course_id
            AND school_year_id=:school_year_id
            AND subject_code=:subject_code
        ");
            
        $updateTopic->bindValue(":teacher_id", $teacher_id);
        $updateTopic->bindValue(":course_id", $course_id);
        $updateTopic->bindValue(":school_year_id", $school_year_id);
        $updateTopic->bindValue(":subject_code", $subject_code);

        $updateTopic->execute();

        if($updateTopic->rowCount() > 0){
            return true;
        }

        return false;

    }
    

    public function AddTopicSingle($course_id,$teacher_id, $school_year_id,
        $topic, $description,$subject_period_name,
        $subject_code, $program_code, $subject_program_id) {

        $teacher_id = $teacher_id == 0 ? NULL : $teacher_id;


        $add = $this->con->prepare("INSERT INTO subject_period_code_topic
            (course_id,teacher_id, school_year_id,
                topic, description,subject_period_name,
                subject_code, program_code, subject_program_id)
            VALUES(:course_id,:teacher_id, :school_year_id,
                :topic, :description,:subject_period_name,
                :subject_code, :program_code, :subject_program_id)");
            
        $add->bindValue(":course_id", $course_id);
        $add->bindValue(":teacher_id", $teacher_id);
        $add->bindValue(":school_year_id", $school_year_id);
        $add->bindValue(":topic", $topic);
        $add->bindValue(":description", $description);
        $add->bindValue(":subject_period_name", $subject_period_name);
        $add->bindValue(":subject_code", $subject_code);
        $add->bindValue(":program_code", $program_code);
        $add->bindValue(":subject_program_id", $subject_program_id);
        // $add->bindValue(":period_order", $period_order);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        
        // else{
        //     echo "Not exec";
        //     return;
        // }

       

        return false;

    }

    public function AddTopicTemplate($topic, $description,
        $subject_period_name, $program_code) {
        try {
            $stmt = $this->con->prepare("INSERT INTO subject_period_code_topic_template
                (topic, description, subject_period_name, program_code)
                VALUES(:topic, :description, :subject_period_name, :program_code)");

            $stmt->bindParam(":topic", $topic);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":subject_period_name", $subject_period_name);
            $stmt->bindParam(":program_code", $program_code);

            if ($stmt->execute()) {
                return true;
            } else {
                return false; // Return false in case of an error
            }
        } catch (PDOException $e) {
            // Handle any exceptions that occur during the database operation
            // You might log the error, return an error message, or take other actions.
            return false;
        }
    }





 

    public function GetDefaultTopicTemplate($program_code) {

        $sql = $this->con->prepare("SELECT *
            FROM subject_period_code_topic_template
            
            WHERE program_code=:program_code");
                
        $sql->bindValue(":program_code", $program_code);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetAllSubjectTopicEnrolledBased(
        $school_year_id,
        $student_id,
        $enrollment_id) {

        $studentSubject = new StudentSubject($this->con);

        $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCode($student_id,
            $school_year_id, $enrollment_id);


            // echo count($allEnrolledSubjectCode);

            $arr = [];

        foreach ($allEnrolledSubjectCode as $key => $value) {

            # code...
            $enrolledSubjectCode = $value['subject_code'];

            // echo $enrolledSubjectCode;
            // echo "<br>";

            $getSubjectTopicAssignments = $this->con->prepare("SELECT 

                t1.subject_period_code_topic_id

                FROM subject_period_code_topic as t1

                WHERE t1.school_year_id=:school_year_id
                AND t1.subject_code=:subject_code
                ORDER BY subject_period_code_topic_id DESC
            ");

            $getSubjectTopicAssignments->bindValue(":school_year_id", $school_year_id);
            $getSubjectTopicAssignments->bindValue(":subject_code", $enrolledSubjectCode);
            $getSubjectTopicAssignments->execute();

            if($getSubjectTopicAssignments->rowCount() > 0){

                while($row = $getSubjectTopicAssignments->fetch(PDO::FETCH_ASSOC)){
                    array_push($arr, $row['subject_period_code_topic_id']);
                }
               
            }
        }

        return $arr;
    }

    public function GetSubjectPeriodCodeTopicRawCodeBySubjectCode(
        $subject_code, $school_year_id) {

        $sql = $this->con->prepare("SELECT program_code
            FROM subject_period_code_topic
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id
            ");
                
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }


    public function GetTeachingCodeTeacherId(
        $subject_code, $school_year_id) {

        $sql = $this->con->prepare("SELECT teacher_id
            FROM subject_period_code_topic
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id
            ");
                
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }



    public function GetAllsubjectPeriodCodeTopics(
        $subject_code, $school_year_id, $teacher_id = null) {

        $teacher_query = "";

        if($teacher_id != NULL){

            $teacher_query = "AND teacher_id=:teacher_id";
        }

        $sql = $this->con->prepare("SELECT subject_period_code_topic_id
            FROM subject_period_code_topic
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id
            $teacher_query
            ");
                
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);

        if($teacher_id != NULL){
            $sql->bindValue(":teacher_id", $teacher_id);
        }
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
        }

        return [];
    }


    public function GetSubjectCodeAssignmentIdByTopicId(
        $subject_period_code_topic_id) {

        $sql = $this->con->prepare("SELECT subject_code_assignment_id
            FROM subject_code_assignment
            
            WHERE subject_period_code_topic_id=:subject_period_code_topic_id
            ");
                
        $sql->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }

    public function GetCourseIdBySubjectCodeAndSchoolYear(
        $subject_code, $school_year_id) {

        $sql = $this->con->prepare("SELECT course_id
            FROM subject_period_code_topic
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id

            ");
                
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;
    }

    public function CheckTeacherOwnedTheSubjectTopic($subject_period_code_topic_id,
        $teacher_id){

        $check = $this->con->prepare("SELECT * FROM subject_period_code_topic
            WHERE subject_period_code_topic_id=:subject_period_code_topic_id
            AND teacher_id=:teacher_id");

        $check->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $check->bindValue(":teacher_id", $teacher_id);
        $check->execute();

        return $check->rowCount() > 0;
    }


     
    public function GetSubjectCodeTopicIdByAssignmentId(
        $subject_code_assignment_id){

        $check = $this->con->prepare("SELECT subject_period_code_topic_id FROM subject_code_assignment
            WHERE subject_code_assignment_id=:subject_code_assignment_id
        ");

        $check->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $check->execute();

        if($check->rowCount() > 0){
            return $check->fetchColumn();
        }
        
        return NULL;
    }

    public function CheckTeacherHasGivenSubjectCodeDefaultTopics(
        $subject_code, $teacher_id, $school_year_id){

        $query = $this->con->prepare("SELECT 
        
            subject_period_code_topic_id 

            FROM subject_period_code_topic

            WHERE teacher_id=:teacher_id
            AND school_year_id=:school_year_id
            AND subject_code=:subject_code

        ");

        $query->bindValue(":teacher_id", $teacher_id);
        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":subject_code", $subject_code);
        $query->execute();
 
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function CheckDefaultTopicAlreadyBeenAssigned(
        $subject_code, $school_year_id, $subject_period_name = null){

        $subject_period_name_query = "";

        if($subject_period_name != NULL){
            $subject_period_name_query = "AND subject_period_name=:subject_period_name";
        }

        $query = $this->con->prepare("SELECT 
        
            subject_period_code_topic_id 

            FROM subject_period_code_topic

            WHERE school_year_id=:school_year_id
            AND subject_code=:subject_code
            $subject_period_name_query

        ");

        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":subject_code", $subject_code);
        if($subject_period_name != NULL){
            $query->bindValue(":subject_period_name", $subject_period_name);

        }
        $query->execute();
 
        return  $query->rowCount() > 0;

    }

    public function RemovalOfDefaultSubjectCodeTopics(
        $subject_period_code_topic_id){

        $query = $this->con->prepare("DELETE FROM subject_period_code_topic

            WHERE subject_period_code_topic_id=:subject_period_code_topic_id

        ");

        $query->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $query->execute();
 
        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function GetSubjectCodeDefaultTopics(
        $subject_code, $course_id, $school_year_id){

        $query = $this->con->prepare("SELECT 
        
            * 

            FROM subject_period_code_topic

            WHERE school_year_id=:school_year_id
            AND subject_code=:subject_code
            AND course_id=:course_id

        ");

        $query->bindValue(":school_year_id", $school_year_id);
        $query->bindValue(":subject_code", $subject_code);
        $query->bindValue(":course_id", $course_id);
        $query->execute();
 
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    
    public function GetAllGivenAssignmentsBasedOnSubjectCodeTopics(
        $assignedSubjectCodeTopics) {

            // var_dump($assignedSubjectCodeTopics);

        if(count($assignedSubjectCodeTopics) > 0){


            
            $givenAssignementsArray = [];
            $subjectCodeAssignmentSubmissionArray = [];

            $subjectCodeAssignment = new SubjectCodeAssignment($this->con);
            
            foreach ($assignedSubjectCodeTopics as $key => $subject_period_code_topic_id) {

                # code...

                $givenAssignements = $subjectCodeAssignment
                    ->GetTotalGivenAssignmentByTopicSection(
                        $subject_period_code_topic_id);

                foreach ($givenAssignements as $key => $value) {
                    # code...

                    $subject_code_assignment_id = $value['subject_code_assignment_id'];
                
                    array_push($givenAssignementsArray, $subject_code_assignment_id);
                }    

            }

            if(count($givenAssignementsArray) > 0){

                foreach ($givenAssignementsArray as $key => $subject_code_assignment_id) {

                    $submission = $this->con->prepare("WITH LatestSubmissions AS (
                        SELECT student_id, subject_code_assignment_id, MAX(date_creation) AS latest_date_creation
                        FROM subject_assignment_submission
                        WHERE date_graded IS NULL
                        GROUP BY student_id, subject_code_assignment_id)

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

                        foreach ($result as $key => $submission_subject_code_assignment) {


                            // array_push($subjectCodeAssignmentSubmissionArray, $submission_subject_code_assignment_id);

                            $db_subject_assignment_submission_id = $submission_subject_code_assignment['subject_assignment_submission_id'];
                            $submission_subject_code_assignment_id = $submission_subject_code_assignment['subject_code_assignment_id'];

                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($this->con);
                            
                            $check = $subjectAssignmentSubmission->CheckOtherSubmissionHasBeenGraded2(
                                $db_subject_assignment_submission_id);
                            
                        
                            if($check == false){

                                if (!in_array($submission_subject_code_assignment_id, $subjectCodeAssignmentSubmissionArray)) {
                                    $subjectCodeAssignmentSubmissionArray[] = $submission_subject_code_assignment_id;
                                }
                                // array_push($subjectCodeAssignmentSubmissionArray, $submission_subject_code_assignment_id);

                            }

                        }

                    }

                }

                // return $subjectCodeAssignmentSubmissionArray;

            }
            
        }

        return $subjectCodeAssignmentSubmissionArray;
    }

}