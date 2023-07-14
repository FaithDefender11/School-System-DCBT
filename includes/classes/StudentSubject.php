<?php

    class StudentSubject{

    private $con, $userLoggedIn, $sqlData;
   
    public function __construct($con, $student_subject_id = null){
        $this->con = $con;
        $this->sqlData = $student_subject_id;

        if(!is_array($student_subject_id)){
            
            $query = $this->con->prepare("SELECT * FROM student_subject
            WHERE student_subject_id=:student_subject_id");

            $query->bindParam(":student_subject_id", $student_subject_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetStudentSubjectId() {
        return isset($this->sqlData['student_subject_id']) ? $this->sqlData["student_subject_id"] : 0; 
    }

    public function GetStudentSubjectCourseId() {
        return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : 0; 
    }


    public function GetStudentSubjectEnrollmentId() {
        return isset($this->sqlData['enrollment_id']) ? $this->sqlData["enrollment_id"] : 0; 
    }

    public function GetStudentSubjectStudentId() {
        return isset($this->sqlData['student_id']) ? $this->sqlData["student_id"] : 0; 
    }

    public function GetStudentSubjectProgramId() {
        return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : 0; 
    }

    public function GetStudentSubjectCode() {
        return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : ""; 
    }


    public function CheckAlreadyCreditedSubject($student_id, $subject_title){

        $sql = $this->con->prepare("SELECT * FROM student_subject_grade as t1

            WHERE t1.student_id=:student_id
            AND t1.subject_title=:subject_title
            AND t1.remarks='Passed'
            AND t1.is_transferee='yes'
            LIMIT 1
            ");

        $sql->bindParam("student_id", $student_id);
        $sql->bindParam("subject_title", $subject_title);
        $sql->execute();

        $isThere = false;

        // if($sql->rowCount() > 0){
        //     // echo "Subject ID: $subject_id already there";
        //     // exit();
        //     $isThere = true;

        // }else{
        //     // echo "not there";
        //     $isThere = false;
        // }

        return $sql->rowCount() > 0;
    }

    public function AddNonFinalDefaultEnrolledSubject($student_id, 
        $student_enrollment_id, $student_course_id, $current_school_year_id,
        $current_school_year_period, $admission_status){


        $is_transferee = $admission_status == "Transferee" ? 1 : 0;
        $is_final = 0;

        $section = new Section($this->con, $student_course_id);

        $section_program_id = $section->GetSectionProgramId($student_course_id);
        $course_level = $section->GetSectionGradeLevel();
        $section_name = $section->GetSectionName();

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final)");
        
        $sql = $this->con->prepare("SELECT * FROM subject_program as t1

            WHERE t1.semester=:semester
            AND t1.course_level=:course_level
            AND t1.program_id=:program_id
            ");

        $sql->bindParam("semester", $current_school_year_period);
        $sql->bindParam("course_level", $course_level);
        $sql->bindParam("program_id", $section_program_id);
        $sql->execute();

        $isFinish = false;

        if($sql->rowCount() > 0){

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                $subject_code = $row['subject_code'];
                $subject_program_id = $row['subject_program_id'];

                $student_subject_code = $section->CreateSectionSubjectCode(
                    $section_name, $subject_code);

                $add_student_subject->bindParam(':student_id', $student_id);
                $add_student_subject->bindParam(':subject_code', $student_subject_code);
                $add_student_subject->bindParam(':enrollment_id', $student_enrollment_id);
                $add_student_subject->bindParam(':course_id', $student_course_id);
                $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
                $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
                $add_student_subject->bindParam(':is_transferee', $is_transferee);
                $add_student_subject->bindParam(':is_final', $is_final);

                if($add_student_subject->execute()){
                    $isFinish = true;
                }
            }
        }

        return $isFinish;

    }

    public function UpdateStudentSubjectCourseId($student_id,
        $current_course_id, $to_change_course_id, $enrollment_id,
        $current_school_year_id, $current_semester){

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final)");
        
        // REMOVE AND ADD CHOSEN course id
        $remove = $this->con->prepare("DELETE FROM student_subject
             
            WHERE student_id=:student_id
            AND course_id=:current_course_id
            AND enrollment_id=:enrollment_id
            AND school_year_id=:current_school_year_id
            ");

        $remove->bindParam(":student_id", $student_id);
        $remove->bindParam(":current_course_id", $current_course_id);
        $remove->bindParam(":enrollment_id", $enrollment_id);
        $remove->bindParam(":current_school_year_id", $current_school_year_id);
        
        $isRemoveAndAdd = false;

        if($remove->execute()){

            $section = new Section($this->con, $to_change_course_id);

            $section_program_id = $section->GetSectionProgramId($to_change_course_id);
            $course_level = $section->GetSectionGradeLevel();
            $section_name = $section->GetSectionName();

            $sql = $this->con->prepare("SELECT * FROM subject_program as t1

                WHERE t1.semester=:semester
                AND t1.course_level=:course_level
                AND t1.program_id=:program_id
                ");

            $sql->bindParam("semester", $current_semester);
            $sql->bindParam("course_level", $course_level);
            $sql->bindParam("program_id", $section_program_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $is_final = 0;
                // It will be overriden in the credit subject table.
                $is_transferee = 0;

                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                    $subject_code = $row['subject_code'];
                    $subject_program_id = $row['subject_program_id'];

                    $student_subject_code = $section->CreateSectionSubjectCode(
                        $section_name, $subject_code);

                    $add_student_subject->bindParam(':student_id', $student_id);
                    $add_student_subject->bindParam(':subject_code', $student_subject_code);
                    $add_student_subject->bindParam(':enrollment_id', $enrollment_id);
                    $add_student_subject->bindParam(':course_id', $to_change_course_id);
                    $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
                    $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
                    $add_student_subject->bindParam(':is_transferee', $is_transferee);
                    $add_student_subject->bindParam(':is_final', $is_final);

                    if($add_student_subject->execute()){
                        $isRemoveAndAdd = true;
                    }
                }
            }
        }
        
        return $isRemoveAndAdd;

    }

    public function StudentSubjectMarkAsFinal($enrollment_id,
        // $student_course_id,
        $student_id, $current_school_year_id){

        $is_final = 0;
        $set_final = 1;
        
        if($enrollment_id == null) return;
        
        $update = $this->con->prepare("UPDATE student_subject as t1
            SET is_final=:set_final


            WHERE t1.enrollment_id = :enrollment_id
            -- AND t1.course_id = :course_id
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
            
            ");
        
        $update->bindParam(":set_final", $set_final);
        $update->bindParam(":enrollment_id", $enrollment_id);
        // $update->bindParam(":course_id", $student_course_id);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":school_year_id", $current_school_year_id);
        $update->bindParam(":is_final", $is_final);

        return $update->execute();

    }

    public function CreditAssignedStudentSubjectNonFinal($student_subject_id,
        $subject_program_id,
        $student_id, $current_school_year_id){

        $is_final = 0;
        $mark_credited = 1;
        $set_null_enrollment_id = NULL;
        $date_creation = date("Y-m-d H:i:s");
        
        
        $update = $this->con->prepare("UPDATE student_subject as t1
            SET is_transferee=:mark_credited,
                enrollment_id=:set_null_enrollment_id,
                course_id = NULL,
                date_creation =:date_creation,
                subject_code = '',
                is_final = 1

            WHERE t1.student_subject_id = :student_subject_id
            AND t1.student_id = :student_id
            AND t1.subject_program_id = :subject_program_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
            
            ");
        
        $update->bindParam(":mark_credited", $mark_credited);
        $update->bindParam(":set_null_enrollment_id", $set_null_enrollment_id);
        $update->bindParam(":date_creation", $date_creation);
        $update->bindParam(":student_subject_id", $student_subject_id);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":subject_program_id", $subject_program_id);
        $update->bindParam(":school_year_id", $current_school_year_id);
        $update->bindParam(":is_final", $is_final);

        return $update->execute();

    }

    public function UnCreditAssignedStudentSubjectNonFinal($student_subject_id,
        $subject_program_id, $student_id, 
        $current_school_year_id, $enrollment_id, $course_id, $subject_code){

        $is_transferee = 0;
        
        $date_creation = date("Y-m-d H:i:s");

        $update = $this->con->prepare("UPDATE student_subject
            SET is_transferee=:is_transferee,
                enrollment_id=:enrollment_id,
                course_id=:course_id,
                -- subject_program_id=:subject_program_id,
                subject_code=:subject_code,
                date_creation=:date_creation,
                is_final = 0

            WHERE student_subject_id = :student_subject_id
            AND student_id = :student_id
            AND subject_program_id = :subject_program_id
            AND school_year_id = :school_year_id
            
        ");
        
        $update->bindParam(":is_transferee", $is_transferee);
        $update->bindParam(":enrollment_id", $enrollment_id);
        $update->bindParam(":course_id", $course_id);
        // $update->bindParam(":subject_program_id", $subject_program_id);
        $update->bindParam(":subject_code", $subject_code);
        $update->bindParam(":date_creation", $date_creation);

        $update->bindParam(":student_subject_id", $student_subject_id);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":subject_program_id", $subject_program_id);
        $update->bindParam(":school_year_id", $current_school_year_id);

        return $update->execute();

    }


    public function ChangingStudentSubjectCourseId($enrollment_id, $student_course_id,
        $student_id, $current_school_year_id, $chosen_course_id,
        $student_subject_id, $student_subject_program_id){

        $is_final = 0;

        $section = new Section($this->con, $chosen_course_id);
        $subject_program = new SubjectProgram($this->con, $student_subject_program_id);

        $program_section = $section->GetSectionName();

        $subject_code = $subject_program->GetSubjectProgramRawCode();

        $section_subject_code = $section->CreateSectionSubjectCode($program_section,
            $subject_code);

        
        $update = $this->con->prepare("UPDATE student_subject as t1
            SET course_id=:chosen_course_id,
                subject_code=:section_subject_code

            WHERE t1.enrollment_id = :enrollment_id
            AND t1.course_id = :course_id
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
            AND t1.student_subject_id = :student_subject_id
            
            ");
        
        $update->bindParam(":chosen_course_id", $chosen_course_id);
        $update->bindParam(":section_subject_code", $section_subject_code);
        $update->bindParam(":enrollment_id", $enrollment_id);
        $update->bindParam(":course_id", $student_course_id);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":school_year_id", $current_school_year_id);
        $update->bindParam(":is_final", $is_final);
        $update->bindParam(":student_subject_id", $student_subject_id);

        return $update->execute();

    }

    public function MarkStudentSubjectAsCredited($student_id, $current_school_year_id,
        $subject_program_id){

        $is_transferee = 1;
        $is_final = 1;
        $enrollment_id = NULL;

        $credit_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_program_id, school_year_id, is_transferee, is_final, enrollment_id)
            VALUES (:student_id, :subject_program_id, :school_year_id, :is_transferee, :is_final, :enrollment_id)");
        
        $credit_subject->bindParam(":student_id", $student_id);
        $credit_subject->bindParam(":subject_program_id", $subject_program_id);
        $credit_subject->bindParam(":school_year_id", $current_school_year_id);
        $credit_subject->bindParam(":is_transferee", $is_transferee);
        $credit_subject->bindParam(":is_final", $is_final);
        $credit_subject->bindParam(":enrollment_id", $enrollment_id);

        return $credit_subject->execute();
    }

    public function CheckStudentSectionSubjectAssignedWithinSY($student_enrollment_id,
        $student_enrollment_course_id, $student_id, $current_school_year_id){

        $is_final = 0;

        $sql = $this->con->prepare("SELECT student_subject_id
            
            FROM student_subject as t1

            WHERE t1.enrollment_id = :enrollment_id
            AND t1.course_id = :course_id
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
        ");
                
        $sql->bindParam(":enrollment_id", $student_enrollment_id);
        $sql->bindParam(":course_id", $student_enrollment_course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":is_final", $is_final);
        $sql->execute();

        return $sql->rowCount() > 0;

    }

    public function GetStudentAssignSubjects($enrollment_id,
            // $course_id, 
            $student_id, $school_year_id){

        $is_final = 0;

        $sql = $this->con->prepare("SELECT 
            t1.enrollment_id, t1.is_transferee, t1.student_id, t1.student_subject_id,
            t2.*, t3.program_section,t3.course_id
            
            FROM student_subject as t1

            INNER JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE (t1.enrollment_id = :enrollment_id OR t1.enrollment_id IS NULL)

            -- Rest AND LOGIC APPLIES
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            -- AND t1.is_final = :is_final
        ");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        // $sql->bindParam(":is_final", $is_final);
        $sql->execute();

        if($sql->rowCount() > 0){

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetTotalEnrolledStudentInSectionSubjectCode($enrollment_id,
            $course_id, $student_id, $school_year_id, $subject_program_id){

            $is_final = 1;
            $sql = $this->con->prepare("SELECT t2.*, t3.program_section
                
                FROM student_subject as t1

                INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
                AND t2.enrollment_status = 'enrolled'

                WHERE t1.enrollment_id = :enrollment_id
                AND t1.course_id = :course_id
                AND t1.student_id = :student_id
                AND t1.school_year_id = :school_year_id
                AND t1.is_final = :is_final
                AND t1.subject_program_id = :subject_program_id
            ");
                
            $sql->bindParam(":enrollment_id", $enrollment_id);
            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":student_id", $student_id);
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindParam(":is_final", $is_final);
            $sql->bindParam(":subject_program_id", $subject_program_id);
            $sql->execute();

            return $sql->rowCount();
        }

}

?>