<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Section.php");



    $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_semester = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    if (isset($_POST['studentId'])
        && !isset($_POST['program_id'])) {

        $studentId = $_POST['studentId'];

        // echo $studentId;

        $student = new Student($con, $studentId);

        $student_prev_course_id = $student->GetStudentCurrentCourseId();

        $section = new Section($con, $student_prev_course_id);

        $student_program_id = $section->GetSectionProgramId($student_prev_course_id);

        // echo $student_program_id;

        // $("#firstName").val(student_object['suffix']);
        // $("#firstName").val(student_object['civil_status']);
        // $("#firstName").val(student_object['nationality']);
        // $("#firstName").val(student_object['sex']);
        // $("#firstName").val(student_object['birthday']);
        // $("#firstName").val(student_object['religion']);
        //                     $("#firstName").val(student_object['birthplace']);

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
        );

        $query = $con->prepare("SELECT * FROM course
            WHERE program_id=:program_id
            AND active= 'yes'
            AND school_year_term=:school_year_term
        ");

        $query->bindParam(":program_id", $student_program_id);
        $query->bindParam(":school_year_term", $current_school_year_term);
        $query->execute();

        if($query->rowCount() > 0){

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $course_id = $row['course_id'];
                $program_section = $row['program_section'];

                // echo $course_id;

                $data[] = array(
                    'course_id' => $course_id,
                    'program_section' => $program_section
                );
            }
        }

        $result = array();
        if(empty($data) == false){
            $result = array(
                'sections' => $data,
                'students' => $object
            );      
        }
        

        // foreach ($data as &$item) {
        //     $item['first_name'] = 'Justine';
        // }
        // unset($item); // Unset the reference to avoid unintended behavior in later code

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

        $query = $con->prepare("SELECT * FROM course
            WHERE program_id=:program_id
            AND active= 'yes'
            AND school_year_term=:school_year_term
        ");

        $query->bindParam(":program_id", $program_id);
        $query->bindParam(":school_year_term", $current_school_year_term);
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