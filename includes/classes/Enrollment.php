<?php

    class Enrollment{

    private $con, $userLoggedIn;

    public function __construct($con)
    {
        $this->con = $con;
    }


    public function GetStudentEnrolled($course_id){

        $sql = $this->con->prepare("SELECT * FROM course as t1

            INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
            WHERE t2.course_id=:course_id
            AND t2.enrollment_status=:enrollment_status
        ");
                
        $sql->bindParam(":course_id", $course_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        return $sql->rowCount();

    }
}
?>