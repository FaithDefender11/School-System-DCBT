<?php

    class Enrollment{

    private $con, $userLoggedIn;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function CheckIdExists($enrollment_id) {

        $query = $this->con->prepare("SELECT * FROM enrollment
                WHERE enrollment_id=:enrollment_id");

        $query->bindParam(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() == 0){
            echo "
                <div class='col-md-12'>
                    <h4 class='text-center text-warning'>Enrollment Form is not. Please contact the admin.</h4>
                </div>
            ";
            exit();
        }
    }

    public function GetStudentEnrolled($course_id, $school_year_id = null){

        $value = 0;

        $sql = $this->con->prepare("SELECT * FROM course as t1

            INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
            AND t2.enrollment_status=:enrollment_status

            -- TO MAKE SURE enrollment student id is not a dummy.
            INNER JOIN student as t3 ON t3.student_id = t2.student_id


            WHERE t2.course_id=:course_id
            AND t2.school_year_id=:school_year_id
        ");
                
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $value = $sql->rowCount();
        }

        return $value;

    }

    public function GetStudentEnrolledInSection($course_id,
        $school_year_id = null,
        $school_year_term = null,
        $school_year_period= null
        ){

        $value = 0;

        $sql = $this->con->prepare("SELECT * 

            FROM course AS t1
            
            INNER JOIN enrollment AS t2 ON t2.course_id = t1.course_id
            AND t2.enrollment_status=:enrollment_status

            INNER JOIN school_year AS t3 ON t3.school_year_id = t2.school_year_id

            -- INNER JOIN student AS t4 ON t4.student_id = t2.student_id

            AND t3.term=:term
            AND t3.period=:period

            WHERE t2.course_id=:course_id
            AND t1.school_year_term=:c_school_year_term
            -- AND t2.school_year_id=:school_year_id
        ");
                
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->bindParam(":course_id", $course_id);
        // $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":term", $school_year_term);
        $sql->bindParam(":period", $school_year_period);
        $sql->bindParam(":c_school_year_term", $school_year_term);
        $sql->execute();

        // if($sql->rowCount() > 0){
        //     $value = $sql->rowCount();
        // }

        return $sql->rowCount();;

    }

    public function GetStudentEnrollmentStatusTypeInSection($course_id,
        $school_year_term = null,
        $school_year_period = null,
        $status_type

        ){

        $value = 0;

        $sql = $this->con->prepare("SELECT * 

            FROM course AS t1
            
            INNER JOIN enrollment AS t2 ON t2.course_id = t1.course_id
            AND t2.enrollment_status=:enrollment_status

            INNER JOIN school_year AS t3 ON t3.school_year_id = t2.school_year_id

            -- INNER JOIN student AS t4 ON t4.student_id = t2.student_id

            AND t3.term=:term
            AND t3.period=:period
            WHERE t2.course_id=:course_id
            AND t1.school_year_term=:c_school_year_term
        ");
                
        $sql->bindValue(":enrollment_status", $status_type);
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":term", $school_year_term);
        $sql->bindParam(":period", $school_year_period);
        $sql->bindParam(":c_school_year_term", $school_year_term);
        $sql->execute();

        if($sql->rowCount() > 0){
            $value = $sql->rowCount();
        }

        return $value;

    }

    public function CheckStudentEnrolled(
        $student_id, $course_id = null,
        $school_year_id){

        $returnBool = false;

        $sql = $this->con->prepare("SELECT enrollment_status FROM enrollment 
            -- AND course_id = :course_id
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            -- AND enrollment_status = :enrollment_status
            ORDER BY enrollment_id DESC
            LIMIT 1
            ");

        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        // $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        if($sql->rowCount() > 0){
            $enrollment_status = $sql->fetchColumn();

            if($enrollment_status == "enrolled"){
                $returnBool = true;
            }
            if($enrollment_status == "tentative"){
                $returnBool = false;
            }
        }

        return $returnBool;
    }

    public function GetStudentPreviousEnrolledForm(
        $student_id, $school_year_id){


        $form_id = NULL;
        

        $sql = $this->con->prepare("SELECT 
        
            enrollment_status,
            enrollment_id,
            enrollment_form_id

            FROM enrollment 

            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            ORDER BY enrollment_id DESC

            ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        # We have two forms
        if($sql->rowCount() > 1){

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                $enrollment_status = $row['enrollment_status'];
                $enrollment_id = $row['enrollment_id'];
                $enrollment_form_id = $row['enrollment_form_id'];
                
                if($enrollment_status == "enrolled"){
                    $form_id = $enrollment_form_id;
                    #REMOVE
                }
            }
        }

        return $form_id;

    }

    public function CheckPreviousEnrolledFormHasSameSectionToCurrrent(
        $student_id, $school_year_id){

        $previous_form_course_id = NULL;
        $current_form_course_id = NULL;
        
        $sql = $this->con->prepare("SELECT 
        
            enrollment_status,
            enrollment_id,
            enrollment_form_id,
            course_id

            FROM enrollment 

            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            ORDER BY enrollment_id DESC

            ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        # We have two forms
        if($sql->rowCount() > 1){

            // echo "qwe";
            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                $enrollment_status = $row['enrollment_status'];
                $course_id = $row['course_id'];
                
                if($enrollment_status == "enrolled"){
                    $previous_form_course_id = $course_id;
                }
                if($enrollment_status == "tentative"){
                    $current_form_course_id = $course_id;
                }
            }
        }

        // var_dump($current_form_course_id);
        // echo "<br>";

        // var_dump($previous_form_course_id);
        // echo "<br>";

        

        if($current_form_course_id != NULL && $previous_form_course_id != NULL
            && $current_form_course_id == $previous_form_course_id){
                return true;
            }


            
        return false;

    }

    public function CheckStudentHasEnrolledFormAndRemove(
        $student_id, $school_year_id){


        $doesRemoved = false;

        $sql = $this->con->prepare("SELECT 

            enrollment_status,
            enrollment_id,
            enrollment_form_id

            FROM enrollment 

            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            ORDER BY enrollment_id DESC

            ");

        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        # We have two forms
        if($sql->rowCount() > 1){

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                $enrollment_status = $row['enrollment_status'];
                $enrollment_id = $row['enrollment_id'];
                
                if($enrollment_status == "enrolled"){

                    #REMOVE
                    $removed = $this->RemoveEnrolledForm($enrollment_id);
                    if($removed == true){
                        $doesRemoved = true;
                    }

                }
            }
           
        }

        return $doesRemoved;
    }

    public function RemoveEnrolledForm($enrollment_id){

        $delete = $this->con->prepare("DELETE FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND enrollment_status = :enrollment_status
            ");

        $delete->bindParam(":enrollment_id", $enrollment_id);
        $delete->bindValue(":enrollment_status", "enrolled");
        $delete->execute();

        if($delete->rowCount() > 0){
            return true;
        }
        return false;
    }
   

    public function CheckRequestEnrollmentRequestValid($enrollment_form_id,
        $student_id, $school_year_id){

        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE student_id = :student_id
            AND enrollment_form_id = :enrollment_form_id
            AND school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            
            ");
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "tentative");

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function GetStudentEnrollmentDateWithinSemester($student_id,
        $course_id, 
        $current_school_year_id){

        $sql = $this->con->prepare("SELECT enrollment_approve FROM enrollment

            WHERE course_id=:course_id
            AND student_id=:student_id
            AND school_year_id=:school_year_id
        ");
                
        $sql->bindParam(":course_id", $course_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->execute();

        return $sql->fetchColumn();

    }


    public function GetStudentSectionGradeElevenSchoolYear($student_id,
        $SEMESTER){
 

        $query = $this->con->prepare("SELECT 

            e.student_id, e.enrollment_id,
            e.enrollment_form_id,
             e.course_id, sy.school_year_id, 
            sy.period, sy.term

            FROM enrollment e

            INNER JOIN school_year sy ON e.school_year_id = sy.school_year_id
            INNER JOIN course c ON c.course_id = e.course_id

            WHERE e.student_id = :student_id
            AND e.enrollment_status=:enrollment_status
            AND sy.period =:first_sem
            -- AND c.course_id =:course_id
            ");

        $query->bindValue(":student_id", $student_id); 
        $query->bindValue(":first_sem", $SEMESTER); 
        $query->bindValue(":enrollment_status", "enrolled"); 
        $query->execute(); 

        if($query->rowCount() > 0){
            $result = $query->fetch(PDO::FETCH_ASSOC);
            // print_r($result);
            return $result;
        }
        return null;
    }



    public function RetakeEnrollment($student_id){
        $query = $this->con->prepare("SELECT 

            t1.*, t2.term, t2.period,
            t3.course_id, t3.program_section,
            t3.course_level

            -- t4.subject_code,

            -- t5.subject_code AS sp_subjectCode

            FROM enrollment AS t1

            INNER JOIN school_year AS t2 ON t2.school_year_id = t1.school_year_id
        
            LEFT JOIN course AS t3 ON t3.course_id = t1.course_id

            -- LEFT JOIN student_subject AS t4 ON t4.enrollment_id = t1.enrollment_id
            -- AND t1.student_id = t4.student_id

            -- LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id


            WHERE t1.student_id=:student_id
            AND t1.retake = 1

            ");

            $query->bindValue(":student_id", $student_id); 
            $query->execute(); 

            if($query->rowCount() > 0){

                // while($row = $query->fetch(PDO::FETCH_ASSOC)){

                //     $enrollment_id = $row['enrollment_id'];
                //     $course_id = $row['course_id'];
                //     $school_year_id = $row['school_year_id'];

                // }
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($result);
                return $result;
            }

            return []; 
    }

    public function GetEnrolledSubjectForm($student_id){
        $query = $this->con->prepare("SELECT 

            t1.*, t2.term, t2.period, t2.school_year_id,
            t3.course_id, t3.program_section,
            t3.course_level

            -- t4.subject_code,

            -- t5.subject_code AS sp_subjectCode

            FROM enrollment AS t1

            INNER JOIN school_year AS t2 ON t2.school_year_id = t1.school_year_id
        
            LEFT JOIN course AS t3 ON t3.course_id = t1.course_id

            WHERE t1.student_id=:student_id
            -- AND t1.retake = 1

            ");

            $query->bindValue(":student_id", $student_id); 
            $query->execute(); 

            if($query->rowCount() > 0){

                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // print_r($result);
                return $result;
            }

            return []; 
    }
 
    public function GetRetake($student_id){

        // echo $grade_level;

        $query = $this->con->prepare("SELECT 

            t1.enrollment_id,
            t1.student_status,
            t2.term,
            t2.period,
            t3.program_section,
            t3.course_id,

            t4.subject_code,

            t5.remarks

            FROM enrollment AS t1

            INNER JOIN school_year AS t2 ON t2.school_year_id = t1.school_year_id
            INNER JOIN course AS t3 ON t3.course_id = t1.course_id

            INNER JOIN student_subject AS t4 ON t4.enrollment_id = t1.enrollment_id
            AND t1.student_id = t4.student_id

            LEFT JOIN student_subject_grade AS t5 ON t5.student_subject_id = t4.student_subject_id
 
            WHERE t1.student_id=:student_id
            AND t1.retake= 1

            ");

        $query->bindValue(":student_id", $student_id); 
        $query->execute(); 

        if($query->rowCount() > 0){

            // while($row = $query->fetch(PDO::FETCH_ASSOC)){
            // }
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            // print_r($result);
            return $result;
        }

        return null;
    }

    public function getEnrollmentSectionDetails($student_id,
        $grade_level, $semester){

        $enrollment = new Enrollment($this->con); 

        // echo $semester;

        $enrollmentDetails = $enrollment->GetStudentSectionGradeLevelSemester(
            $student_id, $grade_level, $semester
        );

        if ($enrollmentDetails === null) return null;

        $enrollment_course_id = $enrollmentDetails['course_id'];
        $enrollment_date_approved = $enrollmentDetails['enrollment_approve'];
        $enrollment_student_status = $enrollmentDetails['student_status'];
        $enrollment_period = $enrollmentDetails['period'];

        $date = new DateTime($enrollment_date_approved);
        $enrollment_date_approved = $date->format('m/d/Y');

        $enrollment_section = new Section($this->con);

        $enrollment_section_program_id = $enrollment_section->GetSectionProgramId($enrollment_course_id);
        $enrollment_section_acronym = $enrollment_section->GetAcronymByProgramId($enrollment_section_program_id);
        $enrollment_section_level = $enrollment_section->GetSectionGradeLevel($enrollment_course_id);

        return array(
            'enrollment_course_id' => $enrollment_course_id,
            'enrollment_date_approved' => $enrollment_date_approved,
            'enrollment_student_status' => $enrollment_student_status,
            'section_program_id' => $enrollment_section_program_id,
            'enrollment_section_acronym' => $enrollment_section_acronym,
            'enrollment_section_level' => $enrollment_section_level,
            'enrollment_period' => $enrollment_period
        );
    }

    public function GetStudentSectionGradeLevelSemester(
        $student_id, $grade_level, $SEMESTER){


        if(false){
            $query = $this->con->prepare("SELECT 

                e.student_id, e.course_id, sy.school_year_id, sy.period, sy.term,
                e.enrollment_id,
                e.enrollment_approve,
                c.course_level,
                c.course_id,
                e.student_status,
                e.enrollment_form_id

                FROM enrollment e

                INNER JOIN school_year sy ON e.school_year_id = sy.school_year_id
                INNER JOIN course c ON c.course_id = e.course_id

                WHERE e.student_id = :student_id
                AND e.enrollment_status = :enrollment_status
                AND sy.period = :selected_semester
                AND c.course_level = :course_level
                AND e.retake = 0
                "); 
        }

        # Getting the latest Enrollment course id
        # Even though, student on his grade 11 enrollment has 2 program_id
        # in this case, we should reflect the current course program.
        $query = $this->con->prepare("SELECT 

            t1.student_id, t1.course_id, t2.school_year_id, t2.period, t2.term,
            t1.enrollment_id,
            t1.enrollment_approve,
            t3.course_level,
            t3.course_id,
            t1.student_status,
            t1.enrollment_form_id

            FROM enrollment t1

            INNER JOIN school_year t2 ON t1.school_year_id = t2.school_year_id
            INNER JOIN course t3 ON t3.course_id = t1.course_id

            WHERE t1.student_id = :student_id
            AND t1.enrollment_status = :enrollment_status
            AND t2.period = :selected_semester
            AND t3.course_level = :course_level
            AND t1.retake = 0

            ORDER BY t1.enrollment_id DESC

            ");

        $query->bindParam(":student_id", $student_id); 
        $query->bindParam(":selected_semester", $SEMESTER); 
        $query->bindValue(":enrollment_status", "enrolled"); 
        $query->bindParam(":course_level", $grade_level); 
        $query->execute(); 

        if($query->rowCount() > 0){
            $result = $query->fetch(PDO::FETCH_ASSOC);
            // print_r($result);
            return $result;
        }

        return null;
    }










    public function GetEnrollmentDetailsByGradeLevelSemester($student_id,
        $grade_level, $SEMESTER, $enrollment_date, $enrollment_section){

      
        $obj = $this->GetStudentSectionGradeLevelSemester($student_id,
            $grade_level, $SEMESTER);
        
        
        $enrollment_course_id = $obj['course_id'];
        $enrollment_date_approved = $obj['enrollment_approve'];

        $date = new DateTime($enrollment_date);
        $enrollment_date_approved = $date->format('m/d/Y');

        $enrollment_student_status = $obj['student_status'];

        // $enrollment_section = new Section($con, $enrollment_course_id);

        $enrollment_section_program_id = $enrollment_section->GetSectionProgramId($enrollment_course_id);
        $enrollment_section_acronym = $enrollment_section->GetAcronymByProgramId($enrollment_section_program_id);
        $enrollment_section_level = $enrollment_section->GetSectionGradeLevel();

        $array = [];

        array_push($array, $enrollment_date_approved);
        array_push($array, $enrollment_student_status);
        array_push($array, $enrollment_section_acronym);
        array_push($array, $enrollment_section_level);

        return $array;

    }

    public function UnionEnrollment($current_school_year_id){

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
            t1.student_unique_id AS student_unique_id,
            t1.new_enrollee AS new_enrollee,
            t2.student_status AS enrollment_student_status,
            t2.is_new_enrollee AS enrollment_is_new_enrollee,
            t2.is_transferee AS enrollment_is_transferee,
            t2.enrollment_id AS enrollment_id
            
            -- Ongoing Irregular Student Query. (BEFORE)

            -- Regular, Irregular.

            FROM student as t1
            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND t1.active = 1
            AND t2.school_year_id = :enrollment_school_year_id

            AND (t2.course_id = t1.course_id OR t2.course_id = 0 OR t2.course_id != 0)

            -- AND t2.student_status = 'Irregular'
            AND t2.registrar_evaluated = 'no'
            AND t2.cashier_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'
            AND t2.enrollment_status != 'enrolled'

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id
            LEFT JOIN program as t4 ON t4.program_id = t3.program_id

            -- WHERE t1.admission_status = 'Transferee'

            AND t2.registrar_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'
            -- AND t2.is_new_enrollee = 'no'


            UNION

            SELECT 
            
            t1.firstname, t1.lastname, NULL, NULL,
            t1.date_creation AS submission_creation,
            t1.program_id,
            t1.admission_status AS student_status_pending,
            NULL AS admission_status,
            NULL AS student_classification,
            t1.pending_enrollees_id,
            NULL AS student_statusv2,
            NULL AS student_course_id,
            NULL AS student_id,
            NULL AS student_unique_id,
            NULL AS new_enrollee,
            NULL AS enrollment_student_status,
            NULL AS enrollment_is_new_enrollee,
            NULL AS enrollment_is_transferee,
            NULL AS enrollment_id

            FROM pending_enrollees as t1
            LEFT JOIN program as t2 ON t2.program_id = t1.program_id
            -- WHERE t1.student_status != 'APPROVED'
            WHERE t1.student_status = 'EVALUATION'
            AND t1.is_finished = 1
            AND t1.school_year_id = :pending_school_year_id

        ");

        $sql->bindValue("pending_school_year_id", $current_school_year_id);
        $sql->bindValue("enrollment_school_year_id", $current_school_year_id);
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

        $sql->bindParam(":enrollment_form_id", $enrollmentFormId);
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
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();
        
        return $sql->fetchColumn();
    }

    public function GetEnrollmentIdNonDependent($student_id, $school_year_id) {
        
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            -- AND enrollment_status = 'tentative'
            
            ORDER BY enrollment_id DESC

            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    public function GetEnrollmentIdByForm($enrollment_id, $school_year_id) {
        
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            -- AND school_year_id = :school_year_id
            
            -- ORDER BY enrollment_id DESC

            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        // $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    public function GetEnrollmentIdNonEnrolled($student_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            
            -- ORDER BY enrollment_id ASC

            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "tentative");
        $sql->execute();
        
        return $sql->fetchColumn();
    }


    function GetStudentIdByEnrollmentId($enrollment_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT student_id FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            -- AND school_year_id = :school_year_id
            
            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        // $sql->bindParam(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    function GetEnrollmentFormIdBased($enrollment_id) {
        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            
            ");
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    function GetEnrollmentFormId($enrollment_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND enrollment_id = :enrollment_id
            AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    function GetEnrollmentFormByFormId($enrollment_id, $course_id,
        $school_year_id) {
        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND enrollment_id = :enrollment_id
            -- AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    public function GetEnrollmentFormCourseId($student_id, $enrollment_id,
        $school_year_id = null) {

        $student_enrollment_course_id = 0;

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT course_id FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            -- AND school_year_id = :school_year_id
            -- AND enrollment_status != 'withdraw'
            ORDER BY enrollment_id DESC
            LIMIT 1
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        if($sql->rowCount() > 0){
            $student_enrollment_course_id = $sql->fetchColumn();
        }

        return $student_enrollment_course_id;

    }

    public function GetEnrollmentFormCourseIdByForm($student_id, $enrollment_id,
        $school_year_id) {

        $student_enrollment_course_id = 0;

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT course_id FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            -- AND enrollment_status != 'withdraw'
            ORDER BY enrollment_id DESC
            LIMIT 1
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();
        if($sql->rowCount() > 0){
            $student_enrollment_course_id = $sql->fetchColumn();
        }

        return $student_enrollment_course_id;

    }

    public function GetEnrollmentFormStudentStatus($student_id, $enrollment_id,
        $school_year_id = null) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT student_status FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            -- AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentFormEnrollmentStatus($student_id, $enrollment_id,
        $school_year_id = null) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_status FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            -- AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentMadeDateForm($student_id, $enrollment_id,
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_date FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentSchoolYearByIdForm(
        $student_id, $enrollment_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT school_year_id FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    // public function GetEnrollmentFormWaitingList($student_id, $enrollment_id,
    //     $school_year_id) {

    //     $waiting_list = "";

    //     // Check if the enrollment form ID already exists in the database

    //     $sql = $this->con->prepare("SELECT waiting_list FROM enrollment 

    //         WHERE enrollment_id = :enrollment_id
    //         AND student_id = :student_id
    //         AND school_year_id = :school_year_id
            
    //     ");
 
    //     $sql->bindValue(":enrollment_id", $enrollment_id);
    //     $sql->bindValue(":student_id", $student_id);
    //     $sql->bindValue(":school_year_id", $school_year_id);
    //     $sql->execute();

    //     if($sql->rowCount() > 0){
    //         $waiting_list = $sql->fetchColumn();
    //     }

    //     return $waiting_list;

    // }


    public function GetEnrollmentFormRetakeStatus($student_id, $enrollment_id,
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT retake FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentFormIsTransferee($student_id, $enrollment_id,
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT is_transferee FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentFormIsTertiary($student_id,
        $enrollment_id,
        $school_year_id = null) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT is_tertiary FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            -- AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;
    }
    
    public function EnrollmentPaymentCompleted(
        $enrollment_id){

        $COMPLETE_PAYMENT = "Complete";

        $sql = $this->con->prepare("UPDATE enrollment
            SET payment_status=:payment_status
            WHERE enrollment_id=:enrollment_id

        ");


        $sql->bindValue(":payment_status", $COMPLETE_PAYMENT);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
            
        return false;
    }

    public function GetEnrollmentPaymentStatus($student_id,
        $enrollment_id) {

        $sql = $this->con->prepare("SELECT payment_status FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }
    public function GetEnrollmentTotalPayment($student_id,
        $enrollment_id) {

        $sql = $this->con->prepare("SELECT enrollment_payment FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

   

    public function GetEnrollmentPaymentMethod($student_id,
        $enrollment_id) {

        $sql = $this->con->prepare("SELECT payment_method FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function CheckEnrollmentFormRegistrarEvaluated($student_id, $enrollment_id,
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT registrar_evaluated FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }



    public function GetEnrollmentFormIsNew($student_id, $enrollment_id,
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT is_new_enrollee FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
        ");
 
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":student_id", $student_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $student_status = $sql->fetchColumn();
        }

        return $student_status;

    }

    public function GetEnrollmentDate($student_id, $course_id, $school_year_id) {
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

    public function GetEnrollmentIsNewEnrollee($enrollment_id, 
        $course_id, $school_year_id = null) {
        
        $sql = $this->con->prepare("SELECT is_new_enrollee FROM enrollment 
            WHERE course_id = :course_id
            AND enrollment_id = :enrollment_id
            -- AND school_year_id = :school_year_id
            
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }

    

    public function GetEnrollmentIsTransferee($student_id, 
        $course_id, $school_year_id) {
        
        $sql = $this->con->prepare("SELECT is_transferee FROM enrollment 
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

    public function GetEnrollmentIsTransfereeByFormId($enrollment_id, 
        $course_id, $school_year_id) {
        
        $sql = $this->con->prepare("SELECT is_transferee FROM enrollment 
            WHERE course_id = :course_id
            AND enrollment_id = :enrollment_id
            -- AND school_year_id = :school_year_id
            ");
        $sql->bindValue(":course_id", $course_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        // $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        return $sql->fetchColumn();
    }


    public function CheckEnrollmentFormIdExists($enrollment_form_id){

        $new_enrollment_form_id = $enrollment_form_id;
        
        $query = $this->con->prepare("SELECT enrollment_form_id FROM enrollment
            WHERE enrollment_form_id = :enrollment_form_id
          ");
        $query->bindParam(":enrollment_form_id", $enrollment_form_id);
        $query->execute();

        // echo $enrollment_form_id;
        
        if($query->rowCount() > 0){
            # Generate new Unique Enrollment Form Id to be use.

            // echo "hey";
            $new_enrollment_form_id = $this->GenerateEnrollmentFormId();

            return $new_enrollment_form_id;
        }

        return $new_enrollment_form_id;
    }

    public function CheckStudentEnrollmentFormExists($school_year_id, $student_id){

        $query = $this->con->prepare("SELECT enrollment_id FROM enrollment
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
          ");
        $query->bindParam(":student_id", $student_id);
        $query->bindParam(":school_year_id", $school_year_id);
        $query->execute();
        
        if($query->rowCount() > 0){
           return true;
        }

        return false;
    }

    public function WaitingPaymentEnrollment($current_school_year_id){

        $default_shs_course_level = 11;
        $is_new_enrollee = 1;
        $is_transferee = 1;
        $regular_Status = "Regular";
        $enrollment_status = "tentative";
        $registrar_evaluated = "yes";

        $registrar_side = $this->con->prepare("SELECT 

            t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
            t1.enrollment_date,
            t1.is_transferee,
            t1.course_id as enrollment_course_id,
            t1.student_status as enrollment_student_status,
            t1.is_new_enrollee as enrollment_is_new_enrollee,
            t1.is_transferee as enrollment_is_transferee,
            t1.registrar_confirmation_date,

            t2.firstname,t2.username, t2.student_statusv2,t2.student_unique_id,
            t2.lastname,t2.course_level,
            t2.course_id, t2.student_id as t2_student_id,
            t2.course_id, t2.course_level,t2.student_status, t2.admission_status,
            t2.is_tertiary, t2.new_enrollee,
            
            t3.program_section

            FROM enrollment as t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id
            LEFT JOIN course as t3 ON t2.course_id = t3.course_id

            WHERE (t1.is_new_enrollee=:is_new_enrollee
                OR 
                t1.is_new_enrollee=:is_new_enrollee2)
            -- AND t1.is_transferee=:is_transferee

            AND (t1.is_transferee = :is_transferee 
                OR 
                t1.is_transferee = :is_transferee2)
            
            AND t1.enrollment_status=:enrollment_status
            AND t1.school_year_id=:school_year_id
            AND t1.registrar_evaluated=:registrar_evaluated
            AND t1.cashier_evaluated=:cashier_evaluated
            ");

        $registrar_side->bindValue(":is_new_enrollee", $is_new_enrollee);
        $registrar_side->bindValue(":is_new_enrollee2", 0);
        $registrar_side->bindValue(":is_transferee", 1);
        $registrar_side->bindValue(":is_transferee2", "0");
        $registrar_side->bindValue(":enrollment_status", $enrollment_status);
        $registrar_side->bindValue(":school_year_id", $current_school_year_id);
        $registrar_side->bindValue(":registrar_evaluated", $registrar_evaluated);
        $registrar_side->bindValue(":cashier_evaluated", "no");
        $registrar_side->execute();

        if($registrar_side->rowCount() > 0){

            return $registrar_side->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

   
    public function WaitingApprovalEnrollment($current_school_year_id){

        $default_shs_course_level = 11;
        $is_new_enrollee = 1;
        $is_transferee = 1;
        $regular_Status = "Regular";
        $enrollment_status = "tentative";
        $registrar_evaluated = "yes";

        $registrar_side = $this->con->prepare("SELECT 

            t1.student_id, 
            t1.cashier_evaluated,
            t1.registrar_evaluated,
            t1.is_transferee,
            t1.enrollment_approve,
            t1.course_id AS enrollment_course_id,
            t1.enrollment_date,
            t1.registrar_confirmation_date,
            t1.is_new_enrollee AS enrollment_is_new_enrollee,
            t1.is_transferee AS enrollment_is_transferee,
            t1.student_status AS enrollment_student_status,



            t2.firstname,t2.username, t2.student_unique_id,
            t2.lastname,t2.course_level,
            t2.admission_status,t2.student_statusv2,
            t2.course_id, t2.student_id AS t2_student_id,
            t2.course_id, t2.course_level,t2.student_status,
            t2.is_tertiary, t2.new_enrollee, 

           
            
            t3.program_section

            FROM enrollment as t1
            INNER JOIN student as t2 ON t2.student_id = t1.student_id

            LEFT JOIN course as t3 ON t2.course_id = t3.course_id

            WHERE (t1.is_new_enrollee=:is_new_enrollee
            OR t1.is_new_enrollee=:is_new_enrollee2)

            AND (t1.is_transferee = :is_transferee 
                OR t1.is_transferee = :is_transferee2)
                
            AND t1.enrollment_status=:enrollment_status
            AND t1.school_year_id=:school_year_id
            AND t1.registrar_evaluated=:registrar_evaluated
            AND t1.cashier_evaluated=:cashier_evaluated
            ");

        $registrar_side->bindValue(":is_new_enrollee", 1);
        $registrar_side->bindValue(":is_new_enrollee2", 0);
        
        $registrar_side->bindValue(":is_transferee", $is_transferee);
        $registrar_side->bindValue(":is_transferee2", "0");

        $registrar_side->bindValue(":enrollment_status", $enrollment_status);
        $registrar_side->bindValue(":school_year_id", $current_school_year_id);
        $registrar_side->bindValue(":registrar_evaluated", "yes");
        $registrar_side->bindValue(":cashier_evaluated", "yes");
        $registrar_side->execute();
    
        if($registrar_side->rowCount() > 0){

            return $registrar_side->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }


    public function EnrolledStudentsWithinSYSemester($current_school_year_id){

        $enrollment_status = "enrolled";
        $registrar_evaluated = "yes";
        $cashier_evaluated = "yes";

        $sql = $this->con->prepare("SELECT 

            t1.student_id, 
            t1.cashier_evaluated,
            t1.registrar_evaluated,
            t1.is_transferee,
            t1.enrollment_approve,
            t1.course_id AS enrollment_course_id,
            t1.enrollment_date,
            t1.registrar_confirmation_date,
            t1.is_new_enrollee AS enrollment_is_new_enrollee,
            t1.is_transferee AS enrollment_is_transferee,
            t1.student_status AS enrollment_student_status,
        
            t2.firstname,t2.username, t2.student_unique_id,
            t2.lastname,
            t2.course_level,
            t2.student_unique_id,
            t2.admission_status,t2.student_statusv2,
            t2.course_id, t2.student_id AS t2_student_id,
            t2.course_id, t2.course_level,t2.student_status,
            t2.is_tertiary, t2.new_enrollee,  
            
            
            t3.program_section 
        
            FROM enrollment as t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id

            LEFT JOIN course as t3 ON t3.course_id = t2.course_id

            WHERE school_year_id=:school_year_id
            AND enrollment_status=:enrollment_status
            AND registrar_evaluated=:registrar_evaluated
            AND cashier_evaluated=:cashier_evaluated
            ");

        $sql->bindValue(":school_year_id", $current_school_year_id);
        $sql->bindValue(":enrollment_status", $enrollment_status);
        $sql->bindValue(":registrar_evaluated", $registrar_evaluated);
        $sql->bindValue(":cashier_evaluated", $cashier_evaluated);
        $sql->execute();
        if($sql->rowCount() > 0){


            $queries = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $queries;
        }
        return [];
    }

    function CheckEnrollmentCashierApproved($enrollment_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND enrollment_id = :enrollment_id
            AND school_year_id = :school_year_id
            AND cashier_evaluated = :cashier_evaluated
            AND enrollment_status != 'withdraw'
            ORDER BY enrollment_id DESC
            LIMIT 1
            
            ");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":cashier_evaluated", "yes");
        // $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function CheckEnrollmentRegistrarApproved($enrollment_id, $course_id,
        $school_year_id) {

        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            -- WHERE course_id = :course_id
            WHERE enrollment_id = :enrollment_id
            AND school_year_id = :school_year_id
            AND registrar_evaluated = :registrar_evaluated
            ORDER BY enrollment_id DESC
            LIMIT 1
            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":registrar_evaluated", "yes");

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function EnrolledStudentInTheEnrollment($current_school_year_id,
        $student_id, $enrollment_form_id){

        $enrollment_status = "enrolled";
        $enrollment_approve = new DateTime();
        $enrollment_approve = $enrollment_approve->format('Y-m-d H:i:s');

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET enrollment_status=:enrollment_status,
                -- enrollment_form_id=:enrollment_form_id,
                enrollment_approve=:enrollment_approve

            
            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            
            ");

        $update_tentative->bindValue(":enrollment_status", $enrollment_status);
        $update_tentative->bindValue(":enrollment_approve", $enrollment_approve);
        $update_tentative->bindValue(":enrollment_form_id", $enrollment_form_id);
        $update_tentative->bindValue(":student_id", $student_id);
        $update_tentative->bindValue(":school_year_id", $current_school_year_id);
        return $update_tentative->execute(); 
      
    }

    public function ChangeEnrollmentProgramCourseId($current_school_year_id,
        $student_id, $enrollment_id, $chosen_course_id, $course_department_type){

        # Automatic IRregular.
        $student_status = "Irregular";

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET course_id=:course_id,
                student_status=:student_status,
                is_tertiary=:is_tertiary

            WHERE enrollment_id=:enrollment_id
            ANd student_id=:student_id
            AND school_year_id=:school_year_id
            
            ");

        $update_tentative->bindValue(":course_id", $chosen_course_id);
        $update_tentative->bindValue(":student_status", $student_status);
        $update_tentative->bindValue(":enrollment_id", $enrollment_id);
        $update_tentative->bindValue(":student_id", $student_id);
        $update_tentative->bindValue(":school_year_id", $current_school_year_id);
        $update_tentative->bindValue(":is_tertiary", $course_department_type);
        $update_tentative->execute();

        if($update_tentative->rowCount() > 0){
            return true;
        }


        
        return false;
    }

    public function MarkAsRegistrarEvaluated($current_school_year_id,
        $student_course_id,
        $student_id, $enrollment_form_id, $enrollment_payment = null){

        $registrar_evaluated = "yes";
        $now = date("Y-m-d H:i:s");


        // echo $enrollment_payment;
        // return;
     
        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET registrar_evaluated=:registrar_evaluated,
                registrar_confirmation_date=:registrar_confirmation_date,
                enrollment_payment=:enrollment_payment
            
            -- WHERE student_id=:student_id
            WHERE school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            AND enrollment_status=:enrollment_status
            -- AND course_id=:course_id
            ");

        $update_tentative->bindParam(":registrar_evaluated", $registrar_evaluated);
        $update_tentative->bindParam(":registrar_confirmation_date", $now);
        $update_tentative->bindParam(":enrollment_payment", $enrollment_payment);
        // $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_form_id", $enrollment_form_id);
        $update_tentative->bindValue(":enrollment_status", "tentative");
        // $update_tentative->bindParam(":course_id", $student_course_id);

        $update_tentative->execute();

        if($update_tentative->rowCount() > 0){
            return true;
        }
      
        return false;

    }

    public function FormUpdateStudentStatus($current_school_year_id,
        $student_id, $enrollment_id, $type){

        $resetRetake = 0;

        $updateSuccess = false;
        if($type == "Regular"){
            $update_tentative = $this->con->prepare("UPDATE enrollment
                SET student_status=:student_status,
                    retake=:retake

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                AND enrollment_id=:enrollment_id
                ");

            $update_tentative->bindParam(":student_status", $type);
            $update_tentative->bindParam(":retake", $resetRetake);
            $update_tentative->bindParam(":student_id", $student_id);
            $update_tentative->bindParam(":school_year_id", $current_school_year_id);
            $update_tentative->bindParam(":enrollment_id", $enrollment_id);

            if($update_tentative->execute() && $update_tentative->rowCount() > 0){
                $updateSuccess = true;
            }   
        }
        if($type == "Irregular"){

            $updateToIrreg = $this->con->prepare("UPDATE enrollment
                SET student_status=:student_status

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                AND enrollment_id=:enrollment_id
                ");

            $updateToIrreg->bindParam(":student_status", $type);
            $updateToIrreg->bindParam(":student_id", $student_id);
            $updateToIrreg->bindParam(":school_year_id", $current_school_year_id);
            $updateToIrreg->bindParam(":enrollment_id", $enrollment_id);
            if($updateToIrreg->execute() && $updateToIrreg->rowCount() > 0){
                $updateSuccess = true;
            } 
        }

        return $updateSuccess;
    }

    public function FormUpdateAsRetake($current_school_year_id,
        $student_id, $enrollment_id, $type){

        $exec = false;
        if($type == "Retake"){
            $update_tentative = $this->con->prepare("UPDATE enrollment
                SET retake=:retake

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                AND enrollment_id=:enrollment_id
                ");

            $update_tentative->bindValue(":retake", 1);
            $update_tentative->bindParam(":student_id", $student_id);
            $update_tentative->bindParam(":school_year_id", $current_school_year_id);
            $update_tentative->bindParam(":enrollment_id", $enrollment_id);

            if($update_tentative->execute()){
                $exec = true;
            }
        }
        elseif($type == "Unretake"){
            $update_tentative = $this->con->prepare("UPDATE enrollment
                SET retake=:retake

                
                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                AND enrollment_id=:enrollment_id
                ");

            $update_tentative->bindValue(":retake", 0);
            $update_tentative->bindParam(":student_id", $student_id);
            $update_tentative->bindParam(":school_year_id", $current_school_year_id);
            $update_tentative->bindParam(":enrollment_id", $enrollment_id);

            if($update_tentative->execute()){
                $exec = true;
            }
        }

        return $exec;
    }


    // Was used by enrolled status (Enrolled Subjects)
    // and tentative status (Approval SubjectInsertion).
    public function FormUpdateCourseId($current_school_year_id,
        $student_id, $enrollment_id,
        $selected_course_id, $student_enrollment_course_id,
        $fromWaitingList = false){

        # student_enrollment_course_id -> STEM11-B 3/3.
        # selected_course_id -> STEM11-A 2/3.

        # Registrar wanted to place STEM11-B = 2/3 and STEM11-A = 3/3

        $exec = false;
        // $enrollment_status = "enrolled";

        $section = new Section($this->con, $selected_course_id);

        $section_current = new Section($this->con, $student_enrollment_course_id);

        $doesSelectedIsFull = false;
        $doesCurrentIsFull = false;

        $doesSelectedSectionFull = $section_current->GetSectionIsFull($selected_course_id);

        $selected_section_capacity = $section_current->GetSectionCapacity($selected_course_id);
        $selected_students_enrolled = $this->GetStudentEnrolled($selected_course_id);

        $current_section_capacity = $section_current->GetSectionCapacity($student_enrollment_course_id);
        $students_enrolled = $this->GetStudentEnrolled($student_enrollment_course_id);
            

        if($students_enrolled >= $current_section_capacity){
            $doesCurrentIsFull = true;
        }

        # At first, it should be false. 2/3
        if($selected_students_enrolled >= $selected_section_capacity){
            $doesSelectedIsFull = true;
        }

        // if($current_section_capacity >= $students_enrolled){
        //     $doesCurrentIsFull = true;
        // }

        $sectionLevel = $section->GetSectionGradeLevel();

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET course_id=:course_id
                -- waiting_list=:waiting_list

            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_id=:enrollment_id
            -- AND enrollment_status=:enrollment_status
            ");

        $update_tentative->bindValue(":course_id", $selected_course_id);
        $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_id", $enrollment_id);
        // $update_tentative->bindValue(":waiting_list", $fromWaitingList == true ? "yes" : "no");

        // $update_tentative->bindParam(":enrollment_status", $enrollment_status);
        $update_tentative->execute();

        if($update_tentative->rowCount() > 0){

            $section_exec = new Section($this->con, $selected_course_id);

            $exec_section_capacity = $section_exec->GetSectionCapacity();
            $exec_students_enrolled = $this->GetStudentEnrolled($selected_course_id);

            # If selected course becomes full.
            if($exec_students_enrolled >= $exec_section_capacity){
                // $doesSelectedIsFull = true;
                # and previous is not full.
                if($doesSelectedIsFull == false){
                    # Update to true = FULL

                    $sectionUpdatedToFull = $section_exec->SetSectionIsFull($selected_course_id);
                    
                    if($sectionUpdatedToFull){
                        // echo "selected course id: $selected_course_id is now full.";
                    }
                };
            }

            # Previous section capacity status (without updating)
            # is full.
            if($doesCurrentIsFull == true){

                $exec_current_section_capacity = $section_exec->GetSectionCapacity();
                $exec_current_students_enrolled = $this->GetStudentEnrolled($student_enrollment_course_id);

                # And now eventually unfull.
                if($exec_current_students_enrolled < $exec_current_section_capacity){
                    # Update to Non Full

                    $sectionUpdatedToUnFull = $section_exec->SetSectionToNonFull($student_enrollment_course_id);
                    
                    if($sectionUpdatedToUnFull){
                        // echo "student_enrollment_course_id: $student_enrollment_course_id is now un-full.";
                    }
                }
            }

            # As changing section, Pay attention if the section is 

            # 1. Currently not full, eventually becomes 
            #    full if registrar placed to other section

            # 2. Becomes un-full, registrar transfer student to other section level.

            // Update student
            $update_student_course = $this->con->prepare("UPDATE student 

                SET course_id=:change_course_id,
                    course_level=:change_course_level

                WHERE student_id=:student_id
                AND active = 1
                ");

            $update_student_course->bindParam(":change_course_id", $selected_course_id);
            $update_student_course->bindParam(":change_course_level", $sectionLevel);
            $update_student_course->bindParam(":student_id", $student_id);
            $update_student_course->execute();

            if($update_student_course->rowCount() > 0){
                $exec = true;
            }
        }
        return $exec;
    }


    public function WaitingListFormUpdateCourseId($current_school_year_id,
        $student_id, $enrollment_id,
        $selected_course_id, $student_enrollment_course_id,
        $fromWaitingList = false){
        
        $student_subject = new StudentSubject($this->con);
        


        # student_enrollment_course_id -> STEM11-B 3/3.
        # selected_course_id -> STEM11-A 2/3.
        # Registrar desired to place STEM11-B = 2/3 and STEM11-A = 3/3

        $exec = false;
        // $enrollment_status = "enrolled";

        $section = new Section($this->con, $selected_course_id);

        $section_current = new Section($this->con, $student_enrollment_course_id);

        $doesSelectedIsFull = false;
        $doesCurrentIsFull = false;

        $doesSelectedSectionFull = $section_current->GetSectionIsFull($selected_course_id);

        $selected_section_capacity = $section_current->GetSectionCapacity($selected_course_id);
        $selected_students_enrolled = $this->GetStudentEnrolled($selected_course_id);

        $current_section_capacity = $section_current->GetSectionCapacity($student_enrollment_course_id);
        $students_enrolled = $this->GetStudentEnrolled($student_enrollment_course_id);
            

        if($students_enrolled >= $current_section_capacity){
            $doesCurrentIsFull = true;
        }

        # At first, it should be false. 2/3
        if($selected_students_enrolled >= $selected_section_capacity){
            $doesSelectedIsFull = true;
        }

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET course_id=:course_id
                -- waiting_list=:waiting_list

            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_id=:enrollment_id
            -- AND enrollment_status=:enrollment_status
            ");

        $update_tentative->bindValue(":course_id", $selected_course_id);
        $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_id", $enrollment_id);
        // $update_tentative->bindValue(":waiting_list", $fromWaitingList == true ? "yes" : "no");

        $update_tentative->execute();

        if($update_tentative->rowCount() > 0){

            # Update section subject code that
            # equivalent to its selected course id
            # Ex. Selected Course subject -> STEM11-A-PE101
            #                             -> STEM11-A-PE102

            # Results: My transform Subject -> STEM11-B-PE101
            # Results: My transform Subject -> STEM11-B-PE102

            $student_subject->UpdateCurrentCodeToSelectedSectionCode(
                $enrollment_id, $student_enrollment_course_id, 0,
                $selected_course_id
            );


            $section_exec = new Section($this->con, $selected_course_id);

            $exec_section_capacity = $section_exec->GetSectionCapacity();
            $exec_students_enrolled = $this->GetStudentEnrolled($selected_course_id);

            # If selected course becomes full.
            if($exec_students_enrolled >= $exec_section_capacity){
                if($doesSelectedIsFull == false){

                    $sectionUpdatedToFull = $section_exec->SetSectionIsFull($selected_course_id);
                    if($sectionUpdatedToFull){
                    }
                };
            }

            # Previous section capacity status (without updating)
            # is full.
            if($doesCurrentIsFull == true){

                $exec_current_section_capacity = $section_exec->GetSectionCapacity();
                $exec_current_students_enrolled = $this->GetStudentEnrolled($student_enrollment_course_id);

                # And now eventually unfull.
                if($exec_current_students_enrolled < $exec_current_section_capacity){
                    # Update to Non Full

                    $sectionUpdatedToUnFull = $section_exec->SetSectionToNonFull($student_enrollment_course_id);
                    
                    if($sectionUpdatedToUnFull){
                        // echo "student_enrollment_course_id: $student_enrollment_course_id is now un-full.";
                    }
                }
            }

            $exec = true;
        }
        return $exec;
    }



    public function EnrollmentFormMarkAsEnrolled($current_school_year_id,
        $student_course_id, $student_id, $enrollment_form_id,
        $student_enrollment_student_status){

        $enrollment_status = "enrolled";
        $now = date("Y-m-d H:i:s");


        if($this->SecureNoPreviousEnrollmentEnrolled(
            $current_school_year_id, $student_id) == true){

            $update_tentative = $this->con->prepare("UPDATE enrollment
                SET enrollment_status=:enrollment_status,
                    enrollment_approve=:enrollment_approve

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                AND enrollment_form_id=:enrollment_form_id
                ");

            $update_tentative->bindValue(":enrollment_status", $enrollment_status);
            $update_tentative->bindValue(":enrollment_approve", $now);
            $update_tentative->bindValue(":student_id", $student_id);
            $update_tentative->bindValue(":school_year_id", $current_school_year_id);
            $update_tentative->bindValue(":enrollment_form_id", $enrollment_form_id);

            if($update_tentative->execute()){
                return true;
            } 
        }else{
            Alert::error("Student has already Enrollment enrolled status.", "");
            return false;
        }

        return false;
    }


    public function EnrollmentFormMarkAsPaid($current_school_year_id,
        $student_id, $enrollment_form_id,
        $enrollment_payment,
        $payment_status,
        $payment_method){

        $cashier_evaluated = "yes";
        $now = date("Y-m-d H:i:s");

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET cashier_evaluated=:cashier_evaluated,
                cashier_confirmation_date=:cashier_confirmation_date,
                enrollment_payment=:enrollment_payment,
                payment_status=:payment_status,
                payment_method=:payment_method
            
            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            ");

        $update_tentative->bindParam(":cashier_evaluated", $cashier_evaluated);
        $update_tentative->bindParam(":cashier_confirmation_date", $now);
        $update_tentative->bindParam(":enrollment_payment", $enrollment_payment);
        $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_form_id", $enrollment_form_id);
        $update_tentative->bindParam(":payment_status", $payment_status);
        $update_tentative->bindParam(":payment_method", $payment_method);
        $update_tentative->execute();

        if($update_tentative->rowCount() > 0){
            return true;
        } 
        return false;
    }

    public function ChangeEnrollmentCourseId($current_school_year_id,
        $student_id, $enrollment_form_id, $student_course_id,$chosen_course_id){


        $change_course_id = $this->con->prepare("UPDATE enrollment
            SET course_id=:chosen_course_id
            
            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            AND course_id=:course_id
            
            ");

        $change_course_id->bindParam(":chosen_course_id", $chosen_course_id);
        $change_course_id->bindParam(":student_id", $student_id);
        $change_course_id->bindParam(":school_year_id", $current_school_year_id);
        $change_course_id->bindParam(":enrollment_form_id", $enrollment_form_id);
        $change_course_id->bindParam(":course_id", $student_course_id);
        return $change_course_id->execute(); 
      
    }

    public function CheckEnrollmentEnrolled($student_id, $course_id,
        $school_year_id, $student_enrollment_id) {

        // Check if the enrollment form ID already exists in the database

        // echo $student_enrollment_id;
        $sql = $this->con->prepare("SELECT * FROM enrollment

            -- WHERE course_id = :course_id
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_id = :enrollment_id
            AND enrollment_status = :enrollment_status
            AND enrollment_approve IS NOT NULL
            
            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_id", $student_enrollment_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function CheckEnrollmentEnrolledStatus($student_id,
        $school_year_id, $student_enrollment_id) {

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT enrollment_status FROM enrollment

            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_id = :enrollment_id
            ");
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_id", $student_enrollment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return "";
    }

    public function GetCourseIdByEnrollmentForm($student_id,
        $enrollment_form_id, $school_year_id) {

        $sql = $this->con->prepare("SELECT course_id FROM enrollment 
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_form_id = :enrollment_form_id
            ");
        
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
            $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchColumn();
        }
        return 0;
    }

    public function GetAllEnrolledEnrollmentCourseIDWithinSemester(
        $school_year_id) {

        $sql = $this->con->prepare("SELECT course_id FROM enrollment AS t1


            
            WHERE t1.school_year_id = :school_year_id
            AND t1.enrollment_status =:enrollment_status
            AND t1.enrollment_approve IS NOT NULL
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
        }
        return [];
    }

    public function GetAllCourseIDsWithRoomInSemester(
        $school_year_id, $semester) {

        if($semester == "First"){
            $sql = $this->con->prepare("SELECT t1.course_id FROM enrollment AS t1

                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                AND t2.first_period_room_id IS NOT NULL

                WHERE t1.school_year_id = :school_year_id
                AND t1.enrollment_status =:enrollment_status
                AND t1.enrollment_approve IS NOT NULL

                GROUP BY t1.course_id
                ");
            
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindValue(":enrollment_status", "enrolled");
            $sql->execute();

            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_COLUMN);
            }
        }

        
        if($semester == "Second"){
            $sql = $this->con->prepare("SELECT t1.course_id FROM enrollment AS t1

                INNER JOIN course as t2 ON t2.course_id = t1.course_id
                -- AND t2.second_period_room_id IS NULL
                -- AND t2.first_period_room_id IS NULL
                -- AND 
                --     (t2.first_period_room_id IS NOT NULL
                --         OR t2.second_period_room_id IS NULL
                --     )

                WHERE t1.school_year_id = :school_year_id
                AND t1.enrollment_status =:enrollment_status
                AND t1.enrollment_approve IS NOT NULL

                GROUP BY t1.course_id
                ");
            
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindValue(":enrollment_status", "enrolled");
            $sql->execute();

            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_COLUMN);
            }

        }


        return [];
    }
    
    public function GetAllSectionToExclude(
        $school_year_term, $semester) {


        if($semester == "Second"){
            $sql = $this->con->prepare("SELECT t1.course_id 
            
                FROM course AS t1

                WHERE school_year_term=:school_year_term
                AND first_period_room_id IS NULL
                AND second_period_room_id IS NULL
                GROUP BY t1.course_id
                ");
            
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_COLUMN);
            }

        }
        return [];

    }

    public function GetAllSectionsFromFirstToSecondWithRoom(
        $school_year_term, $semester) {

        if($semester == "Second"){
            $sql = $this->con->prepare("SELECT t1.course_id FROM course AS t1

                WHERE school_year_term=:school_year_term
                AND (first_period_room_id IS NOT NULL
                    OR second_period_room_id IS NOT NULL)
                GROUP BY t1.course_id
                ");
            
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->execute();

            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_COLUMN);
            }

        }
        return [];
    }

    public function GetSectionsWithWithdraw(
        $school_year_id) {

        $sql = $this->con->prepare("SELECT t1.course_id FROM enrollment AS t1

                WHERE t1.school_year_id = :school_year_id
                AND t1.enrollment_status = :enrollment_status
                GROUP BY t1.course_id
            ");
            
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindValue(":enrollment_status", "withdraw");
            $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
        }

        return [];
    }

    public function UpdateEnrollmentCourseId($student_id, $enrollment_form_id,
        $school_year_id, $course_id) {

        $sql = $this->con->prepare("UPDATE enrollment 
            SET course_id = :course_id
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_form_id = :enrollment_form_id");

        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);

        return $sql->execute();
    }

    public function GetEnrolledNewStudentWithinSemester($school_year_id) {

        $enrollment_status = "enrolled";

        $sql = $this->con->prepare("SELECT student_id FROM enrollment 
            WHERE school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            AND enrollment_approve IS NOT NULL
            AND is_new_enrollee = 1
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_status", $enrollment_status);

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
        }
        return [];
    }

    public function GetNewEnrollmentTentativeIDs($school_year_id) {
        
        $sql = $this->con->prepare("SELECT t1.enrollment_id, t1.student_id 
        
            FROM enrollment as t1

            INNER JOIN student as t2 on t2.student_id = t1.student_id
            AND t2.new_enrollee = 1

            WHERE t1.cashier_evaluated = :cashier_evaluated
            AND t1.enrollment_status = :enrollment_status
            AND t1.is_new_enrollee = :is_new_enrollee
            AND t1.school_year_id = :school_year_id");
        
        
        $sql->bindValue(":cashier_evaluated", "no");
        $sql->bindValue(":enrollment_status", "tentative");
        $sql->bindValue(":is_new_enrollee", 1);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function GetNotNewEnrollmentID($school_year_id) {
        
        $sql = $this->con->prepare("SELECT 
        
            enrollment_id, student_id 
        
            FROM enrollment 
            WHERE cashier_evaluated = :cashier_evaluated
            AND enrollment_status = :enrollment_status
            AND is_new_enrollee = :is_new_enrollee
            AND school_year_id = :school_year_id");
        
        
        $sql->bindValue(":cashier_evaluated", "no");
        $sql->bindValue(":enrollment_status", "tentative");
        $sql->bindValue(":is_new_enrollee", 0);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }
    
    public function GetEnrolledRegularStudentWithinSemester($school_year_id) {

        $sql = $this->con->prepare("SELECT 
        
            t2.student_id 
            
            FROM enrollment AS t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id
            AND t2.student_statusv2 = 'Regular'

            WHERE t1.school_year_id = :school_year_id
            AND t1.enrollment_status = 'enrolled'
            AND t1.enrollment_approve IS NOT NULL
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
        }
        return [];
    }

    public function ApplyEnrollmentOS($student_id, $course_id, $school_year_id,
        $enrollment_form_id, $isRegular, $type){

        $now = date("Y-m-d H:i:s");

        // $registrar_evaluated = $isRegular == "Regular" ? "yes" : "no";
        $registrar_evaluated = "no";
        // $student_status = $isRegular == "Regular" ? "Regular" : "Irregular";
        $student_status = $isRegular == "Regular" ? "Regular" : "";
        $is_tertiary = $type == "Tertiary" ? 1 : 0;

        $sql = $this->con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_form_id, enrollment_approve, enrollment_date,
                is_transferee, registrar_evaluated, student_status, is_tertiary)
            VALUES(:student_id, :course_id, :school_year_id, :enrollment_form_id, :enrollment_approve, :enrollment_date,
                :is_transferee, :registrar_evaluated, :student_status, :is_tertiary)");

        $sql->bindParam(":student_id", $student_id);
        // Registrar would select the course
        $sql->bindValue(":course_id", 0);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
        $sql->bindParam(":enrollment_date", $now);
        $sql->bindValue(":enrollment_approve", NULL, PDO::PARAM_NULL);
        $sql->bindValue(":is_tertiary", $is_tertiary);
        $sql->bindValue(":is_transferee", 0);
        $sql->bindParam(":registrar_evaluated", $registrar_evaluated);
        $sql->bindParam(":student_status", $student_status);

        return $sql->execute();
    }

    public function InsertEnrollmentManualNewStudent($student_id, $course_id, $school_year_id,
        $enrollment_form_id, $student_status, $is_tertiary, $is_transferee, $is_new_enrollee){

        $now = date("Y-m-d H:i:s");

        $registrar_evaluated = "no";

        $returnBool = false;
        
        # Check if student has already have enrollment form within semester
        $checkStudentAlreadyHasForm = $this->CheckStudentEnrollmentFormExists($school_year_id,
            $student_id);

        // if($checkStudentAlreadyHasForm == true){
        //     $url = "../admission/process_enrollment.php?find_section=show&st_id=$student_id&c_id=$course_id";

        //     Alert::error("Student ID: $student_id has enrollment form already.",
        //         $url);
        //     exit();
        //     return false;
        // }

        // Check if Enrollment Form Id is Unique
        // If not generate another one.
        $enrollment_form_id = $this->CheckEnrollmentFormIdExists($enrollment_form_id);

        $sql = $this->con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_form_id, enrollment_approve, enrollment_date,
                is_transferee, is_new_enrollee, registrar_evaluated, student_status, is_tertiary)
            VALUES(:student_id, :course_id, :school_year_id, :enrollment_form_id, :enrollment_approve, :enrollment_date,
                :is_transferee, :is_new_enrollee, :registrar_evaluated, :student_status, :is_tertiary)");

        $sql->bindParam(":student_id", $student_id);
        // Registrar would select the course
        $sql->bindValue(":course_id", $course_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
        $sql->bindParam(":enrollment_date", $now);
        $sql->bindValue(":enrollment_approve", NULL, PDO::PARAM_NULL);
        $sql->bindValue(":is_tertiary", $is_tertiary);
        $sql->bindValue(":is_transferee", $is_transferee);
        $sql->bindValue(":is_new_enrollee", $is_new_enrollee);
        $sql->bindParam(":registrar_evaluated", $registrar_evaluated);
        $sql->bindParam(":student_status", $student_status);

        $sql->execute();
        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }


    public function ChangeFormInsertEnrollment($student_id, $course_id, $school_year_id,
        $enrollment_form_id, $student_status, $is_tertiary, $is_transferee, $is_new_enrollee){

        $now = date("Y-m-d H:i:s");

        $registrar_evaluated = "no";

        // If not generate another one.
        $enrollment_form_id = $this->CheckEnrollmentFormIdExists($enrollment_form_id);

        $sql = $this->con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_form_id, enrollment_approve, enrollment_date,
                is_transferee, is_new_enrollee, registrar_evaluated, student_status, is_tertiary)
            VALUES(:student_id, :course_id, :school_year_id, :enrollment_form_id, :enrollment_approve, :enrollment_date,
                :is_transferee, :is_new_enrollee, :registrar_evaluated, :student_status, :is_tertiary)");

        $sql->bindParam(":student_id", $student_id);
        // Registrar would select the course
        $sql->bindValue(":course_id", $course_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_form_id", $enrollment_form_id);
        $sql->bindParam(":enrollment_date", $now);
        $sql->bindValue(":enrollment_approve", NULL, PDO::PARAM_NULL);
        $sql->bindValue(":is_tertiary", $is_tertiary);
        $sql->bindValue(":is_transferee", $is_transferee);
        $sql->bindValue(":is_new_enrollee", $is_new_enrollee);
        $sql->bindParam(":registrar_evaluated", $registrar_evaluated);
        $sql->bindParam(":student_status", $student_status);

        $sql->execute();
        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }


    public function InsertPendingRequestToEnrollment($generated_student_id,
        $selected_course_id_value, $now, $current_school_year_id, $admission_status, 
        $enrollment_form_id, $type){

        $insert_enrollment = $this->con->prepare("INSERT INTO enrollment
            (student_id, course_id, school_year_id, enrollment_status, is_new_enrollee,
                registrar_evaluated, is_transferee, enrollment_form_id,
                is_tertiary, enrollment_date, student_status)
            VALUES (:student_id, :course_id, :school_year_id, :enrollment_status,
                :is_new_enrollee, :registrar_evaluated, :is_transferee, :enrollment_form_id, :is_tertiary,
                :enrollment_date, :student_status)");

        $insert_enrollment->bindValue(':student_id', $generated_student_id);
        $insert_enrollment->bindValue(':course_id', $selected_course_id_value);
        $insert_enrollment->bindValue(':enrollment_date', $now);
        $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
        $insert_enrollment->bindValue(':enrollment_status', "tentative");
        $insert_enrollment->bindValue(':is_new_enrollee', 1);

        # Modified
        $insert_enrollment->bindValue(':registrar_evaluated', "no");
        $insert_enrollment->bindValue(':is_transferee', $admission_status == "Transferee" ? 1 : 0);
        $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
        $insert_enrollment->bindValue(':is_tertiary', $type == "Tertiary" ? 1 : 0);
        // New Student From Online
        $insert_enrollment->bindValue(':student_status', $admission_status == "Transferee" ? "Irregular" : "Regular");

        return $insert_enrollment->execute();
    }
    
    public function RemovingEnrollmentFormCashierNotEvaluated(
        $enrollment_id, $student_id,
        $school_year_id){

        $cashier_evaluated = "no";

        # Check if enrollment form is not evaluated by Cashier.

        // New Enrollee -> Enrolled -> Removed

        // - Enrollment Form ( New )
        // - Student Table.
        // - Pending Reject
        // - Enrolled Subject, Grade Remove

        $delete = $this->con->prepare("DELETE FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND cashier_evaluated = :cashier_evaluated
            ");

        $delete->bindParam(":enrollment_id", $enrollment_id);
        $delete->bindParam(":student_id", $student_id);
        $delete->bindParam(":school_year_id", $school_year_id);
        $delete->bindParam(":cashier_evaluated", $cashier_evaluated);
        $delete->execute();

        if($delete->rowCount() > 0){
            return true;
        }
        
        return false;
    }

    public function RemovingEnrollmentFormCashierEvaluated(
        $enrollment_id, $student_id,
        $school_year_id){

        $doesCurrentSectionIsFull = false;

        $student_enrollment_course_id = $this->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $school_year_id);

        $section_current = new Section($this->con, $student_enrollment_course_id);
        
        $section_current_capacity_status = $section_current->GetSectionIsFull();
        $current_section_capacity = $section_current->GetSectionCapacity();
        $students_enrolled = $this->GetStudentEnrolled($student_enrollment_course_id);
            
        if($students_enrolled >= $current_section_capacity){
            $doesCurrentSectionIsFull = true;
        }

        $cashier_evaluated = "yes";

        $delete = $this->con->prepare("DELETE FROM enrollment 
            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND cashier_evaluated = :cashier_evaluated
            AND enrollment_status = :enrollment_status
            ");

        $delete->bindParam(":enrollment_id", $enrollment_id);
        $delete->bindParam(":student_id", $student_id);
        $delete->bindParam(":school_year_id", $school_year_id);
        $delete->bindParam(":cashier_evaluated", $cashier_evaluated);
        $delete->bindValue(":enrollment_status", "enrolled");
        $delete->execute();

        if($delete->rowCount() > 0){

            $section_exec = new Section($this->con, $student_enrollment_course_id);

            if($doesCurrentSectionIsFull == true 
                && $section_current_capacity_status == true){

                $sectionUpdatedToUnFull = $section_exec->SetSectionToNonFull($student_enrollment_course_id);
                
                if($sectionUpdatedToUnFull){
                    // echo "student_enrollment_course_id: $student_enrollment_course_id is now un-full.";
                }
            }
            return true;
        }
        
        return false;
    }

    public function RemovingTentativeNewEnrollmentForm(
        $current_school_year_id){
        
        $newEnrollmentList = $this->GetNewEnrollmentTentativeIDs(
            $current_school_year_id);

        $pending = new Pending($this->con);

        $hasDone = false;

        foreach ($newEnrollmentList as $key => $value) {
            # code...
            $student_id = $value['student_id'];
            $enrollment_id = $value['enrollment_id'];

            $student = new Student($this->con, $student_id);

            $student_email = strtolower($student->GetEmail());
            $student_firstname = strtolower($student->GetFirstName());
            $student_lastname = strtolower($student->GetLastName());

            // echo $enrollment_id;

            // $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
            //     $student_email, $student_firstname, $student_lastname);

            // echo $get_student_new_pending_id;
            // echo "<br>";

            $new_enrollment_remove_success = $this->RemovingEnrollmentFormCashierNotEvaluated
                ($enrollment_id,
                $student_id, $current_school_year_id);

            // if($s == false){
            if($new_enrollment_remove_success){

                # Remove Student Table.
                $removeNewStudentSuccess = $student->RemovingNewStudentFromEnrollmentForm(
                    $student_id);
                    
                // if(false){
                # Remove Parent Table.
                // $parent = new PendingParent($this->con);
                // $parentRemoved = $parent->RemovingParentOfNewStudent($student_id);

                # Check if Student has Pending Table. By EMAIL, firstname, lastname
                
                // $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
                //     $student_email, $student_firstname, $student_lastname);
                    
                // if($get_student_new_pending_id !== NULL){
                //     # Pending Mark as REJECTED.
                //     // $successRejected = $pending->MarkAsRejected($get_student_new_pending_id);
                //     $successRemoval = $pending->RemoveNewEnrollee($get_student_new_pending_id);
                // }

                $hasDone = true;
            }
        }
        
        return $hasDone;
    }

    public function RemovingTentativeNotNewEnrollmentForm(
        $current_school_year_id){
        
        $oldEnrollmentList = $this->GetNotNewEnrollmentID(
            $current_school_year_id);

        // print_r($oldEnrollmentList);

        $hasDone = false;

        foreach ($oldEnrollmentList as $key => $value) {
            # code...
            $student_id = $value['student_id'];
            $enrollment_id = $value['enrollment_id'];

            $student = new Student($this->con, $student_id);

            # PREF
            $notNewEnrollment_remove_success = $this->RemovingEnrollmentFormCashierNotEvaluated(
                $enrollment_id,
                $student_id, $current_school_year_id);

                $s = true;

            // if($s == false){
            if($notNewEnrollment_remove_success){

                # Remove Student Table.
                $setToInActiveStudent = $student->UpdateStudentAsInActive(
                    $student_id);
                
                // if(false){
                if($setToInActiveStudent){
                    $hasDone = true;
                }
            }

        }

        return $hasDone;
    }


    public function SecureNoPreviousEnrollmentEnrolled($school_year_id,
        $student_id) {

        $sql = $this->con->prepare("SELECT t1.enrollment_id
        
            FROM enrollment AS t1

            WHERE t1.school_year_id = :school_year_id
            AND t1.student_id = :student_id
            AND t1.enrollment_status = :enrollment_status
            AND t1.enrollment_approve IS NOT NULL
            ");
        
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        if($sql->rowCount() == 0){
            return true;
        }

        return false;
    }

    public function CheckNewTentativeEnrollment($school_year_id,
        $enrollment_id) {

        $sql = $this->con->prepare("SELECT t1.enrollment_id
        
            FROM enrollment AS t1
            WHERE t1.school_year_id = :school_year_id
            AND t1.enrollment_id = :enrollment_id
            AND t1.enrollment_status = :enrollment_status
            AND t1.is_new_enrollee = 1
            ");
        
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "tentative");

        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function CheckStudentHasEnrollmentWithinSemester($school_year_id,
        $student_id) {

        $sql = $this->con->prepare("SELECT t1.enrollment_id
        
            FROM enrollment AS t1

            WHERE t1.school_year_id = :school_year_id
            AND t1.student_id = :student_id
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":student_id", $student_id);

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function CheckTentativeEnrollment($school_year_id,
        $enrollment_id) {

        $sql = $this->con->prepare("SELECT t1.enrollment_id
        
            FROM enrollment AS t1
            WHERE t1.school_year_id = :school_year_id
            AND t1.enrollment_id = :enrollment_id
            AND t1.enrollment_status = :enrollment_status
            ");
        
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "tentative");

        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function CheckEnrolledEnrollment($school_year_id, $enrollment_id) {

        $sql = $this->con->prepare("SELECT t1.enrollment_id
        
            FROM enrollment AS t1
            WHERE t1.school_year_id = :school_year_id
            AND t1.enrollment_id = :enrollment_id
            AND t1.enrollment_status = :enrollment_status
            ");
        
        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function GetEnrollmentCourseIds($school_year_id) : array{

        $sql = $this->con->prepare("SELECT t1.course_id
        
            FROM enrollment AS t1
            WHERE t1.school_year_id = :school_year_id
            AND (
                t1.student_id IS NOT NULL 
                AND t1.student_id != 0
                )

            GROUP BY t1.course_id
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetActivatedLMSAccounts($school_year_id) : array{

        $sql = $this->con->prepare("SELECT t3.*,

            t4.program_section, t1.*

            FROM lms_student  as t1

            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
            AND t2.school_year_id=:school_year_id
            AND t2.enrollment_status=:enrollment_status
            
            LEFT JOIN student as t3 ON t3.student_id = t2.student_id
            LEFT JOIN course as t4 ON t4.course_id = t2.course_id

            WHERE t1.account_status = 1

            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetDeactivatedActivatedLMSAccounts($school_year_id) : array{

        $sql = $this->con->prepare("SELECT t3.*,

            t4.program_section, t1.*

            FROM lms_student  as t1

            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
            AND t2.school_year_id=:school_year_id
            AND t2.enrollment_status=:enrollment_status
            
            LEFT JOIN student as t3 ON t3.student_id = t2.student_id
            LEFT JOIN course as t4 ON t4.course_id = t2.course_id

            WHERE t1.account_status = 0
            AND t1.date_deactivation IS NOT NULL

        ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // public function GetNonActivatedLMSAccounts2($school_year_id) : array{

    //     $return_arr = [];

    //     $allEnrolled = $this->GetEnrolledStudentsWithinSemester($school_year_id);
    //     $activatedElmsAccount = $this->GetActivatedLMSAccounts($school_year_id);

    //     $activatedElmsAccountArr = [];
    //     $allEnrolledArr = [];

    //     foreach ($allEnrolled as $key => $value) {
    //         # code...
    //         array_push($activatedElmsAccountArr, $value['enrollment_id']);
    //     }

    //     foreach ($activatedElmsAccount as $key => $value) {
    //         # code...
    //         array_push($allEnrolledArr, $value['enrollment_id']);
    //     }

    //     $array1 = [1,2,3];
    //     $array2 = [1,2];

    //     // $diff = array_diff($array1, $array2);
    //     $diff = array_diff($activatedElmsAccountArr, $allEnrolledArr);

    //     if(count($diff) > 0)
    //     {
    //         return $diff;
    //     }
                

    //     // $sql = $this->con->prepare("SELECT 
        
    //     //     t1.*

    //     //     FROM enrollment as t1

    //     //     INNER JOIN student as t2 ON t2.student_id = t1.student_id
    //     //     AND t1.school_year_id = :school_year_id
    //     //     AND t1.enrollment_status = :enrollment_status
            
    //     //     ");
        
    //     // $sql->bindParam(":school_year_id", $school_year_id);
    //     // $sql->bindValue(":enrollment_status", "enrolled");

    //     // $sql->execute();

    //     // if($sql->rowCount() > 0){
    //     //     return $sql->fetchAll(PDO::FETCH_ASSOC);
    //     // }
    //     return [];
    // }

    public function GetNonActivatedLMSAccounts($school_year_id) {
        // Assuming you have a database connection object named $this->con
        
        $sql = $this->con->prepare("SELECT

            t3.*,

            t4.program_section, t2.date_activation, t1.enrollment_id

            FROM enrollment as t1
            LEFT JOIN lms_student as t2 ON t1.enrollment_id = t2.enrollment_id
    
            LEFT JOIN student as t3 ON t3.student_id = t1.student_id
            LEFT JOIN course as t4 ON t4.course_id = t1.course_id

            WHERE t2.enrollment_id IS NULL
            AND t1.school_year_id = :school_year_id
            AND t1.enrollment_status = 'enrolled'
            
        ");

        $sql->bindParam(":school_year_id", $school_year_id);
        // $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();
        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC); // You can change the fetch mode as needed
        }
        return [];
        // Fetch and return the results as an array
    }




    public function GetEnrolledStudentsWithinSemester($school_year_id) : array{

        $sql = $this->con->prepare("SELECT 
        
            t1.*

            FROM enrollment as t1

            INNER JOIN student as t2 ON t2.student_id = t1.student_id
            AND t1.school_year_id = :school_year_id
            AND t1.enrollment_status = :enrollment_status
            
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

     
    public function ActivateEnrolledStudent($enrollment_id){

        $now = date("Y-m-d H:i:s");
        $account_status = 1;
       
        $sql = $this->con->prepare("INSERT INTO lms_student
            (enrollment_id, account_status, date_activation)
            VALUES(:enrollment_id, :account_status, :date_activation)
        ");

        $sql->bindParam(":enrollment_id", $enrollment_id);
        $sql->bindParam(":account_status", $account_status);
        $sql->bindParam(":date_activation", $now);

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function DeActivateEnrolledStudent($enrollment_id, $lms_student_id){

        $now = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("UPDATE lms_student
            SET account_status=:set_account_status,
                date_deactivation=:date_deactivation
                -- date_activation=:set_date_activation

            WHERE enrollment_id=:enrollment_id
            AND lms_student_id=:lms_student_id
            -- AND account_status=:current_account_status
        ");

        $sql->bindValue(":set_account_status", 0);
        $sql->bindValue(":date_deactivation", $now);
        // $sql->bindValue(":set_date_activation", NULL);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":lms_student_id", $lms_student_id);
        // $sql->bindValue(":current_account_status", 1);

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function ActivateFromDeactivateEnrolledStudent($enrollment_id, $lms_student_id){

        $now = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("UPDATE lms_student
            SET account_status=:set_account_status,
                -- date_deactivation = :set_date_deactivation,
                date_activation=:set_date_activation

            WHERE enrollment_id=:enrollment_id
            AND lms_student_id=:lms_student_id
        ");

        $sql->bindValue(":set_account_status", 1);
        // $sql->bindValue(":set_date_deactivation", NULL);
        $sql->bindValue(":set_date_activation", $now);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":lms_student_id", $lms_student_id);

        $sql->execute();

        return $sql->rowCount() > 0;
    }
    

}
?>