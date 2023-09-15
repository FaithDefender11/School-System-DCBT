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

    public function AddTopic($course_id,$teacher_id, $school_year_id,
        $topic, $description,$subject_period_name,
        $subject_code, $program_code, $period_order) {

        $teacher_id = $teacher_id == 0 ? NULL : $teacher_id;

        $add = $this->con->prepare("INSERT INTO subject_period_code_topic
            (course_id,teacher_id, school_year_id,
                topic, description,subject_period_name,
                subject_code, program_code, period_order)
            VALUES(:course_id,:teacher_id, :school_year_id,
                :topic, :description,:subject_period_name,
                :subject_code, :program_code, :period_order)");
        
        $add->bindValue(":course_id", $course_id);
        $add->bindValue(":teacher_id", $teacher_id);
        $add->bindValue(":school_year_id", $school_year_id);
        $add->bindValue(":topic", $topic);
        $add->bindValue(":description", $description);
        $add->bindValue(":subject_period_name", $subject_period_name);
        $add->bindValue(":subject_code", $subject_code);
        $add->bindValue(":program_code", $program_code);
        $add->bindValue(":period_order", $period_order);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }

        return false;

    }


    public function AddTopicTemplate(
        $topic, $description,$subject_period_name,
        $program_code) {


        $add = $this->con->prepare("INSERT INTO subject_period_code_topic_template
            (topic, description,subject_period_name,
                program_code)
            VALUES(:topic, :description,:subject_period_name,
                :program_code)");
        
        $add->bindValue(":topic", $topic);
        $add->bindValue(":description", $description);
        $add->bindValue(":subject_period_name", $subject_period_name);
        $add->bindValue(":program_code", $program_code);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }

        return false;

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
     
}