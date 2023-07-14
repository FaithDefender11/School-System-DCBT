<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');


    $department = new Department($con);
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        
        // Things to consider.
        // 1. New Transferee -> Pending Table.
        // 1. Irregular O.S -> Pending Table.

        $student_id = $_GET['id'];
        

        if(isset($_GET['details']) && $_GET['details'] == "show"){

            echo "details show student";
        }
        else if(isset($_GET['finding_section']) && $_GET['finding_section'] == "show"){

            echo "finding_section student";
        }
        else if(isset($_GET['subject_evaluation']) && $_GET['subject_evaluation'] == "show"){

            echo "subject_evaluation student";
        }
    }
?>