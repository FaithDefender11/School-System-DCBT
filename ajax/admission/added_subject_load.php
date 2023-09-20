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

        $checkIfSubjectPreRequisiteHasFailed = $student_subject->CheckIfSubjectPreRequisiteHasFailed(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);

            
        $checkIfPreRequisiteSubjectTakenPassed = $student_subject->CheckIfPreRequisiteSubjectTakenPassed(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);
        
        $checkIfSubjectCodeRetaken = $student_subject->CheckIfSubjectCodeRetaken(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);


        $checkIfChosenSubjectAlreadyCredited = $student_subject->CheckIfChosenSubjectAlreadyCredited(
            $student_id, $subject_code); 
            
        $checkIfPreRequisiteIsNotTaken = $student_subject->CheckIfPreRequisiteIsNotTaken(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code); 


        $hasError = false;
        $hasErrorArr = [];

        // if($checkIfSubjectCodeRetaken == true){
        //     echo "taken_failed";
        //     array_push($hasErrorArr, "taken_failed");
        //     $hasError = true;
        // }

        if($checkIfChosenSubjectAlreadyCredited == true){
            echo "already_credited";
            $hasError = true;
            array_push($hasErrorArr, "already_credited");
        }

        // if($checkIfSubjectAlreadyCredited == true){
        //     echo "already_credited";
        //     $hasError = true;
        //     array_push($hasErrorArr, "already_credited");
        // }

        if($checkIfSubjectAlreadyTaken == true){
            echo "subject_already_taken";
            // echo "already taken by different strand/course";
            $hasError = true;
            array_push($hasErrorArr, "subject_already_taken");

        }
        if($checkIfSubjectAlreadyPassed == true){
            echo "already_passed";
            $hasError = true;
            array_push($hasErrorArr, "already_passed");

        }

        // Pre Requisite taken and failed
        if($checkIfSubjectPreRequisiteHasFailed == true
            && $checkIfSubjectPreRequisiteHasFailed != NULL
        ){

            echo "failed_pre_requisite_of_selected_code";
            // echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";
            $hasError = true;
            array_push($hasErrorArr, "failed_pre_requisite_of_selected_code");
        }

        // Pre requisite is student subject grade is null

        // Chosen subject pre_requisite is not taken
        // And subject pre_requisite is not credited

        // if($checkIfPreRequisiteIsNotTaken == true
        //     && $checkIfChosenSubjectAlreadyCredited == false

        //     ){

        //     echo "subject_prerequisite_not_taken";
        //     array_push($hasErrorArr, "subject_prerequisite_not_taken");
        //     $hasError = true;
        // }


        // $subjectPrerequisiteNotTtaken = $student_subject->CheckIfPreRequisiteIsNotTakenEitherPassedOrCredited(
        //     $student_id, $pre_requisite_code);

        // if($subjectPrerequisiteNotTtaken == true){
        //     echo "subject_prerequisite_not_taken";
        //     array_push($hasErrorArr, "subject_prerequisite_not_taken");
        //     $hasError = true;
        // }

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

        } 

        // Allow Subject to be taken if it was failed by the previous
        // if($checkIfSubjectAlreadyCredited == true){
        //     echo "already credited";
        // }else{
        //     echo "not credited";
        // }


 
    }
?>