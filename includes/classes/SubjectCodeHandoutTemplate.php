<?php

class SubjectCodeHandoutTemplate{

    private $con, $sqlData, $subject_code_handout_template_id;

    public function __construct($con, $subject_code_handout_template_id = null){

        $this->con = $con;
        $this->subject_code_handout_template_id = $subject_code_handout_template_id;

        $query = $this->con->prepare("SELECT * FROM subject_code_handout_template
                WHERE subject_code_handout_template_id=:subject_code_handout_template_id");

        $query->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetHandoutName() {
        return isset($this->sqlData['handout_name']) ? $this->sqlData["handout_name"] : null; 
    }

    public function GetSubject_period_code_topic_template_id () {
        return isset($this->sqlData['subject_period_code_topic_template_id']) ? $this->sqlData["subject_period_code_topic_template_id"] : null; 
    }

    public function GetSubjectCodeHandoutTemplateId () {
        return isset($this->sqlData['subject_code_handout_template_id']) ? $this->sqlData["subject_code_handout_template_id"] : null; 
    }

    public function GetFile () {
        return isset($this->sqlData['file']) ? $this->sqlData["file"] : null; 
    }


    public function GetDateCreation () {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : null; 
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

    public function AddHandoutTemplateToHandoutTopic(
        $subject_period_code_topic_id,
        $subject_code_handout_template_id,
        $handout_name,
        $file){

        $date_creation = date("Y-m-d H:i:s");

       $insert = $this->con->prepare("INSERT INTO subject_code_handout
            (handout_name, subject_period_code_topic_id, subject_code_handout_template_id,
            file, date_creation, is_given)
            VALUES(:handout_name, :subject_period_code_topic_id, :subject_code_handout_template_id,
            :file, :date_creation, :is_given)");

        $insert->bindValue(":handout_name", $handout_name);
        $insert->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $insert->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
        $insert->bindValue(":file", $file);
        $insert->bindValue(":date_creation", $date_creation);
        $insert->bindValue(":is_given", 1);
        $insert->execute();

        if ($insert->rowCount() > 0) {
            return true;
        }

        return false;

    }

    public function AddHandout($subject_period_code_topic_template_id,
        $handout_name,
        $imagePath) {

        $create = $this->con->prepare("INSERT INTO subject_code_handout_template
            (subject_period_code_topic_template_id, handout_name, file)
            VALUES(:subject_period_code_topic_template_id, :handout_name, :file)");
        
        $create->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        $create->bindValue(":handout_name", $handout_name);
        $create->bindValue(":file", $imagePath);
        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateHandout($subject_code_handout_template_id, $handout_name, $imagePath) {
        $update = $this->con->prepare("UPDATE subject_code_handout_template
            SET handout_name = :handout_name, file = :file
            WHERE subject_code_handout_template_id = :subject_code_handout_template_id");

        $update->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
        $update->bindValue(":handout_name", $handout_name);
        $update->bindValue(":file", $imagePath);
        $update->execute();

        if ($update->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function RemovingHandoutTemplateFile(
        $subject_period_code_topic_template_id,
        $subject_code_handout_template_id){

        $query = $this->con->prepare("DELETE FROM subject_code_handout_template
            WHERE subject_code_handout_template_id = :subject_code_handout_template_id
            AND subject_period_code_topic_template_id = :subject_period_code_topic_template_id
        ");
        
        $query->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
        $query->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
        $query->execute();

        if ($query->rowCount() > 0) {
          return true;
        }

        return false;
    }

}
?>