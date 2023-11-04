<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/StudentSubject.php');


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

                <div class="content-header">
                    <header>
                        <div class="title">
                            <h1><?php echo "$subject_title - SY $current_school_year_term $period_shortcut"; ?></h1>
                        </div>
                    </header>
                    <div class="cards">
                        <?php if(count($assignmentTodoIds) > 0): ?>
                        <?php
                            foreach ($assignmentTodoIds as $key => $subjectCodeAssignmentId) {
                                $subject_code_assignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                                
                                $assignment_name = $subject_code_assignment->GetAssignmentName();
                                $assignment_due = $subject_code_assignment->GetDueDate();
                                $assignment_due = date("M d, g:i a", strtotime($assignment_due));
                                $max_score = $subject_code_assignment->GetMaxScore();

                                $assignment_url = "../courses/task_submission.php?sc_id=$subjectCodeAssignmentId&ss_id=$student_subject_id";
                                echo "
                                    <div class='card'>
                                        <sup>Type</sup>
                                        <sub>Dropbox</sub>
                                    </div>
                                    <div class='card'>
                                        <sup>Max Score</sup>
                                        <sub>$max_score</sub>
                                    </div>
                                    <div class='card'>
                                        <sup>Category</sup>
                                        <sub>Assignment</sub>
                                    </div>
                                    <div class='card'>
                                        <sup>Start</sup>
                                        <sub>-</sub>
                                    </div>
                                    <div class='card'>
                                        <sup>Due</sup>
                                        <sub>$assignment_due</sub>
                                    </div>";
                            }
                        ?>
                        <?php else: ?>
                    </div>
                    <div class="tabs">
                        <button 
                            class="tab"
                            style="background-color: var(--mainContentBG); color: black"
                            onclick="window.location.href = '#'"
                            disabled
                        >
                            Instructions
                        </button>
                        <button 
                            class="tab"
                            style="background-color: var(--theme); color: white"
                            onclick="window.location.href = '#'"
                            disabled
                        >
                            Submissions
                        </button>
                    </div>

                    <nav>
                        <a href="<?php echo "$back_url"; ?>">
                            <i class="bi bi-arrow-return-left"></i>
                            Back
                        </a>
                    </nav>

                    <main>
                        <div class="bars">
                            <div class="floating">
                                <header>
                                    <div class="title">
                                        <h3>Score: <em>No submissions</em></h3>
                                    </div>
                                </header>
                                <main>
                                    <div class="progress" style="height: 20px">
                                        <div class="progress-bar" style="width: 0%">0%</div>
                                    </div>
                                </main>
                            </div>
                            <div class="floating">
                                <header>
                                    <div class="title">
                                        <h3>Submission</h3>
                                        <small>Submitted: <em>Past due</em></small>
                                        <small>Attempts: <em>0</em></small>
                                        <small>Max Attempts: <em>-</em></small>
                                    </div>
                                </header>
                            </div>
                            <div class="floating">
                                <header>
                                    <div class="title">
                                        <h3>Instructions</h3>
                                        <p>Some Descriptions</p>
                                        <p><?php echo "$assignment_url"; ?></p>
                                    </div>
                                </header>
                            </div>
                        </div>
                        <?php endif; ?>
                    </main>
                </div>
            </div>
        <?php
        }
        ?>
    </body>
</html>