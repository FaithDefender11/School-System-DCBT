<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Helper.php');


    if(
        isset($_GET['c'])
        ){

        $subject_code = $_GET['c'];

        $school_year = new SchoolYear($con);
        $subjectProgram = new SubjectProgram($con);

        $studentSubject = new StudentSubject($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
        $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
        $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

        $student_subject_id = $studentSubject->GetStudentSubjectIdBySubjectCode($studentLoggedInId,
            $current_school_year_id, $subject_code);

        // echo $student_subject_id;


        $subject_code_assignment = new SubjectCodeAssignment($con);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

        $subjectPeriodCodeTopicRawCode = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicRawCodeBySubjectCode($subject_code, $current_school_year_id);

        // echo $subjectPeriodCodeTopicRawCode;
        $subject_title = $subjectProgram->GetSubjectProgramTitleByRawCode($subjectPeriodCodeTopicRawCode);

        $assignmentTodoIds = $subject_code_assignment->GetAllTodosWithinSubjectCode(
            $studentLoggedInId,
            $current_school_year_id,
            $subject_code);

        
        
        // print_r($assignmentTodoIds);
        
        $period_shortcut = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

        $back_url = "student_dashboard.php";
?>

            <nav>
                <a href="<?php echo "$back_url"; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3><?php echo "$subject_title - SY $current_school_year_term $period_shortcut"; ?></h3>
                        </div>
                    </header>
                    <main>
                        <?php if(count($assignmentTodoIds) > 0): ?>
                            <table class="a" id="assignment_due_table">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Max Score</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($assignmentTodoIds as $key => $subjectCodeAssignmentId) {

                                            # code...   

                                            $subject_code_assignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                            
                                            $assignment_name = $subject_code_assignment->GetAssignmentName();
                                            $assignment_due = $subject_code_assignment->GetDueDate();
                                            $assignment_due = date("M d, g:i a", strtotime($assignment_due));
                                            $max_score = $subject_code_assignment->GetMaxScore();

                                            $assignment_url = "../courses/task_submission.php?sc_id=$subjectCodeAssignmentId&ss_id=$student_subject_id";
                                            echo "
                                                <tr>
                                                    <td >
                                                        <a style='color: inherit' href='$assignment_url'>
                                                            $assignment_name
                                                        </a>
                                                    </td>
                                                    <td>$max_score</td>
                                                    <td>$assignment_due</td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h5>No assignments</h5>
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