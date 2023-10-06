<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/StudentSubject.php");

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

        if($type === "Regular"){

            # Change into Regular

            // echo "Into Regular";
            // $asd = $studentSubject->PopulateBlockSectionSubjects(
            //     $current_school_year_id, $current_school_year_period,
            //     $student_enrollment_course_id,
            //     $student_enrollment_id,
            //     $student_id);
            // return;

            $wasSuccess = $enrollment->FormUpdateStudentStatus($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

            if($wasSuccess){

                # Remove all subjects in the student_subject list
                # Remove also the credited subjects within semester course level

                # Get all Offered Subject Program within semester course level
                # And Insert All appropriate subjects for Regular Student Within *Semester Course Level
                
                $asd = $studentSubject->PopulateBlockSectionSubjects(
                    $current_school_year_id, $current_school_year_period,
                    $student_enrollment_course_id,
                    $student_enrollment_id, $student_id);

                echo "update_success";
                return;
            }
        }



        if($type === "Irregular"){

            # Change into Irregular

            // echo "Into Regular";
            // return;

            $wasSuccess = $enrollment->FormUpdateStudentStatus($current_school_year_id,
            $student_id, $student_enrollment_id, $type);

            if($wasSuccess){

                
                echo "update_success";
                return;
            }
        }
        
        
    }
?>