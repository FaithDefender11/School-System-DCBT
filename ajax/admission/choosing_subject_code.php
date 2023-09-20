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
        && isset($_POST['enrollment_id'])
        && isset($_POST['student_id'])
        
        ) {

        $student_id = $_POST['student_id'];
        $enrollment_id = $_POST['enrollment_id'];

        $searchQuery = trim(strtoupper($_POST['searchQuery']));

        $school_year = new SchoolYear($con);


        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];


        $student = new Student($con, $student_id);
        $enrollment = new Enrollment($con);

        // $student_enrollment_course_id = $student->GetStudentCurrentCourseId();

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $section = new Section($con, $student_enrollment_course_id);
        $section_name = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
        $student_course_level = $student->GetStudentLevel($student_id);

        $program = new Program($con, $student_program_id);


        $program_name = $program->GetProgramAcronym();

        $program_department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $program_department_id);

        $program_department_name =  $department->GetDepartmentName();
        $department_id =  $department->GetDepartmentIdByName($program_department_name);

        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

        $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
        
        $back_url = "http://localhost/school-system-dcbt/registrar/admission/process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$student_enrollment_course_id";

        $department_type = $program_department_name == "Senior High School" ? "SHS" : ( $program_department_name == "Tertiary" ? "Tertiary" : "");
 

        $subject_program = new SubjectProgram($con);

        $availableSubjectCode = $subject_program->GetAvailableSubjectCodeWithinSemester(
            $department_type, $current_school_year_period, 
            $current_school_year_term, $student_id, $student_program_id
        );

        $toSearchArray = array();

        if(count($availableSubjectCode) > 0){

            foreach ($availableSubjectCode as $key => $row) {
                # code...

                $subject_code = $row['subject_code'];
                $subject_program_id = $row['subject_program_id'];
                $program_section = $row['program_section'];

                $data[] = array(
                    "subject_program_id" => $subject_program_id,
                    "subject_code" => $subject_code,
                    "program_section" => $program_section,
                    // "student_enrollment_course_id" => $student_enrollment_course_id,
                    // "student_id" => $student_id,
                );

                array_push($toSearchArray, $subject_program_id);
                array_push($toSearchArray, $subject_code);
                array_push($toSearchArray, $program_section);

            }

            // print_r($data);
            
        }

        // $searchQuery = "PE";

        if(count($data) > 0){

            // print_r($data);
            
            foreach ($data as $key => $value) {
                # code...

                // echo $value;

                $subject_code = $value['subject_code'];

                // echo $subject_code;
                // $subject_program_id = $value['subject_program_id'];
                $program_section = $value['program_section'];

                // if (strpos($subject_code, $searchQuery) !== false) {
                //     echo '<a href="#" class="list-group-item
                //         list-group-item-action border-1">
                //         '.$subject_code.' - ' . $program_section . '</a>';
                // }
             
            }


            $hasValue = false;
            foreach ($data as $value) {

                $subject_code = strtoupper($value['subject_code']);
                $subject_program_id = $value['subject_program_id'];

                $program_section = strtoupper($value['program_section']);
                // $program_section = "Found";
                // Use the strpos() function to check if the searchQuery is present anywhere within the subject_code
                if (strpos($subject_code, $searchQuery) !== false
                    || strpos($program_section, $searchQuery) !== false
                    ) {

                        $hasValue = true;
                    // The searchQuery is found in the subject_code, so you can perform the action you want here
                    // echo "Partial match found: $subject_code";

                    // echo '<a href="#" class="list-group-item
                    //     list-group-item-action border-1">
                    //     '.  $subject_code . ' - ' . $program_section . '</a>';
                        
                    echo "
                        <div>
                            <a  
                                class='list-group-item list-group-item-action border-1'>
                                $subject_code -  $program_section
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