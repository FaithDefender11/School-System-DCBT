<?php

    include_once('../../includes/config.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Department.php');
   
    if (
        isset($_POST['searchQuery'])
        && isset($_POST['student_program_id'])
        && isset($_POST['student_id'])

        ) {


        $student_program_id = $_POST['student_program_id'];
        $student_id = $_POST['student_id'];

        $searchQuery = trim(strtoupper($_POST['searchQuery']));

        $subject_program = new SubjectProgram($con);
 
        $offeredCourseStrandCurriculum = $subject_program->GetCourseStrandCurriculum(
            $student_program_id,
            $student_id);

        $toSearchArray = array();

        if(count($offeredCourseStrandCurriculum) > 0){

            // print_r($offeredCourseStrandCurriculum);

            foreach ($offeredCourseStrandCurriculum as $key => $row) {

                $subject_code = $row['subject_code'];
                $subject_program_id = $row['subject_program_id'];

                $data[] = array(
                    "subject_code" => $subject_code,
                    "subject_program_id" => $subject_program_id
                );

                array_push($toSearchArray, $subject_code);
                array_push($toSearchArray, $subject_program_id);
            }

            // print_r($data);
        }

        // $searchQuery = "PE";

        if(count($data) > 0){

            // print_r($data);


            $hasValue = false;

            foreach ($data as $value) {

                $subject_code = strtoupper($value['subject_code']);
                $subject_program_id = $value['subject_program_id'];

                // $program_section = "Found";

                // Use the strpos() function to check if the searchQuery is present anywhere within the subject_code

                if (strpos($subject_code, $searchQuery) !== false) {

                    $hasValue = true;
                
                    echo "
                        <div>
                            <a style='cursor: pointer;' class='list-group-item list-group-item-action border-1'>
                                $subject_code
                            </a>

                            <input type='hidden' id='provided_subject_program_id_$subject_program_id'
                                name='provided_subject_program_id' 
                                value='$subject_program_id'>              
                        </div>
                    ";
                }
                 
            }

            if($hasValue == false){
                echo '<p class="list-group-item border-1">No Record(s) Found.</p>';
            }
        }
    }

?>