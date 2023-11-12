<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Student.php');

    if(isset($_GET['sc_id'])
    ){

    $subject_code_assignment_id = $_GET['sc_id'];

    // echo "tochecl";
    
    $subject_code_assignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

    $subject_period_code_topic_id = $subject_code_assignment->GetSubjectPeriodCodeTopicId();
    $assignment_name = $subject_code_assignment->GetAssignmentName();

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

    $subjectPeriodCodeTopicRawCode = $subjectPeriodCodeTopic->GetProgramCode();
    $topicName = $subjectPeriodCodeTopic->GetTopic();
    $periodName = $subjectPeriodCodeTopic->GetSubjectPeriodName();
    $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
    $course_id = $subjectPeriodCodeTopic->GetCourseId();

    $school_year = new SchoolYear($con);
    $subjectProgram = new SubjectProgram($con);

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

    $period_shortcut = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

    // $subjectPeriodCodeTopicRawCode = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicRawCodeBySubjectCode($subject_code, $current_school_year_id);
    $subject_title = $subjectProgram->GetSubjectProgramTitleByRawCode($subjectPeriodCodeTopicRawCode);


    // $back_url = "index.php";
    $back_url = "todos_tasks.php?c_id=$course_id&c=$subject_code";

    $givenAssignmentsTodos = [];


    $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

    $toCheckSubmissions = $subjectAssignmentSubmission
        ->GetSubmittedUngradedSubmissionBasedOnAssignment($subject_code_assignment_id);

    // var_dump($toCheckSubmissions);
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
                        <h3><?php echo "$assignment_name"?> <em><?php echo "$topicName ($periodName) SY $current_school_year_term $period_shortcut"; ?></em></h3>
                    </header>
                    <main style="overflow-x: auto">
                        <?php if(count($toCheckSubmissions) > 0): ?>
                            <table class="a" id="assignment_due_table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Submission Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($toCheckSubmissions as $key => $subject_assignment_submission) {

                                            # code...   

                                            $subject_assignment_submission_id = $subject_assignment_submission['subject_assignment_submission_id'];
                                            $student_id = $subject_assignment_submission['student_id'];
                                            $date_creation = $subject_assignment_submission['date_creation'];

                                            $student = new Student($con, $student_id);

                                            $fullName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());


                                            $date_creation = date("M d, g:i a", strtotime($date_creation));


                                            
                                            // $assignment_name = $subject_code_assignment->GetAssignmentName();
                                            // $assignment_due = $subject_code_assignment->GetDueDate();
                                            // $assignment_due = date("M d, g:i a", strtotime($assignment_due));
                                            // $max_score = $subject_code_assignment->GetMaxScore();

                                            // $to_checks_url = "student_tasks_to_check.php?sc_id=$subjectCodeAssignmentId";


                                            $to_check_page = "../class/student_submission_view.php?id=$subject_assignment_submission_id";
                                            echo "

                                                <tr>
                                                    <td>
                                                        <a href=''>
                                                            $fullName
                                                        </a>
                                                    </td>
                                                    <td>$date_creation</td>
                                                    <td>
                                                        <a href='$to_check_page'>
                                                            <button class='btn-sm btn btn-primary'>
                                                                <i class='fas fa-pen'></i>
                                                            </button>
                                                        </a>
                                                    </td>

                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                                <h4>No students to be check</h4>
                        <?php endif; ?>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>