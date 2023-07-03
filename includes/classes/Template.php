<?php

    class Template{

    private $con, $template_id, $sqlData;

    public function __construct($con, $template_id)
    {
        $this->con = $con;
        $this->template_id = $template_id;

        $query = $this->con->prepare("SELECT * FROM subject_template 
            WHERE subject_template_id = :subject_template_id");

        $query->bindParam(":subject_template_id", $template_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetTemplateSubjectName() {
        return isset($this->sqlData['subject_title']) ? $this->sqlData["subject_title"] : "N/A"; 
    }

    
}
?>