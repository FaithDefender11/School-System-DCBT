<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Section.php");

    $school_year = new SchoolYear($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
    $school_year_id = $school_year_obj['school_year_id'];

    if (isset($_POST['program_id'])
        && isset($_POST['current_school_year_term'])) {

        
        $program_id = $_POST['program_id'];
        $current_school_year_term = $_POST['current_school_year_term'];


        $query = $con->prepare("SELECT * FROM course
            WHERE program_id=:program_id
            AND active= 'yes'
            AND school_year_term=:school_year_term
        ");

        $query->bindParam(":program_id", $program_id);
        $query->bindParam(":school_year_term", $current_school_year_term);
        $query->execute();

        if($query->rowCount() > 0){

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
                    'enrollment_capacity' => $enrollment_capacity,
                    'non_enrolled_count' => $non_enrolled_count
                );
            }
        }

        // echo "hewy";
        
        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }

    }

    
?>