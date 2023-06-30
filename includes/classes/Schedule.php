<?php

    class Schedule{

    private $con, $userLoggedIn;

    public function __construct($con)
    {
        $this->con = $con;
    }


    public function CheckIfTeacherAlreadyScheduleToTheSubject($subject_id,
        $teacher_id){

        echo $subject_id;
        echo "<br>";
        echo $teacher_id;
        echo "<br>";

        $sql = $this->con->prepare("SELECT * FROM subject_schedule as t1

            WHERE t1.subject_id=:subject_id
            AND t1.teacher_id=:teacher_id
        ");
                
        $sql->bindParam(":subject_id", $subject_id);
        $sql->bindParam(":teacher_id", $teacher_id);
        $sql->execute();

        return $sql->rowCount() > 0;
    }
}
?>