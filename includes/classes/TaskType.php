<?php

class TaskType{

    private $con, $sqlData, $task_type_id;

    public function __construct($con, $task_type_id = null){

        $this->con = $con;
        $this->task_type_id = $task_type_id;

        $query = $this->con->prepare("SELECT * FROM task_type
                WHERE task_type_id=:task_type_id");

        $query->bindValue(":task_type_id", $task_type_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetTaskName() {
        return isset($this->sqlData['task_name']) ? $this->sqlData["task_name"] : null; 
    }

    public function GetDateCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : null; 
    }

    public function GetEnabled () {
        return isset($this->sqlData['enabled']) ? $this->sqlData["enabled"] : null; 
    }

    public function GetTaskTypeModuleCount(
        $task_type_id, $subject_period_code_topic_id){

         $query = $this->con->prepare("SELECT 
        
            t1.* 

            FROM subject_code_assignment as t1

            
            WHERE t1.task_type_id =:task_type_id
            AND t1.subject_period_code_topic_id=:subject_period_code_topic_id
            -- AND t1.is_given=:is_given

            -- ORDER BY t1.date_creation DESC

        ");

        $query->bindValue(":task_type_id", $task_type_id);
        $query->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
        // $query->bindValue(":is_given", 1);

        $query->execute();
 
        return $query->rowCount();
    }

}

?>