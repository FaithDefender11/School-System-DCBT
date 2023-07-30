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

    public function CheckIdExists($subject_program_id) {

        $query = $this->con->prepare("SELECT * FROM subject_program
                WHERE subject_program_id=:subject_program_id");

        $query->bindParam(":subject_program_id", $subject_program_id);
        $query->execute();

        if($query->rowCount() == 0){
            echo "
                <div class='col-md-12'>
                    <h4 class='text-center text-warning'>ID Doesnt Exists.</h4>
                </div>
            ";
            exit();
        }
    }

    public function GetSubjectProgramRawCode() {
        return isset($this->sqlData['subject_code']) ? ucfirst($this->sqlData["subject_code"]) : ""; 
    }
    
    public function GetSubjectProgramId() {
        return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : ""; 
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

    public function CheckIfSubjectProgramExists($subject_title, $program_id) {


        $query = $this->con->prepare("SELECT * FROM subject_program
                WHERE subject_title=:subject_title
                AND program_id=:program_id
            ");
        $query->bindParam(":subject_title", $subject_title);
        $query->bindParam(":program_id", $program_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function GetPreRequisiteSubjectByCode($subject_code, $subject_program_id) {


        $query = $this->con->prepare("SELECT pre_req_subject_title 
                
            FROM subject_program

            WHERE subject_code=:subject_code
            AND subject_program_id=:subject_program_id
            LIMIT 1
            ");
        $query->bindParam(":subject_code", $subject_code);
        $query->bindParam(":subject_program_id", $subject_program_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return "";
    }

    public function CheckIfSubjectProgramEditExists($subject_title) {

        $query = $this->con->prepare("SELECT * FROM subject_program
                WHERE subject_title=:subject_title
            ");
        $query->bindParam(":subject_title", $subject_title);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;
    }


    public function GetStudentCurriculumBasedOnSemesterSubject($program_id,
        $student_id, $GRADE_ELEVEN, $SELECTED_SEMESTER){

        // Enrollment student course_id
        $subject_query = $this->con->prepare("SELECT 

            t3.subject_code as t3_subject_code,
            t3.subject_id, t3.course_id,
        
            t1.*,

            t4.first,
            t4.second,
            t4.third,
            t4.fourth,
            t4.remarks as grade_remarks,


            t5.time_from,
            t5.time_to,
            t5.schedule_day,
            t5.schedule_time

            FROM subject_program as t1

            LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
            AND t2.student_id=:student_id
            
            LEFT JOIN subject as t3 ON t3.subject_id = t2.subject_id
            LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id
            LEFT JOIN subject_schedule as t5 ON t5.subject_id = t2.subject_id

            WHERE t1.semester=:semester
            AND t1.program_id=:program_id
            AND t1.course_level=:course_level

            ");
         

        $subject_query->bindValue(":semester", $SELECTED_SEMESTER); 
        $subject_query->bindValue(":program_id", $program_id); 
        $subject_query->bindValue(":course_level", $GRADE_ELEVEN); 
        $subject_query->bindValue(":student_id", $student_id); 
        $subject_query->execute();

        if($subject_query->rowCount() > 0){

            $row_sub = $subject_query->fetchAll(PDO::FETCH_ASSOC);
            // print_r($row_sub);
            return $row_sub;
        }
        
        return [];

    }

    public function GetStudentEnrolledSubject($program_id,
        $student_id, $GRADE_ELEVEN, $SELECTED_SEMESTER){

        // Enrollment student course_id
        $subject_query = $this->con->prepare("SELECT 

            -- t3.subject_code as t3_subject_code,
            -- t3.subject_id, t3.course_id,
        
            t1.*,

            t2.subject_code AS student_subject_code,
            t2.student_subject_id,
            t2.enrollment_id,
            t2.is_transferee, 
            t2.is_final, 

            t3.course_id, t3.program_section,
            t4.student_subject_grade_id,
            t4.student_subject_id AS graded_student_subject_id,
            t4.remarks,


            -- t4.first,
            -- t4.second,
            -- t4.third,
            -- t4.fourth,
            -- t4.remarks as grade_remarks,


            t5.time_from,
            t5.time_to,
            t5.schedule_day,
            t5.schedule_time,
            t5.room,

            t6.firstname,
            t6.lastname

            FROM subject_program as t1

            LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
            AND t2.student_id=:student_id
            
            LEFT JOIN course as t3 ON t3.course_id = t2.course_id

            LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id

            LEFT JOIN subject_schedule as t5 ON t5.subject_code = t2.subject_code
            AND t5.course_id = t2.course_id

            LEFT JOIN teacher as t6 ON t6.teacher_id = t5.teacher_id

            WHERE t1.semester=:semester
            AND t1.program_id=:program_id
            AND t1.course_level=:course_level

            ");
         

        $subject_query->bindParam(":semester", $SELECTED_SEMESTER); 
        $subject_query->bindParam(":program_id", $program_id); 
        $subject_query->bindParam(":course_level", $GRADE_ELEVEN); 
        $subject_query->bindParam(":student_id", $student_id); 
        $subject_query->execute();

        if($subject_query->rowCount() > 0){

            $row_sub = $subject_query->fetchAll(PDO::FETCH_ASSOC);
            // print_r($row_sub);
            return $row_sub;
        }
        
        return [];

    }



    public function GetStudentEnrolledSubjectCodeBase($program_id,
        $student_id, $GRADE_ELEVEN, $SELECTED_SEMESTER){

            // echo $program_id;
        // Enrollment student course_id
        $subject_query = $this->con->prepare("SELECT 

            -- t3.subject_code as t3_subject_code,
            -- t3.subject_id, t3.course_id,
            t1.*,

            t2.subject_code AS student_subject_code,
            t2.student_subject_id,
            t2.program_code,
            t2.enrollment_id,
            t2.is_transferee, 
            t2.is_final, 
            t2.retake, 

            t3.course_id, t3.program_section,
            t4.student_subject_id AS graded_student_subject_id,
            t4.remarks
                
            -- t5.time_from,
            -- t5.time_to,
            -- t5.schedule_day,
            -- t5.schedule_time,
            -- t5.room,

            -- t6.firstname,
            -- t6.lastname

            FROM subject_program as t1

            -- LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
            LEFT JOIN student_subject as t2 ON t2.program_code = t1.subject_code
            AND t2.student_id=:student_id

            -- AND t2.retake = 0
            -- AND t2.overlap = 0

            
            LEFT JOIN course as t3 ON t3.course_id = t2.course_id
            LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id
            
            -- LEFT JOIN subject_schedule as t5 ON t5.subject_code = t2.subject_code
            -- AND t5.course_id = t2.course_id

            -- LEFT JOIN teacher as t6 ON t6.teacher_id = t5.teacher_id

            WHERE t1.semester=:semester
            AND t1.program_id=:program_id
            AND t1.course_level=:course_level

        ");

        $subject_query->bindParam(":semester", $SELECTED_SEMESTER); 
        $subject_query->bindParam(":program_id", $program_id); 
        $subject_query->bindParam(":course_level", $GRADE_ELEVEN); 
        $subject_query->bindParam(":student_id", $student_id); 
        $subject_query->execute();

        if($subject_query->rowCount() > 0){

            $row_sub = $subject_query->fetchAll(PDO::FETCH_ASSOC);
            // print_r($row_sub);
            return $row_sub;
        }
        
        return [];

    }

     

    public function GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
        $checkEnrollmentEnrolled, $student_id) {

        foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {

            $course_id = $value['course_id'];
            $course_level = $value['course_level'];
            $subject_code = $value['subject_code'];
            $program_code = $value['program_code'];

            $retake = $value['retake'];

            $subject_title = $value['subject_title'];
            $unit = $value['unit'];
            $program_section = $value['program_section'];
            $subject_type = $value['subject_type'];
            $remarks = $value['remarks'];

            $student_subject_code = $value['student_subject_code'];

            $db_enrollment_id = $value['enrollment_id'];
            $db_is_transferee = $value['is_transferee'];


            $doesEnrollmentRetakeIsZero = $this->DoesEnrollmentRetakeIsZero($db_enrollment_id);
            
            // if($doesEnrollmentRetakeIsZero == true){

            //     echo $program_code;
            //     echo "is zero";
            //     echo "<br>";
            // }else{
            //     echo "<br>";
            //     echo "is not zero";
            //     echo "<br>";
            // }

            $student_subject_id = $value['student_subject_id'];
            $is_final = $value['is_final'];

            $graded_student_subject_id = $value['graded_student_subject_id'];

            $remarks_url = "";

            // All non retake forms that shows within specific grade level and semester
            // ( First time in the system to be that specific level ex. Grade 11 ) 

            $retakeForm = $retake == 1 ? "(Re-taken)" : "";

            if ($student_subject_code != null && $is_final == 1 
                    // && $doesEnrollmentRetakeIsZero
                    ) {

                $subject_code = $student_subject_code;

                if ($student_subject_id != $graded_student_subject_id 
                    && $checkEnrollmentEnrolled == true) {

                    $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";

                    $remarks_url = "
                        <i style='color:blue; cursor:pointer;' onclick='$remarkAsPassed' class='fas fa-marker'></i>
                    ";
                }
                else if ($student_subject_id == $graded_student_subject_id) {
                    $remarks_url = "
                        $remarks $retakeForm
                    ";
                }
            }



            if ($db_enrollment_id == NULL 
                && $db_is_transferee == 1 
                && $is_final == 1) {

                $program_section = "-";
                $remarks_url = "Credited";
            }

            $program_section = $is_final == 0 ? "" : $program_section;

            echo '<tr class="text-center">';
            echo '<td>' . $subject_title . '</td>';
            echo '<td>' . $subject_code . '</td>';
            echo '<td>' . $subject_type . '</td>';
            echo '<td>' . $unit . '</td>';
            echo '<td>' . $program_section . '</td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td>' . $remarks_url . '</td>';
            echo '</tr>';
        }
    }

    public function DoesEnrollmentRetakeIsZero($enrollment_id){

        $subject_query = $this->con->prepare("SELECT 
            * FROM enrollment

            WHERE enrollment_id=:enrollment_id
            AND retake = 0
        ");

        $subject_query->bindParam(":enrollment_id", $enrollment_id);
        $subject_query->execute();

        return $subject_query->rowCount() > 0;

    }

}
?>