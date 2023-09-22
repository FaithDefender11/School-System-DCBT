<?php

class SubjectCodeAssignment{

    private $con, $sqlData, $subject_code_assignment_id;

    public function __construct($con, $subject_code_assignment_id = null){

        $this->con = $con;
        $this->subject_code_assignment_id = $subject_code_assignment_id;

        $query = $this->con->prepare("SELECT * FROM subject_code_assignment
                WHERE subject_code_assignment_id=:subject_code_assignment_id");

        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectPeriodCodeTopicId() {
        return isset($this->sqlData['subject_period_code_topic_id']) ? $this->sqlData["subject_period_code_topic_id"] : NULL; 
    }
    public function GetAssignmentName() {
        return isset($this->sqlData['assignment_name']) ? $this->sqlData["assignment_name"] : ""; 
    }

    public function GetIsGiven() {
        return isset($this->sqlData['is_given']) ? $this->sqlData["is_given"] : NULL; 
    }


    public function GetAssignmentImage() {
        return isset($this->sqlData['assignment_image']) ? $this->sqlData["assignment_image"] : NULL; 
    }

    public function GetDescription() {
        return isset($this->sqlData['description']) ? $this->sqlData["description"] : NULL; 
    }

    public function GetSubject_code_assignment_template_id() {
        return isset($this->sqlData['subject_code_assignment_template_id']) ? $this->sqlData["subject_code_assignment_template_id"] : NULL; 
    }

    public function GetDueDate() {
        return isset($this->sqlData['due_date']) ? $this->sqlData["due_date"] : NULL; 
    }

    public function GetDateCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : NULL; 
    }

    public function GetAllowLateSubmission() {
        return isset($this->sqlData['allow_late_submission']) ? $this->sqlData["allow_late_submission"] : NULL; 
    }

    public function GetMaxScore() {
        return isset($this->sqlData['max_score']) ? $this->sqlData["max_score"] : NULL; 
    }

    public function GetType() {
        return isset($this->sqlData['type']) ? $this->sqlData["type"] : NULL; 
    }

    public function GetAssignmentMaxAttempt() {
        return isset($this->sqlData['max_attempt']) ? $this->sqlData["max_attempt"] : NULL; 
    }

    public function PromptAssignmentIsNotGiven() {

        if($this->GetIsGiven() === 0){
            echo "
                <div class='row'>
                    <div class='col-md-12'>
                        <h3 class='text-muted text-center'>Oh no! Assignment is unavailable</h3>
                    </div>
                </div>
            ";
            exit();
        }
    }
    public function InsertAssignment(
        $subject_period_code_topic_id,
        $assignment_name,$description,
        $max_score,$allow_late_submission,
        $due_date, $type, $max_attempt) {

        $add = $this->con->prepare("INSERT INTO subject_code_assignment
            (subject_period_code_topic_id, assignment_name, description,
                max_score, allow_late_submission, due_date, type, max_attempt)
            VALUES(:subject_period_code_topic_id, :assignment_name, :description,
                :max_score, :allow_late_submission, :due_date, :type, :max_attempt)");

        
        $add->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $add->bindValue(":assignment_name", $assignment_name);
        $add->bindValue(":description", $description);
        $add->bindValue(":max_score", $max_score);
        $add->bindValue(":allow_late_submission", $allow_late_submission);
        $add->bindValue(":due_date", $due_date);
        $add->bindValue(":type", $type);
        $add->bindValue(":max_attempt", $max_attempt);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function InsertAssignmentTemplate(
        $subject_period_code_topic_id,
        $subject_code_assignment_template_id,
        $assignment_name,$description,
        $max_score,$allow_late_submission,
        $due_date, $type, $max_attempt) {

        $add = $this->con->prepare("INSERT INTO subject_code_assignment
            (subject_period_code_topic_id, subject_code_assignment_template_id, assignment_name, description,
                max_score, allow_late_submission, due_date, type, max_attempt, is_given)
            VALUES(:subject_period_code_topic_id, :subject_code_assignment_template_id, :assignment_name, :description,
                :max_score, :allow_late_submission, :due_date, :type, :max_attempt, :is_given)");

        
        $add->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $add->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $add->bindValue(":assignment_name", $assignment_name);
        $add->bindValue(":description", $description);
        $add->bindValue(":max_score", $max_score);
        $add->bindValue(":allow_late_submission", $allow_late_submission);
        $add->bindValue(":due_date", $due_date);
        $add->bindValue(":type", $type);
        $add->bindValue(":max_attempt", $max_attempt);
        $add->bindValue(":is_given", 1);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function UpdateAssignment(
        $subject_period_code_topic_id,
        $subject_code_assignment_id,
        $assignment_name,
        $description,
        $max_score,
        $allow_late_submission,
        $due_date, $max_attempt
    ) {

        // Check if the record with the provided subject_period_code_topic_id exists first
        $checkExistence = $this->con->prepare("SELECT 

            * 
            FROM subject_code_assignment 
            WHERE subject_period_code_topic_id = :subject_period_code_topic_id");
        $checkExistence->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $checkExistence->execute();

        if ($checkExistence->rowCount() > 0) {
            // The record exists, so update it
            $update = $this->con->prepare("UPDATE subject_code_assignment
                SET assignment_name = :assignment_name,
                    description = :description,
                    max_score = :max_score,
                    allow_late_submission = :allow_late_submission,
                    due_date = :due_date,
                    max_attempt = :max_attempt

                WHERE subject_period_code_topic_id = :subject_period_code_topic_id
                AND subject_code_assignment_id = :subject_code_assignment_id
                ");

            $update->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
            $update->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $update->bindValue(":assignment_name", $assignment_name);
            $update->bindValue(":description", $description);
            $update->bindValue(":max_score", $max_score);
            $update->bindValue(":allow_late_submission", $allow_late_submission);
            $update->bindValue(":due_date", $due_date);
            $update->bindValue(":max_attempt", $max_attempt);
            $update->execute();

            if ($update->rowCount() > 0) {
                return true; // Update successful
            } else {
                return false; // Update failed
            }
        } else {
            return false; // Record with the provided subject_period_code_topic_id doesn't exist
        }
    }

    public function UploadAssignmentFiles(
        $subject_code_assignment_id,
        $image) {

        $add = $this->con->prepare("INSERT INTO subject_code_assignment_list
            (subject_code_assignment_id, image)
            VALUES(:subject_code_assignment_id, :image)");
        
        $add->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $add->bindValue(":image", $image);
        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        return false;
    }


    public function GetUploadAssignmentFiles(
        $subject_code_assignment_id) {

        $query = $this->con->prepare("SELECT * 

            FROM subject_code_assignment_list
            WHERE subject_code_assignment_id=:subject_code_assignment_id");
        
        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function GetSingleUploadAssignmentFile(
        $subject_code_assignment_list_id,
        $subject_code_assignment_id) {

        $query = $this->con->prepare("SELECT image

            FROM subject_code_assignment_list
            WHERE subject_code_assignment_list_id=:subject_code_assignment_list_id
            AND subject_code_assignment_id=:subject_code_assignment_id

            ");
        
        $query->bindValue(":subject_code_assignment_list_id", $subject_code_assignment_list_id);
        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return NULL;
    }


    public function GetSubjectTopicAssignmentList(
        $subject_period_code_topic_id) {

        $assignment = $this->con->prepare("SELECT 
                                                                
            t1.assignment_name
            ,t1.subject_code_assignment_id
            ,t1.due_date
            ,t1.max_score

            FROM subject_code_assignment as t1

            WHERE subject_period_code_topic_id=:subject_period_code_topic_id
            AND is_given = :is_given
            
            ORDER BY 
                t1.subject_code_assignment_template_id IS NULL ASC, 
                t1.subject_code_assignment_template_id ASC, 
                t1.date_creation ASC
        ");

        $assignment->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $assignment->bindValue(":is_given", 1);

        $assignment->execute();
        if($assignment->rowCount() > 0){
            return $assignment->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetSubjectTopicHandoutList(
        $subject_period_code_topic_id) {
            
        $handout = $this->con->prepare("SELECT 

            t1.*
            FROM subject_code_handout as t1
            WHERE subject_period_code_topic_id = :subject_period_code_topic_id
            AND is_given = :is_given
            ORDER BY 
                t1.subject_code_handout_template_id IS NULL ASC, 
                t1.subject_code_handout_template_id ASC, 
                t1.date_creation ASC

        ");

        $handout->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $handout->bindValue(":is_given", 1);
        $handout->execute();

        if($handout->rowCount() > 0){
            return $handout->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetAllNotDueAssignmentsBySubjectCode(
        $current_school_year_id, $subject_code){
        
            // echo $program_code;

        $current_date = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("SELECT t1.*, t2.subject_code_assignment_id

            FROM subject_period_code_topic AS t1

            INNER JOIN subject_code_assignment AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id

            WHERE t1.school_year_id = :school_year_id
            AND t1.subject_code = :subject_code

            AND t2.due_date >= :current_date
            AND t2.is_given =:is_given


            ORDER BY subject_code_assignment_id ASC 
        ");
                
        // $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->bindParam(":current_date", $current_date);
        $sql->bindValue(":is_given", 1);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetAllStudentAssignmentsSubmission($student_id,
        $current_school_year_id, $subject_code){
        
            // echo $subject_code;

        $sql = $this->con->prepare("SELECT t1.*

            FROM subject_assignment_submission AS t1

            INNER JOIN subject_code_assignment AS t2 
            ON t2.subject_code_assignment_id = t1.subject_code_assignment_id

            INNER JOIN subject_period_code_topic AS t3 
            ON t3.subject_period_code_topic_id = t2.subject_period_code_topic_id

            AND t3.subject_code = :subject_code
            AND t3.school_year_id = :t3_school_year_id

            WHERE t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id

            GROUP BY t1.subject_code_assignment_id
        ");

                
        $sql->bindParam(":subject_code", $subject_code);
        $sql->bindParam(":t3_school_year_id", $current_school_year_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $current_school_year_id);

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetAllAssignmentsBasedFromSubjectTopic(
        $subject_period_code_topic_id){
        
            // echo $subject_code;

        $sql = $this->con->prepare("SELECT t1.*

            FROM subject_code_assignment AS t1
            WHERE t1.subject_period_code_topic_id = :subject_period_code_topic_id
            AND t1.is_given = :is_given
        ");

                
        $sql->bindParam(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $sql->bindValue(":is_given", 1);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
            // return $sql->fetch(PDO::FETCH_ASSOC);
            // return $sql->fetchAll(PDO::FETCH_COLUMN);

        }

        return [];

    }


    public function GetAllTodosWithinSubjectCode($student_id,
        $current_school_year_id, $subject_code){
        
            // echo $program_code;

        $assignmentTodoIds = [];
        $allAssignmentsArray = [];
        $studentAssignmentSubmissionsArray = [];

        $allAssignments = $this->GetAllNotDueAssignmentsBySubjectCode(
            $current_school_year_id, $subject_code);

        $studentAssignmentSubmissions = $this->GetAllStudentAssignmentsSubmission(
            $student_id, $current_school_year_id,
            $subject_code);

        # Compare all student submissions and all assignments with respect to
        # 1. Current Term Period (Prelim, Midterm, Prefinal, FInal).
        # 1. Subject Code.
        # 2. allAssignments Due Date is not greater than to Current Date 

        // var_dump($studentAssignmentSubmissions);

        foreach ($allAssignments as $key => $value) {
            # code...
            array_push($allAssignmentsArray, $value['subject_code_assignment_id']);
        }

        foreach ($studentAssignmentSubmissions as $key => $value) {
            # code...
            array_push($studentAssignmentSubmissionsArray, $value['subject_code_assignment_id']);
        }

        # subject_code_assignment_id
        $assignmentTodoIds = array_diff($allAssignmentsArray, $studentAssignmentSubmissionsArray); 
        
    
        // Reset the array indexs
        if(count($assignmentTodoIds) > 0){
            $assignmentTodoIds = array_values($assignmentTodoIds);
        }


        // var_dump($assignmentTodoIds);

        return $assignmentTodoIds;

    }

    public function GetAllAssignmentOnTopicBased2(
        $subject_period_code_topic_id) : array {

        $arr = [];

        $now = date("Y-m-d H:i:s");

        $getSubjectTopicAssignments = $this->con->prepare("SELECT 

            t1.subject_code_assignment_id,
            t1.assignment_name

            FROM subject_code_assignment as t1

            WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id
            AND t1.due_date > :now_date

        ");

        $getSubjectTopicAssignments->bindValue(":subject_period_code_topic_id",
            $subject_period_code_topic_id);

        $getSubjectTopicAssignments->bindValue(":now_date", $now);

        $getSubjectTopicAssignments->execute();

        if($getSubjectTopicAssignments->rowCount() > 0){

            //    while($row = $getSubjectTopicAssignments->fetch(PDO::FETCH_ASSOC)){
            //         array_push($arr, $row['subject_code_assignment_id']);
            //    }

            $arr = $getSubjectTopicAssignments->fetchAll(PDO::FETCH_ASSOC);

        }
 
        return $arr;
    }

    public function GetAllAssignmentNotDueTopicBased(
        $subject_period_code_topic_id)  {

        $arr = [];

        $now = date("Y-m-d H:i:s");

        $getSubjectTopicAssignments = $this->con->prepare("SELECT 

            t1.subject_code_assignment_id
            -- ,  t1.assignment_name

            FROM subject_code_assignment as t1

            WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id
            AND t1.due_date > :now_date
            AND t1.is_given =:is_given

            ORDER BY subject_code_assignment_id DESC
            -- LIMIT 1
        ");

        $getSubjectTopicAssignments->bindValue(":subject_period_code_topic_id",
            $subject_period_code_topic_id);

        $getSubjectTopicAssignments->bindValue(":now_date", $now);
        $getSubjectTopicAssignments->bindValue(":is_given", 1);
        $getSubjectTopicAssignments->execute();

        if($getSubjectTopicAssignments->rowCount() > 0){

            // return $getSubjectTopicAssignments->fetchColumn();

            $arr = $getSubjectTopicAssignments->fetchAll(PDO::FETCH_ASSOC);
        }
 
        return $arr;
    }

    public function GetAllAssignmentopicBased(
        $subject_period_code_topic_id)  {

        $arr = [];

        $now = date("Y-m-d H:i:s");

        $getSubjectTopicAssignments = $this->con->prepare("SELECT 

            t1.subject_code_assignment_id
            -- ,  t1.assignment_name

            FROM subject_code_assignment as t1

            WHERE t1.subject_period_code_topic_id=:subject_period_code_topic_id
            AND t1.is_given =:is_given

            ORDER BY subject_code_assignment_id DESC
            -- LIMIT 1
        ");

        $getSubjectTopicAssignments->bindValue(":subject_period_code_topic_id",
            $subject_period_code_topic_id);

        // $getSubjectTopicAssignments->bindValue(":now_date", $now);
        $getSubjectTopicAssignments->bindValue(":is_given", 1);
        $getSubjectTopicAssignments->execute();

        if($getSubjectTopicAssignments->rowCount() > 0){

            // return $getSubjectTopicAssignments->fetchColumn();

            $arr = $getSubjectTopicAssignments->fetchAll(PDO::FETCH_ASSOC);
        }
 
        return $arr;
    }

    public function GetTeacherTeachingSubjects(
        $teacher_id, $current_school_year_id)  {

        $query = $this->con->prepare("SELECT 
            t1.*,
            t2.subject_title,
            t3.program_section

            FROM subject_schedule AS t1  

            LEFT JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id
            LEFT JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE t1.teacher_id=:teacher_id
            AND t1.school_year_id=:school_year_id
        ");

        $query->bindValue(":teacher_id", $teacher_id); 
        $query->bindValue(":school_year_id", $current_school_year_id); 
        $query->execute(); 

        if($query->rowCount() > 0){
            
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetSubjectAssignmentBasedOnTeachingSubject(
        $subject_code, $current_school_year_id, $teacher_id)  {

        $query = $this->con->prepare("SELECT 
            t1.*

            FROM subject_code_assignment AS t1  

            INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
            AND t2.subject_code=:subject_code
            AND t2.teacher_id=:teacher_id
            AND t2.school_year_id=:school_year_id

        ");

        $query->bindValue(":subject_code", $subject_code); 
        $query->bindValue(":teacher_id", $teacher_id); 
        $query->bindValue(":school_year_id", $current_school_year_id); 
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetSubjectHandoutBasedOnTeachingSubject(
        $subject_code, $current_school_year_id, $teacher_id)  {

        $query = $this->con->prepare("SELECT 
            t1.*

            FROM subject_code_handout AS t1  

            INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
            AND t2.subject_code=:subject_code
            AND t2.teacher_id=:teacher_id
            AND t2.school_year_id=:school_year_id

            AND t1.is_given=:is_given

            -- GROUP BY 
        ");

        $query->bindValue(":subject_code", $subject_code); 
        $query->bindValue(":teacher_id", $teacher_id); 
        $query->bindValue(":school_year_id", $current_school_year_id); 
        $query->bindValue(":is_given", 1); 
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    
    public function GetSubjectAssignmentDueBasedOnTeachingSubject(
        $subject_code, $current_school_year_id, $teacher_id)  {

        $query = $this->con->prepare("SELECT 
            t1.*

            FROM subject_code_assignment AS t1  

            INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
            AND t2.subject_code=:subject_code
            AND t2.teacher_id=:teacher_id
            AND t2.school_year_id=:school_year_id

        ");

        $query->bindValue(":subject_code", $subject_code); 
        $query->bindValue(":teacher_id", $teacher_id); 
        $query->bindValue(":school_year_id", $current_school_year_id); 
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetStudentGradeBookOnTeachingSubject(
        $subject_code, $current_school_year_id)  {

        $stud = $this->con->prepare("SELECT 
            t3.firstname
            ,t3.lastname
            ,t3.student_unique_id
            ,t3.student_id

            FROM student_subject as t1
            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
            AND t2.enrollment_status ='enrolled'

            INNER JOIN student as t3 ON t3.student_id = t2.student_id

            WHERE t1.subject_code=:subject_code
            AND t1.school_year_id=:school_year_id
            
            -- GROUP BY t3.student_id
        ");

        $stud->bindParam(":subject_code", $subject_code);
        $stud->bindParam(":school_year_id", $current_school_year_id);
        $stud->execute();

        if($stud->rowCount() > 0){
            return $stud->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


  

    public function GetNonTemplateAssignmentBasedOnSubjectTopic(
        $subject_period_code_topic_id) {
            
        $submission = $this->con->prepare("SELECT 

            t1.subject_code_assignment_id AS nonTemplateSubjectCodeAssignmentId,
            t1.assignment_name AS nonTemplateSubjectAssignmentName,
            t1.is_given AS nonTemplateSubjectAssignmentIsGiven

            FROM subject_code_assignment AS t1
             
            WHERE t1.subject_period_code_topic_id = :subject_period_code_topic_id
            AND subject_code_assignment_template_id IS NULL

        ");

        $submission->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $submission->execute();
         
        if($submission->rowCount() > 0){
            return $submission->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GiveAssignment(
        $subject_period_code_topic_id,
        $teacher_id,
        $subject_code_assignment_id,
        $type) {



        $type = $type === "give" ? 1 : 0;

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

        $doesOwnedByAuthorizedTeacher = $subjectPeriodCodeTopic->CheckTeacherOwnedTheSubjectTopic(
            $subject_period_code_topic_id, $teacher_id
        );
        
        if($doesOwnedByAuthorizedTeacher === true){

            $update = $this->con->prepare("UPDATE subject_code_assignment
                SET is_given = :is_given
                WHERE subject_code_assignment_id = :subject_code_assignment_id");

            $update->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $update->bindValue(":is_given", $type);
            $update->execute();


            if ($update->rowCount() > 0) {
                return true;
            }
        }


        return false;
    }


    public function RemoveAssignment(
        $subject_period_code_topic_id,
        $teacher_id,
        $subject_code_assignment_id) {

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

        $doesOwnedByAuthorizedTeacher = $subjectPeriodCodeTopic->CheckTeacherOwnedTheSubjectTopic(
            $subject_period_code_topic_id, $teacher_id
        );

        if($doesOwnedByAuthorizedTeacher === true){

            $update = $this->con->prepare("DELETE FROM subject_code_assignment
                WHERE subject_code_assignment_id = :subject_code_assignment_id");

            $update->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $update->execute();
            if ($update->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function RemovingAssignmentFiles(
        $subject_code_assignment_id,
        $subject_code_assignment_list_id){

        $query = $this->con->prepare("DELETE FROM subject_code_assignment_list
            WHERE subject_code_assignment_list_id = :subject_code_assignment_list_id
            AND subject_code_assignment_id = :subject_code_assignment_id
        ");
        
        $query->bindValue(":subject_code_assignment_list_id", $subject_code_assignment_list_id);
        $query->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        }

        return false;

    }

   

}