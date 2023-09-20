<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/StudentSubjectGrade.php");
    
    if (isset($_POST['student_id_modal'])
        && isset($_POST['student_subject_id_modal'])
        && isset($_POST['first_quarter_input'])
        && isset($_POST['second_quarter_input'])
        && isset($_POST['third_quarter_input'])
        && isset($_POST['fourth_quarter_input'])
 
    ) {

        $student_grade = new StudentSubjectGrade($con);

        $student_id = $_POST['student_id_modal'];
        $student_subject_id = $_POST['student_subject_id_modal'];


        $first_quarter_input = $_POST['first_quarter_input'];
        $second_quarter_input = $_POST['second_quarter_input'];
        $third_quarter_input = $_POST['third_quarter_input'];
        $fourth_quarter_input = $_POST['fourth_quarter_input'];



        // echo " first_quarter_input: $first_quarter_input";
        // echo " second_quarter_input: $second_quarter_input";
        // echo " third_quarter_input: $third_quarter_input";
        // echo " fourth_quarter_input: $fourth_quarter_input";

        $wasSuccess = $student_grade->AddGradeToSubjectCode($student_id,
            $student_subject_id,
            $first_quarter_input,
            $second_quarter_input,
            $third_quarter_input,
            $fourth_quarter_input
            );
        if($wasSuccess){
            echo "add_grade_success";
            return;
        }




       
        // $query = $con->prepare("DELETE FROM course 
        //     WHERE course_id = :course_id
        //     -- AND is_remove = 1
        //     ");
            
        // $query->bindParam(":course_id", $course_id);

        // if($query->execute()){
        //     echo "success_delete";
        // }
        
    }
    else{
        echo "Something went wrong on the course_id";
    }
?>