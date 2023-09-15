<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    

    if(isset($_GET['id'])){

        $subject_assignment_submission_id = $_GET['id'];
        $subject_code = $_GET['c'];

        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);

        $subject_grade = $subjectAssignmentSubmission->GetSubjectGrade();
        $subjectCodeAssignmentId = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);

        $max_score = $subjectCodeAssignment->GetMaxScore();


        $back_url = "student_submission_view.php?id=$subject_assignment_submission_id&c=$subject_code";

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['edit_given_assignment_'. $subject_assignment_submission_id])
            && isset($_POST['subject_grade'])){
            

            $subject_grade = $_POST['subject_grade'];

            // echo $subject_grade;

            $wasUpdateSuccesss = $subjectAssignmentSubmission->AssignGrade(
                $subject_assignment_submission_id, $subject_grade, $max_score
            );
            if($wasUpdateSuccesss){
                Alert::success("Successfully modified", $back_url);
                exit();
            }

            # Check if it rewached the max score, it fnot proceed to editing.

        }

        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>


                <div class='col-md-10 offset-md-1'>
                    <div class='card'>

                        <div class='card-header'>
                            <h4 class='text-center text-muted mb-3'>Modify Grade</h4>
                            <span>Maximum Score: <?php echo $max_score; ?></span>
                        </div>

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="subject_grade" class='mb-2'>* Given Grade</label>

                                    <input maxlength="3" value="<?php echo $subject_grade; ?>" required class='form-control' type='text' 
                                        placeholder='Add Handout' id="subject_grade" name='subject_grade'>
                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_given_assignment_<?php echo $subject_assignment_submission_id; ?>'>Save Section</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                
            </div>
        <?php

    }

    ?>