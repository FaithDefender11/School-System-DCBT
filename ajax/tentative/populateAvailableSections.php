<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    
    if (isset($_POST['current_level'])
        && isset($_POST['selected_program_id'])
        && isset($_POST['school_year_id'])
    
        ) {

        $current_level = intval($_POST['current_level']);

        $selected_program_id = intval($_POST['selected_program_id']);

        $school_year_id = intval($_POST['school_year_id']);


        $sy = new SchoolYear($con, $school_year_id);

        $term = $sy->GetTerm();
        $period = $sy->GetPeriod();

        // echo "school_year_id: $school_year_id";


        // echo "selected_program_id: $selected_program_id";
        // echo "current_level: $current_level";


        $query = $con->prepare("SELECT * FROM course
            WHERE program_id=:program_id
            AND course_level=:course_level
            -- AND school_year_term=:school_year_term
            
        ");

        $query->bindValue(":program_id", $selected_program_id);
        $query->bindValue(":course_level", $current_level);
        // $query->bindValue(":school_year_term", $school_year_term);
        $query->execute();

        if($query->rowCount() > 0){

            // echo "has ";

            $section = new Section($con);


            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $course_id = $row['course_id'];
                $program_section = $row['program_section'];
                $capacity = $row['capacity'];

                $non_enrolled_count = $section->GetEnrollmentCourseIdNonEnrolledCount($course_id,
                    $school_year_id);

                $enrollment_capacity = $section->GetEnrollmentCourseIdEnrolledCount($course_id,
                    $school_year_id);

                $data[] = array(

                    'course_id' => $course_id,
                    'program_section' => $program_section,
                    'capacity' => $capacity,

                    'non_enrolled_count' => $non_enrolled_count,
                    'enrollment_capacity' => $enrollment_capacity,
                );
            }
        }else{
            // echo "nothing";
        }

        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }



    }

?>