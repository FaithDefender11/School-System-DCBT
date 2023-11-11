<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');

    if(isset($_GET['id'])
        ){
         
        $student_subject_id = $_GET['id'];
           
        // echo $student_subject_id;

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $studentSubject = new StudentSubject($con, $student_subject_id);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $student_subject_id);

        $subject_code = $studentSubject->GetStudentSubjectCode();
        $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();
        $school_year_id = $studentSubject->GetSchoolYearId();

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);

        $subject_title = $subjectProgram->GetTitle();

        // var_dump($subject_code);

        $allGivenAssignments = $subjectCodeAssignment->GetSubjectCodeAssignments(
            $subject_code, $school_year_id);

        // var_dump($allGivenAssignments);
        
        // $back_url = "index.php?id=$student_subject_id";
        $back_url = "subject_module.php?id=$student_subject_id";
?>

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
                    "second",
                    "second",
                    "second"
                );
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
                            <h3><?= $subject_title;?> Scores</h3>
                        </div>
                    </header>
                    <main>
                        <?php if(count($allGivenAssignments) > 0):?>
                            <table class="a" id="progress_table">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Start</th>
                                        <th>Due</th>
                                        <th>Submitted</th>
                                        <th>Graded</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $now = date("Y-m-d H:i:s");
                                        $totalScore = 0;
                                        $totalOver = 0;

                                        foreach ($allGivenAssignments as $key => $row) {
                                            
                                            $subject_code_assignment_id = $row['subject_code_assignment_id'];
                                            $assignment_name = $row['assignment_name'];

                                            $max_score = $row['max_score'];

                                            
                                            $date_creation = $row['date_creation'];
                                            $start_date = date("M d, h:i a", strtotime($date_creation));
                                            
                                            $due_date_db = $row['due_date'];
                                            
                                            $due_date = date("M d, h:i a", strtotime($due_date_db));


                                            $submitted_status = "";


                                            $submitted_grade_status = "";


                                            $score = 0;

                                            $total = "";

                                            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

                                            $statusSubmission = $subjectAssignmentSubmission
                                                ->CheckStatusSubmission(
                                                $subject_code_assignment_id,
                                                $studentLoggedInId, $school_year_id);

                                            
                                            $equivalent = "";

                                            

                                            if($statusSubmission !== NULL){

                                                $submitted_grade =  $statusSubmission['subject_grade'];
                                                // $graded_over_score =  $statusSubmission['max_score'];
                                                $date_graded =  $statusSubmission['date_graded'];


                                                $score = $submitted_grade;

                                                if($submitted_grade != NULL){
                                                    
                                                    $submitted_grade_status = "
                                                        <i style='color: green;' class='fas fa-check'></i>
                                                    ";

                                                    $totalScore += $submitted_grade;
                                                    $totalOver += $max_score;
                                                    

                                                    $pecentage_equivalent = ($submitted_grade / $max_score) * 100;
                                                    $equivalent = round($pecentage_equivalent, 0, PHP_ROUND_HALF_UP);
                                                    $equivalent = $equivalent . "%";
                                                    
                                                }

                                                if($submitted_grade == NULL && $date_graded == NULL){
                                                    $submitted_grade_status = "
                                                        <i style='color: orange;' class='fas fa-times'></i>
                                                    ";
                                                    $score = "??";
                                                }

                                                $submitted_status = "
                                                    <i style='color: green;' class='fas fa-check'></i>
                                                ";

                                            }else if($statusSubmission == NULL){

                                                $submitted_status = "
                                                    <i style='color: red;' class='fas fa-times'></i>
                                                ";
                                                $submitted_grade_status = "
                                                        <i style='color: red;' class='fas fa-times'></i>
                                                ";

                                                $nowTimestamp = strtotime($now);

                                                if(strtotime($due_date_db) <=  $nowTimestamp){
                                                    $submitted_status = "
                                                        <i style='color: orange;' class='fas fa-flag'></i>
                                                    ";

                                                    $submitted_grade_status = "
                                                        <i style='color: orange;' class='fas fa-flag'></i>
                                                    ";
                                                    $equivalent = "";

                                                    $totalOver += $max_score;
                                                    // $totalScore += $max_score;

                                                }else if(strtotime($due_date_db) >  $nowTimestamp){

                                                    // $submitted_status = "
                                                    //     <i style='color: red;' class='fas fa-times'></i>
                                                    // ";
                                                    // $submitted_grade_status = "
                                                    //         <i style='color: red;' class='fas fa-times'></i>
                                                    // ";

                                                    $submitted_status = "-";
                                                    $score = "?";
                                                    $submitted_grade_status = "";
                                                    $equivalent = "";
                                                }
                                            }

                                            echo "
                                                <tr>
                                                    <td>$assignment_name</td>
                                                    <td>$start_date</td>
                                                    <td>$due_date</td>
                                                    <td>$submitted_status</td>
                                                    <td>$submitted_grade_status</td>
                                                    <td>$score / $max_score  &nbsp; $equivalent </td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                                <?php if(true): ?>
                                    <tr>
                                        <td colspan="6">Overall: 
                                            <?php
                                                if($totalOver > 0){
                                                    $pecentage_equivalent_total = ($totalScore / $totalOver) * 100;
                                                $equivalent_total = round($pecentage_equivalent_total, 0, PHP_ROUND_HALF_UP);
                                                $equivalent_total = $equivalent_total . "%";

                                                echo "$totalScore / $totalOver = $equivalent_total";
                                            }else{
                                                echo "??";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        <?php else: ?>
                            <h4 style="text-align: center;">No Data</h4>
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