<?php

    include_once('../../includes/config.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Department.php');
   
    ?>
    <head>
        <style>
          .select_achor.active {
                background-color: #4285f4;
            }
        </style>
    </head>
    <?php
    if (
        isset($_POST['searchQuery'])
        // && isset($_POST['enrollment_id'])
        // && isset($_POST['student_id'])
        
        ) {

        // $student_id = $_POST['student_id'];
        // $enrollment_id = $_POST['enrollment_id'];

        $searchQuery = trim(strtoupper($_POST['searchQuery']));

        // echo $searchQuery;
        // return;

        $school_year = new SchoolYear($con);
        $student = new Student($con);


        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        $allOngoingActive = $student->GetAllOngoingActive();

        $toSearchArray = array();

        if(count($allOngoingActive) > 0){

            foreach ($allOngoingActive as $key => $row) {
                # code...

                $student_id = $row['student_id'];
                $student_unique_id = $row['student_unique_id'];
                $firstname = ucfirst($row['firstname']);
                $lastname = ucfirst($row['lastname']);
                $program_section = $row['program_section'];

                $data[] = array(
                    "student_id" => $student_id,
                    "student_unique_id" => $student_unique_id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "program_section" => $program_section
                );

                // array_push($toSearchArray, $student_id);
                // array_push($toSearchArray, $student_unique_id);
                // array_push($toSearchArray, $firstname);
                // array_push($toSearchArray, $lastname);

            }

            // print_r($data);
            
        }

        // $searchQuery = "PE";

        // if(false){
        if(count($data) > 0){

            $hasValue = false;
            foreach ($data as $value) {

                $firstname = strtoupper($value['firstname']);
                $lastname = strtoupper($value['lastname']);
                $student_id = $value['student_id'];

                $student_unique_id = $value['student_unique_id'];
                $program_section = $value['program_section'];

                // $program_section = "Found";
                // Use the strpos() function to check if the searchQuery is present anywhere within the subject_code
                if (strpos($firstname, $searchQuery) !== false
                    || strpos($lastname, $searchQuery) !== false
                    || strpos($student_unique_id, $searchQuery) !== false
                    ) {

                        $hasValue = true;

                        $firstname = ucfirst($firstname);
                        $lastname = ucfirst($lastname);
             
                    echo "
                        <div>
                            <a style='cursor: pointer;' class='select_achor list-group-item list-group-item-action border-1'>
                                #$student_unique_id $firstname $lastname -- $program_section
                            </a>
                            <input type='hidden' id='provided_subject_program_id_'
                                name='provided_subject_program_id' 
                                value='$student_unique_id'>              
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


<script>
    $(document).ready(function() {
        $('.select_achor').click(function() {
            $('.select_achor').removeClass('active'); // Remove "active" class from all navigation items
            $(this).addClass('active'); // Add "active" class to the clicked navigation item
        });
    });
</script>