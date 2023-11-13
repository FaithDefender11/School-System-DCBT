<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/EnrollmentAudit.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    
    if (isset($_POST['student_subject_id'])) {

       

        $student_subject_id = $_POST['student_subject_id'];
       
        // echo $student_subject_id;
        $studentSubject = new StudentSubject($con, $student_subject_id);

        $enrollment_id = $studentSubject->GetEnrollmentId();
        $course_id = $studentSubject->GetStudentSubjectCourseId();
        $student_subject_school_year_id = $studentSubject->GetSchoolYearId();
        $program_code = $studentSubject->GetStudentProgramCode();

        $subject_program_id = $studentSubject->GetStudentSubjectProgramId();

        $subject_program = new SubjectProgram($con, $subject_program_id);

        $subject_title = $subject_program->GetTitle();

        $section = new Section($con, $course_id);
        $student_subject = new StudentSubject($con);

        $sectionName = $section->GetSectionName();
 
        $student_subject_code = $section->CreateSectionSubjectCode($sectionName, $program_code);



        $registrarUserId = isset($_SESSION["registrarUserId"]) 
            ? $_SESSION["registrarUserId"] : "";
        
        $registrarName = "";

        if($registrarUserId != ""){
            $user = new User($con, $registrarUserId);
            $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
        }

        $now = date("Y-m-d H:i:s");
        $date_creation = date("M d, Y h:i a", strtotime($now));

        $description = "Registrar '$registrarName' has removed a subject load of '$subject_title ($student_subject_code)' on $date_creation";
        $enrollmentAudit= new EnrollmentAudit($con);

        $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
            $enrollment_id,
            $description, $student_subject_school_year_id, $registrarUserId
        );
        
        $query = $con->prepare("DELETE FROM student_subject 
            WHERE student_subject_id = :student_subject_id");
        
        $query->bindValue(":student_subject_id", $student_subject_id);

        if($query->execute()){
            echo "success_delete";
        }

    }
?>