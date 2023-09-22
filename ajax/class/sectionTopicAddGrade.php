<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectAssignmentSubmission.php");
    
    if (isset($_POST['student_id'])
        && isset($_POST['subject_code_assignment_id'])
        && isset($_POST['grade_input_value'])
        && isset($_POST['max_score'])
    ) {


        $grade_input_value = $_POST['grade_input_value'];
        $student_id = $_POST['student_id'];
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];
        $max_score = $_POST['max_score'];
        $school_year_id = $_POST['school_year_id'];
        
        // echo "grade_input_value: $grade_input_value";
        // echo "<br>";

        // echo "max_score: $max_score";
        // echo "<br>";

        // return;
        
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);
        
        if($grade_input_value > $max_score || $grade_input_value < 0 || $grade_input_value == ""){
            echo "invalid_graded_value";
            return;
        }
        $wasSuccess = $subjectAssignmentSubmission->GradingUnSubmitAssignment(
            $subject_code_assignment_id, $student_id, $school_year_id,
            $grade_input_value
        );

        if($wasSuccess){

            echo "success_graded";
            return;
        }

    }
?>