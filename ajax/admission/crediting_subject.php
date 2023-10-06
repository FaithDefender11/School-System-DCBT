<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['subject_program_id'])
    
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['subject_code'])
        
        && isset($_POST['type']) && $_POST['type'] == "Credit"
        ) {

        $subject_program_id = $_POST['subject_program_id'];

    

        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $subject_code = $_POST['subject_code'];

        $student_subject = new StudentSubject($con);

        $wasSuccess = $student_subject->MarkStudentSubjectAsCredited($student_id,
            $current_school_year_id, $subject_program_id, $subject_code);

        if($wasSuccess){
            echo "credited_success";
        }
 
    }
    // else{
    //     echo "Something went wrong on the subject_program_id";
    // }

    
    if (isset($_POST['subject_program_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "Uncredit"
        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $enrollment_id = $_POST['enrollment_id'];




        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];

        $school_year = new SchoolYear($con, $current_school_year_id);
        $period = $school_year->GetPeriod();

        $student_subject = new StudentSubject($con);
        $enrollment = new Enrollment($con);

        $enrollment_student_status = $enrollment->GetEnrollmentFormStudentStatus($student_id,
            $enrollment_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId(
                $student_id, $enrollment_id);

        $section = new Section($con, $student_enrollment_course_id);
        $sectionName = $section->GetSectionName();

        $student_enrollment_course_level = $section->GetSectionGradeLevel();

        $subjectProgram = new SubjectProgram($con, $subject_program_id);
        
        $doesSubjectProgramOfferedWithinSemester = $subjectProgram->CheckSubjectProgramIsWithinSemesterOffered(
            $subject_program_id, $period, $student_enrollment_course_level);

        // var_dump($doesSubjectProgramOfferedWithinSemester);
        // return;
        
        // if($enrollment_student_status == "Regular"){




        //     $program_code = $subjectProgram->GetSubjectProgramRawCode();

        //     $subject_code = $section->CreateSectionSubjectCode($sectionName, $program_code);


        //     # Check if subject program is within the current semester
        //     # if so, add to the student cart,
        //     $wasUndoCreditedSubject = $student_subject->AddSubjectProgramIntoStudentSubjectList(
        //         $student_id, $subject_code, $enrollment_id,
        //         $student_enrollment_course_id, $subject_program_id,
        //         $current_school_year_id, $program_code
        //     );
        // }
        

        // echo $enrollment_student_status;
        // return;

        $query = $con->prepare("DELETE FROM student_subject 
            WHERE school_year_id = :school_year_id
            AND subject_program_id = :subject_program_id
            AND student_id = :student_id
            AND is_transferee = :is_transferee
            AND is_final = :is_final

            ");
        $query->bindValue(":school_year_id", $current_school_year_id);
        $query->bindValue(":subject_program_id", $subject_program_id);
        $query->bindValue(":student_id", $student_id);
        $query->bindValue(":is_transferee", 1);
        $query->bindValue(":is_final", 1);
        $query->execute();

        if($query->rowCount() > 0){

            # Once student form is regular and the credited subject
            # is within the same current semester (1st Semester)
            # Example: Credited = OCC, OCC is offered in 1st Semester
            # That subject should be added to the cart (just like undoing behavior)

            if($enrollment_student_status == "Regular" 
                && $doesSubjectProgramOfferedWithinSemester === true){

                $subjectProgram = new SubjectProgram($con, $subject_program_id);
                $program_code = $subjectProgram->GetSubjectProgramRawCode();

                $subject_code = $section->CreateSectionSubjectCode($sectionName,
                    $program_code);

                # Check if subject program is within the current semester
                # if so, add to the student cart,
                $wasUndoCreditedSubject = $student_subject->AddSubjectProgramIntoStudentSubjectList(
                    $student_id, $subject_code, $enrollment_id,
                    $student_enrollment_course_id, $subject_program_id,
                    $current_school_year_id, $program_code
                );

            }

            echo "uncredited_success";
        }

    }

    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "creditEnrolledSubject"
        && isset($_POST['student_subject_id'])
        && isset($_POST['subject_code'])

        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_subject_id = $_POST['student_subject_id'];
        $subject_code = $_POST['subject_code'];

        $student_subject = new StudentSubject($con);


        $wasSuccess = $student_subject->CreditAssignedStudentSubjectNonFinal(
            $student_subject_id, $subject_program_id,
            $student_id, $current_school_year_id, $subject_code);

        if($wasSuccess == true){
            echo "credited_success";
        }
 
    }


    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['type']) && $_POST['type'] == "unCreditEnrolledSubject"
        && isset($_POST['student_subject_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['student_subject_code'])

        ) {

        $subject_program_id = $_POST['subject_program_id'];
        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_subject_id = $_POST['student_subject_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];
        $student_subject_code = $_POST['student_subject_code'];

        $student_subject = new StudentSubject($con);

        // echo $student_subject_code;

        $wasSuccess = $student_subject->UnCreditAssignedStudentSubjectNonFinal(
            $student_subject_id, $subject_program_id,
            $student_id, $current_school_year_id, $enrollment_id,
            $student_enrollment_course_id, $student_subject_code);

        if($wasSuccess == true){
            echo "un_credited_success";
        }
 
    }

?>