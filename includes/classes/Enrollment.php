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

    public function CheckStudentEnrolled($student_id, $course_id, $school_year_id){

        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_status = :enrollment_status
            
            ");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        return $sql->rowCount() > 0;
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

    public function GetStudentSectionGradeLevelSemester(
        $student_id, $grade_level, $SEMESTER){

        // echo $grade_level;

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

        $query->bindValue(":student_id", $student_id); 
        $query->bindValue(":selected_semester", $SEMESTER); 
        $query->bindValue(":enrollment_status", "enrolled"); 
        $query->bindValue(":course_level", $grade_level); 
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
            t1.student_unique_id AS student_unique_id,
            t1.new_enrollee AS new_enrollee,
            t2.student_status AS enrollment_student_status,
            t2.is_new_enrollee AS enrollment_is_new_enrollee,
            t2.is_transferee AS enrollment_is_transferee
            
            -- Ongoing Irregular Student Query. (BEFORE)

            -- Regular, Irregular.

            FROM student as t1
            INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
            AND t1.active = 1

            AND (t2.course_id = t1.course_id OR t2.course_id = 0 OR t2.course_id != 0)

            -- AND t2.student_status = 'Irregular'
            AND t2.registrar_evaluated = 'no'
            AND t2.cashier_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'

            LEFT JOIN course as t3 ON t3.course_id = t1.course_id
            LEFT JOIN program as t4 ON t4.program_id = t3.program_id

            -- WHERE t1.admission_status = 'Transferee'

            AND t2.registrar_evaluated = 'no'
            AND t2.enrollment_status = 'tentative'
            -- AND t2.is_new_enrollee = 'no'

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
            NULL AS student_unique_id,
            NULL AS new_enrollee,
            NULL AS enrollment_student_status,
            NULL AS enrollment_is_new_enrollee,
            NULL AS enrollment_is_transferee

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

    function GetEnrollmentIdNonDependent($student_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_id FROM enrollment 
            WHERE student_id = :student_id
            AND school_year_id = :school_year_id
            
            ");
        // $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
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

    public function GetEnrollmentFormCourseId($student_id, $enrollment_id,
        $school_year_id) {

        $student_enrollment_course_id = 0;

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT course_id FROM enrollment 

            WHERE enrollment_id = :enrollment_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            
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
        $school_year_id) {

        $student_status = "";

        // Check if the enrollment form ID already exists in the database

        $sql = $this->con->prepare("SELECT student_status FROM enrollment 

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

    public function GetEnrollmentIsNewEnrollee($student_id, 
        $course_id, $school_year_id) {
        
        $sql = $this->con->prepare("SELECT is_new_enrollee FROM enrollment 
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

            t1.student_id, t1.cashier_evaluated,t1.registrar_evaluated,
            t1.is_transferee, t1.enrollment_approve,
            t1.course_id as enrollment_course_id,
            t1.enrollment_date,
            t1.registrar_confirmation_date,

            t2.firstname,t2.username, t2.student_unique_id,
            t2.lastname,t2.course_level,
            t2.admission_status,t2.student_statusv2,
            t2.course_id, t2.student_id as t2_student_id,
            t2.course_id, t2.course_level,t2.student_status,
            t2.is_tertiary, t2.new_enrollee, 

            t1.is_new_enrollee AS enrollment_is_new_enrollee,
            t1.is_transferee AS enrollment_is_transferee,
            t1.student_status AS enrollment_student_status,
            
            t3.program_section

            FROM enrollment as t1
            INNER JOIN student as t2 ON t2.student_id = t1.student_id

            LEFT JOIN course as t3 ON t2.course_id = t3.course_id

            WHERE (t1.is_new_enrollee=:is_new_enrollee
            OR t1.is_new_enrollee=:is_new_enrollee2)

                AND (t1.is_transferee = :is_transferee OR t1.is_transferee = :is_transferee2)
                
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
        $registrar_side->bindValue(":registrar_evaluated", $registrar_evaluated);
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

        $sql = $this->con->prepare("SELECT t2.*, t3.program_section 
        
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

    function CheckEnrollmentCashierApproved($student_id, $course_id, $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND cashier_evaluated = :cashier_evaluated
            -- AND enrollment_status != :enrollment_status
            
            ");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":cashier_evaluated", "yes");
        // $sql->bindValue(":enrollment_status", "enrolled");

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    function CheckEnrollmentRegistrarApproved($student_id, $course_id,
        $school_year_id) {
        // Check if the enrollment form ID already exists in the database
        $sql = $this->con->prepare("SELECT enrollment_form_id FROM enrollment 
            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND registrar_evaluated = :registrar_evaluated
            -- AND enrollment_status != :enrollment_status
            
            ");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindValue(":registrar_evaluated", "yes");
        // $sql->bindValue(":enrollment_status", "enrolled");

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

    public function MarkAsRegistrarEvaluated($current_school_year_id,
        $student_course_id,
        $student_id, $enrollment_form_id){

        $registrar_evaluated = "yes";
        $now = date("Y-m-d H:i:s");
     
        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET registrar_evaluated=:registrar_evaluated,
                registrar_confirmation_date=:registrar_confirmation_date
            
            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            AND course_id=:course_id
            ");

        $update_tentative->bindParam(":registrar_evaluated", $registrar_evaluated);
        $update_tentative->bindParam(":registrar_confirmation_date", $now);
        $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_form_id", $enrollment_form_id);
        $update_tentative->bindParam(":course_id", $student_course_id);

        return $update_tentative->execute();
      
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
        $student_id, $enrollment_id, $course_id){

        $exec = false;
        // $enrollment_status = "enrolled";

        $section = new Section($this->con, $course_id);

        $sectionLevel = $section->GetSectionGradeLevel();

        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET course_id=:course_id

            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_id=:enrollment_id
            -- AND enrollment_status=:enrollment_status
            ");

        $update_tentative->bindValue(":course_id", $course_id);
        $update_tentative->bindParam(":student_id", $student_id);
        $update_tentative->bindParam(":school_year_id", $current_school_year_id);
        $update_tentative->bindParam(":enrollment_id", $enrollment_id);
        // $update_tentative->bindParam(":enrollment_status", $enrollment_status);

        if($update_tentative->execute() && $update_tentative->rowCount() > 0){

            // Update student
            $update_student_course = $this->con->prepare("UPDATE student 
                SET course_id=:change_course_id,
                    course_level=:change_course_level

                WHERE student_id=:student_id
                AND active = 1
                ");

            $update_student_course->bindParam(":change_course_id", $course_id);
            $update_student_course->bindParam(":change_course_level", $sectionLevel);
            $update_student_course->bindParam(":student_id", $student_id);
            
            if($update_student_course->execute()){
                $exec = true;
            }
        }
        return $exec;
    }

    public function EnrollmentFormMarkAsEnrolled($current_school_year_id,
        $student_course_id, $student_id, $enrollment_form_id,
        $student_enrollment_student_status){

        $enrollment_status = "enrolled";
        $now = date("Y-m-d H:i:s");


        $update_tentative = $this->con->prepare("UPDATE enrollment
            SET enrollment_status=:enrollment_status,
                enrollment_approve=:enrollment_approve

                -- student_status=:student_status

            
            WHERE student_id=:student_id
            AND school_year_id=:school_year_id
            AND enrollment_form_id=:enrollment_form_id
            -- AND course_id=:course_id
            ");

        $update_tentative->bindValue(":enrollment_status", $enrollment_status);
        $update_tentative->bindValue(":enrollment_approve", $now);
        // $update_tentative->bindValue(":student_status", $student_enrollment_student_status);
        $update_tentative->bindValue(":student_id", $student_id);
        $update_tentative->bindValue(":school_year_id", $current_school_year_id);
        $update_tentative->bindValue(":enrollment_form_id", $enrollment_form_id);
        // $update_tentative->bindValue(":course_id", $student_course_id);

        if($update_tentative->execute()){
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

        $sql = $this->con->prepare("SELECT * FROM enrollment

            WHERE course_id = :course_id
            AND student_id = :student_id
            AND school_year_id = :school_year_id
            AND enrollment_id = :enrollment_id
            AND enrollment_status = :enrollment_status
            AND enrollment_approve IS NOT NULL
            
            ");
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":student_id", $student_id);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":enrollment_id", $student_enrollment_id);
        $sql->bindValue(":enrollment_status", "enrolled");
        $sql->execute();

        return $sql->rowCount() > 0 ? "true" : "false";
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

    public function GetAllEnrolledEnrollmentCourseIDWithinSemester($school_year_id) {

        $sql = $this->con->prepare("SELECT course_id FROM enrollment 
            WHERE school_year_id = :school_year_id
            AND enrollment_status = 'enrolled'
            AND enrollment_approve IS NOT NULL
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);
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

        $sql = $this->con->prepare("SELECT student_id FROM enrollment 
            WHERE school_year_id = :school_year_id
            AND enrollment_status = 'enrolled'
            AND enrollment_approve IS NOT NULL
            AND is_new_enrollee = 1
            ");
        
        $sql->bindParam(":school_year_id", $school_year_id);

        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll(PDO::FETCH_COLUMN);
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

        // $student_status = $isRegular == "Regular" ? "Regular" : "";

        // $is_tertiary = $is_tertiary == "Tertiary" ? 1 : 0;

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
    

}
?>