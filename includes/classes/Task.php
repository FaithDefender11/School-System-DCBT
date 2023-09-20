<?php

class Task{

    private $con, $sqlData;

    public function __construct($con){
        $this->con = $con;
    }

    
    public function MarkStudentAsApplicable() {

        $school_year = new SchoolYear($this->con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];

        $studentQuery = $this->con->prepare("SELECT *
            
            FROM student as t1

            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND school_year_id=:school_year_id
            AND enrollment_status=:enrollment_status


            WHERE t1.active = 1
            AND t1.nsy_applicable = 0
            
        ");
        $studentQuery->bindParam(":school_year_id", $current_school_year_id);
        $studentQuery->bindValue(":enrollment_status", "enrolled");
        $studentQuery->execute();

        if($studentQuery->rowCount() > 0){

            $enrollment = new Enrollment($this->con);
            $student_subject = new StudentSubject($this->con);

            while($row = $studentQuery->fetch(PDO::FETCH_ASSOC)){

                $student_name = $row['firstname'];
                $student_id = $row['student_id'];

                // Get student enrollment form id within current semester & S.Y
                $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
                    $current_school_year_id);

                $applicableStudentId = $student_subject->CheckCurrentSemesterSubjectAllPassed($student_enrollment_id,
                    $student_id, $current_school_year_id);

                if($applicableStudentId != 0){

                    $student = new Student($this->con, $applicableStudentId);

                    $applicable = $student->DoesApplicableToApplyNextYear();

                    if($applicable == 0){

                        if($student->UpdateStudentApplicableApplyNextSY($applicableStudentId) == true){

                            // Student id that has qualified requirements.
                            // Enrollment form based.
                            echo "Student ID: ". $applicableStudentId . " has been eligible to apply next s_y";

                        }
                    }
                }
                else{
                    // echo "nothing eligible";
                }
            }
        }
    }

    
}

?>