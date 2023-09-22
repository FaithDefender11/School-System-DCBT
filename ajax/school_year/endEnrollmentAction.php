<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Student.php");


    if(isset($_POST['current_school_year_id'])){

        $school_year_id = $_POST['current_school_year_id'];
        // echo $school_year_id;

        $school_year = new SchoolYear($con, $school_year_id);

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $school_year->GetPeriod();
        $school_year_term = $school_year->GetTerm();


        $enrollment = new Enrollment($con);
        $section = new Section($con);
        $school_year = new SchoolYear($con, $school_year_id);

        $pending = new Pending($con);

        # Once Clicked. It should current date >= startEnrollmentDate
        $enrollment_status_inactive = 0;

        $removeCreatedSectionHasNoEnrolled = $section->RemoveUnEnrolledSectionWithinSemester(
            $school_year_term, $school_year_period, $school_year_id
        );

        # 1. Remove All new ENrolle Table.
        # 2. Remove all form from new student tentative and its created student table
        # 3. Remove all form from old student tentative and will de-activate the student account.

        # This will removed all new enrollee table
        $removedAllNewEnrolleeInSemester = $pending->RemoveAllPendingEnrolleeWithinSemester(
            $school_year_id);
        
        # This will removed all enrollment form from New Student Tentative 
        # and its created Student Table.
        $removeTentativeNewEnrollment = $enrollment->RemovingTentativeNewEnrollmentForm(
            $school_year_id);
        
        # This will removed all enrollment form from Old Student Tentative form
        # and will de-activate the student account.
        $removeTentativeOldEnrollment = $enrollment->RemovingTentativeNotNewEnrollmentForm(
            $school_year_id);

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }
?>