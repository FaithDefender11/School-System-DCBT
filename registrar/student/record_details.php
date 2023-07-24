<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SchoolYear.php');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];

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

        $email = $student->GetEmail();
        $birthplace = $student->GetStudentBirthPlace();
        $religion = $student->GetReligion();
        $civil_status = $student->GetCivilStatus();
        $nationality = $student->GetNationality();

        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();

        
        $student_program_id = $section->GetSectionProgramId($student_course_id);
        
        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $student_enrollment_id, $current_school_year_id);

        $student_enrollment_program_id = $section->
            GetSectionProgramId($student_enrollment_course_id);

            // echo $student_enrollment_id;
        // echo $student_enrollment_id;

        $enrollment_date = $enrollment->GetStudentEnrollmentDateWithinSemester($student_id, $student_course_id, $current_school_year_id);


        // $student_enrollment_id = $enrollment->GetStudentEnrollmentDateWithinSemester($student_id, $student_course_id, $current_school_year_id);

        // echo $student_course_id;

        $checkEnrollmentEnrolled = $enrollment->CheckEnrollmentEnrolled($student_id,
                $student_course_id, $current_school_year_id, $student_enrollment_id);

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
        
        
        $enrollmentRecordDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_ELEVEN, $FIRST_SEMESTER);

        if($enrollmentRecordDetails1 != null){

            $enrollment_date_approved11_1st = $enrollmentRecordDetails1['enrollment_date_approved'];
            $enrollment_section_acronym11_1st = $enrollmentRecordDetails1['enrollment_section_acronym'];
            $enrollment_section_level11_1st = $enrollmentRecordDetails1['enrollment_section_level'];
            $enrollment_period11_1st = $enrollmentRecordDetails1['enrollment_period'];
            $enrollment_student_status11_1st = $enrollmentRecordDetails1['enrollment_student_status'];
        }

        $enrollmentRecordDetails2 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_ELEVEN, $SECOND_SEMESTER);
        
        if($enrollmentRecordDetails2 != null){

            $enrollment_date_approved11_2nd = $enrollmentRecordDetails2['enrollment_date_approved'];
            $enrollment_section_acronym11_2nd = $enrollmentRecordDetails2['enrollment_section_acronym'];
            $enrollment_section_level11_2nd = $enrollmentRecordDetails2['enrollment_section_level'];
            $enrollment_period11_2nd = $enrollmentRecordDetails2['enrollment_period'];
            $enrollment_student_status11_2nd = $enrollmentRecordDetails2['enrollment_student_status'];
        }


        $enrollmentRecordDetails3 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_TWELVE, $FIRST_SEMESTER);
        
        
        if($enrollmentRecordDetails3 != null){

            $enrollment_date_approved12_1st = $enrollmentRecordDetails3['enrollment_date_approved'];
            $enrollment_section_acronym12_1st = $enrollmentRecordDetails3['enrollment_section_acronym'];
            $enrollment_section_level12_1st = $enrollmentRecordDetails3['enrollment_section_level'];
            $enrollment_period12_1st = $enrollmentRecordDetails3['enrollment_period'];
            $enrollment_student_status12_1st = $enrollmentRecordDetails3['enrollment_student_status'];
        }


        $enrollmentRecordDetails4 = $enrollment->getEnrollmentSectionDetails($student_id,
            $GRADE_TWELVE, $SECOND_SEMESTER);
        
        
        if($enrollmentRecordDetails4 != null){

            $enrollment_date_approved12_2nd = $enrollmentRecordDetails4['enrollment_date_approved'];
            $enrollment_section_acronym12_2nd = $enrollmentRecordDetails4['enrollment_section_acronym'];
            $enrollment_section_level12_2nd = $enrollmentRecordDetails4['enrollment_section_level'];
            $enrollment_period12_2nd = $enrollmentRecordDetails4['enrollment_period'];
            $enrollment_student_status12_2nd = $enrollmentRecordDetails4['enrollment_student_status'];
        }


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

            include_once('./enrolled_subjectv2.php');

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