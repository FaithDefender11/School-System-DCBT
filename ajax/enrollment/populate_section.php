<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Section.php");

    $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];

    // echo $school_year_id;
    $current_school_year_semester = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    if (isset($_POST['studentId']) && !isset($_POST['program_id'])) {

        // Unique ID
        $student_unique_id = $_POST['studentId'];

        $student_check = new Student($con);

        $checkIfOngoing = $student_check->ValidateIfOngoingStudent($student_unique_id);

        if($checkIfOngoing == false){
            echo json_encode([]);
            // echo "false";
            return;
        }else{
            // echo "true";
        }

        $student = new Student($con, $student_unique_id);

        $student_prev_course_id = $student->GetStudentCurrentCourseId();

        $section = new Section($con, $student_prev_course_id);

        $student_program_section = $section->GetSectionName();

        $student_program_id = $section->GetSectionProgramId($student_prev_course_id);

        // echo $student_program_id;
        $student_status = $student->GetStudentStatus();

        $lastname = $student->GetLastName();
        $firstname = $student->GetFirstName();
        $middle_name = $student->GetMiddleName();
        $suffix = $student->GetSuffix();
        $civil_status = $student->GetCivilStatus();
        $nationality = $student->GetNationality();
        $sex = $student->GetStudentGender();
        $birthday = $student->GetStudentBirthdays();
        $religion = $student->GetReligion();
        $birthplace = $student->GetStudentBirthPlace();
        $address = $student->GetStudentAddress();
        $contact_number = $student->GetContactNumber();
        $email = $student->GetEmail();
        $lrn = $student->GetStudentLRN();

        $object = (object)array(
            'lastname' => $lastname,
            'firstname' => $firstname,
            'middle_name' => $middle_name,
            'suffix' => $suffix,
            'student_status' => $student_status,
            'lrn' => $lrn,
            'suffix' => $suffix,
            'civil_status' => $civil_status,
            'nationality' => $nationality,
            'sex' => $sex,
            'birthday' => $birthday,
            'religion' => $religion,
            'birthplace' => $birthplace,
            'address' => $address,
            'contact_number' => $contact_number,
            'email' => $email,
            'student_current_program_section' => $student_program_section,
            'student_current_course_id' => $student_prev_course_id
        );

        $course_fulled_ids = $section->GetSectionWhoReachedTheMaximumCapacityOnEnrollment(
            $current_school_year_id);
        
        // $course_fulled_ids = [1281, 1289];
        // print_r($course_fulled_ids);

        // Create an array of placeholders for the course_ids
        $placeholders = array_map(function ($id) {
            return ":course_id_$id";
        }, $course_fulled_ids);


        $query = $con->prepare("SELECT * 
        
            FROM course

            WHERE program_id=:program_id
            AND course_id NOT IN (" . implode(',', $placeholders) . ")

            AND active= 'yes'
            AND school_year_term=:school_year_term

        ");

        // AND is_full=:is_full

        $query->bindParam(":program_id", $student_program_id);
        $query->bindParam(":school_year_term", $current_school_year_term);
        // $query->bindValue(":is_full", "no");

        foreach ($course_fulled_ids as $course_id) {
            $paramName = ":course_id_$course_id";
            $query->bindValue($paramName, $course_id);
        }

        $query->execute();

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $course_id = $row['course_id'];
                $program_section = $row['program_section'];

                $data[] = array(
                    'course_id' => $course_id,
                    'program_section' => $program_section
                );
            }
            
        }

        $result = array();

        // if(empty($data)){
            $result = array(
                'sections' => $data,
                'students' => $object
            );      
        // }
        
        if(empty($result)){
            echo json_encode([]);
        }else{
            echo json_encode($result);
        }
        
        // var_dump($result);
    }

    if (isset($_POST['program_id'])
        && !isset($_POST['studentId'])) {

        $program_id = $_POST['program_id'];

        // echo $program_id;

        // return;

        # Reflect the current number status of each section, based on number of enrolled form.

        
        $query = $con->prepare("SELECT * FROM course as t1


            WHERE t1.program_id=:program_id
            
            AND t1.active= 'yes'
            AND t1.school_year_term=:school_year_term
            -- AND is_full=:is_full

        ");

        $query->bindParam(":program_id", $program_id);
        $query->bindParam(":school_year_term", $current_school_year_term);
        // $query->bindValue(":is_full", "no");
        $query->execute();

        $section = new Section($con);

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $course_id = $row['course_id'];
                $program_section = $row['program_section'];
                $capacity = $row['capacity'];
                $program_id = $row['program_id'];

                // $enrollment_capacity = $row['capacity'];


                $non_enrolled_count = $section->GetEnrollmentCourseIdNonEnrolledCount($course_id,
                    $school_year_id);

                $enrollment_capacity = $section->GetEnrollmentCourseIdEnrolledCount($course_id,
                    $school_year_id);


                // $enrollment_capacity = 2;

                // var_dump($enrollment_capacity);

                $data[] = array(
                    'course_id' => $course_id,
                    'program_section' => $program_section,
                    'capacity' => $capacity,
                    'enrollment_capacity' => $enrollment_capacity,
                    'non_enrolled_count' => $non_enrolled_count,
                    
                    'program_id' => $program_id
                );
            }
        }
        
        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }
    }

    // else{
    //     echo "Something went wrong on the selected_department_id";
    // }



?>