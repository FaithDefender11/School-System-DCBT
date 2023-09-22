<?php

class SubjectPeriodCodeTopicTemplate{

    private $con, $sqlData, $subject_period_code_topic_template_id;

    public function __construct($con, $subject_period_code_topic_template_id = null){

        $this->con = $con;
        $this->subject_period_code_topic_template_id = $subject_period_code_topic_template_id;

        $query = $this->con->prepare("SELECT * FROM subject_period_code_topic_template
                WHERE subject_period_code_topic_template_id=:subject_period_code_topic_template_id");

        $query->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetTopic() {
        return isset($this->sqlData['topic']) ? $this->sqlData["topic"] : ""; 
    }

    public function GetDescription() {
        return isset($this->sqlData['description']) ? $this->sqlData["description"] : ""; 
    }
    public function GetTeacherId() {
        return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : NULL; 
    }
    public function GetCourseId() {
        return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : NULL; 
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

    public function GetTopicTemplateIdByTopicName($topic) {

        $sql = $this->con->prepare("SELECT subject_period_code_topic_template_id
            FROM subject_period_code_topic_template
            
            WHERE topic=:topic");
                
        $sql->bindValue(":topic", $topic);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;

    }

    public function UpdateTopicTemplate($subject_period_code_topic_template_id,
        $topic, $description, $subject_period_name, $program_code) {
        try {
            $stmt = $this->con->prepare("UPDATE subject_period_code_topic_template
                SET topic = :topic, description = :description, subject_period_name = :subject_period_name, program_code = :program_code
                WHERE subject_period_code_topic_template_id = :subject_period_code_topic_template_id");

            $stmt->bindParam(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
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
    

}
?>