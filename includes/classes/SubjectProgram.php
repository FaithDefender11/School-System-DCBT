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

    public function GetSubjectProgramTotalUnit($program_id) {

        $totalunits = 0;
        $query = $this->con->prepare("SELECT * FROM subject_program
                WHERE program_id=:program_id");

        $query->bindParam(":program_id", $program_id);
        $query->execute();

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $unit_row = $row['unit'];

                $totalunits += $unit_row;
            }
          
        }
        // echo $totalunits;
        return $totalunits;
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
            t4.first,
            t4.second,
            t4.third,
            t4.fourth,
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

     

    public function GradeRecordsSHSBody(
        $enrolledSubjectsGradeLevelSemesterBased,
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


            $first = $value['first'] == 0 ? "-" : $value['first'];
            $second = $value['second'] == 0 ? "-" : $value['second'];
            $third = $value['third'] == 0 ? "-" : $value['third'];
            $fourth = $value['fourth'] == 0 ? "-" : $value['fourth'];

            // $second = $value['second'];
            // $third = $value['third'];
            // $fourth = $value['fourth'];

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

            // echo $student_subject_code;
            // echo "<br>";
            // echo $is_final;
            // echo "<br>";

            if ($student_subject_code != null && $is_final == 1) {

                # For Section Subject Code
                // $subject_code = $student_subject_code;

                // echo $student_subject_id;
                // echo "<br>";
                // echo $graded_student_subject_id;
                // echo "<br>";
                // var_dump($checkEnrollmentEnrolled);
                // echo "<br>";

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

            $average = "";

            if($first != 0 && $second != 0 && $third != 0 && $fourth != 0){

                $average = $this->calculateAverage($first, $second, $third, $fourth);
            }




            echo '<tr class="text-center">';
            echo '<td>' . $subject_title . '</td>';
            echo '<td>' . $subject_code . '</td>';
            echo '<td>' . $subject_type . '</td>';
            echo '<td>' . $unit . '</td>';
            echo '<td>' . $program_section . '</td>';
            echo '<td>'.$first.'</td>';
            echo '<td>'.$second.'</td>';
            echo '<td>'.$third.'</td>';
            echo '<td>'.$fourth.'</td>';
            echo '<td>'.$average.'</td>';
            echo '<td>' . $remarks_url . '</td>';
            echo '</tr>';
        }
    }
    public function calculateAverage($num1, $num2, $num3, $num4) {
        $sum = $num1 + $num2 + $num3 + $num4;
        $average = $sum / 4;
        return round($average);
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
    public function GetSubjectProgramIdByProgramCode($program_code) {

        $sql = $this->con->prepare("SELECT subject_program_id
            FROM subject_program
            
            WHERE subject_code=:subject_code");
                
        $sql->bindValue(":subject_code", $program_code);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;

    }


    public function GetAvailableSubjectCodeWithinSemester($department_type,
        $current_school_year_period, $current_school_year_term,
        $student_id, $student_program_id, $selected_subject_program_id = null){
 
            if($selected_subject_program_id != null){
                $sql = $this->con->prepare("SELECT 
                                                
                    t1.*
                    ,t2.program_section
                    ,t2.course_id

                    ,t3.student_subject_id,
                    t3.is_final AS ss_is_final,
                    t3.enrollment_id AS ss_enrollment_id,
                    t3.subject_program_id AS ss_subject_program_id,

                    t3.is_transferee AS ss_is_transferee,
                    t3.school_year_id AS ss_school_year_id,
                    t3.course_id AS ss_course_id,
                    t3.student_id AS ss_student_id,

                    t4.student_subject_id AS ssg_student_subject_id
                    
                    FROM subject_program AS t1

                    INNER JOIN course as t2 ON t2.program_id = t1.program_id
                    -- AND t2.active = 'yes'
                    -- AND t2.is_remove = 0
                    AND t2.course_level = t1.course_level
                    AND (
                        t2.program_id = :student_program_id
                        OR t1.program_id != :student_program_id
                            AND t1.subject_type='Core'
                        )

                    LEFT JOIN student_subject as t3 ON t1.subject_program_id = t3.subject_program_id
                    AND t3.student_id=:student_id

                    LEFT JOIN student_subject_grade AS t4 ON t4.student_subject_id = t3.student_subject_id
                    AND t4.remarks = 'Passed'
                    
                    WHERE t1.subject_program_id = :subject_program_id
                    AND t2.school_year_term=:school_year_term
                    AND t2.is_full= 'no'
                    
                    
                    GROUP BY t1.subject_program_id,
                        t2.course_id
                        
                    ORDER BY t1.course_level,
                        t1.semester, t2.program_section DESC
                    
                ");

                $sql->bindParam(":subject_program_id", $selected_subject_program_id);
                $sql->bindParam(":student_id", $student_id);
                $sql->bindParam(":student_program_id", $student_program_id);
                $sql->bindParam(":school_year_term", $current_school_year_term);

                $sql->execute();
                
                if($sql->rowCount() > 0){
                    return $sql->fetchAll(PDO::FETCH_ASSOC);
                }
            }else{
                $sql = $this->con->prepare("SELECT 
                                            
                    t1.*
                    ,t2.program_section
                    ,t2.course_id

                    ,t3.student_subject_id,
                    t3.is_final AS ss_is_final,
                    t3.enrollment_id AS ss_enrollment_id,
                    t3.subject_program_id AS ss_subject_program_id,

                    t3.is_transferee AS ss_is_transferee,
                    t3.school_year_id AS ss_school_year_id,
                    t3.course_id AS ss_course_id,
                    t3.student_id AS ss_student_id,

                    t4.student_subject_id AS ssg_student_subject_id
                    
                    FROM subject_program AS t1

                    INNER JOIN course as t2 ON t2.program_id = t1.program_id
                    AND t2.course_level = t1.course_level
                    AND (
                        t2.program_id = :student_program_id
                        OR t1.program_id != :student_program_id
                            AND t1.subject_type='Core'
                        )

                    LEFT JOIN student_subject as t3 ON t1.subject_program_id = t3.subject_program_id
                    AND t3.student_id=:student_id

                    LEFT JOIN student_subject_grade AS t4 ON t4.student_subject_id = t3.student_subject_id
                    AND t4.remarks = 'Passed'
                    
                    WHERE t1.department_type = :department_type
                    AND t1.semester=:semester
                    AND t2.active= 'yes'
                    AND t2.school_year_term=:school_year_term
                    AND t2.is_full= 'no'

                    -- AND t1.program_id= 4
                    -- AND t1.course_level=12

                    GROUP BY t1.subject_program_id,
                     t2.course_id

                    ORDER BY t1.course_level,
                    t1.semester, t2.program_section DESC
                    
                ");

                $sql->bindParam(":department_type", $department_type);
                $sql->bindParam(":semester", $current_school_year_period);
                $sql->bindParam(":school_year_term", $current_school_year_term);
                $sql->bindParam(":student_id", $student_id);
                $sql->bindParam(":student_program_id", $student_program_id);

                $sql->execute();

                if($sql->rowCount() > 0){
                    return $sql->fetchAll(PDO::FETCH_ASSOC);
                }
                
            }

        return [];
    }


    public function GetCourseStrandCurriculum($student_program_id,
        $student_id, $selected_subject_program_id = null){
     

        if($selected_subject_program_id != null){

            $sql = $this->con->prepare("SELECT 
                                        
                t1.*,

                t2.subject_program_id AS ss_subject_program_id,

                t2.is_transferee, t2.enrollment_id,
                t2.student_subject_id, t2.is_final,
                t2.student_id,
                t2.school_year_id,

                t3.student_subject_id AS ssg_student_subject_id
            
                FROM subject_program AS t1

                LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
                
                AND t2.student_id =:student_id


                LEFT JOIN student_subject_grade AS t3 ON t3.student_subject_id = t2.student_subject_id
                AND t3.remarks = 'Passed'
                AND t3.student_id = t2.student_id

                
                WHERE t1.program_id=:program_id
                AND t1.subject_program_id=:subject_program_id
                -- AND t1.is_final=1

                ORDER BY t1.course_level,t1.semester
            ");

            $sql->bindParam(":program_id", $student_program_id);
            $sql->bindParam(":student_id", $student_id);
            $sql->bindParam(":subject_program_id", $selected_subject_program_id);

            $sql->execute();

            if($sql->rowCount() > 0){

                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        }


        $sql = $this->con->prepare("SELECT 
                                        
            t1.*,

            t2.subject_program_id AS ss_subject_program_id,

            t2.is_transferee,
            t2.enrollment_id,
            t2.student_subject_id,
            t2.is_final,
            t2.student_id,
            t2.school_year_id,

            t3.student_subject_id AS ssg_student_subject_id
        
            FROM subject_program AS t1

            LEFT JOIN student_subject as t2 ON t2.subject_program_id = t1.subject_program_id
            
            AND t2.student_id =:student_id


            LEFT JOIN student_subject_grade AS t3 ON t3.student_subject_id = t2.student_subject_id
            AND t3.remarks = 'Passed'
            AND t3.student_id = t2.student_id

            
            WHERE t1.program_id=:program_id
            -- AND t1.school_year_id=:school_year_id
            -- AND t1.is_final=1

            ORDER BY t1.course_level,t1.semester
        ");

        $sql->bindParam(":program_id", $student_program_id);
        $sql->bindParam(":student_id", $student_id);

        $sql->execute();
        if($sql->rowCount() > 0){

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetSectionSubjectEnrolledStudents($subject_program_id,
        $course_id, $section_subject_code){
     

        $count = 0;
  
        $sql = $this->con->prepare("SELECT t1.student_id FROM student_subject AS t1

            INNER JOIN enrollment AS t2 ON t2.enrollment_id = t1.enrollment_id
            AND t1.is_final = 1
            AND t2.enrollment_status = :enrollment_status
            
            WHERE t1.subject_code=:subject_code
            -- AND t2.student_subject_id=:student_subject_id
        ");

        $sql->bindParam(":subject_code", $section_subject_code);
        $sql->bindValue(":enrollment_status", "enrolled");
        // $sql->bindParam(":student_subject_id", $student_subject_id);

        $sql->execute();

        if($sql->rowCount() > 0){

            // return $sql->fetchAll(PDO::FETCH_ASSOC);
            $count += $sql->rowCount();
        }

        return $count;

    }
    

    
    public function GetProgramCodeBySubjectCode($subject_code, $course_id){
     
        $sql = $this->con->prepare("SELECT t1.program_code 
        
            FROM student_subject AS t1
 
            WHERE t1.subject_code=:subject_code
            AND t1.course_id=:course_id
            LIMIT 1
        ");

        $sql->bindParam(":subject_code", $subject_code);
        $sql->bindParam(":course_id", $course_id);
        $sql->execute();

        if($sql->rowCount() > 0){

           return $sql->fetchColumn();
        }

        return "";

    }

    public function GetSubjectProgramTitleByRawCode($subject_code){
     
        $sql = $this->con->prepare("SELECT t1.subject_title 
        
            FROM subject_program AS t1
 
            WHERE t1.subject_code=:subject_code
            LIMIT 1
        ");

        $sql->bindParam(":subject_code", $subject_code);
        $sql->execute();

        if($sql->rowCount() > 0){

        //    return $sql->fetchAll(PDO::FETCH_ASSOC);
           return $sql->fetchColumn();
        }

        return [];

    }
}
?>