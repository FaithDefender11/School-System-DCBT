<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/EnrollmentAudit.php");

    if (isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id']) 
        && isset($_POST['type'])
        && ($_POST['type'] == 'Retake' || $_POST['type'] == 'Unretake')
        ){


        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $student_id = $_POST['student_id']; 
        $current_school_year_id = $_POST['current_school_year_id']; 
        $type = $_POST['type']; 

        // echo $type;

        
        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->FormUpdateAsRetake($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

        if($wasSuccess){
            echo "update_success";
        }
        
    }
    
    else if (isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id']) 
        && isset($_POST['current_school_year_id']) 
        && isset($_POST['type'])
        && $_POST['type'] !== 'Retake'
        && $_POST['type'] !== 'Unretake'
        ){


        $registrarUserId = isset($_SESSION["registrarUserId"]) 
        ? $_SESSION["registrarUserId"] : "";


        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $student_id = $_POST['student_id']; 
        $current_school_year_id = $_POST['current_school_year_id']; 
        $type = $_POST['type']; 

        // echo $type;
        // return;

        $enrollment = new Enrollment($con);
        $school_year = new SchoolYear($con, $current_school_year_id);
        $current_school_year_period = $school_year->GetPeriod();

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId(
                $student_id, $student_enrollment_id);

        $section = new Section($con, $student_enrollment_course_id);
        $studentSectionName = $section->GetSectionName();
        $studentSectionLevel = $section->GetSectionGradeLevel();

        // $studentSectionLevel = $section->CreateSectionSubjectCode(
        //     $studentSectionName, );

        $studentSectionProgramId = $section->GetSectionProgramId($student_enrollment_course_id);

        $studentSubject = new StudentSubject($con);
        
        
    
        // var_dump($asd);
        // return;

        // echo "heyy";

        $enrollmentAudit = new EnrollmentAudit($con);

        $now = date("Y-m-d H:i:s");
        $date_creation = date("M d, Y h:i a", strtotime($now));

        $registrarName = "";

        if($registrarUserId != ""){
            $user = new User($con, $registrarUserId);
            $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
        }

        if($type === "Regular"){

            # Change into Regular

            $wasSuccess = $enrollment->FormUpdateStudentStatus($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

            if($wasSuccess){

                $description = "Registrar '$registrarName' has been changed the enrollment status into 'Regular' on $date_creation";

                $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                    $student_enrollment_id,
                    $description, $current_school_year_id, $registrarUserId
                );

                # Remove all subjects in the student_subject list
                # Remove also the credited subjects within semester course level

                # Get all Offered Subject Program within semester course level
                # And Insert All appropriate subjects for Regular Student Within *Semester Course Level
                
                $doesPopulationSubjectCompleted = $studentSubject->PopulateBlockSectionSubjects(
                    $current_school_year_id, $current_school_year_period,
                    $student_enrollment_course_id,
                    $student_enrollment_id, $student_id,
                    
                    $registrarUserId);

                echo "update_success";
                return;
            }
        }



        if($type === "Irregular"){

            # Change into Irregular

            // echo "Into Regular";
            // return;

            $description = "Registrar '$registrarName' has been changed the enrollment status into 'Irregular' on $date_creation";

            $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                $student_enrollment_id,
                $description, $current_school_year_id, $registrarUserId
            );

            $wasSuccess = $enrollment->FormUpdateStudentStatus($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

            if($wasSuccess){
                
                echo "update_success";
                return;
            }
        }
        
        
    }
?>