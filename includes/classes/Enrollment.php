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

    public function UnionEnrollment(){

        $sql = $this->con->prepare(" SELECT t1.firstname, t1.lastname, 

            t1.student_statusv2,
            t1.admission_status,
            t2.enrollment_date AS submission_creation,
            t3.program_id,
            NULL AS student_status_pending,
            t1.admission_status AS admission_status,
            t1.is_tertiary AS student_classification,
            NULL AS pending_enrollees_id,
            t1.student_statusv2 AS student_statusv2,
            t2.course_id AS student_course_id,
            t1.student_id AS student_id,
            t1.student_unique_id AS student_unique_id
            
            -- Ongoing Student QUERY.

            FROM student as t1
            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND t2.course_id = t1.course_id

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id
            LEFT JOIN program as t4 ON t4.program_id = t3.program_id
            WHERE t1.admission_status = 'Transferee'

            AND t2.registrar_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'
            AND t2.is_new_enrollee = 'no'

            UNION

            SELECT t1.firstname, t1.lastname, NULL, NULL,
            t1.date_creation AS submission_creation,
            t1.program_id,
            t1.admission_status AS student_status_pending,
            -- t1.student_status AS student_status_pending,
            NULL AS admission_status,
            NULL AS student_classification,
            t1.pending_enrollees_id,
            NULL AS student_statusv2,
            NULL AS student_course_id,
            NULL AS student_id,
            NULL AS student_unique_id

            FROM pending_enrollees as t1
            LEFT JOIN program as t2 ON t2.program_id = t1.program_id
            WHERE t1.student_status != 'APPROVED'
            AND t1.is_finished = 1
        ");

        $sql->execute();
        if($sql->rowCount() > 0){
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }


    public function GenerateEnrollmentFormId(){

        // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $length = 6;
        // $enrollmentFormId = substr(str_shuffle($characters), 0, $length);

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        $maxAttempts = 100;

        // $sql = $this->con->prepare("SELECT * FROM enrollment
        //     WHERE enrollment_form_id=:enrollment_form_id");

        // $sql->bindValue(":enrollment_form_id", $enrollmentFormId);
        // $sql->execute();

        // if($sql->rowCount() > 0){

        //     # Changed the enrollment_form_id
        // }

        $enrollmentFormId = $this->generateUniqueId($characters, $length);
        $attempt = 1;

        while ($this->isUniqueIdExists($enrollmentFormId) 
            && $attempt <= $maxAttempts) {
            $enrollmentFormId = $this->generateUniqueId($characters, $length);
            $attempt++;
        }

        if ($attempt > $maxAttempts) {
            // Maximum attempts reached, handle the error accordingly
            // You can throw an exception, display an error message, or take any other desired action
            
        }

        return $enrollmentFormId;
    }

    function generateUniqueId($characters, $length) {
        return substr(str_shuffle($characters), 0, $length);
    }   

    function isUniqueIdExists($enrollmentFormId) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT * FROM enrollment 
            WHERE enrollment_form_id = :enrollment_form_id");
        $sql->bindValue(":enrollment_form_id", $enrollmentFormId);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function GetEnrollmentId($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        
        return $sql->fetchColumn();
    }

    function GetEnrollmentFormId($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    function GetEnrollmentDate($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_date FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }
}
?>