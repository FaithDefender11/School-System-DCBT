<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");


    if(isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "breakEnded"
        && isset($_POST['school_year_term'])){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];

        // echo $name_period;

        $school_year = new SchoolYear($con, $school_year_id);

        # Once Clicked. It should current date >= startEnrollmentDate
        $break_ended = 1;

        $wasSuccess = $school_year->SetBreakEnded(
                $school_year_id, $break_ended);

        if($wasSuccess){

            $hasChangeIntoNextSY = $school_year->SetAnotherRowOfSchoolYear(
                $school_year_id);

            if($hasChangeIntoNextSY){

                // Current Year Period Should be InActive.
                $currentYearPeriodInActive = $school_year->SetCurrentYearPeriodInActive($school_year_id);
                if($currentYearPeriodInActive){
                    echo "success_update";
                    return;
                }
            }
        }
    }

?>