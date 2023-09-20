<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Alert.php");
    
    if (isset($_POST['enrollment_form_id'])
        && isset($_POST['student_id'])
        && isset($_POST['school_year_id'])
    ) {

        $enrollment_form_id = $_POST['enrollment_form_id'];
        $school_year_id = $_POST['school_year_id'];
        $student_id = $_POST['student_id'];

        $student = new Student($con, $student_id);
        $enrollment = new Enrollment($con);

        $is_new_enrollee = 0;
        $is_tertiary = $student->GetIsTertiary();

        # Previous Student Status
        $student_status = $student->GetStudentStatus();

        $enrollment_student_status = $student_status;

        $is_transferee = 0;
        $course_id = 0;



        $doesStudentHasEnrollmentForm = $enrollment->CheckStudentEnrollmentFormExists(
            $school_year_id, $student_id);

        if($doesStudentHasEnrollmentForm == true){
            echo "has_already_enrollment_form";
            return;
        }


        $newEnrollmentSuccess = $enrollment->InsertEnrollmentManualNewStudent(
            $student_id, $course_id, $school_year_id,
            $enrollment_form_id, $enrollment_student_status,
            $is_tertiary, $is_transferee, $is_new_enrollee);

        if($enrollment_student_status == "Regular" 
            && $newEnrollmentSuccess){

            // $student_enrollment_id = $con->lastInsertId();

            // array_push($array, "os_create_form_success");
            // array_push($array, $student_enrollment_id);

            // $data = array(
            //     "type" => "os_create_form_success",
            //     "student_enrollment_id", 1
            // );

            // echo json_encode($data);

            echo "os_create_form_success";
            return;
          
        }
         

    }
        
?>