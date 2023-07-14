<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');


    if(isset($_GET['id']) && isset($_GET['e_id'])){

        $school_year = new SchoolYear($con);
        $enrollment = new Enrollment($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $school_year_id = $school_year_obj['school_year_id'];

        $student_id = $_GET['id'];
        $enrollment_form_id = $_GET['e_id'];

        $checkRequestValid = $enrollment->CheckRequestEnrollmentRequestValid(
            $enrollment_form_id, $student_id, $school_year_id
        );

        if(!$checkRequestValid){
            echo "Oops.";
            exit();
        }

        echo " My Form ID: $enrollment_form_id";
    }

?>