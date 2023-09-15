<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectAssignmentSubmission.php");
    
    if (isset($_POST['grade_input'])
        && isset($_POST['subject_assignment_submission_id'])
        && isset($_POST['max_score'])
    ) {


        $max_score = $_POST['max_score'];

        $grade_input = $_POST['grade_input'];
        $subject_assignment_submission_id = $_POST['subject_assignment_submission_id'];
        
        
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
            $subject_assignment_submission_id);
        

        // $current_school_year_id = $_POST['current_school_year_id'];
        // $teacher_id = $_POST['teacher_id'];

        $wasSuccess = $subjectAssignmentSubmission->AssignGrade($subject_assignment_submission_id,
            $grade_input, $max_score);

        if($wasSuccess){
            echo "success";
            return;
        }


    }
?>