<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/SchoolYear.php");


    if(isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "startEnrollmentDate"
        && isset($_POST['school_year_term'])){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];

        $school_year = new SchoolYear($con, $school_year_id);

        # Once Clicked. It should current date >= startEnrollmentDate
        $enrollment_status_active = 1;

        $wasSuccess = $school_year->SetEnrollmentOngoingStatus(
                $school_year_id, $enrollment_status_active, "start_enrollment_date");

        if($wasSuccess){
            echo "success_update";
            return;
        }
    }

?>