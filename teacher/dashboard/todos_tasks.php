<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/TaskType.php');

    if(
        isset($_GET['c'])  && isset($_GET['c_id'])
        ){

        $subject_code = $_GET['c'];
        $course_id = $_GET['c_id'];


        // echo $subject_code;

        $school_year = new SchoolYear($con);
        $subjectProgram = new SubjectProgram($con);

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
        $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
        $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

        $period_shortcut = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

        $subjectPeriodCodeTopicRawCode = $subjectPeriodCodeTopic->GetSubjectPeriodCodeTopicRawCodeBySubjectCode($subject_code, $current_school_year_id);
        $subject_title = $subjectProgram->GetSubjectProgramTitleByRawCode($subjectPeriodCodeTopicRawCode);
        
        $allTopics = $subjectPeriodCodeTopic->GetAllsubjectPeriodCodeTopics($subject_code,
            $current_school_year_id, $teacherLoggedInId);

        $givenAssignmentsTodos = $subjectPeriodCodeTopic->GetAllGivenAssignmentsBasedOnSubjectCodeTopics(
            $allTopics);
        
        
            
        // var_dump($givenAssignmentsTodos);


        $back_url = "index.php";
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
                            <h3><?php echo "$subject_title - SY $current_school_year_term $period_shortcut"; ?></h3>
                        </div>
                    </header>
                    <main style='overflow-x: auto'>
                        <?php if(count($givenAssignmentsTodos) > 0): ?>
                            <table class="a" id="assignment_due_table">
                                <thead>
                                    <tr>
                                        <th class="text-start">Assignment</th>  
                                        <th>Max Score</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($givenAssignmentsTodos as $key => $subjectCodeAssignmentId) {

                                            # code...   

                                            $subject_code_assignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                            
                                            $assignment_name = $subject_code_assignment->GetAssignmentName();

                                            $subjectPeriodCodeTopicId = $subject_code_assignment->GetSubjectPeriodCodeTopicId();

                                            $subjectPeriodCodeTopic =  new SubjectPeriodCodeTopic($con, $subjectPeriodCodeTopicId);

                                            $topicName = $subjectPeriodCodeTopic->GetTopic();

                                            $assignment_due = $subject_code_assignment->GetDueDate();
                                            $assignment_due = date("M d, g:i a", strtotime($assignment_due));
                                            $max_score = $subject_code_assignment->GetMaxScore();

                                            $to_checks_url = "student_tasks_to_check.php?sc_id=$subjectCodeAssignmentId";

                                            $subject_code_topic_name = "
                                                <span class='text-muted'>&nbsp; ($topicName)</span>
                                            ";

                                            echo "

                                                <tr>
                                                    <td>
                                                        <a href='$to_checks_url'>
                                                            $assignment_name $subject_code_topic_name
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
                                <h4>No assignments</h4>
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