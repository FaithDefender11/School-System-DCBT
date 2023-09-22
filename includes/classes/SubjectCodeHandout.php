<?php

class SubjectCodeHandout{

    private $con, $sqlData, $subject_code_handout_id;

    public function __construct($con, $subject_code_handout_id = null){

        $this->con = $con;
        $this->subject_code_handout_id = $subject_code_handout_id;

        $query = $this->con->prepare("SELECT * FROM subject_code_handout
                WHERE subject_code_handout_id=:subject_code_handout_id");

        $query->bindValue(":subject_code_handout_id", $subject_code_handout_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetHandoutName() {
        return isset($this->sqlData['handout_name']) ? $this->sqlData["handout_name"] : null; 
    }

    public function GetSubject_period_code_topic_id () {
        return isset($this->sqlData['subject_period_code_topic_id']) ? $this->sqlData["subject_period_code_topic_id"] : null; 
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

    public function AddHandout($subject_period_code_topic_id, $handout_name,
        $imagePath) {

        $create = $this->con->prepare("INSERT INTO subject_code_handout
            (subject_period_code_topic_id, handout_name, file)
            VALUES(:subject_period_code_topic_id, :handout_name, :file)");
        
        $create->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $create->bindValue(":handout_name", $handout_name);
        $create->bindValue(":file", $imagePath);
        $create->execute();

        if($create->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function UpdateHandout($subject_code_handout_id, $handout_name, $imagePath) {
        $update = $this->con->prepare("UPDATE subject_code_handout
            SET handout_name = :handout_name, file = :file
            WHERE subject_code_handout_id = :subject_code_handout_id");

        $update->bindValue(":subject_code_handout_id", $subject_code_handout_id);
        $update->bindValue(":handout_name", $handout_name);
        $update->bindValue(":file", $imagePath);
        $update->execute();

        if ($update->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function GetNonTemplateHandoutBasedOnSubjectTopic(
        $subject_period_code_topic_id) {
            
        $submission = $this->con->prepare("SELECT 

            t1.subject_code_handout_id AS nonTemplateSubjectCodeHandoutId,
            t1.file AS nonTemplateFile,
            t1.handout_name AS nonTemplateHandoutName,
            t1.is_given AS nonTemplateSubjectHandoutIsGiven


            FROM subject_code_handout AS t1
             
            WHERE t1.subject_period_code_topic_id = :subject_period_code_topic_id
            AND subject_code_handout_template_id IS NULL
        ");

        $submission->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        $submission->execute();
         
        if($submission->rowCount() > 0){
            return $submission->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GiveHandout(
        $subject_period_code_topic_id,
        $teacher_id,
        $subject_code_handout_id) {

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

        $doesOwnedByAuthorizedTeacher = $subjectPeriodCodeTopic->CheckTeacherOwnedTheSubjectTopic(
            $subject_period_code_topic_id, $teacher_id
        );
        
        if($doesOwnedByAuthorizedTeacher === true){

            $update = $this->con->prepare("UPDATE subject_code_handout
                SET is_given = :is_given
                WHERE subject_code_handout_id = :subject_code_handout_id");

            $update->bindValue(":subject_code_handout_id", $subject_code_handout_id);
            $update->bindValue(":is_given", 1);
            $update->execute();


            if ($update->rowCount() > 0) {
                return true;
            }
        }


        return false;
    }

    public function UnGiveHandout(
        $subject_period_code_topic_id,
        $teacher_id,
        $subject_code_handout_id) {
        
        if($this->CheckTeacherOwnedTheSubjectTopic($subject_period_code_topic_id,
            $teacher_id) == true){

            $update = $this->con->prepare("UPDATE subject_code_handout
                SET is_given = :is_given
                WHERE subject_code_handout_id = :subject_code_handout_id");

            $update->bindValue(":subject_code_handout_id", $subject_code_handout_id);
            $update->bindValue(":is_given", 0);
            $update->execute();

            if ($update->rowCount() > 0) {
                return true;
            }
        }
        return false;
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
 
    public function RemoveHandout(
        $subject_period_code_topic_id,
        $teacher_id,
        $subject_code_handout_id) {

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

        $doesOwnedByAuthorizedTeacher = $subjectPeriodCodeTopic->CheckTeacherOwnedTheSubjectTopic(
            $subject_period_code_topic_id, $teacher_id
        );
        
        if($doesOwnedByAuthorizedTeacher === true){

            $update = $this->con->prepare("DELETE FROM subject_code_handout
                WHERE subject_code_handout_id = :subject_code_handout_id");

            $update->bindValue(":subject_code_handout_id", $subject_code_handout_id);
            $update->execute();

            if ($update->rowCount() > 0) {
                return true;
            }
            
        }

        return false;
    }

    
}
?>