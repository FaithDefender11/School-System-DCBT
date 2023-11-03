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

    public function GetEnrollmentId() {
        return isset($this->sqlData['enrollment_id']) ? $this->sqlData["enrollment_id"] : NULL; 
    }

    public function GetSchoolYearId() {
        return isset($this->sqlData['school_year_id']) ? $this->sqlData["school_year_id"] : ""; 
    }

    public function GetStudentProgramCode() {
        return isset($this->sqlData['program_code']) ? $this->sqlData["program_code"] : ""; 
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

        return $sql->rowCount() > 0;
    }

    public function GetStudentSubjectCourseIdByYearAndCode($subject_code, $school_year_id){

        $sql = $this->con->prepare("SELECT t1.course_id FROM student_subject as t1

            WHERE t1.subject_code=:subject_code
            AND t1.school_year_id=:school_year_id
            LIMIT 1
            ");

        $sql->bindParam("subject_code", $subject_code);
        $sql->bindParam("school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return NULL;
    }

    public function AddNonFinalDefaultEnrolledSubject($student_id, 
        $student_enrollment_id, $student_course_id, $current_school_year_id,
        $current_school_year_period, $admission_status = null){


        $is_transferee = $admission_status == "Transferee" ? 1 : 0;
        $is_final = 0;

        $section = new Section($this->con, $student_course_id);

        $section_program_id = $section->GetSectionProgramId($student_course_id);
        $course_level = $section->GetSectionGradeLevel();
        $section_name = $section->GetSectionName();

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final, program_code)

            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code)");
        
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

            // echo "hey";
            $hasError = false;

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
             
                $subject_code = $row['subject_code'];
                $pre_requisite_code = $row['pre_req_subject_title'];
                $subject_program_id = $row['subject_program_id'];

                // echo $subject_program_id;
                // echo "<br>";

                $student_subject = new StudentSubject($this->con);

                $checkIfSubjectAlreadyPassed = $student_subject->
                    CheckIfSubjectAlreadyPassed($student_id, $subject_code);

                $checkIfSubjectNotPassedForPreRequisite = $student_subject->CheckIfSubjectPreRequisiteHasFailed(
                    $student_id,
                    $pre_requisite_code, $current_school_year_id, $subject_code);

                $checkIfCredited = $this->CheckIfSubjectAlreadyCredited($student_id, $subject_code);
                
                if($checkIfCredited == true){
                    // $hasError = true;
                    continue;
                }
                
                if($checkIfSubjectAlreadyPassed == true){
                    // $hasError = true;
                    continue;
                }

                // responsible for filtering-out the failed pre-requisite subjects
                // of current target subject
                if($checkIfSubjectNotPassedForPreRequisite == true){
                    // $hasError = true;
                    continue;
                }

                if(true){
                  
                    $student_subject_code = $section->CreateSectionSubjectCode(
                        $section_name, $subject_code);
                    
                    $add_student_subject->bindParam(':student_id', $student_id);
                    $add_student_subject->bindParam(':subject_code', $student_subject_code);
                    $add_student_subject->bindParam(':enrollment_id', $student_enrollment_id);
                    $add_student_subject->bindParam(':course_id', $student_course_id);
                    $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
                    $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
                    $add_student_subject->bindValue(':is_transferee', 0);
                    $add_student_subject->bindParam(':is_final', $is_final);
                    $add_student_subject->bindParam(':program_code', $subject_code);

                    if($add_student_subject->execute()){
                        $isFinish = true;
                    }
                }
            }
        }
        return $isFinish;
    }


    public function PopulateBlockSectionSubjects(
        $current_school_year_id,
        $current_school_year_period,
        $student_enrollment_course_id,
        $student_enrollment_id,
        $student_id){


        $section = new Section($this->con, $student_enrollment_course_id);
        $studentSectionName = $section->GetSectionName();
        $student_course_level = $section->GetSectionGradeLevel();
        $student_course_program_id = $section->GetSectionProgramId($student_enrollment_course_id);


        $subjectProgram = new SubjectProgram($this->con);

        # 1. Remove all subjects in the student_subject list
        $removeAllGivenSubjects = $this->RemoveAllInsertedStudentSubjectList(
            $student_enrollment_id, $current_school_year_id, $student_id);
        

        $sql = $this->con->prepare("SELECT t1.* FROM subject_program as t1

            WHERE t1.semester=:semester
            AND t1.course_level=:course_level
            AND t1.program_id=:program_id
            ");

        $sql->bindParam("semester", $current_school_year_period);
        $sql->bindParam("course_level", $student_course_level);
        $sql->bindParam("program_id", $student_course_program_id);
        $sql->execute();

        $isFinish = false;

        if($sql->rowCount() > 0){

            $add_student_subject = $this->con->prepare("INSERT INTO student_subject
                (student_id, subject_code, enrollment_id, course_id, subject_program_id,
                school_year_id, is_transferee, is_final, program_code)
                VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
                :school_year_id, :is_transferee, :is_final, :program_code)");

            // $asd = $sql->fetchAll(PDO::FETCH_ASSOC);
            // return $asd;

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
             
                $program_code = $row['subject_code'];
                $pre_requisite_code = $row['pre_req_subject_title'];
                $subject_program_id = $row['subject_program_id'];
 
                # 2. Remove the credited subjects within semester course level (if ever)

                $checkSubjectProgramOfferedWithinSemester = $subjectProgram
                    ->CheckSubjectProgramIsWithinSemesterOffered(
                        $subject_program_id,
                        $current_school_year_period, $student_course_level);

                if($checkSubjectProgramOfferedWithinSemester == true){
                    $removeAllCreditedSubjectsWithinSemester = $this->RemoveAllInsertedCreditedStudentSubjectList(
                        $current_school_year_id, $student_id,
                        $subject_program_id);
                }
                
                # 3. Add Block Sections Subjects.
                // $subjectProgram = new SubjectProgram($this->con, $subject_program_id);
                // $program_code = $subjectProgram->GetSubjectProgramRawCode();

                $student_subject_code = $section->CreateSectionSubjectCode($studentSectionName,
                    $program_code);

                $add_student_subject->bindParam(':student_id', $student_id);
                $add_student_subject->bindParam(':subject_code', $student_subject_code);
                $add_student_subject->bindParam(':enrollment_id', $student_enrollment_id);
                $add_student_subject->bindParam(':course_id', $student_enrollment_course_id);
                $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
                $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
                $add_student_subject->bindValue(':is_transferee', 0);
                $add_student_subject->bindValue(':is_final', 0);
                $add_student_subject->bindParam(':program_code', $program_code);

                if($add_student_subject->execute()){
                    $isFinish = true;
                }

        
            }
        }
        return $isFinish;
    }

    public function RemoveAllInsertedStudentSubjectList(
        $enrollment_id, $school_year_id, $student_id){

        $sql = $this->con->prepare("DELETE FROM student_subject

            WHERE enrollment_id = :enrollment_id
            AND school_year_id = :school_year_id
            AND student_id = :student_id
        ");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function RemoveAllInsertedCreditedStudentSubjectList(
        $school_year_id, $student_id, $subject_program_id){


        $sql = $this->con->prepare("DELETE FROM student_subject

            WHERE school_year_id = :school_year_id
            AND student_id = :student_id
            AND is_transferee = :is_transferee
            AND subject_program_id = :subject_program_id
            
        ");
                
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindValue(":is_transferee", 1);
        $sql->bindValue(":subject_program_id", $subject_program_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }
    public function CheckIfStudentSubjectAlreadyCredited($enrollment_id){

        $code = "";
        $sql = $this->con->prepare("SELECT subject_code

            FROM student_subject AS t1

            WHERE t1.enrollment_id = :enrollment_id");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $code =  $sql->fetchColumn();
        }

        return $code;
    }
    
    // updax
    public function UpdateStudentSubjectCourseId($student_id,
        $current_course_id, $to_change_course_id, $enrollment_id,
        $current_school_year_id, $current_semester){

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final, program_code)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code)");
        
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
                    $pre_requisite_code = $row['pre_req_subject_title'];
                    $subject_program_id = $row['subject_program_id'];


                    $student_subject = new StudentSubject($this->con);

                    $checkIfSubjectAlreadyPassed = $student_subject->
                        CheckIfSubjectAlreadyPassed($student_id, $subject_code);

                    $checkIfSubjectNotPassedForPreRequisite = $student_subject->
                        CheckIfSubjectPreRequisiteHasFailed($student_id,
                            $pre_requisite_code, $current_school_year_id, $subject_code);

                    $checkIfCredited = $this->CheckIfSubjectAlreadyCredited($student_id, $subject_code);

                    // $hasError = false;

                    if($checkIfCredited == true){
                        continue;
                    }
                    if($checkIfSubjectAlreadyPassed == true){
                        continue;
                    }
                    if($checkIfSubjectNotPassedForPreRequisite == true){
                        continue;
                    }

                    // echo $subject_code;
                    // echo "<br>";

                    // else
                    
                    if(true){
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
                        $add_student_subject->bindParam(':program_code', $subject_code);

                        if($add_student_subject->execute()){
                            $isRemoveAndAdd = true;
                        }
                    }





                }
            }
        }
        
        return $isRemoveAndAdd;

    }

    public function UpdateStudentSubjectCourseIdApprove($student_id,
        $current_course_id, $to_change_course_id, $enrollment_id,
        $current_school_year_id, $current_semester,
        $checkFormEnrolled = null){



        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final, program_code)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code)");
        
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
                $enrolled_status = 1;

                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                    $subject_code = $row['subject_code'];
                    $pre_requisite_code = $row['pre_req_subject_title'];
                    $subject_program_id = $row['subject_program_id'];


                    $student_subject = new StudentSubject($this->con);

                    $checkIfSubjectAlreadyPassed = $student_subject->
                        CheckIfSubjectAlreadyPassed($student_id, $subject_code);

                    $checkIfSubjectNotPassedForPreRequisite = $student_subject->
                        CheckIfSubjectPreRequisiteHasFailed($student_id,
                            $pre_requisite_code, $current_school_year_id, $subject_code);

                    $checkIfCredited = $this->CheckIfSubjectAlreadyCredited($student_id, $subject_code);

                    // $hasError = false;

                    // if($checkIfCredited == true){
                    //     continue;
                    // }
                    // if($checkIfSubjectAlreadyPassed == true){
                    //     continue;
                    // }
                    // if($checkIfSubjectNotPassedForPreRequisite == true){
                    //     continue;
                    // }

                    // echo $subject_code;
                    // echo "<br>";

                    // else
                    
                    if(true){
                        $student_subject_code = $section->CreateSectionSubjectCode(
                            $section_name, $subject_code);

                        $add_student_subject->bindParam(':student_id', $student_id);
                        $add_student_subject->bindParam(':subject_code', $student_subject_code);
                        $add_student_subject->bindParam(':enrollment_id', $enrollment_id);
                        $add_student_subject->bindParam(':course_id', $to_change_course_id);
                        $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
                        $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
                        $add_student_subject->bindParam(':is_transferee', $is_transferee);
                        $add_student_subject->bindValue(':is_final', $checkFormEnrolled == "enrolled" ? $enrolled_status : 0);
                        $add_student_subject->bindParam(':program_code', $subject_code);

                        if($add_student_subject->execute()){
                            $isRemoveAndAdd = true;
                        }
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
        $student_id, $current_school_year_id, $program_code){

        $is_final = 0;
        $mark_credited = 1;
        $set_null_enrollment_id = NULL;
        $date_creation = date("Y-m-d H:i:s");
        
        
        $update = $this->con->prepare("UPDATE student_subject as t1
            SET is_transferee=:mark_credited,
                enrollment_id=:set_null_enrollment_id,
                course_id = NULL,
                date_creation =:date_creation,
                subject_code = NULL,
                program_code =:program_code,
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
        $update->bindParam(":program_code", $program_code);

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

 
    public function ChangingStudentSubjectCourseIdInApprove($enrollment_id,
        $student_enrollment_course_id, $student_id, 
        $current_school_year_id, $chosen_course_id,
        $student_subject_program_id){

        $is_final = 0;

        $section = new Section($this->con, $chosen_course_id);
        $subject_program = new SubjectProgram($this->con, $student_subject_program_id);

        $program_section = $section->GetSectionName();

        $subject_code = $subject_program->GetSubjectProgramRawCode();

        $section_subject_code = $section->CreateSectionSubjectCode($program_section,
            $subject_code);

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final, program_code)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code)");


        # Remove
        $remove = $this->con->prepare("DELETE FROM student_subject
             
            WHERE student_id=:student_id
            AND course_id=:current_course_id
            AND enrollment_id=:enrollment_id
            AND school_year_id=:current_school_year_id
            ");

        $remove->bindParam(":student_id", $student_id);
        $remove->bindParam(":current_course_id", $student_enrollment_course_id);
        $remove->bindParam(":enrollment_id", $enrollment_id);
        $remove->bindParam(":current_school_year_id", $current_school_year_id);

        if($remove->execute()){

            # Add new one based on new enrollment course id.


        }


        
        // $update = $this->con->prepare("UPDATE student_subject as t1
        //     SET course_id=:chosen_course_id,
        //         subject_code=:section_subject_code

        //     WHERE t1.enrollment_id = :enrollment_id
        //     AND t1.course_id = :course_id
        //     AND t1.student_id = :student_id
        //     AND t1.school_year_id = :school_year_id
        //     AND t1.is_final = :is_final
        //     AND t1.student_subject_id = :student_subject_id
            
        //     ");
        
        // $update->bindParam(":chosen_course_id", $chosen_course_id);
        // $update->bindParam(":section_subject_code", $section_subject_code);
        // $update->bindParam(":enrollment_id", $enrollment_id);
        // $update->bindParam(":course_id", $student_course_id);
        // $update->bindParam(":student_id", $student_id);
        // $update->bindParam(":school_year_id", $current_school_year_id);
        // $update->bindParam(":is_final", $is_final);

        // return $update->execute();

    }


    public function MarkStudentSubjectAsCredited($student_id,
        $current_school_year_id,
        $subject_program_id, $program_code){

        $is_transferee = 1;
        $is_final = 1;
        $enrollment_id = NULL;

        $credit_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_program_id, school_year_id, is_transferee, is_final, enrollment_id, program_code)
            VALUES (:student_id, :subject_program_id, :school_year_id, :is_transferee, :is_final, :enrollment_id, :program_code)");
        
        $credit_subject->bindParam(":student_id", $student_id);
        $credit_subject->bindParam(":subject_program_id", $subject_program_id);
        $credit_subject->bindParam(":school_year_id", $current_school_year_id);
        $credit_subject->bindParam(":is_transferee", $is_transferee);
        $credit_subject->bindParam(":is_final", $is_final);
        $credit_subject->bindParam(":enrollment_id", $enrollment_id);
        $credit_subject->bindParam(":program_code", $program_code);

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

    public function GetSchoolYearIdByEnrollmentId($enrollment_id,
            $student_id
        ){

        $is_final = 0;

        $sql = $this->con->prepare("SELECT 
            t1.enrollment_id, t1.is_transferee,
            t1.student_id, t1.student_subject_id,
            t1.subject_code as ss_subject_code,
            t1.course_id as enrolled_course_id,
            t2.*, t3.program_section,t3.course_id
            
            FROM student_subject as t1

            INNER JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE (t1.enrollment_id = :enrollment_id OR t1.enrollment_id IS NULL)
            AND t1.student_id = :student_id
        ");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];

    }

    public function GetStudentAssignSubjects(
        $enrollment_id, $student_id){

        $is_final = 0;

        $sql = $this->con->prepare("SELECT 
            t1.enrollment_id,
            t1.is_transferee,
            t1.student_id,
            t1.student_subject_id,
            t1.subject_code AS ss_subject_code,
            t1.course_id AS enrolled_course_id,
            t2.*,
            
            t3.program_section,
            t3.course_id
            
            FROM student_subject as t1

            INNER JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id

            WHERE (t1.enrollment_id = :enrollment_id OR t1.enrollment_id IS NULL)
            AND t1.student_id = :student_id
        ");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    // public function GetStudentAssignSubjects2($enrollment_id,
    //         $student_id){

    //     $sql = $this->con->prepare("SELECT 
    //         t1.enrollment_id, t1.is_transferee,
    //         t1.student_id, t1.student_subject_id,
    //         t1.subject_code as ss_subject_code,
    //         t1.course_id as enrolled_course_id,
    //         t2.*, t3.program_section,t3.course_id
            
    //         FROM student_subject as t1

    //         INNER JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id

    //         LEFT JOIN course as t3 ON t3.course_id = t1.course_id

    //         WHERE (t1.enrollment_id = :enrollment_id 
    //             OR t1.enrollment_id IS NULL)

    //         AND t1.student_id = :student_id
    //     ");
                
    //     $sql->bindParam(":enrollment_id", $enrollment_id);
    //     $sql->bindParam(":student_id", $student_id);
    //     $sql->execute();

    //     if($sql->rowCount() > 0){

    //         return $sql->fetchAll(PDO::FETCH_ASSOC);
    //     }

    //     return [];

    // }

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
    public function CheckCurrentSemesterSubjectAllPassed($enrollment_id,
        $student_id, $school_year_id){

        $is_final = 1;

        $isHit = false;

        $current_non_transferee_subject = $this->con->prepare("SELECT t1.*
            
            FROM student_subject as t1

            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
            AND t2.enrollment_status = 'enrolled'

            WHERE t1.enrollment_id = :enrollment_id
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
            AND t1.is_transferee = 0
        ");
            
        $current_non_transferee_subject->bindParam(":enrollment_id", $enrollment_id);
        $current_non_transferee_subject->bindParam(":student_id", $student_id);
        $current_non_transferee_subject->bindParam(":school_year_id", $school_year_id);
        $current_non_transferee_subject->bindParam(":is_final", $is_final);
        $current_non_transferee_subject->execute();

        $studentSubjectCount = null;
        $studentSubjectGradedCount = null;

        $studentSubjectArray = [];
        $studentSubjectGradeArray = [];

        if($current_non_transferee_subject->rowCount() > 0){

            $studentSubjectCount = $current_non_transferee_subject->rowCount();

            while($row = $current_non_transferee_subject->fetch(PDO::FETCH_ASSOC)){

                $student_subject_id = $row['student_subject_id'];

                array_push($studentSubjectArray, $student_subject_id);

                $enrolledSubjectGrade = $this->con->prepare("SELECT student_subject_id
                    
                    FROM student_subject_grade as t1

                    WHERE t1.student_subject_id = :student_subject_id
                    AND t1.student_id = :student_id
                    AND t1.is_transferee = 0
                    AND t1.remarks = 'Passed'
                ");

                $enrolledSubjectGrade->bindParam(":student_subject_id", $student_subject_id);
                $enrolledSubjectGrade->bindParam(":student_id", $student_id);
                $enrolledSubjectGrade->execute();

                if($enrolledSubjectGrade->rowCount() > 0){

                    $student_subject_grade_student_subject_id = $enrolledSubjectGrade->fetchColumn();
                    array_push($studentSubjectGradeArray, $student_subject_grade_student_subject_id);

                    // $studentSubjectGradedCount += $enrolledSubjectGrade->rowCount();

                    // echo $studentSubjectGradedCount;
                    // echo "<br>";

                    // echo $student_subject_grade_id;
                    // echo "<br>";

                }

            }
        }
        $eligible = 0;
        // Check if the arrays have the same count
        
        if (count($studentSubjectArray) === count($studentSubjectGradeArray)) {

            // Check if the arrays have the same values
            $diff = array_diff(array_values($studentSubjectArray),
                array_values($studentSubjectGradeArray));

             "The arrays have the same count and the same integer values.";
            if (empty($diff)) {

                $eligible = $student_id;

                // $student = new Student($this->con, $student_id);

                // $applicable = $student->DoesApplicableToApplyNextYear();

                // if($applicable == 0){

                //     if($student->UpdateStudentApplicableApplyNextSY($student_id) == true){

                //         // Student id that has qualified requirements.
                //         // Enrollment form based.
                //         // echo "apply";
                //         // $isHit = true;
                //     }
                // }

            } else {
                // return 0;
                // echo "The arrays have the same count but different integer values.";
            }
        }
        return $eligible;

    }

    public function CheckCreditedSubject($student_subject_id){

        $is_final = 0;

        $sql = $this->con->prepare("SELECT student_subject_id
            
            FROM student_subject as t1

            WHERE t1.student_subject_id = :student_subject_id
            AND t1.is_transferee = 1
            AND t1.is_final = 1
        ");
                
        $sql->bindParam(":student_subject_id", $student_subject_id);
        $sql->execute();

        return $sql->rowCount() > 0;

    }
    public function CheckIfSubjectProgramAlreadyInsertedWithinSemester($student_id,
        $subject_program_id, $current_school_year_id, $current_semester){

        $sql = $this->con->prepare("SELECT student_subject_id
            
            FROM student_subject as t1

            INNER JOIN school_year as t2 ON t2.school_year_id = t1.school_year_id
            AND t2.period = :current_semester

            WHERE t1.student_id = :student_id
            AND t1.subject_program_id = :subject_program_id
            AND t1.school_year_id = :current_school_year_id
        ");
                
        $sql->bindParam(":current_semester", $current_semester);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":current_school_year_id", $current_school_year_id);
        $sql->execute();

        return $sql->rowCount() > 0;

    }

    public function CheckIfSubjectAlreadyCredited($student_id,
        // $subject_program_id,
        $subject_code){
        
        $sql = $this->con->prepare(" SELECT t1.student_subject_id

            FROM student_subject AS t1

            WHERE t1.student_id = :student_id
                AND t1.subject_program_id IN (
                    SELECT subject_program_id
                    FROM subject_program
                    WHERE subject_code = :subject_code
                )
                AND t1.is_transferee = 1
                AND t1.is_final = 1
        ");

                
        $sql->bindParam(":student_id", $student_id);
        // $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->execute();

        return $sql->rowCount() > 0;

    }

    public function InsertStudentSubjectNonFinal($student_id, $student_subject_code,
        $enrollment_id, $course_id, $subject_program_id,
        $current_school_year_id, $subject_code,
        $student_enrollment_course_level, $checkIfSubjectCodeRetaken = null){


        // Check if overlap = enrollment_course_id is lower to the desired
        // subject load.

        $subject_program = new SubjectProgram($this->con, $subject_program_id);
        $section = new Section($this->con, $course_id);

        $subject_code_course_level = $subject_program->GetCourseLevel();

        // When current student course level is lower or greater to the selected
        // subject load (applicable for irregular).
        if($student_enrollment_course_level != $subject_code_course_level){
            // echo "subject overlap";
        }



        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            (student_id, subject_code, enrollment_id, course_id, subject_program_id,
            school_year_id, is_transferee, is_final, program_code, overlap, retake)
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code, :overlap, :retake)");

        
        $add_student_subject->bindParam(':student_id', $student_id);
        $add_student_subject->bindParam(':subject_code', $student_subject_code);
        $add_student_subject->bindParam(':enrollment_id', $enrollment_id);
        $add_student_subject->bindParam(':course_id', $course_id);
        $add_student_subject->bindParam(':subject_program_id', $subject_program_id);
        $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
        $add_student_subject->bindValue(':is_transferee', 0);
        $add_student_subject->bindValue(':is_final', 0);
        $add_student_subject->bindParam(':program_code', $subject_code);
        $add_student_subject->bindValue(':overlap', $student_enrollment_course_level != $subject_code_course_level ? 1 : 0);
        $add_student_subject->bindValue(':retake', $checkIfSubjectCodeRetaken == true ? 1 : 0);
        $add_student_subject->execute();
        
        if($add_student_subject->rowCount() > 0){
            return true;
        }
        return false;

    }
    public function CheckIfSubjectAlreadyTaken($student_id,
        // $subject_program_id,
        $subject_code){

        
        $sql = $this->con->prepare("SELECT t1.student_subject_id

            FROM student_subject AS t1

            WHERE t1.student_id = :student_id
                AND t1.subject_program_id IN (
                    SELECT subject_program_id
                    FROM subject_program
                    WHERE subject_code = :subject_code
                )
                AND t1.is_transferee = 0
                AND t1.is_final = 0
                AND t1.enrollment_id IS NOT NULL
        ");

                
        $sql->bindParam(":student_id", $student_id);
        // $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->execute();

        return $sql->rowCount() > 0;

    }

    public function CheckIfSubjectAlreadyPassed($student_id,
        // $subject_program_id,
        $subject_code){

        
        $sql = $this->con->prepare("SELECT t1.student_subject_id

            FROM student_subject AS t1

            INNER JOIN student_subject_grade as t2 
            ON t2.student_subject_id = t1.student_subject_id

            WHERE t1.student_id = :student_id
            AND t1.program_code=:program_code
            AND t1.is_final = 1
            AND t2.remarks=  'Passed'

        ");

        $checkIfSubjectAlreadyPassed  = false;

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":program_code", $subject_code);
        $sql->execute();

        if($sql->rowCount() > 0){
            // echo "passed";
            $checkIfSubjectAlreadyPassed  = true;

        }else{
            // echo "hmm";
        }

        return $checkIfSubjectAlreadyPassed;
    }

    // 2 ###

    public function CheckIfSubjectPreRequisiteHasFailed($student_id,
        $pre_requisite_code, $current_school_year_id,
        $subject_code){

        $sql = $this->con->prepare("SELECT t2.remarks

            FROM student_subject AS t1

            LEFT JOIN student_subject_grade as t2 
            ON t2.student_subject_id = t1.student_subject_id

            WHERE t1.student_id=:student_id
            AND t1.program_code = :program_code
            AND t1.is_final = 1
            AND t1.is_transferee = 0
            AND t1.school_year_id != :current_school_year_id
            
            ORDER BY t1.student_subject_id DESC
            LIMIT 1

        ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":program_code", $pre_requisite_code);
        $sql->bindParam(":current_school_year_id", $current_school_year_id);
        $sql->execute();

        // return $sql->rowCount() > 0;
        $isFailedSelectedSubjectCode = NULL;

        if($sql->rowCount() > 0){

            // echo "hmm";
            $remarks = $sql->fetchColumn();

            // If subject pre requisite subject is inserted
            // but not yet passed

            // if($remarks == null){
            //     $isFailedSelectedSubjectCode = true;
            // }

            // If subject pre requisite subject is inserted
            // the remarks is failed
            if($remarks != null && $remarks == "Failed"){
                // Can get Another
                $isFailedSelectedSubjectCode = true;
                // echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";
            
            // If subject pre requisite subject is inserted
            // the remarks is passed
            }
        } 
        return $isFailedSelectedSubjectCode;
    }

    // 1 ###

    public function CheckIfPreRequisiteSubjectTakenPassed($student_id,
        $pre_requisite_code, $current_school_year_id,
        $subject_code){

            // echo $pre_requisite_code;

        $isPreRequisiteTaken = NULL;

        if($pre_requisite_code == "None" ) return true;

        if($pre_requisite_code != "None"){

            $sql = $this->con->prepare("SELECT t2.remarks

            FROM student_subject AS t1

            LEFT JOIN student_subject_grade as t2 
            ON t2.student_subject_id = t1.student_subject_id

            WHERE t1.student_id=:student_id
            AND t1.program_code = :program_code
            AND t1.is_final = 1
            AND t1.is_transferee = 0
            AND t1.school_year_id != :current_school_year_id
            
            ORDER BY t1.student_subject_id DESC
            LIMIT 1

        ");

            $sql->bindParam(":student_id", $student_id);
            $sql->bindParam(":program_code", $pre_requisite_code);
            $sql->bindParam(":current_school_year_id", $current_school_year_id);
            $sql->execute();

            // return $sql->rowCount() > 0;

            if($sql->rowCount() > 0){

                // echo "hmm";
                $remarks = $sql->fetchColumn();
              
                // if($remarks == null){
                //     $isPreRequisiteTaken = false;
                // }
                // // If subject pre requisite subject is inserted
                // // the remarks is failed
                // if($remarks != null && $remarks == "Failed"){
                //     $isPreRequisiteTaken = false;
                //     // echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";
                
                // // If subject pre requisite subject is inserted
                // // the remarks is passed
                // }
                
                if($remarks != null && $remarks == "Passed"){
                    // echo "You have already passed the subject $pre_requisite_code, so you can get $subject_code";
                    $isPreRequisiteTaken = true;
                }
            } 
        }

        
        return $isPreRequisiteTaken;
    }

    public function CheckIfPreRequisiteIsNotTaken(
        $student_id,
        $pre_requisite_code){

        // echo $pre_requisite_code;

        $isPreRequisiteNotTaken = false;

        if($pre_requisite_code == "None" ) return false;
        if($pre_requisite_code == "" ) return false;

        if($pre_requisite_code != "None"
           || $pre_requisite_code != "" ){

            $sql = $this->con->prepare("SELECT t1.student_subject_id

                FROM student_subject AS t1

                WHERE t1.student_id=:student_id
                AND t1.program_code = :program_code
                
                LIMIT 1
            ");

            $sql->bindParam(":student_id", $student_id);
            $sql->bindParam(":program_code", $pre_requisite_code);
            $sql->execute();

            return $sql->rowCount() == 0;
            
        }

        
        return $isPreRequisiteNotTaken;
    }


    # Check if Pre requisite is NOT TAKEN

    # 1. Taken by normal -> Not credited and passed
    # 2. Taken by credited -> Credited.
    
    public function CheckIfPreRequisiteIsNotTakenEitherPassedOrCredited($student_id,
        $pre_requisite_code){

            // echo $pre_requisite_code;
            // return;

        $isPreRequisiteNotTaken = true;

        if($pre_requisite_code == "None" ) return true;

        
        if($pre_requisite_code != "None"){

            if(
                $this->CheckIfSubjectAlreadyPassed(
                    $student_id, $pre_requisite_code) == true
                || 
                
                $this->CheckIfChosenSubjectAlreadyCredited(
                    $student_id, $pre_requisite_code) == true){

                $isPreRequisiteNotTaken = false;
            }
             
        }

        
        // return $$sql->rowCount() > 0;
        return $isPreRequisiteNotTaken;
    }

    public function CheckProgramCoreSubject($student_id,
        $pre_requisite_code, $current_school_year_id,
        $subject_code){

        // Check if core subject has pre-requisite
        // Check if that pre-requisite has passed the student
        $isValidCoreSubject = false;
        
        return $isValidCoreSubject;
    }

    public function CheckSubjectProgramHasBeenSelectedWithinSY($student_id,
        $subject_program_id, $school_year_id, $subject_code = null){

 
        $sql = $this->con->prepare("SELECT t1.student_subject_id

            FROM student_subject AS t1

            WHERE t1.student_id = :student_id
            AND subject_program_id = :subject_program_id
            AND subject_code = :subject_code
            AND t1.school_year_id = :school_year_id

        ");

                
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->execute();

        return $sql->rowCount() > 0;
        
    }


    public function CheckIfChosenSubjectAlreadyCredited($student_id,
        $program_code){
        
            // echo $program_code;
        $sql = $this->con->prepare("SELECT t1.student_subject_id

            FROM student_subject AS t1

            WHERE t1.student_id = :student_id
            AND program_code = :program_code

                -- AND t1.subject_program_id IN (
                --     SELECT subject_program_id
                --     FROM subject_program
                --     WHERE program_code = :program_code
                -- )
                -- AND t1.is_transferee = 1
                -- AND t1.is_final = 1

            AND t1.is_transferee = 1
            AND t1.is_final = 1

        ");

                
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":program_code", $program_code);
        $sql->execute();

        return $sql->rowCount() > 0;
    }
 
    public function CheckIfSubjectCodeRetaken($student_id,
        $pre_requisite_code, $current_school_year_id,
        $subject_code){
        
        // echo $subject_code;

        $hasTakenAndFailed = false;

        if($subject_code == "" ) return false;

        $sql = $this->con->prepare("SELECT t2.remarks

            FROM student_subject AS t1

            LEFT JOIN student_subject_grade as t2 
            ON t2.student_subject_id = t1.student_subject_id
            -- AND t2.student_id

            WHERE t1.student_id=:student_id
            AND t1.program_code = :program_code
            AND t1.is_final = 1
            AND t1.is_transferee = 0
            AND t1.school_year_id != :current_school_year_id
            AND t2.remarks = 'Failed'
            
            ORDER BY t1.student_subject_id DESC
            LIMIT 1
        ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":program_code", $subject_code);
        $sql->bindParam(":current_school_year_id", $current_school_year_id);
        $sql->execute();

        // return $sql->rowCount() > 0;

        if($sql->rowCount() > 0){

            $remarks = $sql->fetchColumn();
            // echo "qwe";
            if($remarks == null){
                $hasTakenAndFailed = false;
            }
            
            if($remarks != null && $remarks == "Failed"){
                // Had taken but failed
                $hasTakenAndFailed = true;
                // echo " Had taken but failed";
        
            }else if($remarks != null && $remarks == "Passed"){
                // Had taken but passed
                $hasTakenAndFailed = false;
                // echo " Had taken but passed";
            }
        } 

        return $hasTakenAndFailed;
    }

    public function CheckSubjectPreReqNotPassedValidation($student_id,
        $pre_requisite_code, $current_school_year_id,
        $subject_code){

            // echo $pre_requisite_code;

        # REQUIREMENTS

        // Trying to insert NSTP103 -> having a pre-req of NSTP102
        // BUt the NSTP102is not yet enrolled in the prev seemester.


        $sql = $this->con->prepare("SELECT 
        
            t2.remarks
            ,t1.program_code
            ,t1.student_subject_id

            FROM student_subject AS t1

            LEFT JOIN student_subject_grade as t2 
            ON t2.student_subject_id = t1.student_subject_id

            WHERE t1.student_id=:student_id
            AND t1.program_code=:program_code
            -- AND t1.is_final = 1
            AND t1.is_transferee = 0
            AND t1.school_year_id != :current_school_year_id
            
            ORDER BY t1.student_subject_id DESC
            LIMIT 1
        ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":program_code", $pre_requisite_code);
        $sql->bindParam(":current_school_year_id", $current_school_year_id);
        $sql->execute();

        // return $sql->rowCount() > 0;
        $isFailedSelectedSubjectCode = false;

        if($sql->rowCount() > 0){

            // echo "qwe";

            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $remarks = $row['remarks'];
            $program_code = $row['program_code'];
            $student_subject_id = $row['student_subject_id'];

            // echo $student_subject_id;

            if($remarks != null && $remarks == "Failed"){
                // Can get Another
                $isFailedSelectedSubjectCode = true;
                echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";

            }else if($remarks != null && $remarks == "Passed"){
                echo "You have already passed the subject $pre_requisite_code, so you can get $subject_code";
                $isFailedSelectedSubjectCode = false;
            }

            // echo $remarks;
        } else{
            echo "hmm";
        }
        return $isFailedSelectedSubjectCode;
    }

    public function GetStudentSubjectCodeByEnrollmentId($enrollment_id){
        
        $code = "";
        $sql = $this->con->prepare("SELECT subject_code

            FROM student_subject AS t1

            WHERE t1.enrollment_id = :enrollment_id");
                
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $code =  $sql->fetchColumn();
        }

        return $code;
    }

    // public function GetStudentSubjectIdBySubjectCode($subject_code,
    //     $student_id, $school_year_id){
        
    //     $code = NULL;
    //     $sql = $this->con->prepare("SELECT student_subject_id

    //         FROM student_subject AS t1

    //         WHERE t1.subject_code = :subject_code
    //         AND t1.student_id = :student_id
    //         AND t1.school_year_id = :school_year_id
        
    //     ");
                
    //     $sql->bindParam(":subject_code", $subject_code);
    //     $sql->bindParam(":student_id", $student_id);
    //     $sql->bindParam(":school_year_id", $school_year_id);
    //     $sql->execute();

    //     if($sql->rowCount() > 0){
    //         $code =  $sql->fetchColumn();
    //     }

    //     return $code;
    // }

    public function GetAEnrolledSubjectByEnrollmentId($student_id,
        $enrollment_id){

        $query = $this->con->prepare("SELECT 
            t4.subject_code AS student_subject_code,
            t4.is_final,
            t4.enrollment_id,
            t4.is_transferee,
            t4.student_subject_id,
            t4.retake AS ss_retake,
            t4.overlap AS ss_overlap,
            

            t5.subject_code AS sp_subjectCode,
            t5.subject_type,
            t5.subject_title,
            t5.unit,

            t6.program_section,

            t7.student_subject_id as graded_student_subject_id,
            t7.remarks,

            t8.subject_schedule_id,
            t8.course_id AS subject_schedule_course_id,
            t8.subject_program_id AS subject_subject_program_id,
            t8.time_from,
            t8.time_to,
            t8.schedule_day,
            t8.schedule_time,
            -- t8.room,

            t9.firstname,
            t9.lastname

            FROM student_subject AS t4 

            LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
            LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
            LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

            LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t4.subject_code
            AND t8.course_id = t4.course_id

            LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

            WHERE t4.student_id=:student_id
            AND t4.enrollment_id=:enrollment_id

            ORDER BY t5.subject_title DESC
        ");

        $query->bindValue(":student_id", $student_id); 
        $query->bindValue(":enrollment_id", $enrollment_id); 
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetIdBySubjectCode($section_subject_code,
        $school_year_id
        ){

        $sql = $this->con->prepare("SELECT student_subject_id 
        
            FROM student_subject

            WHERE school_year_id=:school_year_id
            AND subject_code=:subject_code");

        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":subject_code", $section_subject_code);
        $sql->execute();

        if($sql->rowCount() > 0){
            
            return $sql->fetchColumn();
        }
        return null;
    }

    public function ChangingStudentSubjectCourseId($enrollment_id,
        $student_course_id,
        $student_id, $current_school_year_id, $chosen_course_id,
        $student_subject_id, $student_subject_program_id){

        $section = new Section($this->con, $chosen_course_id);
        $subject_program = new SubjectProgram($this->con, $student_subject_program_id);

        $program_section = $section->GetSectionName();

        $subject_code = $subject_program->GetSubjectProgramRawCode();

        $chosen_section_subject_code = $section->CreateSectionSubjectCode($program_section,
            $subject_code);

        $update = $this->con->prepare("UPDATE student_subject as t1
            SET course_id=:chosen_course_id,
                subject_code=:section_subject_code

            WHERE t1.enrollment_id = :enrollment_id
            AND t1.course_id = :course_id
            AND t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.student_subject_id = :student_subject_id
            
            ");
        
        $update->bindParam(":chosen_course_id", $chosen_course_id);
        $update->bindParam(":section_subject_code", $chosen_section_subject_code);
        $update->bindParam(":enrollment_id", $enrollment_id);
        $update->bindParam(":course_id", $student_course_id);
        $update->bindParam(":student_id", $student_id);
        $update->bindParam(":school_year_id", $current_school_year_id);
        $update->bindParam(":student_subject_id", $student_subject_id);

        return $update->execute();

    }

    public function UpdateCurrentCodeToSelectedSectionCode(
        $student_enrollment_id,
        $student_enrollment_course_id,
        $student_subject_program_id,
        $selected_course_id){


        $section = new Section($this->con, $selected_course_id);
        $sectionLevel = $section->GetSectionGradeLevel();
        $program_section = $section->GetSectionName();
        
        $selected_section_program_id = $section->GetSectionProgramId($selected_course_id);
        
        $subject_program = new SubjectProgram($this->con, $student_subject_program_id);

        $subject_code = $subject_program->GetSubjectProgramRawCode();

        $section_subject_code = $section->CreateSectionSubjectCode($program_section,
            $subject_code);

        $array = [];

        # Get all subject code of selected course.
        $query = $this->con->prepare("SELECT *

            FROM subject_program  

            WHERE program_id = :program_id
            AND course_level = :course_level
            ");
                
        $query->bindParam(":program_id", $selected_section_program_id);
        $query->bindParam(":course_level", $sectionLevel);
        $query->execute();

        $isUpdateFinished = false;

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $subject_code = $row['subject_code'];
                // $chosen_course_id = $row['course_id'];

                $chosen_section_subject_code = $section->CreateSectionSubjectCode($program_section,
                    $subject_code);
                
                # Check if selected subject code is 
                # equal to student_subject subject code

                $my_subject = $this->con->prepare("SELECT program_code

                    FROM student_subject  

                    WHERE program_code = :program_code
                    AND enrollment_id = :enrollment_id
                    AND course_id = :course_id
                    LIMIT 1
                    ");
                        
                $my_subject->bindParam(":program_code", $subject_code);
                $my_subject->bindParam(":enrollment_id", $student_enrollment_id);
                $my_subject->bindParam(":course_id", $student_enrollment_course_id);
                $my_subject->execute();

                if($my_subject->rowCount() > 0){

                    $current_subject_row = $my_subject->fetch(PDO::FETCH_ASSOC);

                    $current_subject_code = $current_subject_row['program_code'];


                    // array_push($array, $my_subject->fetchColumn());
                    // array_push($array, $chosen_section_subject_code);

                    $update_current_subject = $this->con->prepare("UPDATE student_subject
                        SET subject_code = :subject_code,
                            course_id = :to_change_course_id
                        
                        WHERE program_code = :program_code
                        AND enrollment_id = :enrollment_id
                        AND course_id = :course_id
                    ");

                    $update_current_subject->bindParam(":subject_code", $chosen_section_subject_code);
                    $update_current_subject->bindParam(":to_change_course_id", $selected_course_id);
                    $update_current_subject->bindParam(":program_code", $subject_code);
                    $update_current_subject->bindParam(":enrollment_id", $student_enrollment_id);
                    $update_current_subject->bindParam(":course_id", $student_enrollment_course_id);
                    $update_current_subject->execute();

                    $isUpdateFinished = true;
                }

            }
        }
        
        return $isUpdateFinished;
    }

    public function RemovingSubjectLoads($student_id, $enrollment_id,
        $current_school_year_id){

 
        // REMOVE AND ADD CHOSEN course id
        $remove = $this->con->prepare("DELETE FROM student_subject
             
            WHERE student_id=:student_id
            AND enrollment_id=:enrollment_id
            AND school_year_id=:current_school_year_id
            ");

        $remove->bindParam(":student_id", $student_id);
        $remove->bindParam(":enrollment_id", $enrollment_id);
        $remove->bindParam(":current_school_year_id", $current_school_year_id);
        $remove->execute();

        if($remove->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function GetAllEnrolledSubjectCode($student_id,
        $current_school_year_id, $enrollment_id){
        
            // echo $program_code;
        $sql = $this->con->prepare("SELECT t1.subject_code

            FROM student_subject AS t1

            WHERE t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.enrollment_id = :enrollment_id

        ");

                
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function GetAllEnrolledSubjectCodeELMS($student_id,
        $school_year_id, $enrollment_id){
        
            // echo $program_code;
        $query = $this->con->prepare("SELECT 

            t4.subject_code AS student_subject_code,
            t4.is_final,
            t4.enrollment_id,
            t4.is_transferee,
            t4.student_subject_id,
            t4.retake AS ss_retake,
            t4.overlap AS ss_overlap,

            t5.subject_code AS sp_subjectCode,
            t5.subject_type,
            t5.subject_title,
            t5.unit,

            t6.program_section,

            t7.student_subject_id as graded_student_subject_id,
            t7.remarks,

            t8.subject_schedule_id,
            t8.course_id AS subject_schedule_course_id,
            t8.subject_program_id AS subject_subject_program_id,
            t8.time_from,
            t8.time_to,
            t8.schedule_day,
            t8.schedule_time,

            t9.firstname,
            t9.lastname

            FROM student_subject AS t4 

            LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
            LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
            LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

            LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t4.subject_code
            AND t8.course_id = t4.course_id

            LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

            WHERE t4.student_id=:student_id
            AND t4.enrollment_id=:enrollment_id
            AND t4.school_year_id=:school_year_id

            GROUP BY t4.subject_code

            ORDER BY t5.subject_title DESC
        ");

        $query->bindValue(":student_id", $student_id); 
        $query->bindValue(":enrollment_id", $enrollment_id); 
        $query->bindValue(":school_year_id", $school_year_id); 
        
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetAllPassedPreviousEnrolledSubjects($student_id,
        $school_year_id){
        
        // echo $program_code;
        # we should consuder the remarks of enrolled subject to be 'PASSED'
        # to be officially completed the subject
        $query = $this->con->prepare("SELECT 
        
            t1.student_subject_id,
            t4.subject_code,
            t4.subject_type,
            t4.subject_title,
            t4.unit,
            t9.firstname,
            t9.lastname
            
            FROM student_subject AS t1 


            INNER JOIN enrollment AS t2 ON t2.enrollment_id = t1.enrollment_id

            -- INNER JOIN student_subject_grade AS t3 ON t3.student_subject_id = t1.student_subject_id
            -- AND t3.remarks = 'Passed'

            LEFT JOIN subject_program AS t4 ON t4.subject_program_id = t1.subject_program_id
            
            LEFT JOIN course AS t6 ON t6.course_id = t1.course_id

            LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t1.subject_code
            AND t8.course_id = t1.course_id

            LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

            WHERE t1.student_id=:student_id

            -- Not equal to current school year
            
            AND t1.school_year_id != :school_year_id
 

            ORDER BY t1.school_year_id ASC
        ");

        $query->bindValue(":student_id", $student_id); 
        $query->bindValue(":school_year_id", $school_year_id); 
        
        $query->execute(); 

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function CheckHasCreditedSubjectWithinSemester(
        $student_id, $school_year_id){


        $sql = $this->con->prepare("SELECT 
            t1.*
            
            FROM student_subject as t1

            WHERE t1.student_id = :student_id
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
        ");
                
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":is_final", 1);

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function GetStudentSubjectIdBySubjectCode(
        $student_id, $school_year_id, $subject_code){


        $sql = $this->con->prepare("SELECT 
            t1.student_subject_id
            
            FROM student_subject as t1

            WHERE t1.student_id = :student_id
            AND t1.subject_code = :subject_code
            AND t1.school_year_id = :school_year_id
            AND t1.is_final = :is_final
        ");
                
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":is_final", 1);

        $sql->execute();

        if($sql->rowCount() > 0){
            
            return $sql->fetchColumn();
        }

        return 0;
    }

    public function SendingEmailAfterSuccessfulEnrollment(
        $processEnrolled){

        echo "<script>\n";
        echo "let processEnrolledJs = `$processEnrolled`;\n";
        echo "processEnrolledJs = processEnrolledJs.trim();\n";
        echo "if (processEnrolledJs == false) {\n";
        echo "var buttonToClick = document.getElementById('toClickButton');\n";
        echo "buttonToClick.click();\n";
        echo "\$processEnrolled = true;\n";
        echo "}\n";
        echo "</script>";

    }


    public function AddSubjectProgramIntoStudentSubjectList(

        $student_id, $subject_code, $enrollment_id,
        $student_enrollment_course_id, $student_subject_program_id,
        $current_school_year_id, $program_code){

        $add_student_subject = $this->con->prepare("INSERT INTO student_subject
            
            (student_id, subject_code, enrollment_id, course_id,
            subject_program_id, school_year_id, is_transferee, is_final, program_code)
            
            VALUES (:student_id, :subject_code, :enrollment_id, :course_id, :subject_program_id,
            :school_year_id, :is_transferee, :is_final, :program_code)
        ");

        $add_student_subject->bindParam(':student_id', $student_id);
        $add_student_subject->bindParam(':subject_code', $subject_code);
        $add_student_subject->bindParam(':enrollment_id', $enrollment_id);
        $add_student_subject->bindParam(':course_id', $student_enrollment_course_id);
        $add_student_subject->bindParam(':subject_program_id', $student_subject_program_id);
        $add_student_subject->bindParam(':school_year_id', $current_school_year_id);
        $add_student_subject->bindValue(':is_transferee', 0);
        $add_student_subject->bindValue(':is_final', 0);
        $add_student_subject->bindParam(':program_code', $program_code);
        $add_student_subject->execute();
        
        if($add_student_subject->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function GetStudentSubjectIdBySectionSubjectCode(
        $sectionSubjectCode, $student_id, $enrollment_id){

        $sql = $this->con->prepare("SELECT student_subject_id FROM student_subject

            WHERE subject_code = :subject_code
            AND enrollment_id = :enrollment_id
            AND student_id = :student_id
        ");
                
        $sql->bindParam(":subject_code", $sectionSubjectCode);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }

        return NULL;;
    }

}

?>