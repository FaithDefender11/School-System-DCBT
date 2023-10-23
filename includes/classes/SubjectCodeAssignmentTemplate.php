<?php

class SubjectCodeAssignmentTemplate{

    private $con, $sqlData, $subject_code_assignment_template_id;

    public function __construct($con, $subject_code_assignment_template_id = null){

        $this->con = $con;
        $this->subject_code_assignment_template_id = $subject_code_assignment_template_id;

        $query = $this->con->prepare("SELECT * FROM subject_code_assignment_template
                WHERE subject_code_assignment_template_id=:subject_code_assignment_template_id");

        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectPeriodCodeTopicTemplate_id() {
        return isset($this->sqlData['subject_period_code_topic_template_id']) ? $this->sqlData["subject_period_code_topic_template_id"] : null; 
    }

    public function GetSubjectPeriodCodeTopicId() {
        return isset($this->sqlData['subject_period_code_topic_template_id']) ? $this->sqlData["subject_period_code_topic_template_id"] : NULL; 
    }
    public function GetAssignmentName() {
        return isset($this->sqlData['assignment_name']) ? $this->sqlData["assignment_name"] : ""; 
    }

    public function GetAssignmentImage() {
        return isset($this->sqlData['assignment_image']) ? $this->sqlData["assignment_image"] : NULL; 
    }

    public function GetDescription() {
        return isset($this->sqlData['description']) ? $this->sqlData["description"] : NULL; 
    }

    public function GetTaskTypeId() {
        return isset($this->sqlData['task_type_id']) ? $this->sqlData["task_type_id"] : NULL; 
    }

    public function GetDueDate() {
        return isset($this->sqlData['due_date']) ? $this->sqlData["due_date"] : NULL; 
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

    public function GetCodeAssignmentTopicTemplateList(
        $subject_period_code_topic_template_id,
        $task_type_id = NULL) {

        $task_type_query = "";

        // echo $subject_period_code_topic_template_id;

        if($task_type_id !== NULL){
            $task_type_query = "AND t1.task_type_id =:task_type_id";
            // echo $task_type_query;
        }

        $sql = $this->con->prepare("SELECT 
        
            t1.*

            ,t2.subject_code_assignment_id as template_subject_code_assignment_id

            FROM subject_code_assignment_template as t1
            LEFT JOIN subject_code_assignment as t2 ON t2.subject_code_assignment_template_id = t1.subject_code_assignment_template_id
            
            WHERE subject_period_code_topic_template_id = :subject_period_code_topic_template_id
            $task_type_query

        ");
                
        $sql->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        
        if($task_type_id != NULL){
            // echo "yyy: $task_type_id";
            $sql->bindValue(":task_type_id", $task_type_id);
        }

        $sql->execute();

        if($sql->rowCount() > 0){
            // echo "hey";

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetCodeHandoutTopicTemplateList($subject_period_code_topic_template_id) {

        $sql = $this->con->prepare("SELECT *
            FROM subject_code_handout_template
            
            WHERE subject_period_code_topic_template_id=:subject_period_code_topic_template_id");
                
        $sql->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetTemplateUploadAssignmentFiles(
        $subject_code_assignment_template_id) {

        $query = $this->con->prepare("SELECT * 

            FROM subject_code_assignment_template_list
            WHERE subject_code_assignment_template_id=:subject_code_assignment_template_id");
        
        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetSingleTemplateUploadAssignmentFile(
        $subject_code_assignment_template_list_id,
        $subject_code_assignment_template_id) {

        $query = $this->con->prepare("SELECT image

            FROM subject_code_assignment_template_list
            WHERE subject_code_assignment_template_list_id=:subject_code_assignment_template_list_id
            AND subject_code_assignment_template_id=:subject_code_assignment_template_id

            ");
        
        $query->bindValue(":subject_code_assignment_template_list_id", $subject_code_assignment_template_list_id);
        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return NULL;
    }

    public function GetAssignmentTemplateListFiles(
        $subject_code_assignment_template_id) {

        $query = $this->con->prepare("SELECT *

            FROM subject_code_assignment_template_list
            WHERE subject_code_assignment_template_id=:subject_code_assignment_template_id

            ");
        
        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }
    public function RemovingAssignmentTemplateFiles($subject_code_assignment_template_list_id,
        $subject_code_assignment_template_id){

        $query = $this->con->prepare("DELETE FROM subject_code_assignment_template_list
            WHERE subject_code_assignment_template_list_id = :subject_code_assignment_template_list_id
            AND subject_code_assignment_template_id = :subject_code_assignment_template_id
        ");
        
        $query->bindValue(":subject_code_assignment_template_list_id", $subject_code_assignment_template_list_id);
        $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $query->execute();

        if ($query->rowCount() > 0) {
          return true;
        }
        return false;

    }
   

    public function AddAssignmentTemplate(
        $subject_period_code_topic_template_id,
        $assignment_name, $description,
        $max_score, $type, $task_type_id){

        $insert = $this->con->prepare("INSERT INTO subject_code_assignment_template
            (assignment_name, subject_period_code_topic_template_id, description,
                max_score, type, task_type_id)
            VALUES(:assignment_name, :subject_period_code_topic_template_id, :description,
                :max_score, :type, :task_type_id)");

        $insert->bindValue(":assignment_name", $assignment_name);
        $insert->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        $insert->bindValue(":description", $description);
        // $insert->bindValue(":max_attempt", $max_attempt);
        $insert->bindValue(":max_score", $max_score);
        $insert->bindValue(":type", $type);
        $insert->bindValue(":task_type_id", $task_type_id);
        $insert->execute();

        if($insert->rowCount() > 0){
            return true;
        }

        return false;

    }


    public function UpdateAssignmentTemplate(
        $subject_code_assignment_template_id,
        $assignment_name,
        $description,
        $max_score,
        $type, $task_type_id
    ) {
        $update = $this->con->prepare("UPDATE subject_code_assignment_template
            SET assignment_name = :assignment_name,
                description = :description,
                max_score = :max_score,
                type = :type,
                task_type_id = :task_type_id
                
            WHERE subject_code_assignment_template_id = :subject_code_assignment_template_id
            ");

        $update->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
        $update->bindValue(":assignment_name", $assignment_name);
        $update->bindValue(":description", $description);
        $update->bindValue(":max_score", $max_score);
        $update->bindValue(":type", $type);
        $update->bindValue(":task_type_id", $task_type_id);

        $update->execute();

        if ($update->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function UploadAssignmentTemplateFiles(
        $subject_code_assignment_template_id,
        $image) {

        $add = $this->con->prepare("INSERT INTO subject_code_assignment_template_list
            (subject_code_assignment_template_id, image)
            VALUES(:subject_code_assignment_template_id, :image)
        ");
        
        $add->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id );
        $add->bindValue(":image", $image);
        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }
        return false;
    }

}

?>