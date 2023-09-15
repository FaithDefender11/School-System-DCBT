<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Student.php");
    
    if (isset($_POST['current_school_year_id'])
        && isset($_POST['student_enrollment_id'])
        && isset($_POST['student_id'])
        && isset($_POST['chosen_course_id'])){
 
 
        $student_enrollment_id = $_POST['student_enrollment_id'];   
        $current_school_year_id = $_POST['current_school_year_id'];   
        $student_id = $_POST['student_id'];   
        $chosen_course_id = $_POST['chosen_course_id'];   



        $enrollment = new Enrollment($con);
        $section = new Section($con);

        $get_chosen_course_program_id = $section->GetSectionProgramId($chosen_course_id);

        $program = new Program($con, $get_chosen_course_program_id);

        $get_program_department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $get_program_department_id);

        $department_name = $department->GetDepartmentName();

        $course_department_type = $department_name === "Senior High School" 
            ? 0 : ($department_name === "Tertiary" ? 1 : -1);

        // echo $course_department_type;

        $changeCourseId = $enrollment->ChangeEnrollmentProgramCourseId(
            $current_school_year_id,
            $student_id, $student_enrollment_id,
            $chosen_course_id, $course_department_type);

        $student = new Student($con);

        $update_academic_type = $student->UpdateStudentAcademicType($student_id,
            $course_department_type);

        if($changeCourseId == true){
            echo "success_change_program";
            return;
        }else if($changeCourseId == false){
            echo "changing_program_went_wrong";
            return;
        }
    }
?>