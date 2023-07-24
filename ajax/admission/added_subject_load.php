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

        $sy = new SchoolYear($con, $current_school_year_id);

        $current_semester = $sy->GetSchoolYearPeriod();

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
            // echo $student_subject_code;
        
        
        // Check if Subject Program ID is already inserted within semester.
        $alreadyInsertedThisSemester = $student_subject->CheckIfSubjectProgramAlreadyInsertedWithinSemester(
            $student_id, $subject_program_id,
            $current_school_year_id, $current_semester);

        // Check If Subject Program Code is already credited by the student records.
        // Within S.Y or Prev S.Y
        $checkIfSubjectAlreadyCredited = $student_subject->CheckIfSubjectAlreadyCredited(
            $student_id,
            $subject_code);

        // Check If Subject Program Code is already inserted
        // which registrar placed on different course/strand.
        // Example.
        // NSTP101 HUMMS -> ALREADY INSERTED
        // NSTP101 STEM -> HIT
        $checkIfSubjectAlreadyTaken = $student_subject->CheckIfSubjectAlreadyTaken(
            $student_id,
            $subject_code);

        $checkIfSubjectAlreadyPassed = $student_subject->CheckIfSubjectAlreadyPassed(
            $student_id,
            $subject_code);

        $checkIfSubjectNotPassedForPreRequisite = $student_subject->CheckIfSubjectNotPassedForValidation(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);

            
        $checkIfPreRequisiteSubjectTakenPassed = $student_subject->CheckIfPreRequisiteSubjectTakenPassed(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);
        
        $checkIfSubjectCodeRetaken = $student_subject->CheckIfSubjectCodeRetaken(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);
 

        // if($alreadyInsertedThisSemester == true){
        //     echo "already inserted";
        //     $hasError = true;

        // }

        $hasError = false;
        $hasErrorArr = [];

        // if($checkIfSubjectCodeRetaken == true){
        //     echo "taken_failed";
        //     array_push($hasErrorArr, "taken_failed");
        // }
        
        if($checkIfSubjectAlreadyCredited == true){
            echo "already_credited";
            $hasError = true;
            array_push($hasErrorArr, "already_credited");
        }
        if($checkIfSubjectAlreadyTaken == true){
            echo "taken_different_strand";
            // echo "already taken by different strand/course";
            $hasError = true;
            array_push($hasErrorArr, "taken_different_strand");

        }
        if($checkIfSubjectAlreadyPassed == true){
            // echo "taken_different_strand";
            echo "already_passed";
            $hasError = true;
            array_push($hasErrorArr, "already_passed");

        }
        if($checkIfSubjectNotPassedForPreRequisite == true){

            echo "failed_pre_requisite_of_selected_code";
            // echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";
            $hasError = true;
            array_push($hasErrorArr, "failed_pre_requisite_of_selected_code");

        }
        if($checkIfPreRequisiteSubjectTakenPassed == false){

            echo "subject_prerequisite_not_taken";
            array_push($hasErrorArr, "subject_prerequisite_not_taken");

            $hasError = true;
        }

        if(empty($hasErrorArr) == true){
            // echo $hasError;
            // echo " without err";
            $insertSubjectLoad = $student_subject->InsertStudentSubjectNonFinal($student_id, $student_subject_code,
                $enrollment_id, $course_id, $subject_program_id,
                $current_school_year_id, $subject_code, $student_enrollment_course_level, $checkIfSubjectCodeRetaken);

            if($insertSubjectLoad == true){
               echo "add_success";
               return;
            }

        }else{
            // echo "with error";
            print_r($hasErrorArr);
        }

        // Allow Subject to be taken if it was failed by the previous
        // if($checkIfSubjectAlreadyCredited == true){
        //     echo "already credited";
        // }else{
        //     echo "not credited";
        // }


 
    }
?>