
<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/SubjectAssignmentSubmission.php");
    require_once("../../includes/classes/Notification.php");
    
    if (isset($_POST['grade_input'])
        && isset($_POST['subject_assignment_submission_id'])
        && isset($_POST['max_score'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['subject_code_assignment_id'])
        && isset($_POST['subject_code'])
        
    ) {

        $max_score = $_POST['max_score'];

        $grade_input = $_POST['grade_input'];

        $current_school_year_id = $_POST['current_school_year_id'];
        $subject_code_assignment_id = $_POST['subject_code_assignment_id'];
        $subject_code = $_POST['subject_code'];

        $subject_assignment_submission_id = $_POST['subject_assignment_submission_id'];

        // var_dump($current_school_year_id);
        // var_dump($subject_code_assignment_id);
        // var_dump($subject_code);
        // var_dump($subject_assignment_submission_id);

        // return;
        
        if($grade_input > $max_score){
            // Alert::error("Given grade has reached the established max score.", "");
            // exit();
            echo "invalid";
            return false;
        }
        
        $notification = new Notification($con);

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
            $subject_assignment_submission_id);
        

        // $current_school_year_id = $_POST['current_school_year_id'];
        // $teacher_id = $_POST['teacher_id'];

        $wasSuccess = $subjectAssignmentSubmission->AssignGrade($subject_assignment_submission_id,
            $grade_input, $max_score);

        if($wasSuccess){

            # Add notification for Student that his submission is now graded.
            $gradedSubmissionNotif = $notification->TeacherGradedStudentSubmissionNotification(
                $subject_code, $current_school_year_id,
                $subject_assignment_submission_id, $subject_code_assignment_id
            );

            echo "success";
            return;
        }


    }
?>