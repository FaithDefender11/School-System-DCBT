<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');

    // echo Helper::RemoveSidebar();

    ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
        <style>
            <?php include "../../assets/css/content.css" ?>
        </style>
    <?php

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];

        // echo "qwe";
        $GRADE_TWELVE = 12;
        $GRADE_ELEVEN = 11;

        $FIRST_YEAR= 1;
        $SECOND_YEAR= 2;
        $THIRD_YEAR= 3;
        $FOURTH_YEAR= 4;

        $FIRST_SEMESTER = "First";
        $SECOND_SEMESTER = "Second";

        $subject_program = new SubjectProgram($con);

        $enrollment = new Enrollment($con);

        $student = new Student($con, $student_id);

        $parent = new StudentParent($con, $student_id);

        $student_subject = new StudentSubject($con);

        $prompIfIDNotExists = $student->CheckIdExists($student_id);
        $raw_type = $student->CheckIfTertiary($student_id);

        $student_level = $student->GetStudentLevel($student_id);

        $student_type = $student->GetIsTertiary();

        $student_course_id = $student->GetStudentCurrentCourseId($student_id);

        $section = new Section($con, $student_course_id);

        $section_program_id = $section->GetSectionProgramId($student_course_id);
        $section_acronym = $section->GetAcronymByProgramId($section_program_id);

        $type = $raw_type == 1 ? "Tertiary" : "Senior High School";

        $unique_id = $student->GetStudentUniqueId($student_id);

        $firstname = $student->GetFirstName();
        $middle_name = $student->GetMiddleName();
        $suffix = $student->GetSuffix();
        $lastname = $student->GetLastName();
        $birthday = $student->GetStudentBirthdays();
        $address = $student->GetStudentAddress();
        $sex = $student->GetStudentSex();
        $contact_number = $student->GetContactNumber();
        $student_unique_id = $student->GetStudentUniqueId();
        $student_admission_status = $student->GetAdmissionStatus();
        $student_active_status = $student->CheckIfActive();

        // echo $student_active_status;
        
        $email = $student->GetEmail();
        $birthplace = $student->GetStudentBirthPlace();
        $religion = $student->GetReligion();
        $civil_status = $student->GetCivilStatus();
        $nationality = $student->GetNationality();

        $studentHasForm = $student->CheckUnEnrolledStudentDoesntHavePrevForm($student_id,
            $current_school_year_id);

        $parent_id = $parent->GetParentID();


        // Guardian
        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();
        $parent_relationship = $parent->GetGuardianRelationship();
        // 

        // Father
        $father_firstname = $parent->GetFatherFirstName();
        $father_lastname = $parent->GetFatherLastName();
        $father_middle = $parent->GetFatherMiddleName();
        $father_suffix = $parent->GetFatherSuffix();
        $father_contact_number = $parent->GetFatherContactNumber();
        $father_email = $parent->GetFatherEmail();
        $father_occupation = $parent->GetFatherOccupation();


        // Mother
        $mother_firstname = $parent->GetMotherFirstName();
        $mother_lastname = $parent->GetMotherLastName();
        $mother_middle = $parent->GetMotherMiddleName();
        $mother_suffix = $parent->GetMotherSuffix();
        $mother_contact_number = $parent->GetMotherContactNumber();
        $mother_email = $parent->GetMotherEmail();
        $mother_occupation = $parent->GetMotherOccupation();

        // 

        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_program_id = $section->
            GetSectionProgramId($student_enrollment_course_id);

            // echo $student_enrollment_course_id;
        // echo $student_enrollment_id;


        $student_program_id = $section->GetSectionProgramId($student_course_id == 0 ? $student_enrollment_course_id : $student_course_id);


        $enrollment_date = $enrollment->GetStudentEnrollmentDateWithinSemester($student_id, $student_course_id, $current_school_year_id);


        // $student_enrollment_id = $enrollment->GetStudentEnrollmentDateWithinSemester($student_id, $student_course_id, $current_school_year_id);

        // echo $student_course_id;

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $current_school_year_id, $student_enrollment_id);

                // var_dump($checkEnrollmentEnrolled);

        $cashierEvaluated = $enrollment->CheckEnrollmentCashierApproved($student_id,
                $student_course_id, $current_school_year_id);

        $registrarEvaluated = $enrollment->CheckEnrollmentRegistrarApproved($student_id,
                $student_course_id, $current_school_year_id);

        $payment_status = "";

        if($checkEnrollmentEnrolled == true 
            && $cashierEvaluated == true 
            && $registrarEvaluated == true){

            $payment_status = "Enrolled";

        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == true 
            && $registrarEvaluated == true){

            $payment_status = "Approved";
            
        }else if($checkEnrollmentEnrolled == false 
            && $cashierEvaluated == false 
            && $registrarEvaluated == true){

            $payment_status = "Waiting Payment";
        }
        else if($checkEnrollmentEnrolled == false 
            && $registrarEvaluated == false
            && $cashierEvaluated == false){

            $payment_status = "Evaluation";
        }

        $enrollment_section = new Section($con);
        $student_enrollment_course_level = $enrollment_section->GetSectionGradeLevel($student_enrollment_course_id);
        

        $regularEnrolledStudents = $enrollment->GetEnrolledRegularStudentWithinSemester(
            $current_school_year_id);

        if(isset($_GET['details']) && $_GET['details'] == "show"){

            include_once('./details.php');
        }

        if(isset($_GET['grade_records']) && $_GET['grade_records'] == "show"){

            $check = $student_subject->CheckCurrentSemesterSubjectAllPassed(
                $student_enrollment_id, $student_id, $current_school_year_id
            );
            include_once('./grade_record.php');

        }

        if(isset($_GET['enrolled_subject']) && $_GET['enrolled_subject'] == "show"){

            include_once('./enrolled_subject.php');

        }
    }


?>

<script>

    // 
    function MarkAsPassed(subject_id, student_id, remarks,
        student_subject_id, course_id, subject_title){

        // console.log('click');

        $.post('../../ajax/subject/grading_temporary.php', {
            student_id,
            subject_id,
            remarks,
            student_subject_id, 
            course_id,
            subject_title

        }).done(function (data) {
            // console.log(data)
            Swal.fire({
                icon: 'success',
                title: `Subject: ${subject_title} remarked as Passed.`,
                showConfirmButton: false,
                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                toast: true,
                position: 'top-end',
                showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
                },
                hideClass: {
                popup: '',
                backdrop: ''
                }
            }).then((result) => {
                location.reload();
            });
            
        });
    }

    function RemarkAsPassed(student_subject_id, student_id, remarks, subject_title){

        console.log('click');

        $.post('../../ajax/subject/grading_temporary.php', {
           student_subject_id, student_id, remarks

        }).done(function (data) {
            // console.log(data)
            Swal.fire({
                icon: 'success',
                title: `Subject: ${subject_title} remarked as Passed.`,
                showConfirmButton: false,
                timer: 800, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                toast: true,
                position: 'top-end',
                showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
                },
                hideClass: {
                popup: '',
                backdrop: ''
                }
            }).then((result) => {
                location.reload();
            });
            
        });
    }
</script>