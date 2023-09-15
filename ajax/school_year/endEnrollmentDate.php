<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Student.php");


    if(isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "endEnrollmentDate"
        && isset($_POST['school_year_term'])){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];

        // echo $name_period;

        $enrollment = new Enrollment($con);
        $section = new Section($con);
        $school_year = new SchoolYear($con, $school_year_id);

        $pending = new Pending($con);

        # Once Clicked. It should current date >= startEnrollmentDate
        $enrollment_status_inactive = 0;

        $wasSuccess = $school_year->SetEndEnrollmentDate(
            $school_year_id, $enrollment_status_inactive,
            "end_enrollment_date");

        # All section who havent room in the First Semester will be removed.
        # Registrar should unenroll the enrolled student in the section
        # Knowing that the room is now all taken

        // if($school_year_period == "First"){
        //     $firstSemExcessRoomRemove = $section->RemoveUnEnrolledSectionInFirstSemester(
        //         $school_year_term,
        //         $school_year_id);
        // }

        // if($school_year_period == "Second"){
        //     $secondSemExcessRoomRemove = $section->RemoveUnEnrolledSectionInSecondSemester(
        //         $school_year_term, "Second");

        $removeCreatedSectionHasNoEnrolled = $section->RemoveUnEnrolledSectionWithinSemester(
            $school_year_term, $school_year_period, $school_year_id
        );

        # This will removed all new enrollee table
        $removedAllNewEnrolleeInSemester = $pending->RemoveAllPendingEnrolleeWithinSemester(
            $school_year_id);
        
        # This will removed all New Student Tentative and its created Student Table.
        $removeTentativeNewEnrollment = $enrollment->RemovingTentativeNewEnrollmentForm(
            $school_year_id);
        
        # This will removed all Old Student Tentative form
        # Will De-activate the student account.
        $removeTentativeOldEnrollment = $enrollment->RemovingTentativeNotNewEnrollmentForm(
            $school_year_id);

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }

?>