
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Enrollment.php');

    $room = new Room($con);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $uniqueCourseIds = $enrollment->GetEnrollmentCourseIds($current_school_year_id);


    if(isset($_GET['id'])
        && isset($_GET['period'])
        && isset($_GET['term'])
        ){

        $room_id = $_GET['id'];
        $period = ucfirst($_GET['period']);
        $term = $_GET['term'];

        

    }


?>