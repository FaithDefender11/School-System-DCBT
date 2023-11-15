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

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Modify Grade</h3>
                            <small>Maximum Score: <?php echo $max_score; ?></small>
                        </div>
                    </header>
                    <main>
                        <form method="post">
                            <div class="row">
                                <span>
                                    <label for="subject_grade">* Given Grade</label>
                                    <div>
                                        <input maxlength="3" value="<?php echo $subject_grade; ?>" required class='form-control' type='text' 
                                            placeholder='Add Handout' id="subject_grade" name='subject_grade'>
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button type="submit" class="clean" name="edit_given_assignment_<?php echo $subject_assignment_submission_id; ?>">Save Section</button>
                            </div>
                        </form>
                    </main>
                </div>
            </main>
        </div>
    <?php 
    }
    ?>
    </body>
</html>