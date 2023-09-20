<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['course_id'])
        
        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $course_id = $_POST['course_id'];

        // echo $subject_program_id;
        // echo "<br>";
        // echo $current_school_year_id;
        // echo "<br>";

        // echo $student_id;
        // echo "<br>";
        // echo $student_enrollment_course_id;
        // echo "<br>";

        // echo $course_id;
        // echo "<br>";
        // echo $enrollment_id;
        // echo "<br>";

        $sy = new SchoolYear($con, $current_school_year_id);

        $subject_program = new SubjectProgram($con, $subject_program_id);

        $subject_code = $subject_program->GetSubjectProgramRawCode();

        $pre_requisite_code = $subject_program->GetPreRequisiteSubjectByCode(
            $subject_code, $subject_program_id
        );


        $section = new Section($con, $course_id);
        $student_subject = new StudentSubject($con);

        $sectionName = $section->GetSectionName();

        $student_enrollment_course_level = $section->GetSectionGradeLevel($student_enrollment_course_id);

        $student_subject_code = $section->CreateSectionSubjectCode($sectionName, $subject_code);
 
        $insertSubjectLoad = $student_subject->InsertStudentSubjectNonFinal($student_id, $student_subject_code,
            $enrollment_id, $course_id, $subject_program_id,
            $current_school_year_id, $subject_code, $student_enrollment_course_level, null);

        if($insertSubjectLoad == true){
            echo "add_success";
            return;
        }else{
            echo "not";
            return;
        }
 
    }
?>