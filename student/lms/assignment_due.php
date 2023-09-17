<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');

    if(isset($_GET['c'])){

        $subject_code = $_GET['c'];

        $school_year = new SchoolYear($con);
        $subjectProgram = new SubjectProgram($con);



        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
        $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
        $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

        // echo $subject_code;

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

            <div class="content">

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">

                                <h4 style="font-weight: bold;" class="text-primary"><?php echo "$subject_title - SY $current_school_year_term $period_shortcut"; ?></h4>

                            </div>
                        </header>

                        <main>

                            <?php if(count($assignmentTodoIds) > 0): ?>

                                <table id="assignment_due_table" class="a" style="margin: 0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Assignment</th>  
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

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$assignment_name</td>
                                                        <td>$max_score</td>
                                                        <td>$assignment_due</td>

                                                    </tr>
                                                ";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            
                                <?php else:?>
                                    <h5 style="margin-bottom: 7px;">No assignments</h5>

                            <?php endif; ?>

                        </main>
                    </div>
                </main>
            </div>
        
        <?php

    }
?>