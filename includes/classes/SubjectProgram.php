<?php

class SubjectProgram{

    private $con, $subject_program_id, $sqlData;


    public function __construct($con, $subject_program_id = null){
        $this->con = $con;
        $this->subject_program_id = $subject_program_id;

        $query = $this->con->prepare("SELECT * FROM subject_program
                WHERE subject_program_id=:subject_program_id");

        $query->bindValue(":subject_program_id", $subject_program_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectProgramRawCode() {
        return isset($this->sqlData['subject_code']) ? ucfirst($this->sqlData["subject_code"]) : ""; 
    }

    public function GetProgramId() {
        return isset($this->sqlData['program_id']) ? $this->sqlData["program_id"] : ""; 
    }

    public function GetPreRequisite() {
        return isset($this->sqlData['pre_req_subject_title']) ? $this->sqlData["pre_req_subject_title"] : ""; 
    }

    public function GetSubjectType() {
        return isset($this->sqlData['subject_type']) ? ucfirst($this->sqlData["subject_type"]) : ""; 
    }

    public function GetUnit() {
        return isset($this->sqlData['unit']) ? ucfirst($this->sqlData["unit"]) : ""; 
    }

    public function GetTitle() {
        return isset($this->sqlData['subject_title']) ? ucfirst($this->sqlData["subject_title"]) : ""; 
    }
    public function GetDescription() {
        return isset($this->sqlData['description']) ? ucfirst($this->sqlData["description"]) : ""; 
    }

    public function GetSemester() {
        return isset($this->sqlData['semester']) ? ucfirst($this->sqlData["semester"]) : ""; 
    }

    public function GetCourseLevel() {
        return isset($this->sqlData['course_level']) ? ucfirst($this->sqlData["course_level"]) : ""; 
    }

    public function GetTemplateId() {
        return isset($this->sqlData['subject_template_id']) ? ucfirst($this->sqlData["subject_template_id"]) : ""; 
    }

}
?>